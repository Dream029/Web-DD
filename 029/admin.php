<?php
session_start();

// ตรวจสอบการเข้าสู่ระบบ (หากยังไม่ล็อกอิน ให้ส่งกลับไปหน้า login.php)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'config/db.php';

// ป้องกันกรณีตัวแปร $conn ไม่ถูกสร้าง
if (!isset($conn) || $conn->connect_error) {
    die("Connection failed: " . ($conn->connect_error ?? "Database connection missing"));
}

// 1. เพิ่มช่างซ่อมใหม่
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_technician') {
    $fullname = trim($_POST['fullname'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($fullname !== '' && $username !== '') {
        $stmt = $conn->prepare("INSERT INTO users (username, fullname, role, phone) VALUES (?, ?, 'technician', ?)");
        if ($stmt) {
            $stmt->bind_param("sss", $username, $fullname, $phone);
            $stmt->execute();
        }
    }
    header("Location: admin.php");
    exit;
}

// 2. ลบช่างซ่อม
if (isset($_GET['del_tech'])) {
    $tech_id = intval($_GET['del_tech']);
    $conn->query("DELETE FROM users WHERE id = '$tech_id'");
    header("Location: admin.php");
    exit;
}

// 3. เพิ่มหมวดหมู่บริการแอร์
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_category') {
    $cat_name = trim($_POST['category_name'] ?? '');
    if ($cat_name !== '') {
        $stmt = $conn->prepare("INSERT INTO categories (category_name) VALUES (?)");
        if ($stmt) {
            $stmt->bind_param("s", $cat_name);
            $stmt->execute();
        }
    }
    header("Location: admin.php");
    exit;
}

// 4. ลบหมวดหมู่บริการ
if (isset($_GET['del_cat'])) {
    $cat_id = intval($_GET['del_cat']);
    $conn->query("DELETE FROM categories WHERE id = '$cat_id'");
    header("Location: admin.php");
    exit;
}

// 5. จ่ายงานให้ช่าง
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'assign_tech') {
    $ticket_id = intval($_POST['ticket_id'] ?? 0);
    $technician_id = intval($_POST['technician_id'] ?? 0);

    if ($ticket_id > 0 && $technician_id > 0) {
        $stmt = $conn->prepare("UPDATE tickets SET technician_id = ?, status = 'assigned' WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("ii", $technician_id, $ticket_id);
            $stmt->execute();
        }
    }
    header("Location: admin.php");
    exit;
}

// 6. ลบใบแจ้งซ่อม
if (isset($_GET['del_ticket'])) {
    $ticket_id = intval($_GET['del_ticket']);
    $conn->query("DELETE FROM tickets WHERE id = '$ticket_id'");
    header("Location: admin.php");
    exit;
}

// ฟังก์ชันช่วย Query ปลอดภัย
function safe_count($conn, $sql)
{
    $res = $conn->query($sql);
    if ($res && $row = $res->fetch_row()) {
        return $row[0];
    }
    return 0;
}

// สถิติ
$count_all = safe_count($conn, "SELECT COUNT(*) FROM tickets");
$count_pending = safe_count($conn, "SELECT COUNT(*) FROM tickets WHERE status='pending'");
$count_progress = safe_count($conn, "SELECT COUNT(*) FROM tickets WHERE status IN ('assigned', 'in_progress')");
$count_completed = safe_count($conn, "SELECT COUNT(*) FROM tickets WHERE status='completed'");

// ดึงรายการช่างซ่อม
$techs = [];
$res_tech = $conn->query("SELECT * FROM users WHERE role='technician' ORDER BY id DESC");
if ($res_tech) {
    while ($r = $res_tech->fetch_assoc()) {
        $techs[] = $r;
    }
}

// ดึงหมวดหมู่
$categories = [];
$res_cat = $conn->query("SELECT * FROM categories ORDER BY id DESC");
if ($res_cat) {
    while ($r = $res_cat->fetch_assoc()) {
        $categories[] = $r;
    }
}

