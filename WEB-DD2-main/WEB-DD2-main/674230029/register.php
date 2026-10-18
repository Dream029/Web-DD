<?php
// register.php - หน้าเข้าสู่ระบบ/สมัครสมาชิก
$event_id = $_GET['event_id'] ?? 'nakhonsawan-2026';
$error_message = '';
$success_message = '';

// ตรวจสอบการส่งฟอร์ม (POST Request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error_message = 'กรุณากรอกข้อมูลให้ครบถ้วนทั้งอีเมลและรหัสผ่าน';
    } else {
        // ประมวลผลเข้าสู่ระบบหรือนำทางไปกรอกข้อมูลผู้สมัครขั้นต่อไป
        $success_message = "เข้าสู่ระบบสำเร็จ! กำลังนำคุณไปยังหน้ากรอกข้อมูลผู้แข่งขัน...";
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบเพื่อสมัคร - NAKHONSAWAN SPEED TRAIL</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 flex flex-col items-center justify-center min-h-screen py-10 px-4">

    <!-- Header Logo & Event Title -->
    <div class="text-center mb-6 space-y-1">
        <!-- Mountain Logo Icon -->
        <div class="flex justify-center mb-2">
            <svg class="w-20 h-12 text-black" viewBox="0 0 100 50" fill="currentColor">
                <path d="M 10 40 L 30 15 L 45 30 L 60 5 L 80 40 Z" fill="none" stroke="currentColor" stroke-width="4"
                    stroke-linejoin="round" />
                <path d="M 25 40 L 40 20 L 55 40 Z" fill="currentColor" />
            </svg>
        </div>
        <h1 class="text-2xl font-black tracking-widest text-gray-900 uppercase">IT NPRU</h1>
        <div class="flex items-center justify-center space-x-2 text-sm font-bold tracking-wider text-gray-800">
            <span class="h-[2px] w-8 bg-black"></span>
            <span>SPEED TRAIL</span>
            <span class="h-[2px] w-8 bg-black"></span>
        </div>
        <p class="text-xs text-gray-600 font-medium">IT NPRU</p>
        <p class="text-[11px] text-gray-400">ระบบรับสมัครโดย Run IT</p>
    </div>

    <!-- Login Card Box -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 max-w-sm w-full p-6 sm:p-8 space-y-5">

        <h2 class="text-center font-bold text-gray-800 text-sm">เข้าสู่ระบบเพื่อสมัคร</h2>

        <!-- Alert Error/Success -->
        <?php if ($error_message): ?>
            <div class="p-3 bg-red-50 text-red-600 text-xs rounded-xl text-center font-medium">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="p-3 bg-green-50 text-green-600 text-xs rounded-xl text-center font-medium">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <!-- Social Login Buttons -->
        <div class="space-y-2.5">
            <!-- Facebook Button -->
            <!-- ปุ่ม Facebook ใน register.php -->
            <a href="fb-login.php"
                class="w-full flex items-center justify-center space-x-2 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-medium rounded-full transition">
                <svg class="w-4 h-4 text-blue-600 fill-current" viewBox="0 0 24 24">
                    <path
                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                </svg>
                <span>เข้าสู่ระบบด้วยเฟซบุ๊ก</span>
            </a>

            <!-- Google Button -->
            <a href="oauth_connect.php?provider=google"
                class="w-full flex items-center justify-center space-x-2 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-medium rounded-full transition">
                <svg class="w-4 h-4" viewBox="0 0 24 24">
                    <path fill="#4285F4"
                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                    <path fill="#34A853"
                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                    <path fill="#FBBC05"
                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" />
                    <path fill="#EA4335"
                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" />
                </svg>
                <span>เข้าสู่ระบบด้วยกูเกิล</span>
            </a>

            <!-- LINE Button -->
            <a href="oauth_connect.php?provider=line"
                class="w-full flex items-center justify-center space-x-2 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-medium rounded-full transition">
                <div
                    class="w-4 h-4 bg-[#00C300] rounded-full flex items-center justify-center text-white text-[8px] font-bold">
                    LINE
                </div>
                <span>เข้าสู่ระบบด้วย LINE</span>
            </a>
        </div>

        <!-- Divider -->
        <div class="relative flex py-1 items-center">
            <div class="flex-grow border-t border-gray-200"></div>
            <span class="flex-shrink mx-3 text-gray-400 text-xs font-light">หรือ</span>
            <div class="flex-grow border-t border-gray-200"></div>
        </div>

        <!-- Email & Password Form -->
        <form action="runner-info.php" method="POST" class="space-y-3">
            <div>
                <label for="email" class="block text-xs font-medium text-gray-700 mb-1">อีเมล <span
                        class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" required placeholder="example@email.com"
                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
            </div>

            <div>
                <label for="password" class="block text-xs font-medium text-gray-700 mb-1">รหัสผ่าน <span
                        class="text-red-500">*</span></label>
                <input type="password" id="password" name="password" required placeholder="••••••••"
                    class="w-full px-3 py-2 bg-white border border-gray-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
            </div>

            <button type="submit"
                class="w-full py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-full transition mt-2">
                ต่อไป
            </button>
        </form>

        <!-- Register Link -->
        <div class="text-center pt-2">
            <p class="text-[11px] text-gray-600">
                หากคุณไม่เคยเข้ามาเยี่ยมชม <a href="#"
                    class="underline font-semibold text-gray-800 hover:text-orange-500">สมัครสมาชิกก่อน</a>
            </p>
        </div>

    </div>

    <!-- Language Selector Bottom -->
    <div class="mt-6 flex items-center space-x-3 text-xs text-gray-500">
        <button class="px-3 py-1 rounded-md hover:bg-gray-200 transition">US เอ็น</button>
        <button class="px-3 py-1 bg-gray-200 text-gray-800 rounded-md font-semibold">TH ไทย</button>
    </div>

</body>

</html>