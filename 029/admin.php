<?php
require_once 'db.php';

// ดึงข้อมูลทั้งหมดจากตาราง
$sql = "SELECT * FROM registrations ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - รายชื่อผู้สมัครวิ่ง IT RUN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Prompt', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-950 text-gray-100 p-8">

    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-cyan-400">📊 รายชื่อผู้ลงทะเบียนวิ่ง (Admin Dashboard)</h1>
            <a href="029.php"
                class="bg-gray-800 hover:bg-gray-700 text-gray-300 px-4 py-2 rounded-lg text-sm border border-gray-700">กลับหน้าหลัก</a>
        </div>

        <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden shadow-xl">
            <table class="w-full text-left text-sm text-gray-300">
                <thead class="bg-gray-800 text-cyan-400 uppercase font-mono text-xs border-b border-gray-700">
                    <tr>
                        <th class="p-4">#</th>
                        <th class="p-4">ชื่อ - นามสกุล</th>
                        <th class="p-4">เลขบัตรประชาชน</th>
                        <th class="p-4">เบอร์โทร</th>
                        <th class="p-4">ระยะทาง</th>
                        <th class="p-4">ไซส์เสื้อ</th>
                        <th class="p-4">วันที่สมัคร</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-800/50 transition">
                                <td class="p-4 font-mono text-gray-500"><?= $row['id']; ?></td>
                                <td class="p-4 font-medium text-white"><?= htmlspecialchars($row['fullname']); ?></td>
                                <td class="p-4 font-mono"><?= htmlspecialchars($row['id_card']); ?></td>
                                <td class="p-4 font-mono"><?= htmlspecialchars($row['phone']); ?></td>
                                <td class="p-4"><span
                                        class="bg-cyan-500/10 text-cyan-400 px-2.5 py-1 rounded-full text-xs font-mono border border-cyan-500/20"><?= htmlspecialchars($row['distance']); ?></span>
                                </td>
                                <td class="p-4 font-mono text-emerald-400"><?= htmlspecialchars($row['shirt_size']); ?></td>
                                <td class="p-4 text-xs text-gray-500 font-mono"><?= $row['created_at']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="p-6 text-center text-gray-500">ยังไม่มีข้อมูลผู้สมัครในขณะนี้</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>