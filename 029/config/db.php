<?php
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "repair_db";

$conn = null;
$db_connected = false;

mysqli_report(MYSQLI_REPORT_OFF);

try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if (!$conn->connect_error) {
        $db_connected = true;
        $conn->set_charset("utf8mb4");
    }
} catch (Exception $e) {
    $db_connected = false;
}
?>