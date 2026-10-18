<?php
// Mock Data: ข้อมูลกิจกรรมวิ่งสำหรับนำมาวนลูปแสดงผล
// ✅ ลิงก์ตัวอย่างที่เป็นไฟล์รูปภาพโดยตรง (ลงท้ายด้วยนามสกุลไฟล์ หรือเป็น direct image stream)
$heroEvent = [
    'title' => 'IT NPRU NAKHON PATHOM',
    'subtitle' => '“ขึ้นชื่อเรื่องความไกล พร้อมกับความท้าทาย ”',
    'date' => '20 สิงหาคม 2569',
    'location' => 'มหาวิทยาลัยราชภัฏนครปฐม',
    'organizer' => 'สาขาเทคโนโลยีสารสนเทศ',
    'image' => 'IMG/NPRU.jpg',
    'distances' => '40KM • 25KM • 11KM • 5KM'
];

$newEvents = [
    [
        'title' => 'งานเดิน-วิ่ง การกุศล ครั้งที่ 29 KNT RUN 2026',
        'date' => '5 ธันวาคม 2569',
        'location' => 'ลานอเนกประสงค์ หลังตลาดน้ำบางน้ำผึ้ง จ.สมุทรปราการ',
        'organizer' => 'โรงพยาบาลส่งเสริมสุขภาพตำบลบางน้ำผึ้ง และกลุ่มก้าวในมูลนิธิ',
        'image' => 'https://images.unsplash.com/photo-1530549387789-4c1017266635?auto=format&fit=crop&w=500&q=80'
    ],
    [
        'title' => 'EXRUN 2026 Run for Education "วิ่งสุดมันส์ กับคนพันธุ์เอ็กซ์ ปีที่ 8"',
        'date' => '22 พฤศจิกายน 2569',
        'location' => 'มหาวิทยาลัยเกษตรศาสตร์ บางเขน กรุงเทพมหานคร',
        'organizer' => 'บริษัท พริ้นติ้ง โด โซลูชั่น จำกัด',
        'image' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&w=500&q=80'
    ]
];

$popularEvents = [
    [
        'title' => 'Kirei Kirei Global HandWashing Day Run วิ่งด้วยใจให้ปอดสะอาดปี 3',
        'date' => '4 ตุลาคม 2569',
        'location' => 'สวนวชิรเบญจทัศ (สวนรถไฟ) กรุงเทพมหานคร',
        'organizer' => 'บริษัท ไลอ้อน (ประเทศไทย) จำกัด',
        'image' => 'https://images.unsplash.com/photo-1502904550040-7534597429ae?auto=format&fit=crop&w=500&q=80'
    ],
    [
        'title' => 'BUENG BORAPHET NIGHT RUN 2026',
        'date' => '26 กันยายน 2569',
        'location' => 'อาคารอเนกประสงค์ บึงบอระเพ็ด จ.นครสวรรค์',
        'organizer' => 'อบจ.นครสวรรค์',
        'image' => 'https://images.unsplash.com/photo-1516245834210-c4c142787335?auto=format&fit=crop&w=500&q=80'
    ]
];

