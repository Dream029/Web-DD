<?php
session_start();
require_once 'config/db.php';

// หากล็อกอินอยู่แล้ว ให้เปลี่ยนหน้าไปยัง admin.php
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin') {
    header("Location: admin.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($password)) {
        // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
        $stmt = $conn->prepare("SELECT id, username, password, fullname, role FROM users WHERE username = ?");
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($user = $result->fetch_assoc()) {
                // ตรวจสอบรหัสผ่าน (รองรับทั้ง password_verify หรือข้อความธรรมดา)
                if (password_verify($password, $user['password']) || $password === $user['password']) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['fullname'] = $user['fullname'];
                    $_SESSION['role'] = $user['role'];

                    header("Location: admin.php");
                    exit;
                } else {
                    $error = 'รหัสผ่านไม่ถูกต้อง';
                }
            } else {
                $error = 'ไม่พบชื่อผู้ใช้งานนี้ในระบบ';
            }
        } else {
            $error = 'เกิดข้อผิดพลาดของระบบฐานข้อมูล';
        }
    } else {
        $error = 'กรุณากรอกชื่อผู้ใช้และรหัสผ่านให้ครบถ้วน';
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>เข้าสู่ระบบ - AC Repair Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.net/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #f4f6f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: #ffffff;
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 400px;
            padding: 30px;
        }

        .btn-primary-custom {
            background-color: #0d6efd;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            padding: 10px;
        }

        .btn-primary-custom:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>

<body>

    <div class="login-card">
        <div class="text-center mb-4">
            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                style="width: 60px; height: 60px;">
                <i class="fa-solid fa-user-shield fa-2x"></i>
            </div>
            <h4 class="fw-bold text-dark mt-2">เข้าสู่ระบบผู้ดูแล</h4>
            <p class="text-muted small">AC Repair Service System</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2 small rounded-3" role="alert">
                <i class="fa-solid fa-circle-exclamation me-1"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-semibold text-dark">ชื่อผู้ใช้งาน (Username)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i
                            class="fa-solid fa-user"></i></span>
                    <input type="text" name="username" class="form-control border-start-0 bg-light"
                        placeholder="ระบุชื่อผู้ใช้งาน" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-semibold text-dark">รหัสผ่าน (Password)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i
                            class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" class="form-control border-start-0 bg-light"
                        placeholder="ระบุรหัสผ่าน" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary-custom text-white w-100 fw-semibold mb-3">
                <i class="fa-solid fa-right-to-bracket me-1"></i> เข้าสู่ระบบ
            </button>

            <div class="text-center">
                <a href="index.php" class="text-decoration-none small text-muted"><i
                        class="fa-solid fa-arrow-left me-1"></i> กลับหน้าหลัก</a>
            </div>
        </form>
    </div>

</body>

</html>