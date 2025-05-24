<?php
session_start();
require 'config.php';

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['userid'];

$sql = "SELECT username, email, tel, role FROM users WHERE userid = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("เกิดข้อผิดพลาดในการเตรียม SQL: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$base_url = "http://" . $_SERVER['HTTP_HOST'] . "/bee_pro";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>โปรไฟล์</title>
    <link href="<?php echo $base_url; ?>/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'include/menu.php'; ?>

<div class="container mt-5">
    <h3>👤 โปรไฟล์ผู้ใช้</h3>
    <div class="card mt-3 shadow">
        <div class="card-body">
            <p><strong>ชื่อผู้ใช้:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>อีเมล:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>เบอร์โทร:</strong> <?php echo htmlspecialchars($user['tel']); ?></p>
            <p><strong>สิทธิ์:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
        </div>
    </div>
</div>
</body>
</html>
