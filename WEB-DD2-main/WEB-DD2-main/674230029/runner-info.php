<?php
session_start();

// ตั้งค่าเขตเวลา
date_default_timezone_set('Asia/Bangkok');

// ไฟล์สำหรับบันทึกประวัติผู้สมัคร
$json_file = 'runners.json';

// อ่านข้อมูลผู้สมัครเดิมจากไฟล์ JSON (ถ้าไม่มี ให้สร้างอาร์เรย์ว่างหรือข้อมูลตัวอย่าง)
$runners = [];
if (file_exists($json_file)) {
    $json_data = file_get_contents($json_file);
    $runners = json_decode($json_data, true) ?? [];
} else {
    // ข้อมูลตัวอย่างเริ่มต้น
    $runners = [
        [
            'id' => 1,
            'email' => 'somchai@example.com',
            'fullname' => 'สมชาย สายวิ่ง',
            'distance' => '11KM - Mini Marathon',
            'shirt_size' => 'L (40")',
            'bib_number' => 'BIB-1001',
            'created_at' => '2026-08-20 09:15:00'
        ],
        [
            'id' => 2,
            'email' => 'somsri@example.com',
            'fullname' => 'สมศรี มีแรงวิ่ง',
            'distance' => '5KM - Fun Run',
            'shirt_size' => 'M (38")',
            'bib_number' => 'BIB-1002',
            'created_at' => '2026-08-20 09:45:00'
        ]
    ];
    file_put_contents($json_file, json_encode($runners, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

// รับค่าอีเมลจากหน้าลงทะเบียน
$email = $_POST['email'] ?? ($_SESSION['email'] ?? 'runner@example.com');
$_SESSION['email'] = $email;

// สุ่มเลขอันดับนักวิ่ง (BIB)
$auto_bib = "BIB-" . rand(1000, 9999);

// ตรวจสอบเมื่อมีการกดส่งฟอร์มลงทะเบียน
$success = false;
$new_runner_data = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_runner'])) {
    $fullname = trim($_POST['fullname'] ?? '');
    $shirt_size = trim($_POST['shirt_size'] ?? '');
    $bib_number = trim($_POST['bib_number'] ?? '');
    $distance = trim($_POST['distance'] ?? '');

    if (!empty($fullname) && !empty($shirt_size) && !empty($distance)) {
        // สร้างข้อมูลผู้สมัครคนใหม่
        $new_runner_data = [
            'id' => count($runners) + 1,
            'email' => $email,
            'fullname' => $fullname,
            'distance' => $distance,
            'shirt_size' => $shirt_size,
            'bib_number' => $bib_number,
            'created_at' => date('Y-m-d H:i:s')
        ];

        // เพิ่มเข้าในรายการและบันทึกลงไฟล์ JSON
        array_unshift($runners, $new_runner_data); // เอาคนสมัครล่าสุดขึ้นบนสุด
        file_put_contents($json_file, json_encode($runners, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>กรอกข้อมูลและประวัติผู้สมัคร - NAKHONSAWAN SPEED TRAIL</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen py-10 px-4 flex flex-col items-center">

    <!-- Header Logo & Event Title -->
    <div class="text-center mb-6 space-y-1">
        <div class="flex justify-center mb-2">
            <svg class="w-16 h-10 text-black" viewBox="0 0 100 50" fill="currentColor">
                <path d="M 10 40 L 30 15 L 45 30 L 60 5 L 80 40 Z" fill="none" stroke="currentColor" stroke-width="4"
                    stroke-linejoin="round" />
                <path d="M 25 40 L 40 20 L 55 40 Z" fill="currentColor" />
            </svg>
        </div>
        <h1 class="text-xl font-black tracking-widest text-gray-900 uppercase">IT NPRU SPEED TRAIL</h1>
        <p class="text-xs text-gray-500">ระบบรับสมัครนักวิ่งออนไลน์</p>
    </div>

    <!-- Runner Information Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 max-w-md w-full p-6 sm:p-8 space-y-5 mb-10">

        <?php if ($success && $new_runner_data): ?>
            <!-- Alert แสดงผลเมื่อลงทะเบียนสำเร็จ -->
            <div class="text-center py-4 space-y-3">
                <div
                    class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto text-2xl font-bold">
                    ✓</div>
                <h2 class="text-lg font-bold text-gray-800">สมัครแข่งขันเรียบร้อย!</h2>
                <p class="text-xs text-gray-500">ระบบได้บันทึกข้อมูลของคุณลงในประวัติผู้สมัครแล้ว</p>

                <div class="bg-gray-50 p-4 rounded-2xl text-left text-xs space-y-2 border border-gray-100">
                    <p><strong>บัญชีผู้ใช้:</strong> <?php echo htmlspecialchars($new_runner_data['email']); ?></p>
                    <p><strong>ชื่อ-นามสกุล:</strong> <?php echo htmlspecialchars($new_runner_data['fullname']); ?></p>
                    <p><strong>ระยะทาง:</strong> <?php echo htmlspecialchars($new_runner_data['distance']); ?></p>
                    <p><strong>ไซส์เสื้อ:</strong> <?php echo htmlspecialchars($new_runner_data['shirt_size']); ?></p>
                    <p><strong>เลข BIB นักวิ่ง:</strong> <span
                            class="text-orange-600 font-bold"><?php echo htmlspecialchars($new_runner_data['bib_number']); ?></span>
                    </p>
                </div>

                <a href="runner-info.php"
                    class="inline-block mt-2 px-6 py-2 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full hover:bg-gray-200 transition">
                    + สมัครเพิ่มอีกคน
                </a>
            </div>
        <?php else: ?>

            <form action="" method="POST" class="space-y-4">
                <input type="hidden" name="submit_runner" value="1">

                <h2 class="font-bold text-gray-800 text-sm text-center">กรอกข้อมูลผู้แข่งขัน</h2>

                <!-- บัญชีผู้ใช้ -->
                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 flex justify-between items-center text-xs">
                    <span class="text-gray-500">สมัครในนาม:</span>
                    <span class="font-medium text-gray-800"><?php echo htmlspecialchars($email); ?></span>
                </div>

                <!-- ชื่อ - นามสกุล -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">ชื่อ - นามสกุล <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="fullname" required placeholder="นายสมชาย สายวิ่ง"
                        class="w-full px-3 py-2 bg-white border border-gray-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-orange-400">
                </div>

                <!-- ระยะการแข่งขัน -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">ระยะทาง <span
                            class="text-red-500">*</span></label>
                    <select name="distance" required
                        class="w-full px-3 py-2 bg-white border border-gray-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-orange-400">
                        <option value="">-- เลือกระยะทาง --</option>
                        <option value="5KM - Fun Run">5KM - Fun Run</option>
                        <option value="11KM - Mini Marathon">11KM - Mini Marathon</option>
                        <option value="25KM - Half Marathon Trail">25KM - Half Marathon Trail</option>
                        <option value="40KM - Full Marathon Trail">40KM - Full Marathon Trail</option>
                    </select>
                </div>

                <!-- ไซส์เสื้อ -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">ไซส์เสื้อ <span
                            class="text-red-500">*</span></label>
                    <div class="grid grid-cols-5 gap-1.5">
                        <?php foreach (['S (36")', 'M (38")', 'L (40")', 'XL (42")', '2XL (44")'] as $size): ?>
                            <label
                                class="flex items-center justify-center py-2 border border-gray-200 rounded-xl text-xs hover:bg-orange-50 cursor-pointer has-[:checked]:bg-orange-500 has-[:checked]:text-white has-[:checked]:border-orange-500 transition">
                                <input type="radio" name="shirt_size" value="<?php echo $size; ?>" required class="hidden">
                                <span><?php echo explode(' ', $size)[0]; ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- เลขอันดับนักวิ่ง (BIB) -->
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">เลขอันดับนักวิ่ง (BIB Number)</label>
                    <input type="text" name="bib_number" value="<?php echo $auto_bib; ?>" readonly
                        class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-xl text-xs font-bold text-orange-600 focus:outline-none cursor-not-allowed">
                    <p class="text-[10px] text-gray-400 mt-1">* ระบบสุ่มเลข BIB ให้อัตโนมัติ</p>
                </div>

                <!-- ปุ่มยืนยัน -->
                <button type="submit"
                    class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-full transition shadow-md shadow-blue-200 mt-4">
                    ยืนยันการสมัคร
                </button>
            </form>

        <?php endif; ?>

    </div>

    <!-- Table Section: แสดงประวัติรายชื่อผู้สมัครทั้งหมด -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 max-w-4xl w-full p-6 sm:p-8 space-y-4">
        <div class="flex justify-between items-center border-b pb-4">
            <div>
                <h3 class="font-bold text-gray-800 text-base">📋 ประวัติรายชื่อผู้สมัครแข่งขันทั้งหมด</h3>
                <p class="text-xs text-gray-500">จำนวนผู้สมัครปัจจุบัน <?php echo count($runners); ?> คน</p>
            </div>
            <a href="index.php" class="text-xs text-blue-600 hover:underline">← กลับหน้าหลัก</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-100 text-gray-700 uppercase font-semibold">
                    <tr>
                        <th class="py-3 px-4">เลข BIB</th>
                        <th class="py-3 px-4">ชื่อ - นามสกุล</th>
                        <th class="py-3 px-4">ระยะทาง</th>
                        <th class="py-3 px-4">ไซส์เสื้อ</th>
                        <th class="py-3 px-4">อีเมล</th>
                        <th class="py-3 px-4">วันที่สมัคร</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($runners)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-6 text-gray-400">ยังไม่มีประวัติการสมัคร</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($runners as $runner): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3 px-4 font-bold text-orange-600">
                                    <?php echo htmlspecialchars($runner['bib_number']); ?>
                                </td>
                                <td class="py-3 px-4 font-medium text-gray-900">
                                    <?php echo htmlspecialchars($runner['fullname']); ?>
                                </td>
                                <td class="py-3 px-4"><?php echo htmlspecialchars($runner['distance']); ?></td>
                                <td class="py-3 px-4"><span
                                        class="bg-gray-100 px-2 py-1 rounded-md text-[11px] font-semibold"><?php echo htmlspecialchars($runner['shirt_size']); ?></span>
                                </td>
                                <td class="py-3 px-4 text-gray-500"><?php echo htmlspecialchars($runner['email']); ?></td>
                                <td class="py-3 px-4 text-gray-400 text-[11px]">
                                    <?php echo htmlspecialchars($runner['created_at']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>