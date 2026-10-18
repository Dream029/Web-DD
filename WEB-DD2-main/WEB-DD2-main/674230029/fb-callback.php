<?php
session_start();

$app_id = 'YOUR_FACEBOOK_APP_ID';
$app_secret = 'YOUR_FACEBOOK_APP_SECRET'; // นำ App Secret มาใส่ตรงนี้
$redirect_uri = 'http://localhost/fb-callback.php';

// ตรวจสอบว่ามี Code ส่งมาจาก Facebook หรือไม่
if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // 1. นำ code ไปแลก Access Token จาก Facebook
    $token_url = "https://graph.facebook.com/v18.0/oauth/access_token?" . http_build_query([
        'client_id' => $app_id,
        'redirect_uri' => $redirect_uri,
        'client_secret' => $app_secret,
        'code' => $code
    ]);

    $response = file_get_contents($token_url);
    $params = json_decode($response, true);

    if (isset($params['access_token'])) {
        $access_token = $params['access_token'];

        // 2. ดึงข้อมูลโปรไฟล์ผู้ใช้ (ID, ชื่อ, อีเมล)
        $graph_url = "https://graph.facebook.com/v18.0/me?" . http_build_query([
            'fields' => 'id,name,email,picture',
            'access_token' => $access_token
        ]);

        $user_data = json_decode(file_get_contents($graph_url), true);

        // ตัวอย่างการเก็บข้อมูลลง Session เพื่อนำไปใช้งานต่อในระบบ
        $_SESSION['user_email'] = $user_data['email'] ?? '';
        $_SESSION['user_name'] = $user_data['name'] ?? '';
        $_SESSION['fb_id'] = $user_data['id'] ?? '';

        // ส่งผู้ใช้ไปยังหน้าลงทะเบียนวิ่งต่อ
        header('Location: index.php?status=success');
        exit;
    }
}

// กรณีเกิดข้อผิดพลาด ให้เด้งกลับหน้า Login
header('Location: register.php?error=fb_failed');
exit;
?>