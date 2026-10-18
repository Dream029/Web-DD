<?php
session_start();

// กำหนดค่าจาก Facebook Developers Console (https://developers.facebook.com)
$app_id = 'YOUR_FACEBOOK_APP_ID'; // นำ App ID มาใส่ตรงนี้
$redirect_uri = 'http://localhost/fb-callback.php'; // URL หน้าที่จะให้ Facebook ส่งผู้ใช้กลับมา
$permissions = ['email', 'public_profile']; // สิทธิ์การเข้าถึงข้อมูลที่ขอจากผู้ใช้

// สร้าง URL สำหรับ Facebook OAuth Dialog
$params = [
    'client_id' => $app_id,
    'redirect_uri' => $redirect_uri,
    'scope' => implode(',', $permissions),
    'response_type' => 'code',
    'state' => bin2hex(random_bytes(16)) // สร้าง CSRF token เพื่อความปลอดภัย
];

$_SESSION['fb_state'] = $params['state'];

$facebook_login_url = 'https://www.facebook.com/?locale=th_TH' . http_build_query($params);

// Redirect เด้งไปยังหน้า Login ของ Facebook ทันที
header('Location: ' . $facebook_login_url);
exit;
?>