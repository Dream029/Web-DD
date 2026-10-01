<?php
require_once 'config/db.php';

// ดึงงานที่มอบหมายให้ช่าง
$tasks = [];
if ($db_connected) {
    $res = $conn->query("SELECT t.*, c.category_name FROM tickets t LEFT JOIN categories c ON t.category_id = c.id ORDER BY t.id DESC");
    while ($row = $res->fetch_assoc()) {
        $tasks[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ส่วนงานช่างซ่อม - AC Repair System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.net/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background: #f1f5f9;
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
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
        }
    </style>
</head>

<body>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark"><i class="fa-solid fa-wrench text-primary me-2"></i>รายการงานซ่อมแอร์
                (สำหรับช่าง)</h3>
            <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-house me-1"></i>
                กลับหน้าหลัก</a>
        </div>

        <div class="row g-3">
            <?php foreach ($tasks as $task): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge bg-secondary"><?php echo $task['ticket_number']; ?></span>
                                    <span class="badge bg-primary px-2 py-1"><?php echo $task['status']; ?></span>
                                </div>
                                <h5 class="fw-bold mt-2 text-dark"><?php echo htmlspecialchars($task['title']); ?></h5>
                                <p class="text-muted small mb-1"><i
                                        class="fa-solid fa-location-dot text-danger me-1"></i><strong>สถานที่:</strong>
                                    <?php echo htmlspecialchars($task['location']); ?></p>
                                <p class="text-muted small mb-3"><i
                                        class="fa-solid fa-align-left me-1"></i><strong>อาการ:</strong>
                                    <?php echo htmlspecialchars($task['description']); ?></p>
                            </div>

                            <button class="btn btn-primary w-100 fw-semibold" data-bs-toggle="modal"
                                data-bs-target="#editModal<?php echo $task['id']; ?>">
                                <i class="fa-solid fa-pen-to-square me-1"></i> อัปเดตงานซ่อม
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal บันทึกงานซ่อม -->
                <div class="modal fade" id="editModal<?php echo $task['id']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content border-0 shadow rounded-4">
                            <div class="modal-header bg-dark text-white rounded-top-4">
                                <h5 class="modal-title"><i class="fa-solid fa-pen-to-square me-2"></i>จัดการงานซ่อม:
                                    <?php echo $task['ticket_number']; ?></h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="technician.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="update_work">
                                <input type="hidden" name="ticket_id" value="<?php echo $task['id']; ?>">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">อัปเดตสถานะ</label>
                                        <select name="status" class="form-select">
                                            <option value="in_progress" <?php echo $task['status'] === 'in_progress' ? 'selected' : ''; ?>>กำลังซ่อม/กำลังล้าง
                                            </option>
                                            <option value="completed" <?php echo $task['status'] === 'completed' ? 'selected' : ''; ?>>ซ่อมเสร็จสิ้น</option>
                                            <option value="cancelled" <?php echo $task['status'] === 'cancelled' ? 'selected' : ''; ?>>ยกเลิกรายการ</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">บันทึกการซ่อม / เติมน้ำยา /
                                            เปลี่ยนอะไหล่</label>
                                        <textarea name="repair_note" class="form-control"
                                            rows="3"><?php echo htmlspecialchars($task['repair_note'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">ค่าใช้อะไหล่/ค่าบริการ (บาท)</label>
                                        <input type="number" step="0.01" name="cost" class="form-control"
                                            value="<?php echo $task['cost']; ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">แนบรูปถ่ายหลังซ่อมเสร็จ</label>
                                        <input type="file" name="image_after" class="form-control" accept="image/*">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                    <button type="submit" class="btn btn-success fw-semibold"><i
                                            class="fa-solid fa-floppy-disk me-1"></i> บันทึกผลงาน</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php
    // อัปเดตการทำงานของช่าง
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_work') {
        $ticket_id = $_POST['ticket_id'];
        $status = $_POST['status'];
        $repair_note = trim($_POST['repair_note']);
        $cost = floatval($_POST['cost']);

        $image_after_sql = "";
        if (isset($_FILES['image_after']) && $_FILES['image_after']['error'] == 0) {
            $ext = pathinfo($_FILES['image_after']['name'], PATHINFO_EXTENSION);
            $filename = time() . '_after_' . uniqid() . '.' . $ext;
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }
            if (move_uploaded_file($_FILES['image_after']['tmp_name'], 'uploads/' . $filename)) {
                $image_after_sql = ", image_after = '$filename'";
            }
        }

        $completed_sql = ($status === 'completed') ? ", completed_at = NOW()" : "";

        $sql = "UPDATE tickets SET status = '$status', repair_note = '$repair_note', cost = '$cost' $image_after_sql $completed_sql WHERE id = '$ticket_id'";
        if ($conn->query($sql)) {
            echo "<script>
            Swal.fire({
                title: 'บันทึกสำเร็จ!',
                text: 'อัปเดตผลการซ่อมเรียบร้อยแล้ว',
                icon: 'success',
                confirmButtonText: 'ตกลง'
            }).then(() => { window.location.href='technician.php'; });
        </script>";
        }
    }
    ?>
</body>

</html>