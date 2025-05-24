<?php
session_start();
$base_url = "http://" . $_SERVER['HTTP_HOST'] . "/bee_pro";
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Order Success</title>
  <link href="<?php echo $base_url; ?>/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<?php include 'include/menu.php'; ?>

<div class="container py-5">
  <div class="text-center">
    <h1 class="text-success"><i class="bi bi-check-circle-fill"></i> ขอบคุณสำหรับการสั่งซื้อ!</h1>
    <p class="lead">คำสั่งซื้อของคุณได้รับการบันทึกเรียบร้อยแล้ว</p>
    <a href="<?php echo $base_url; ?>/index.php" class="btn btn-primary mt-4">กลับสู่หน้าหลัก</a>
  </div>
</div>

<script src="<?php echo $base_url; ?>/assets/js/bootstrap.min.js"></script>
</body>
</html>
