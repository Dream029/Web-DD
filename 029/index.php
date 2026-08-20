<?php
require_once 'db.php';

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'register') {
    $fullname = trim($_POST['fullname'] ?? '');
    $id_card = trim($_POST['id_card'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $distance = trim($_POST['distance'] ?? '');
    $shirt_size = trim($_POST['shirt_size'] ?? '');

    if (!empty($fullname) && !empty($id_card) && !empty($phone) && !empty($distance) && !empty($shirt_size)) {
        $stmt = $conn->prepare("INSERT INTO registrations (fullname, id_card, phone, distance, shirt_size) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $fullname, $id_card, $phone, $distance, $shirt_size);

        if ($stmt->execute()) {
            $message = "ลงทะเบียนงานวิ่ง IT RUN สำเร็จ! ข้อมูลถูกบันทึกลงฐานข้อมูลแล้ว";
        } else {
            $message = "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "กรุณากรอกข้อมูลให้ครบทุกช่อง";
    }
}

// ข้อมูลงานวิ่ง IT (Featured Event)
$featured_event = [
    'title' => 'IT & TECH NIGHT RUN 2026',
    'slogan' => '“วิ่งเพื่อสุขภาพ บัคอย่าหาทำ โค้ดอย่าให้พัง Run For Cyber Health”',
    'date' => '22 พฤศจิกายน 2569',
    'location' => 'อุทยานวิทยาศาสตร์ประเทศไทย (Thailand Science Park) ปทุมธานี',
    'organizer' => 'IT Runner Club & Developer Community',
    'image' => 'https://images.unsplash.com/photo-1532444458054-01a7dd3e9fca?auto=format&fit=crop&w=800&q=80'
];

// ข้อมูลงานวิ่งสไตล์ IT อื่นๆ
$events = [
    [
        'title' => 'Cyber Hackathon Trail Run 2026',
        'date' => '5 ธันวาคม 2569',
        'location' => 'สวนวชิรเบญจทัศ (สวนรถไฟ) กรุงเทพฯ',
        'image' => 'https://images.unsplash.com/photo-1530549387789-4c1017266635?auto=format&fit=crop&w=500&q=80',
        'tag' => 'เปิดใหม่'
    ],
    [
        'title' => 'DevOps Charity Mini Marathon 10K',
        'date' => '22 พฤศจิกายน 2569',
        'location' => 'มหาวิทยาลัยเกษตรศาสตร์ บางเขน',
        'image' => 'https://images.unsplash.com/photo-1452626038306-9aae5e071dd3?auto=format&fit=crop&w=500&q=80',
        'tag' => 'เปิดใหม่'
    ],
    [
        'title' => 'Cloud Native Fun Run & Health Tech',
        'date' => '4 ตุลาคม 2569',
        'location' => 'สวนหลวง ร.9 กรุงเทพฯ',
        'image' => 'https://images.unsplash.com/photo-1516132006923-6cf348e5dee2?auto=format&fit=crop&w=500&q=80',
        'tag' => 'ยอดนิยม'
    ],
    [
        'title' => 'Full-Stack City Night Run 2026',
        'date' => '26 กันยายน 2569',
        'location' => 'ลานคนเมือง เสาชิงช้า กรุงเทพฯ',
        'image' => 'https://images.unsplash.com/photo-1513593771513-7b58b6c4af38?auto=format&fit=crop&w=500&q=80',
        'tag' => 'ยอดนิยม'
    ]
];
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RUNLAH - IT RUNNING HUB</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Prompt', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-950 text-gray-100 min-h-screen">

    <?php if (!empty($message)): ?>
        <script>
            alert("<?= $message; ?>");
        </script>
    <?php endif; ?>

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
                    <a href="index.php" class="text-cyan-400 border-b-2 border-cyan-400 pb-1">หน้าหลัก</a>
                    <a href="calendar.php" class="text-gray-400 hover:text-cyan-400 transition">ปฏิทินงานวิ่ง IT</a>
                    <a href="results.php" class="text-gray-400 hover:text-cyan-400 transition">ผลการแข่งขัน</a>
                    <a href="admin.php" class="text-gray-400 hover:text-cyan-400 transition">สำหรับผู้จัดการระบบ</a>
                </nav>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative hidden sm:block">
                    <input type="text" placeholder="ค้นหางานวิ่งไอที..."
                        class="bg-gray-800 text-gray-200 border border-gray-700 rounded-full py-1.5 px-4 pr-10 text-sm focus:outline-none focus:border-cyan-500 w-52">
                    <button class="absolute right-3 top-2 text-gray-400">🔍</button>
                </div>
                <button class="text-gray-400 hover:text-white">🌐</button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        <section class="bg-gray-900 rounded-2xl shadow-2xl overflow-hidden border border-cyan-500/30 mb-10 relative">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                <div class="p-4">
                    <img src="<?= $featured_event['image']; ?>" alt="<?= $featured_event['title']; ?>"
                        class="w-full h-80 object-cover rounded-xl shadow-lg border border-gray-800">
                </div>
                <div class="p-6 md:pl-0 space-y-4">
                    <div class="flex items-center space-x-2">
                        <span
                            class="bg-cyan-500/10 text-cyan-400 text-xs px-3 py-1 rounded-full font-mono border border-cyan-500/30">FEATURED
                            IT RUN</span>
                        <span
                            class="bg-emerald-500/10 text-emerald-400 text-xs px-3 py-1 rounded-full font-mono border border-emerald-500/30">OPEN
                            NOW</span>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white tracking-wide"><?= $featured_event['title']; ?>
                    </h1>
                    <p class="text-cyan-300/80 italic text-sm font-light"><?= $featured_event['slogan']; ?></p>

                    <div class="space-y-2 text-sm text-gray-300 pt-2">
                        <p>📅 <strong class="text-white"><?= $featured_event['date']; ?></strong></p>
                        <p>📍 <span><?= $featured_event['location']; ?></span></p>
                        <p class="text-gray-400 text-xs">👤 ผู้จัดงาน: <?= $featured_event['organizer']; ?></p>
                    </div>

                    <div class="pt-4 flex space-x-3">
                        <button onclick="openDetailModal()"
                            class="px-5 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 font-medium rounded-lg text-sm border border-gray-700 transition">รายละเอียด</button>
                        <button onclick="openRegisterModal()"
                            class="px-6 py-2 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-medium rounded-lg text-sm shadow-lg shadow-cyan-500/20 transition transform hover:-translate-y-0.5">
                            สมัครเลย! (เริ่มต้น 450฿)
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Events List -->
        <section>
            <div class="flex space-x-6 border-b border-gray-800 mb-6 text-lg font-semibold">
                <button class="text-cyan-400 border-b-2 border-cyan-400 pb-2">งานวิ่งเปิดใหม่</button>
                <button class="text-gray-500 hover:text-gray-300 pb-2 transition">ยอดนิยม</button>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($events as $event): ?>
                    <div
                        class="bg-gray-900 rounded-xl overflow-hidden shadow-lg hover:shadow-cyan-500/10 transition border border-gray-800 hover:border-cyan-500/40 flex flex-col justify-between">
                        <div>
                            <div class="relative">
                                <img src="<?= $event['image']; ?>" alt="<?= $event['title']; ?>"
                                    class="w-full h-44 object-cover">
                                <span
                                    class="absolute top-2 left-2 bg-gray-950/80 text-cyan-400 text-[10px] font-mono px-2 py-0.5 rounded border border-cyan-500/30">
                                    <?= $event['tag']; ?>
                                </span>
                            </div>
                            <div class="p-4 space-y-2">
                                <h3 class="font-bold text-sm text-gray-100 line-clamp-2 leading-snug">
                                    <?= $event['title']; ?>
                                </h3>
                                <p class="text-xs text-cyan-400 font-mono"><?= $event['date']; ?></p>
                                <p class="text-xs text-gray-400 line-clamp-2"><?= $event['location']; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <!-- Modal 1: รายละเอียดงานวิ่ง (Detail Modal) -->
    <div id="detailModal"
        class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div
            class="bg-gray-900 border border-cyan-500/40 rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto p-6 shadow-2xl relative text-gray-200 space-y-6">
            <button onclick="closeDetailModal()"
                class="absolute top-4 right-4 text-gray-400 hover:text-white text-xl font-bold">&times;</button>

            <h2 class="text-xl font-bold text-cyan-400 border-b border-gray-800 pb-2">🏆 ถ้วยรางวัล แบ่งชายและหญิง
                (ไม่มีแบ่งสัญชาติ)</h2>

            <div class="space-y-6 text-sm">
                <!-- TRAIL 42K -->
                <div class="bg-gray-800/50 p-4 rounded-xl border border-gray-700">
                    <h3 class="text-amber-400 font-bold mb-1">🔰 TRAIL 42 ก.ม.</h3>
                    <p class="text-xs text-gray-400 mb-3">เสื้อ Finisher สำหรับนักวิ่ง 42 Km, 21 Km, 10 Km
                        ที่เข้าเส้นชัยทุกคน</p>
                    <p class="text-xs font-semibold mb-2">ถ้วยรางวัลแยกตามกลุ่มอายุ แบ่งประเภทชายและหญิง (ชาย 5 รางวัล
                        และ หญิง 5 รางวัล)</p>
                    <div class="grid grid-cols-2 gap-2 text-center text-xs">
                        <div class="bg-orange-600 text-white py-1 rounded font-semibold">Male (ชาย)</div>
                        <div class="bg-orange-600 text-white py-1 rounded font-semibold">Female (หญิง)</div>
                        <div class="bg-gray-800 py-1 rounded border border-gray-700">รุ่นอายุไม่เกิน 29 ปี</div>
                        <div class="bg-gray-800 py-1 rounded border border-gray-700">รุ่นอายุไม่เกิน 29 ปี</div>
                        <div class="bg-gray-800 py-1 rounded border border-gray-700">รุ่นอายุ 30 – 39 ปี</div>
                        <div class="bg-gray-800 py-1 rounded border border-gray-700">รุ่นอายุ 30 – 39 ปี</div>
                        <div class="bg-gray-800 py-1 rounded border border-gray-700">รุ่นอายุ 40 – 49 ปี</div>
                        <div class="bg-gray-800 py-1 rounded border border-gray-700">รุ่นอายุ 40 – 49 ปี</div>
                        <div class="bg-gray-800 py-1 rounded border border-gray-700">รุ่นอายุ 50 ปีขึ้นไป</div>
                        <div class="bg-gray-800 py-1 rounded border border-gray-700">รุ่นอายุ 50 ปีขึ้นไป</div>
                    </div>
                </div>

                <!-- TRAIL 5K -->
                <div class="bg-gray-800/50 p-4 rounded-xl border border-gray-700">
                    <h3 class="text-amber-400 font-bold mb-1">🔰 TRAIL 5 ก.ม.</h3>
                    <p class="text-xs font-semibold mb-2">ถ้วยรางวัลแยกตามกลุ่มอายุไม่เกิน 15 ปี และ 16 ปีขึ้นไป</p>
                    <div class="grid grid-cols-2 gap-2 text-center text-xs">
                        <div class="bg-orange-600 text-white py-1 rounded font-semibold">Male (ชาย)</div>
                        <div class="bg-orange-600 text-white py-1 rounded font-semibold">Female (หญิง)</div>
                        <div class="bg-gray-800 py-1 rounded border border-gray-700">รุ่นอายุไม่เกิน 15 ปี</div>
                        <div class="bg-gray-800 py-1 rounded border border-gray-700">รุ่นอายุไม่เกิน 15 ปี</div>
                        <div class="bg-gray-800 py-1 rounded border border-gray-700">รุ่นอายุ 16 ปีขึ้นไป</div>
                        <div class="bg-gray-800 py-1 rounded border border-gray-700">รุ่นอายุ 16 ปีขึ้นไป</div>
                    </div>
                </div>

                <!-- ตารางราคา -->
                <div class="border-t border-gray-800 pt-4">
                    <h3 class="text-lg font-bold text-cyan-400 mb-3">💵 ประเภทและราคาค่าสมัคร</h3>
                    <div class="space-y-4">
                        <div class="bg-gray-800/40 p-3 rounded-lg border border-gray-700">
                            <p class="font-bold text-white">เทรล 42 ก.ม.</p>
                            <p class="text-xs text-gray-400 mb-2">เสื้อที่ระลึก, เหรียญ, หมายเลขวิ่ง, เสื้อ FINISHER
                                หลังเข้าเส้นชัย</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                <div
                                    class="bg-red-900/60 border border-red-700/50 p-2 rounded flex justify-between items-center">
                                    <span>ราคาพิเศษ (ถึง 27 ส.ค.)</span>
                                    <span class="font-bold text-red-300">฿1,800</span>
                                </div>
                                <div
                                    class="bg-gray-800 p-2 rounded flex justify-between items-center border border-gray-700">
                                    <span>ราคาปกติ (28 ส.ค. เป็นต้นไป)</span>
                                    <span class="font-bold text-gray-300">฿2,000</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-800/40 p-3 rounded-lg border border-gray-700">
                            <p class="font-bold text-white">เทรล 10 ก.ม.</p>
                            <p class="text-xs text-gray-400 mb-2">เสื้อที่ระลึก, เหรียญ, หมายเลขวิ่ง</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                <div
                                    class="bg-emerald-900/60 border border-emerald-700/50 p-2 rounded flex justify-between items-center">
                                    <span>ราคาพิเศษ (ถึง 27 ส.ค.)</span>
                                    <span class="font-bold text-emerald-300">฿800</span>
                                </div>
                                <div
                                    class="bg-gray-800 p-2 rounded flex justify-between items-center border border-gray-700">
                                    <span>ราคาปกติ (28 ส.ค. เป็นต้นไป)</span>
                                    <span class="font-bold text-gray-300">฿1,000</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-800/40 p-3 rounded-lg border border-gray-700">
                            <p class="font-bold text-white">เทรล 5 ก.ม.</p>
                            <p class="text-xs text-gray-400 mb-2">เสื้อที่ระลึก, เหรียญ, หมายเลขวิ่ง</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                <div
                                    class="bg-amber-900/60 border border-amber-700/50 p-2 rounded flex justify-between items-center">
                                    <span>ราคาพิเศษ (ถึง 27 ส.ค.)</span>
                                    <span class="font-bold text-amber-300">฿450</span>
                                </div>
                                <div
                                    class="bg-gray-800 p-2 rounded flex justify-between items-center border border-gray-700">
                                    <span>ราคาปกติ (28 ส.ค. เป็นต้นไป)</span>
                                    <span class="font-bold text-gray-300">฿600</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-800 flex justify-center">
                <button onclick="closeDetailModal(); openRegisterModal();"
                    class="px-8 py-2.5 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-bold rounded-full shadow-lg shadow-cyan-500/30 transition transform hover:scale-105">
                    สมัครเลย!
                </button>
            </div>
        </div>
    </div>

    <!-- Modal 2: แบบฟอร์มสมัครวิ่ง (Register Modal) -->
    <div id="registerModal"
        class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-gray-900 border border-cyan-500/40 rounded-2xl max-w-lg w-full p-6 shadow-2xl relative">
            <button onclick="closeRegisterModal()"
                class="absolute top-4 right-4 text-gray-400 hover:text-white text-xl font-bold">&times;</button>
            <h2 class="text-xl font-bold text-white mb-1">🏃‍♂️ แบบฟอร์มสมัครวิ่ง IT RUN</h2>
            <p class="text-xs text-cyan-400/80 mb-6 font-mono">EVENT: <?= $featured_event['title']; ?></p>

            <form action="" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="register">

                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">ชื่อ - นามสกุล *</label>
                    <input type="text" name="fullname" required placeholder="นาย สมชาย สายโค้ด"
                        class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg p-2.5 text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">เลขบัตรประชาชน *</label>
                        <input type="text" name="id_card" maxlength="13" required placeholder="1234567890123"
                            class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg p-2.5 text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">เบอร์โทรศัพท์ *</label>
                        <input type="tel" name="phone" required placeholder="0812345678"
                            class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg p-2.5 text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">เลือกระยะทาง & ราคา *</label>
                        <select name="distance" id="distanceSelect" required onchange="updatePrice()"
                            class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg p-2.5 text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none">
                            <option value="" data-price="0">-- กรุณาเลือก --</option>
                            <option value="5KM" data-price="450">5 KM (Fun Run) - 450฿</option>
                            <option value="10KM" data-price="800">10 KM (Mini Marathon) - 800฿</option>
                            <option value="21KM" data-price="1200">21 KM (Half Marathon) - 1,200฿</option>
                            <option value="42KM" data-price="1800">42 KM (Full Marathon) - 1,800฿</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-300 mb-1">ไซส์เสื้อ *</label>
                        <select name="shirt_size" required
                            class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg p-2.5 text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none">
                            <option value="">-- กรุณาเลือก --</option>
                            <option value="S">S (รอบอก 36")</option>
                            <option value="M">M (รอบอก 38")</option>
                            <option value="L">L (รอบอก 40")</option>
                            <option value="XL">XL (รอบอก 42")</option>
                            <option value="2XL">2XL (รอบอก 44")</option>
                        </select>
                    </div>
                </div>

                <!-- แสดงราคาสรุป -->
                <div
                    class="bg-gray-800/80 border border-gray-700 rounded-lg p-3 flex justify-between items-center mt-2">
                    <span class="text-xs text-gray-400">ค่าธรรมเนียมสมัคร:</span>
                    <span id="priceDisplay" class="text-lg font-bold text-cyan-400">0 ฿</span>
                </div>

                <div class="pt-4 flex justify-end space-x-3 border-t border-gray-800 mt-6">
                    <button type="button" onclick="closeRegisterModal()"
                        class="px-4 py-2 border border-gray-700 rounded-lg text-sm text-gray-400 hover:bg-gray-800">ยกเลิก</button>
                    <button type="submit"
                        class="px-6 py-2 bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-lg text-sm font-semibold shadow-lg shadow-cyan-500/20">ยืนยันการสมัคร</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Detail Modal
        function openDetailModal() {
            document.getElementById('detailModal').classList.remove('hidden');
            document.getElementById('detailModal').classList.add('flex');
        }
        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.getElementById('detailModal').classList.remove('flex');
        }

        // Register Modal
        function openRegisterModal() {
            document.getElementById('registerModal').classList.remove('hidden');
            document.getElementById('registerModal').classList.add('flex');
        }
        function closeRegisterModal() {
            document.getElementById('registerModal').classList.add('hidden');
            document.getElementById('registerModal').classList.remove('flex');
        }

        // Auto Price Calculation
        function updatePrice() {
            const select = document.getElementById('distanceSelect');
            const selectedOption = select.options[select.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            document.getElementById('priceDisplay').innerText = Number(price).toLocaleString() + ' ฿';
        }
    </script>
</body>

</html>