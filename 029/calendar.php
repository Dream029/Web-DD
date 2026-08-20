<?php
require_once 'db.php';

// ข้อมูลงานวิ่งในปฏิทิน
$calendar_events = [
    [
        'title' => 'AIMS Kids Series Songkhla 2026',
        'date' => '22 สิงหาคม 2569',
        'location' => 'หาดชลาทัศน์ จ.สงขลา',
        'image' => 'https://images.unsplash.com/photo-1530549387789-4c1017266635?auto=format&fit=crop&w=300&q=80'
    ],
    [
        'title' => 'Bangkok Airways Sukhothai Half Marathon 2026',
        'date' => '22-23 สิงหาคม 2569',
        'location' => 'อุทยานประวัติศาสตร์สุโขทัย จ.สุโขทัย',
        'image' => 'https://images.unsplash.com/photo-1452626038306-9aae5e071dd3?auto=format&fit=crop&w=300&q=80'
    ],
    [
        'title' => 'Colorful Run สนุกกัน สันเขื่อน STAGE 2 มุกดาหาร',
        'date' => '22 สิงหาคม 2569',
        'location' => 'สนามหน้าที่ว่าการอำเภอเมืองมุกดาหาร จ.มุกดาหาร',
        'image' => 'https://images.unsplash.com/photo-1516132006923-6cf348e5dee2?auto=format&fit=crop&w=300&q=80'
    ],
    [
        'title' => 'READY - TRAIL 2026',
        'date' => '23 สิงหาคม 2569',
        'location' => 'เขื่อนขุนด่านปราการชล จ.นครนายก',
        'image' => 'https://images.unsplash.com/photo-1513593771513-7b58b6c4af38?auto=format&fit=crop&w=300&q=80'
    ]
];
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ปฏิทินงานวิ่ง - RUNLAH</title>
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
                    <a href="calendar.php" class="text-cyan-400 border-b-2 border-cyan-400 pb-1">ปฏิทินงานวิ่ง IT</a>
                    <a href="#" class="text-gray-400 hover:text-cyan-400 transition">ผลการแข่งขัน</a>
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
    <main class="max-w-6xl mx-auto px-4 py-8">

        <!-- Hero Banner -->
        <div class="relative rounded-2xl overflow-hidden shadow-2xl mb-8">
            <img src="https://images.unsplash.com/photo-1530549387789-4c1017266635?auto=format&fit=crop&w=1200&q=80"
                alt="Running Hero" class="w-full h-64 md:h-80 object-cover filter brightness-50">
            <div
                class="absolute inset-0 flex items-end p-6 md:p-10 bg-gradient-to-t from-gray-950 via-gray-950/20 to-transparent">
                <h1 class="text-2xl md:text-4xl font-bold text-white">ปฏิทินงานวิ่งในไทย สัปดาห์นี้</h1>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-3 mb-8 flex flex-wrap gap-4 text-sm font-medium">
            <button class="px-4 py-2 bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-lg shadow-md">📅
                ดูตามสัปดาห์</button>
            <button class="px-4 py-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition">📍
                ดูจากแผนที่</button>
            <button class="px-4 py-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition">🏷️
                ดูตามประเภท</button>
        </div>

        <!-- Grid Container: Calendar Left + List Right -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- Left Side: Interactive Calendar Widget (4 cols) -->
            <div class="lg:col-span-4 bg-gray-900 border border-gray-800 rounded-xl p-5 h-fit shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <button class="text-gray-400 hover:text-white">&lt;</button>
                    <h2 class="font-bold text-white text-base">สิงหาคม 2569</h2>
                    <button class="text-gray-400 hover:text-white">&gt;</button>
                </div>

                <div class="grid grid-cols-7 gap-1 text-center text-xs font-semibold text-gray-500 mb-2">
                    <div>อา</div>
                    <div>จ</div>
                    <div>อ</div>
                    <div>พ</div>
                    <div>พฤ</div>
                    <div>ศ</div>
                    <div>ส</div>
                </div>

                <div class="grid grid-cols-7 gap-1 text-center text-xs text-gray-300">
                    <div class="p-2 text-gray-600">20</div>
                    <div class="p-2 text-gray-600">21</div>
                    <div class="p-2 text-gray-600">22</div>
                    <div class="p-2 text-gray-600">23</div>
                    <div class="p-2 text-gray-600">24</div>
                    <div class="p-2 text-gray-600">25</div>
                    <div class="p-2 text-gray-600">26</div>
                    <div class="p-2 text-gray-600">27</div>
                    <div class="p-2 text-gray-600">28</div>
                    <div class="p-2 text-gray-600">29</div>
                    <div class="p-2 text-gray-600">30</div>
                    <div class="p-2 text-gray-600">31</div>
                    <div class="p-2">1</div>
                    <div class="p-2">2</div>
                    <div class="p-2">3</div>
                    <div class="p-2">4</div>
                    <div class="p-2">5</div>
                    <div class="p-2">6</div>
                    <div class="p-2">7</div>
                    <div class="p-2">8</div>
                    <div class="p-2">9</div>
                    <div class="p-2">10</div>
                    <div class="p-2">11</div>
                    <div class="p-2">12</div>
                    <div class="p-2">13</div>
                    <div class="p-2">14</div>
                    <div class="p-2">15</div>
                    <div class="p-2">16</div>
                    <div class="p-2">17</div>
                    <div class="p-2">18</div>
                    <div class="p-2">19</div>
                    <div class="p-2 bg-cyan-500/20 text-cyan-400 rounded-full font-bold border border-cyan-500/50">20
                    </div>
                    <div class="p-2 bg-cyan-600 text-white rounded-full font-bold">21</div>
                    <div class="p-2 bg-cyan-600 text-white rounded-full font-bold">22</div>
                    <div class="p-2 bg-cyan-600 text-white rounded-full font-bold">23</div>
                    <div class="p-2">24</div>
                    <div class="p-2">25</div>
                    <div class="p-2">26</div>
                    <div class="p-2">27</div>
                    <div class="p-2">28</div>
                    <div class="p-2">29</div>
                    <div class="p-2">30</div>
                </div>
            </div>

            <!-- Right Side: Event Cards List (8 cols) -->
            <div class="lg:col-span-8 space-y-4">
                <div class="bg-gray-900/60 p-3 rounded-lg border border-gray-800">
                    <h3 class="text-sm font-bold text-cyan-400">วันเสาร์ที่ 22 สิงหาคม 2569</h3>
                </div>

                <?php foreach ($calendar_events as $event): ?>
                    <div
                        class="bg-gray-900 border border-gray-800 hover:border-cyan-500/40 rounded-xl p-4 flex gap-4 items-center shadow-lg transition">
                        <img src="<?= $event['image']; ?>" alt="<?= $event['title']; ?>"
                            class="w-24 h-24 sm:w-28 sm:h-28 object-cover rounded-lg flex-shrink-0">
                        <div class="space-y-1">
                            <h4 class="font-bold text-white text-base hover:text-cyan-400 transition cursor-pointer">

                                <?= $event['title']; ?>
                            </h4>
                            <p class="text-xs text-cyan-400 font-mono">📅
                                <?= $event['date']; ?>
                            </p>
                            <p class="text-xs text-gray-400">📍
                                <?= $event['location']; ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </main>

</body>

</html>