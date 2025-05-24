<?php
session_start();
session_unset(); // เคลียร์ตัวแปร session ทั้งหมด
session_destroy(); // จบ session
header("Location: index.php"); // หรือเปลี่ยนเส้นทางกลับหน้าหลัก
exit();
