<?php
require_once 'db.php';

// รับค่าค้นหา
$search_bib = trim($_GET['bib'] ?? '');
$search_name = trim($_GET['name'] ?? '');

// ดึงข้อมูลจากฐานข้อมูล MySQL (ตาราง results)
$sql = "SELECT * FROM results WHERE 1=1";
$params = [];
$types = "";

if (!empty($search_bib)) {
    $sql .= " AND bib LIKE ?";
    $params[] = "%" . $search_bib . "%";
    $types .= "s";
}

if (!empty($search_name)) {
    $sql .= " AND fullname LIKE ?";
    $params[] = "%" . $search_name . "%";
    $types .= "s";
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$query_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ผลการแข่งขัน - RUNLAH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Prompt', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-950 text-gray-100 min-h-screen">

    <!-- Header Navigation -->
    <header class="bg-gray-900/90 backdrop-blur-md border-b border-cyan-500/30 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-8">
                <a href="index.php" class="flex items-center space-x-2 text-cyan-400 font-bold text-2xl tracking-wider">
                    <span class="text-3xl animate-pulse">⚡</span>
                    <span
                        class="bg-gradient-to-r from-cyan-400 to-blue-500 bg-clip-text text-transparent">IT-RUNLAH</span>
                </a>
                <nav class="hidden md:flex space-x-6 text-sm font-medium">
                    <a href="index.php" class="text-gray-400 hover:text-cyan-400 transition">หน้าหลัก</a>
                    <a href="calendar.php" class="text-gray-400 hover:text-cyan-400 transition">ปฏิทินงานวิ่ง IT</a>
                    <a href="results.php" class="text-cyan-400 border-b-2 border-cyan-400 pb-1">ผลการแข่งขัน</a>
                    <a href="admin.php" class="text-gray-400 hover:text-cyan-400 transition">สำหรับผู้จัดการระบบ</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-4 py-8">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">⏱️ ตรวจสอบผลการแข่งขัน (Race Results)</h1>
            <p class="text-gray-400 text-sm">ค้นหาผลการแข่งขัน สถิติเวลา และลำดับของคุณจากฐานข้อมูลได้ที่นี่</p>
        </div>

        <!-- Search Form -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 mb-8 shadow-xl">
            <form action="" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1">หมายเลข BIB</label>
                    <input type="text" name="bib" value="<?= htmlspecialchars($search_bib); ?>"
                        placeholder="เช่น A-1001"
                        class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg p-2.5 text-sm focus:border-cyan-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-400 mb-1">ชื่อ - นามสกุล</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($search_name); ?>"
                        placeholder="เช่น สมชาย"
                        class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg p-2.5 text-sm focus:border-cyan-500 outline-none">
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-medium p-2.5 rounded-lg text-sm shadow-md transition">🔍
                        ค้นหาผลการแข่งขัน</button>
                </div>
            </form>
        </div>

        <!-- Results Table -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden shadow-xl">
            <div class="p-4 border-b border-gray-800 flex justify-between items-center">
                <h3 class="font-bold text-cyan-400">รายการผลการแข่งขันล่าสุด</h3>
                <span class="text-xs text-gray-500">พบทั้งหมด <?= $query_result->num_rows; ?> รายการ</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-300">
                    <thead class="bg-gray-800/80 text-gray-400 text-xs uppercase font-mono">
                        <tr>
                            <th class="p-3">BIB</th>
                            <th class="p-3">ชื่อนักวิ่ง</th>
                            <th class="p-3">ชื่องานวิ่ง</th>
                            <th class="p-3">ระยะทาง</th>
                            <th class="p-3">Gun Time</th>
                            <th class="p-3">Chip Time</th>
                            <th class="p-3 text-center">อันดับทั่วไป</th>
                            <th class="p-3 text-center">อันดับรุ่น</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        <?php if ($query_result->num_rows > 0): ?>
                            <?php while ($row = $query_result->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-800/50 transition">
                                    <td class="p-3 font-mono text-cyan-400 font-bold"><?= htmlspecialchars($row['bib']); ?></td>
                                    <td class="p-3 font-semibold text-white"><?= htmlspecialchars($row['fullname']); ?></td>
                                    <td class="p-3 text-gray-300"><?= htmlspecialchars($row['event_name']); ?></td>
                                    <td class="p-3"><span
                                            class="bg-cyan-500/10 text-cyan-400 text-xs px-2 py-0.5 rounded border border-cyan-500/30"><?= htmlspecialchars($row['distance']); ?></span>
                                    </td>
                                    <td class="p-3 font-mono text-gray-400"><?= htmlspecialchars($row['gun_time']); ?></td>
                                    <td class="p-3 font-mono text-emerald-400 font-bold">
                                        <?= htmlspecialchars($row['chip_time']); ?>
                                    </td>
                                    <td class="p-3 text-center font-bold text-amber-400">
                                        #<?= htmlspecialchars($row['overall_rank']); ?></td>
                                    <td class="p-3 text-center font-bold text-cyan-400">
                                        #<?= htmlspecialchars($row['cat_rank']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="p-6 text-center text-gray-500">
                                    ไม่พบข้อมูลผลการแข่งขันตามเงื่อนไขที่คุณค้นหา</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

</body>

</html>