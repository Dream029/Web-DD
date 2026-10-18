<?php
/**
 * Request — จัดการคำร้องขอเปิดหมู่เรียนพิเศษ
 */
class Request
{
    private PDO    $conn;
    private Logger $logger;
    private const TABLE = 'special_course_requests';
    private const VIEW  = 'vw_special_course_requests';

    public function __construct()
    {
        $this->conn   = Database::getInstance()->getConnection();
        $this->logger = new Logger();
    }

    /**
     * สร้างคำร้องใหม่
     */
    public function create(array $data): array
    {
        try {
            $this->conn->beginTransaction();

            // 1. สร้างข้อมูลนักศึกษาให้อัตโนมัติถ้ายังไม่มีในระบบ
            $stmtStudent = $this->conn->prepare('SELECT COUNT(*) FROM students WHERE student_id = :id');
            $stmtStudent->execute([':id' => $data['student_id']]);
            if ((int)$stmtStudent->fetchColumn() === 0) {
                $stmtInsStudent = $this->conn->prepare(
                    "INSERT INTO students (student_id, student_prefix, student_firstname, student_lastname, student_email, faculty, study_year, student_type)
                     VALUES (:id, 'นาย', 'นักศึกษา', 'ทดสอบ', :email, 'เทคโนโลยีสารสนเทศ', 4, 'regular')"
                );
                $stmtInsStudent->execute([
                    ':id'    => $data['student_id'],
                    ':email' => $data['student_id'] . '@student.ac.th'
                ]);
            }

            // 2. สร้างข้อมูลรายวิชาให้อัตโนมัติถ้ายังไม่มีในระบบ
            $stmtCourse = $this->conn->prepare('SELECT COUNT(*) FROM courses WHERE course_code = :code');
            $stmtCourse->execute([':code' => $data['course_code']]);
            if ((int)$stmtCourse->fetchColumn() === 0) {
                $stmtInsCourse = $this->conn->prepare(
                    "INSERT INTO courses (course_code, course_name_th, credit_theory, credit_practice, credit_self)
                     VALUES (:code, :name, 3, 0, 6)"
                );
                $stmtInsCourse->execute([
                    ':code' => $data['course_code'],
                    ':name' => 'วิชา ' . $data['course_code']
                ]);
            }

            // 3. ออกหมายเลขคำร้อง
            $requestNumber = $this->generateRequestNumber(
                (int)$data['semester'],
                (int)$data['academic_year']
            );

            // 4. บันทึกคำร้องลงฐานข้อมูล
            $stmt = $this->conn->prepare(
                'INSERT INTO ' . self::TABLE . '
                     (request_number, student_id, course_code,
                      semester, academic_year, reason,
                      expected_students, is_in_regular_schedule, request_date)
                 VALUES
                     (:request_number, :student_id, :course_code,
                      :semester, :academic_year, :reason,
                      :expected_students, :is_in_regular_schedule, :request_date)'
            );

            $stmt->execute([
                ':request_number'         => $requestNumber,
                ':student_id'             => $data['student_id'],
                ':course_code'            => $data['course_code'],
                ':semester'               => (int)$data['semester'],
                ':academic_year'          => (int)$data['academic_year'],
                ':reason'                 => $data['reason'],
                ':expected_students'      => (int)($data['expected_students'] ?? 1),
                ':is_in_regular_schedule' => (int)($data['is_in_regular_schedule'] ?? 0),
                ':request_date'           => date('Y-m-d'),
            ]);

            $requestId = (int)$this->conn->lastInsertId();

            $this->logger->log($requestId, 'CREATE', 'สร้างคำร้องขอเปิดหมู่เรียนพิเศษ');

            $this->conn->commit();

            return [
                'success'        => true,
                'request_id'     => $requestId,
                'request_number' => $requestNumber,
                'message'        => 'ยื่นคำร้องสำเร็จ กรุณาบันทึกหมายเลขคำร้องเพื่อติดตามผล',
            ];

        } catch (\Exception $e) {
            $this->conn->rollBack();
            error_log('[Request::create] ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการยื่นคำร้อง: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * ค้นหาตามหมายเลขคำร้อง
     */
    public function trackByNumber(string $requestNumber): array|false
    {
        $stmt = $this->conn->prepare(
            'SELECT * FROM ' . self::VIEW . ' WHERE request_number = :rn LIMIT 1'
        );
        $stmt->execute([':rn' => $requestNumber]);
        return $stmt->fetch();
    }

    /**
     * ดึงคำร้องทั้งหมด (สำหรับ Admin)
     */
    public function getAll(array $filters = []): array
    {
        $conditions = [];
        $params     = [];

        if (!empty($filters['status_id'])) {
            $conditions[]          = 'status_id = :status_id';
            $params[':status_id']  = (int)$filters['status_id'];
        }
        if (!empty($filters['semester'])) {
            $conditions[]         = 'semester = :semester';
            $params[':semester']  = (int)$filters['semester'];
        }
        if (!empty($filters['academic_year'])) {
            $conditions[]              = 'academic_year = :academic_year';
            $params[':academic_year']  = (int)$filters['academic_year'];
        }

        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

        $stmt = $this->conn->prepare(
            'SELECT * FROM ' . self::VIEW . " $where ORDER BY created_at DESC"
        );
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * อัปเดตสถานะ (อนุมัติ / ไม่อนุมัติ)
     */
    public function updateStatus(
        int    $requestId,
        int    $statusId,
        string $reviewNotes,
        string $reviewedBy,
        ?int   $instructorId = null
    ): bool {
        try {
            $this->conn->beginTransaction();

            $extra  = '';
            $params = [
                ':request_id'   => $requestId,
                ':status_id'    => $statusId,
                ':reviewed_by'  => $reviewedBy,
                ':review_notes' => $reviewNotes,
            ];

            if ($statusId === 3 && $instructorId !== null) {
                $extra                   = ', instructor_id = :instructor_id, approval_date = NOW()';
                $params[':instructor_id'] = $instructorId;
            }

            $stmt = $this->conn->prepare(
                'UPDATE ' . self::TABLE . "
                    SET status_id    = :status_id,
                        review_date  = NOW(),
                        reviewed_by  = :reviewed_by,
                        review_notes = :review_notes
                        $extra
                  WHERE request_id   = :request_id"
            );
            $stmt->execute($params);

            $statusLabel = $statusId === 3 ? 'อนุมัติ' : 'ไม่อนุมัติ';
            $this->logger->log(
                $requestId,
                'UPDATE_STATUS',
                "{$statusLabel}คำร้อง: {$reviewNotes}",
                $reviewedBy
            );

            $this->conn->commit();
            return true;

        } catch (\Exception $e) {
            $this->conn->rollBack();
            error_log('[Request::updateStatus] ' . $e->getMessage());
            return false;
        }
    }

    /**
     * รันหมายเลขคำร้องออโต้
     */
    private function generateRequestNumber(int $semester, int $academicYear): string
    {
        $yearShort = substr((string)$academicYear, -2);
        $prefix    = "SCR-{$yearShort}{$semester}-";

        $stmt = $this->conn->prepare(
            'SELECT COUNT(*) FROM ' . self::TABLE . ' WHERE request_number LIKE :prefix'
        );
        $stmt->execute([':prefix' => $prefix . '%']);
        $count = (int)$stmt->fetchColumn();

        return $prefix . str_pad((string)($count + 1), 4, '0', STR_PAD_LEFT);
    }
}