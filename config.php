<?php
$base_url = 'http://localhost/bee_pro';

$servername = "localhost";
$username = "root";
$password = "csrmu123"; // หรือใส่ password ถ้าคุณตั้งไว้ เช่น "csrmu123"
$dbname = "user_system";
$port = 3307; // ✅ ระบุ port ที่ถูกต้อง




// เชื่อมต่อโดยระบุ port
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

mysqli_set_charset($conn, "utf8mb4");
?>