// กรองข้อมูลรายการแจ้งซ่อม
$filter_status = $_GET['filter_status'] ?? 'all';
$where_clause = "";
if ($filter_status !== 'all') {
    $safe_status = $conn->real_escape_string($filter_status);
    $where_clause = "WHERE t.status = '$safe_status'";
}

$all_tickets = [];
$sql = "SELECT t.*, 
        IFNULL(c.category_name, 'ทั่วไป') as category_name, 
        IFNULL(u.fullname, '-') as tech_name 
        FROM tickets t 
        LEFT JOIN categories c ON t.category_id = c.id 
        LEFT JOIN users u ON t.technician_id = u.id 
        $where_clause ORDER BY t.id DESC";

$res_all = $conn->query($sql);
if (!$res_all) {
    $sql_fallback = "SELECT * FROM tickets $where_clause ORDER BY id DESC";
    $res_all = $conn->query($sql_fallback);
}

if ($res_all) {
    while ($r = $res_all->fetch_assoc()) {
        $all_tickets[] = $r;
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ผู้ดูแลระบบ - AC Repair Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.net/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #f4f6f9;
            color: #333333;
        }

        .navbar-custom {
            background-color: #0d6efd;
            padding: 14px 24px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        .card-custom {
            background: #ffffff;
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .table-custom {
            background-color: #ffffff;
        }

        .table-custom thead th {
            background-color: #f8f9fa;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }

        .btn-primary-custom {
            background-color: #0d6efd;
            border: none;
            border-radius: 8px;
            font-weight: 500;
        }

        .btn-primary-custom:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>

<body>

    <!-- Header Navbar -->
    <nav class="navbar navbar-custom mb-4">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <span class="navbar-brand text-white fw-bold fs-4 mb-0">
                <i class="fa-solid fa-user-shield me-2"></i>AC Repair Service - ผู้ดูแลระบบ
            </span>
            <div class="d-flex align-items-center gap-2">
                <span class="text-white small me-2">
                    <i class="fa-solid fa-circle-user me-1"></i>
                    <?php echo htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['username']); ?>
                </span>
                <a href="index.php" class="btn btn-light btn-sm fw-semibold rounded-3 text-primary px-3">
                    <i class="fa-solid fa-house me-1"></i> หน้าหลัก
                </a>
                <a href="logout.php" class="btn btn-outline-light btn-sm fw-semibold rounded-3 px-3">
                    <i class="fa-solid fa-right-from-bracket me-1"></i> ออกจากระบบ
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4">

        <!-- สถิติภาพรวม -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card card-custom p-3 border-start border-4 border-primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small">งานทั้งหมด</span>
                            <h2 class="fw-bold text-primary mb-0"><?php echo $count_all; ?></h2>
                        </div>
                        <i class="fa-solid fa-list-check fa-2x text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-custom p-3 border-start border-4 border-warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small">รอดำเนินการ</span>
                            <h2 class="fw-bold text-warning mb-0"><?php echo $count_pending; ?></h2>
                        </div>
                        <i class="fa-solid fa-clock fa-2x text-warning opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-custom p-3 border-start border-4 border-info">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small">กำลังดำเนินการ</span>
                            <h2 class="fw-bold text-info mb-0"><?php echo $count_progress; ?></h2>
                        </div>
                        <i class="fa-solid fa-screwdriver-wrench fa-2x text-info opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-custom p-3 border-start border-4 border-success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small">ซ่อมเสร็จสิ้น</span>
                            <h2 class="fw-bold text-success mb-0"><?php echo $count_completed; ?></h2>
                        </div>
                        <i class="fa-solid fa-circle-check fa-2x text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- ตารางจัดการใบแจ้งซ่อม -->
            <div class="col-lg-8">
                <div class="card card-custom p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 text-dark"><i
                                class="fa-solid fa-tasks text-primary me-2"></i>รายการแจ้งซ่อมทั้งหมด</h5>
                        <div class="btn-group btn-group-sm">
                            <a href="admin.php?filter_status=all"
                                class="btn <?php echo $filter_status === 'all' ? 'btn-primary' : 'btn-outline-primary'; ?>">ทั้งหมด</a>
                            <a href="admin.php?filter_status=pending"
                                class="btn <?php echo $filter_status === 'pending' ? 'btn-warning text-white' : 'btn-outline-warning'; ?>">รอดำเนินการ</a>
                            <a href="admin.php?filter_status=in_progress"
                                class="btn <?php echo $filter_status === 'in_progress' ? 'btn-info text-white' : 'btn-outline-info'; ?>">กำลังซ่อม</a>
                            <a href="admin.php?filter_status=completed"
                                class="btn <?php echo $filter_status === 'completed' ? 'btn-success' : 'btn-outline-success'; ?>">เสร็จสิ้น</a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-custom mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>รหัส</th>
                                    <th>อาการปัญหา / หัวข้อ</th>
                                    <th>สถานที่</th>
                                    <th>สถานะ</th>
                                    <th>ช่างผู้ดูแล</th>
                                    <th>มอบหมายช่าง</th>
                                    <th>จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($all_tickets)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">ไม่พบรายการแจ้งซ่อมในระบบ</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($all_tickets as $row): ?>
                                        <tr>
                                            <td><span
                                                    class="badge bg-light text-dark border"><?php echo htmlspecialchars($row['ticket_number'] ?? $row['id']); ?></span>
                                            </td>
                                            <td class="fw-semibold text-dark">
                                                <?php echo htmlspecialchars($row['title'] ?? $row['problem_type'] ?? 'แจ้งซ่อมแอร์'); ?>
                                            </td>
                                            <td class="small text-muted">
                                                <?php echo htmlspecialchars($row['location'] ?? '-'); ?>
                                            </td>
                                            <td>
                                                <?php
                                                $st = $row['status'] ?? 'pending';
                                                $st_badge = [
                                                    'pending' => 'bg-warning text-white',
                                                    'assigned' => 'bg-info text-white',
                                                    'in_progress' => 'bg-primary',
                                                    'completed' => 'bg-success',
                                                    'cancelled' => 'bg-danger'
                                                ];
                                                ?>
                                                <span class="badge <?php echo $st_badge[$st] ?? 'bg-secondary'; ?>">
                                                    <?php echo strtoupper($st); ?>
                                                </span>
                                            </td>
                                            <td class="small"><?php echo htmlspecialchars($row['tech_name'] ?? '-'); ?></td>
                                            <td>
                                                <form action="admin.php" method="POST" class="d-flex gap-1">
                                                    <input type="hidden" name="action" value="assign_tech">
                                                    <input type="hidden" name="ticket_id" value="<?php echo $row['id']; ?>">
                                                    <select name="technician_id" class="form-select form-select-sm"
                                                        style="width: 120px;" required>
                                                        <option value="">-- เลือกช่าง --</option>
                                                        <?php foreach ($techs as $tech): ?>
                                                            <option value="<?php echo $tech['id']; ?>" <?php echo isset($row['technician_id']) && $row['technician_id'] == $tech['id'] ? 'selected' : ''; ?>>
                                                                <?php echo htmlspecialchars($tech['fullname'] ?? $tech['username']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <button type="submit" class="btn btn-sm btn-primary-custom text-white"><i
                                                            class="fa-solid fa-check"></i></button>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="admin.php?del_ticket=<?php echo $row['id']; ?>"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('ยืนยันการลบรายการนี้?');"><i
                                                        class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ฝั่งขวา: จัดการช่าง + จัดการหมวดหมู่ -->
            <div class="col-lg-4">
                <!-- จัดการช่างซ่อม -->
                <div class="card card-custom p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 text-dark"><i
                                class="fa-solid fa-users-gear text-primary me-2"></i>จัดการรายชื่อช่าง</h5>
                        <button class="btn btn-sm btn-primary-custom text-white px-3" data-bs-toggle="collapse"
                            data-bs-target="#addTechForm"><i class="fa-solid fa-plus me-1"></i> เพิ่มช่าง</button>
                    </div>

                    <div class="collapse mb-3" id="addTechForm">
                        <form action="admin.php" method="POST" class="card card-body bg-light border-0 p-3 rounded-3">
                            <input type="hidden" name="action" value="add_technician">
                            <div class="mb-2">
                                <input type="text" name="fullname" class="form-control form-control-sm"
                                    placeholder="ชื่อ-นามสกุล ช่าง" required>
                            </div>
                            <div class="mb-2">
                                <input type="text" name="username" class="form-control form-control-sm"
                                    placeholder="Username เข้าสู่ระบบ" required>
                            </div>
                            <div class="mb-2">
                                <input type="text" name="phone" class="form-control form-control-sm"
                                    placeholder="เบอร์โทรศัพท์">
                            </div>
                            <button type="submit" class="btn btn-sm btn-success w-100">บันทึกช่างใหม่</button>
                        </form>
                    </div>

                    <ul class="list-group list-group-flush rounded-3">
                        <?php if (empty($techs)): ?>
                            <li class="list-group-item text-muted text-center py-2">ยังไม่มีข้อมูลช่างซ่อม</li>
                        <?php else: ?>
                            <?php foreach ($techs as $t): ?>
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center py-2 px-0 border-bottom">
                                    <div>
                                        <div class="fw-semibold text-dark small">
                                            <?php echo htmlspecialchars($t['fullname'] ?? $t['username']); ?>
                                        </div>
                                        <small class="text-muted" style="font-size: 11px;"><i
                                                class="fa-solid fa-phone me-1"></i><?php echo htmlspecialchars($t['phone'] ?? '-'); ?></small>
                                    </div>
                                    <a href="admin.php?del_tech=<?php echo $t['id']; ?>" class="text-danger"
                                        onclick="return confirm('ลบรายการนี้?');"><i class="fa-solid fa-user-minus"></i></a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- จัดการประเภทปัญหา -->
                <div class="card card-custom p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 text-dark"><i
                                class="fa-solid fa-tags text-primary me-2"></i>หมวดหมู่ปัญหา</h5>
                        <button class="btn btn-sm btn-primary-custom text-white px-3" data-bs-toggle="collapse"
                            data-bs-target="#addCatForm"><i class="fa-solid fa-plus me-1"></i> เพิ่ม</button>
                    </div>

                    <div class="collapse mb-3" id="addCatForm">
                        <form action="admin.php" method="POST" class="d-flex gap-2">
                            <input type="hidden" name="action" value="add_category">
                            <input type="text" name="category_name" class="form-control form-control-sm"
                                placeholder="ชื่อหมวดหมู่ปัญหา" required>
                            <button type="submit" class="btn btn-sm btn-success">บันทึก</button>
                        </form>
                    </div>

                    <ul class="list-group list-group-flush rounded-3">
                        <?php if (empty($categories)): ?>
                            <li class="list-group-item text-muted text-center py-2">ยังไม่มีหมวดหมู่</li>
                        <?php else: ?>
                            <?php foreach ($categories as $c): ?>
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center py-2 px-0 border-bottom">
                                    <span class="small text-dark"><?php echo htmlspecialchars($c['category_name']); ?></span>
                                    <a href="admin.php?del_cat=<?php echo $c['id']; ?>" class="text-danger small"
                                        onclick="return confirm('ลบหมวดหมู่นี้?');"><i class="fa-solid fa-xmark"></i></a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- กราฟสรุป -->
                <div class="card card-custom p-4">
                    <h5 class="fw-bold mb-3 text-dark"><i
                            class="fa-solid fa-chart-pie me-2 text-primary"></i>สัดส่วนสถานะงาน</h5>
                    <div style="max-width: 200px; margin: auto;">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const ctx = document.getElementById('statusChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['รอดำเนินการ', 'กำลังซ่อม', 'เสร็จสิ้น'],
                    datasets: [{
                        data: [<?php echo $count_pending; ?>, <?php echo $count_progress; ?>, <?php echo $count_completed; ?>],
                        backgroundColor: ['#ffc107', '#0dcaf0', '#198754']
                    }]
                },
                options: {
                    plugins: { legend: { labels: { color: '#333' } } }
                }
            });
        }
    </script>

</body>

</html>