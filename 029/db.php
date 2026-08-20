<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "it_runlah";

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($host, $user, $pass, $dbname);

// ตั้งค่าให้รองรับภาษาไทย
$conn->set_charset("utf8mb4");

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>