$recommendedEvents = [
    [
        'title' => 'คลองเพกา เทรล 2026',
        'desc' => 'งาน ซีรี่ 4 สนาม 3 จังหวัด ครั้งที่ 2 สามารถติดตามงานต่อไปได้ที่เพจ',
        'date' => '18 ตุลาคม 2569',
        'location' => 'แคมป์ปิ้งวิว อ.ประจันตคาม จ.ปราจีนบุรี',
        'organizer' => 'บริษัท เพยะ วัน (ไทยแลนด์) จำกัด',
        'image' => 'https://images.unsplash.com/photo-1486218119243-13883505764c?auto=format&fit=crop&w=600&q=80'
    ],
    [
        'title' => 'Run With The Flow 2026 (UDON) "Empower you, Empower the flow"',
        'desc' => '',
        'date' => '20 ธันวาคม 2569',
        'location' => 'สวนสาธารณะหนองประจักษ์ จังหวัดอุดรธานี',
        'organizer' => 'บริษัท เอเจนซี่ จำกัด',
        'image' => 'https://images.unsplash.com/photo-1552674605-db6ffd4facb5?auto=format&fit=crop&w=500&q=80'
    ],
    [
        'title' => 'Nakhonsawan 21',
        'desc' => '',
        'date' => '4 ตุลาคม 2569',
        'location' => 'อุทยานสวรรค์ จ.นครสวรรค์',
        'organizer' => 'ชมรมคนวิ่งเมืองพระบาง',
        'image' => 'https://images.unsplash.com/photo-1476480862126-209bfaa8edc8?auto=format&fit=crop&w=500&q=80'
    ]
];
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RUN IT - รวมงานวิ่งทั่วไทย</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Kanit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800 antialiased min-h-screen flex flex-col justify-between">

    <!-- Header / Navbar -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Logo & Navigation -->
            <div class="flex items-center space-x-8">
                <a href="#" class="flex items-center space-x-2 text-2xl font-black text-blue-600 tracking-wider">
                    <svg class="w-8 h-8 fill-current text-blue-600" viewBox="0 0 24 24">
                        <path
                            d="M13.5 5.5c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zM9.8 8.9L7 23h2.1l1.8-8 2.1 2v6h2v-7.5l-2.1-2 .6-3c1.3 1.5 3.3 2.5 5.5 2.5v-2c-1.9 0-3.5-1-4.3-2.4l-1-1.6c-.4-.6-1-1-1.7-1-.3 0-.5.1-.8.1L4 8.3V13h2V9.8l3.8-.9z" />
                    </svg>
                    <span>RUN IT</span>
                </a>
                <nav class="flex gap-6 text-sm">
                    <!-- เมนูที่เลือกอยู่ -->
                    <a href="#" class="font-bold text-blue-600 border-b-2 border-blue-600 pb-1">
                        หน้าหลัก
                    </a>

                    <!-- เมนูทั่วไป + สีเวลาเอาเมาส์ชี้ -->
                    <a href="#" class="text-gray-600 hover:text-blue-600 pb-1 transition">
                        ปฏิทินงานวิ่ง
                    </a>
                    <a href="#" class="text-gray-600 hover:text-blue-600 pb-1 transition">
                        ผลการแข่งขัน
                    </a>
                    <a href="#" class="text-gray-600 hover:text-blue-600 pb-1 transition">
                        สำหรับผู้จัดงาน
                    </a>
                </nav>
            </div>

            <!-- Search Bar & User Actions -->
            <div class="flex items-center space-x-4">
                <div class="relative hidden sm:block">
                    <input type="text" placeholder="ค้นหา"
                        class="w-48 lg:w-64 bg-gray-100 rounded-full py-1.5 pl-4 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <svg class="w-4 h-4 text-gray-400 absolute right-3 top-2.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button class="p-2 text-gray-600 hover:text-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9" />
                    </svg>
                </button>
                <button class="p-2 text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">

        <!-- Featured / Hero Banner Section -->
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 p-6 items-center">
                <!-- Left Image -->
                <div class="md:col-span-7 relative group overflow-hidden rounded-xl">
                    <img src="<?php echo $heroEvent['image']; ?>" alt="<?php echo $heroEvent['title']; ?>"
                        class="w-full h-80 object-cover rounded-xl transform group-hover:scale-105 transition duration-300">
                    <div
                        class="absolute bottom-3 left-3 bg-black/60 backdrop-blur-md text-white text-xs px-3 py-1.5 rounded-md font-medium">
                        <?php echo $heroEvent['distances']; ?>
                    </div>
                </div>
                <!-- Right Details -->
                <div class="md:col-span-5 space-y-4">
                    <h1 class="text-2xl font-bold text-gray-900"><?php echo $heroEvent['title']; ?></h1>
                    <p class="text-gray-600 text-sm italic"><?php echo $heroEvent['subtitle']; ?></p>

                    <div class="space-y-2 text-sm text-gray-700 pt-2">
                        <p class="flex items-center text-gray-900 font-medium">
                            <span class="mr-2">📅</span> <?php echo $heroEvent['date']; ?>
                        </p>
                        <p class="flex items-start text-xs text-gray-500">
                            <span class="mr-2">📍</span> <?php echo $heroEvent['location']; ?>
                        </p>
                        <p class="flex items-center text-xs text-gray-500">
                            <span class="mr-2">📢</span> ผู้จัดงาน: <span
                                class="font-medium text-gray-700 ml-1"><?php echo $heroEvent['organizer']; ?></span>
                        </p>
                    </div>

                    <div class="pt-4 flex items-center space-x-3">
                        <a href="#"
                            class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-full transition">รายละเอียด</a>
                        <a href="register.php"
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-full">
                            สมัครเลย!
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Dynamic Grid Sections: เปิดใหม่ & ยอดนิยม -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Section: เปิดใหม่ -->
            <section>
                <div class="border-b-2 border-orange-500 inline-block mb-4">
                    <h2 class="text-lg font-bold text-gray-900 pb-1">เปิดใหม่</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach ($newEvents as $event): ?>
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                            <img src="<?php echo $event['image']; ?>" alt="<?php echo $event['title']; ?>"
                                class="w-full h-36 object-cover">
                            <div class="p-4 space-y-2">
                                <h3 class="font-bold text-sm text-gray-900 line-clamp-2"><?php echo $event['title']; ?></h3>
                                <p class="text-xs text-gray-500"><?php echo $event['date']; ?></p>
                                <p class="text-xs text-gray-400 line-clamp-2"><?php echo $event['location']; ?></p>
                                <p class="text-[11px] text-gray-400 pt-1 border-t border-gray-100 truncate">
                                    <?php echo $event['organizer']; ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Section: ยอดนิยม -->
            <section>
                <div class="border-b-2 border-orange-500 inline-block mb-4">
                    <h2 class="text-lg font-bold text-gray-900 pb-1">ยอดนิยม</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach ($popularEvents as $event): ?>
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                            <img src="<?php echo $event['image']; ?>" alt="<?php echo $event['title']; ?>"
                                class="w-full h-36 object-cover">
                            <div class="p-4 space-y-2">
                                <h3 class="font-bold text-sm text-gray-900 line-clamp-2"><?php echo $event['title']; ?></h3>
                                <p class="text-xs text-gray-500"><?php echo $event['date']; ?></p>
                                <p class="text-xs text-gray-400 line-clamp-2"><?php echo $event['location']; ?></p>
                                <p class="text-[11px] text-gray-400 pt-1 border-t border-gray-100 truncate">
                                    <?php echo $event['organizer']; ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>

        <!-- Section: แนะนำเลย -->
        <section>
            <div class="border-b-2 border-orange-500 inline-block mb-4">
                <h2 class="text-lg font-bold text-gray-900 pb-1">แนะนำเลย</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Large Card -->
                <div
                    class="md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row">
                    <img src="<?php echo $recommendedEvents[0]['image']; ?>"
                        alt="<?php echo $recommendedEvents[0]['title']; ?>"
                        class="md:w-1/2 h-56 md:h-auto object-cover">
                    <div class="p-5 flex flex-col justify-between space-y-3">
                        <div class="space-y-2">
                            <h3 class="font-bold text-base text-gray-900"><?php echo $recommendedEvents[0]['title']; ?>
                            </h3>
                            <p class="text-xs text-gray-600"><?php echo $recommendedEvents[0]['desc']; ?></p>
                            <p class="text-xs text-gray-500 pt-2"><?php echo $recommendedEvents[0]['date']; ?></p>
                            <p class="text-xs text-gray-400"><?php echo $recommendedEvents[0]['location']; ?></p>
                        </div>
                        <p class="text-[11px] text-gray-400 border-t border-gray-100 pt-2">ผู้จัดงาน:
                            <?php echo $recommendedEvents[0]['organizer']; ?>
                        </p>
                    </div>
                </div>

                <!-- Small Cards Column -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 gap-4">
                    <?php for ($i = 1; $i < count($recommendedEvents); $i++): ?>
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 flex space-x-3 hover:shadow-md transition">
                            <img src="<?php echo $recommendedEvents[$i]['image']; ?>"
                                class="w-24 h-24 object-cover rounded-lg flex-shrink-0">
                            <div class="flex flex-col justify-between text-xs">
                                <div>
                                    <h4 class="font-bold text-gray-900 line-clamp-1">
                                        <?php echo $recommendedEvents[$i]['title']; ?>
                                    </h4>
                                    <p class="text-gray-500 mt-1"><?php echo $recommendedEvents[$i]['date']; ?></p>
                                    <p class="text-gray-400 truncate max-w-[180px]">
                                        <?php echo $recommendedEvents[$i]['location']; ?>
                                    </p>
                                </div>
                                <p class="text-[10px] text-gray-400 truncate">
                                    <?php echo $recommendedEvents[$i]['organizer']; ?>
                                </p>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </section>

        <!-- See All Button -->
        <div class="text-center pt-4">
            <button
                class="px-8 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-semibold rounded-full transition">
                ดูกิจกรรมอื่นๆ ทั้งหมด...
            </button>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-200 border-t border-gray-300 text-gray-600 text-xs py-10 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Column 1 -->
            <div>
                <h4 class="font-bold text-gray-800 mb-3 text-sm">เริ่มต้น</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-orange-500">หน้าหลัก</a></li>
                    <li><a href="#" class="hover:text-orange-500">ปฏิทินงานวิ่ง</a></li>
                    <li><a href="#" class="hover:text-orange-500">ผลการแข่งขัน</a></li>
                    <li><a href="#" class="hover:text-orange-500">สถานะการสมัคร</a></li>
                    <li><a href="#" class="hover:text-orange-500">ตั้งค่า</a></li>
                </ul>
            </div>

            <!-- Column 2 -->
            <div>
                <h4 class="font-bold text-gray-800 mb-3 text-sm">สำหรับเจ้าของหรือผู้จัดกิจกรรม</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-orange-500">บริการสำหรับผู้จัดงาน</a></li>
                    <li><a href="#" class="hover:text-orange-500">ประชาสัมพันธ์กิจกรรมฟรี!</a></li>
                </ul>
            </div>

            <!-- Column 3 -->
            <div>
                <h4 class="font-bold text-gray-800 mb-3 text-sm">บริษัท</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-orange-500">เกี่ยวกับเรา</a></li>
                    <li>✉️ support@runlah.com</li>
                    <li>🌐 https://www.facebook.com/runlah</li>
                </ul>
            </div>

            <!-- Column 4 -->
            <div>
                <h4 class="font-bold text-gray-800 mb-3 text-sm">นโยบายกฎหมาย</h4>
                <ul class="space-y-2">
                    <li>สงวนลิขสิทธิ์ (c) 2026 บริษัท อินโนสปริ้นท์ จำกัด</li>
                    <li><a href="#" class="hover:text-orange-500">ข้อตกลงการให้บริการ</a></li>
                    <li><a href="#" class="hover:text-orange-500">นโยบายความเป็นส่วนตัว</a></li>
                    <li><a href="#" class="hover:text-orange-500">ตั้งค่าคุกกี้</a></li>
                </ul>
            </div>
        </div>
    </footer>

</body>

</html>