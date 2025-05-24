<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];

if ($role == 'admin') {
    echo "Welcome Admin, you can manage the system here.";
    header("Location:");
    // แสดงฟังก์ชั่นสำหรับ admin
} else {
    echo "Welcome User, this is your dashboard.";
    // แสดงฟังก์ชั่นสำหรับ user
}
?>
