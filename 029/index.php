<?php
require_once 'config/db.php';

// ดึงหมวดหมู่แอร์
$categories = [];
if ($db_connected) {
    $res = $conn->query("SELECT * FROM categories");
    while ($row = $res->fetch_assoc()) {
        $categories[] = $row;
    }
}

// ค้นหาติดตามสถานะ
$search = trim($_GET['search'] ?? '');
$my_tickets = [];
if ($db_connected && $search !== '') {
    $safe_search = $conn->real_escape_string($search);
    $res = $conn->query("SELECT t.*, c.category_name, u.fullname as tech_name FROM tickets t LEFT JOIN categories c ON t.category_id = c.id LEFT JOIN users u ON t.technician_id = u.id WHERE t.ticket_number LIKE '%$safe_search%' OR t.location LIKE '%$safe_search%' ORDER BY t.id DESC");
    while ($row = $res->fetch_assoc()) {
        $my_tickets[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ระบบแจ้งซ่อมเครื่องปรับอากาศ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.net/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background: #f8fafc;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08) !important;
        }

        .btn {
            transition: all 0.2s ease;
        }

        .btn:active {
            transform: scale(0.97);
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php"><i class="fa-solid fa-snowflake me-2"></i>AC Repair
                Service</a>
            <div>
                <a href="technician.php" class="btn btn-outline-light btn-sm me-2"><i
                        class="fa-solid fa-wrench me-1"></i> สำหรับช่าง</a>
                <a href="admin.php" class="btn btn-light btn-sm"><i class="fa-solid fa-user-shield me-1"></i>
                    ผู้ดูแลระบบ</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row g-4">
            <!-- ฟอร์มแจ้งซ่อม -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h4 class="fw-bold text-primary mb-3"><i
                            class="fa-solid fa-paper-plane me-2"></i>แบบฟอร์มแจ้งซ่อมแอร์</h4>
                    <form action="index.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="create_ticket">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">ประเภทปัญหา</label>
                            <select name="category_id" class="form-select" required>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['category_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">ระดับความด่วน</label>
                            <select name="priority" class="form-select">
                                <option value="low">ปกติ</option>
                                <option value="medium" selected>ปานกลาง</option>
                                <option value="high">ด่วน</option>
                                <option value="urgent">เร่งด่วนมาก (ห้องผู้บริหาร/Server)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">สถานที่ / อาคารและเลขห้อง</label>
                            <input type="text" name="location" class="form-control"
                                placeholder="เช่น อาคาร B ชั้น 2 ห้อง 201" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">หัวข้อปัญหา / สรุปอาการ</label>
                            <input type="text" name="title" class="form-control"
                                placeholder="เช่น แอร์มีน้ำหยดลงโต๊ะทำงาน" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">รายละเอียดเพิ่มเติม</label>
                            <textarea name="description" class="form-control" rows="2"
                                placeholder="ยี่ห้อแอร์ หรือรายละเอียดเพิ่มเติม..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">แนบรูปถ่ายปัญหา (ถ้ามี)</label>
                            <input type="file" name="image_before" id="image_before_input" class="form-control"
                                accept="image/*">
                            <div class="mt-2 text-center">
                                <img id="preview-img" src="#" alt="ภาพตัวอย่าง"
                                    class="img-fluid rounded-3 d-none shadow-sm" style="max-height: 180px;">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold"><i
                                class="fa-solid fa-paper-plane me-1"></i> ส่งข้อมูลแจ้งซ่อม</button>
                    </form>
                </div>
            </div>

            <!-- ค้นหาและติดตามสถานะ -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h4 class="fw-bold text-dark mb-3"><i
                            class="fa-solid fa-magnifying-glass me-2 text-primary"></i>ติดตามสถานะงานซ่อม</h4>
                    <form action="index.php" method="GET" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control"
                            placeholder="ใส่รหัสแจ้งซ่อม (เช่น AIR-...) หรือชื่อห้อง"
                            value="<?php echo htmlspecialchars($search); ?>" required>
                        <button type="submit" class="btn btn-primary px-3 d-flex align-items-center gap-1">
                            <i class="fa-solid fa-magnifying-glass"></i> ค้นหา
                        </button>
                    </form>
                </div>

                <?php if (!empty($my_tickets)): ?>
                    <?php foreach ($my_tickets as $t): ?>
                        <div class="card border-0 shadow-sm rounded-3 mb-3 p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-primary"><i
                                        class="fa-solid fa-ticket me-1"></i><?php echo $t['ticket_number']; ?></span>
                                <span class="badge bg-primary px-3 py-2"><?php echo strtoupper($t['status']); ?></span>
                            </div>
                            <h6 class="fw-bold text-dark"><?php echo htmlspecialchars($t['title']); ?></h6>
                            <p class="text-muted small mb-1"><i
                                    class="fa-solid fa-location-dot me-1 text-danger"></i><?php echo htmlspecialchars($t['location']); ?>
                            </p>
                            <p class="text-muted small mb-2"><i class="fa-solid fa-user-gear me-1 text-primary"></i>ช่างผู้ดูแล:
                                <?php echo $t['tech_name'] ?? 'กำลังจัดสรรช่าง'; ?></p>

                            <!-- Timeline ความคืบหน้า -->
                            <div class="d-flex justify-content-between align-items-center my-3 position-relative px-2">
                                <div class="progress position-absolute w-100" style="height: 4px; z-index: 0; left:0;">
                                    <div class="progress-bar bg-success" style="width: <?php
                                    echo ($t['status'] == 'completed') ? '100%' : (($t['status'] == 'in_progress') ? '66%' : (($t['status'] == 'assigned') ? '33%' : '0%'));
                                    ?>;"></div>
                                </div>

                                <div class="text-center position-relative z-1">
                                    <span
                                        class="badge rounded-circle p-2 <?php echo ($t['status'] != 'pending') ? 'bg-success text-white' : 'bg-primary'; ?>"><i
                                            class="fa-solid fa-file-invoice"></i></span>
                                    <div class="small mt-1" style="font-size: 11px;">รับเรื่อง</div>
                                </div>
                                <div class="text-center position-relative z-1">
                                    <span
                                        class="badge rounded-circle p-2 <?php echo in_array($t['status'], ['assigned', 'in_progress', 'completed']) ? 'bg-success text-white' : 'bg-secondary'; ?>"><i
                                            class="fa-solid fa-user-gear"></i></span>
                                    <div class="small mt-1" style="font-size: 11px;">จ่ายงาน</div>
                                </div>
                                <div class="text-center position-relative z-1">
                                    <span
                                        class="badge rounded-circle p-2 <?php echo in_array($t['status'], ['in_progress', 'completed']) ? 'bg-success text-white' : 'bg-secondary'; ?>"><i
                                            class="fa-solid fa-screwdriver-wrench"></i></span>
                                    <div class="small mt-1" style="font-size: 11px;">กำลังซ่อม</div>
                                </div>
                                <div class="text-center position-relative z-1">
                                    <span
                                        class="badge rounded-circle p-2 <?php echo ($t['status'] == 'completed') ? 'bg-success text-white' : 'bg-secondary'; ?>"><i
                                            class="fa-solid fa-check"></i></span>
                                    <div class="small mt-1" style="font-size: 11px;">เสร็จสิ้น</div>
                                </div>
                            </div>

                            <?php if ($t['repair_note']): ?>
                                <div class="p-2 bg-light rounded-3 small"><strong><i
                                            class="fa-solid fa-comment-dots text-primary me-1"></i>บันทึกช่าง:</strong>
                                    <?php echo htmlspecialchars($t['repair_note']); ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php elseif ($search !== ''): ?>
                    <div class="alert alert-warning text-center rounded-3"><i
                            class="fa-solid fa-circle-exclamation me-1"></i> ไม่พบข้อมูลการแจ้งซ่อมที่ค้นหา</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Live Image Preview
        document.getElementById('image_before_input').addEventListener('change', function (e) {
            const file = e.target.files[0];
            const preview = document.getElementById('preview-img');
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('d-none');
            }
        });
    </script>

    <?php
    // การประมวลผลเมื่อกดบันทึก
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_ticket') {
        if ($db_connected) {
            $category_id = $_POST['category_id'];
            $priority = $_POST['priority'];
            $location = trim($_POST['location']);
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $ticket_number = 'AIR-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            $image_before = NULL;
            if (isset($_FILES['image_before']) && $_FILES['image_before']['error'] == 0) {
                $ext = pathinfo($_FILES['image_before']['name'], PATHINFO_EXTENSION);
                $filename = time() . '_' . uniqid() . '.' . $ext;
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0777, true);
                }
                if (move_uploaded_file($_FILES['image_before']['tmp_name'], 'uploads/' . $filename)) {
                    $image_before = $filename;
                }
            }

            $stmt = $conn->prepare("INSERT INTO tickets (ticket_number, category_id, priority, location, title, description, image_before, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");
            $stmt->bind_param("sisssss", $ticket_number, $category_id, $priority, $location, $title, $description, $image_before);

            if ($stmt->execute()) {
                echo "<script>
                Swal.fire({
                    title: 'ส่งแจ้งซ่อมสำเร็จ!',
                    text: 'รหัสติดตามของคุณคือ: $ticket_number',
                    icon: 'success',
                    confirmButtonText: 'ตกลง'
                }).then(() => { window.location.href='index.php?search=$ticket_number'; });
            </script>";
            }
        }
    }
    ?>

</body>

</html>