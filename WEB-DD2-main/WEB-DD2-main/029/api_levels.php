<?php
// กำหนด Header ให้ส่งออกเป็น JSON UTF-8
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

try {
    $db = new MathPuzzleDB();

    // 1. ตรวจสอบข้อมูลในฐานข้อมูล SQLite
    $existingLevels = $db->getAllLevels();

    // 2. ถ้าฐานข้อมูลยังว่างเปล่า ให้ดึงข้อมูลจาก levels.json มาบันทึกลงฐานข้อมูลออโต้ (Auto-Seed)
    if (empty($existingLevels) && file_exists('levels.json')) {
        $jsonContent = file_get_contents('levels.json');
        $levelsData = json_decode($jsonContent, true);

        if (is_array($levelsData)) {
            foreach ($levelsData as $level) {
                $db->saveLevel(
                    $level['id'],
                    $level['category'],
                    $level['text'],
                    $level['answers'],
                    $level['choices']
                );
            }
            // ดึงข้อมูลใหม่อีกครั้งหลังจากบันทึกสำเร็จ
            $existingLevels = $db->getAllLevels();
        }
    }

    // 3. ส่งข้อมูลโจทย์ทั้งหมดกลับไปให้หน้าเว็บ
    echo json_encode($existingLevels, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Error processing request: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>