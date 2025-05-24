<?php
require 'config.php'; // เชื่อมต่อกับฐานข้อมูล
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email']; // เพิ่มตัวแปรนี้
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $tel = $_POST['tel'];
    
    // กำหนด role เป็น 'user' ตลอด
    $role = 'user';

    // แก้ไข SQL ให้มี 4 คอลัมน์ตรงกับ 4 ค่า
    $sql = "INSERT INTO users (username, password, role, email,tel) VALUES (?, ?, ?, ?,?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: " . $conn->error);
    }

    // ใช้ bind_param ให้ถูกต้องกับจำนวนคอลัมน์
    $stmt->bind_param("sssss", $username, $password, $role, $email,$tel);

    if ($stmt->execute()) {
        echo "✅ Registration successful!";
    } else {
        echo "❌ Error: " . $stmt->error;
    }
}
?>
<?php
$base_url = "http://" . $_SERVER['HTTP_HOST'] . "/bee_pro";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title?>Registration System PDO</title>
    <link href="<?php echo $base_url; ?>/assets/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <div class="container">
    <h3 class="mt-4">สมัครสมาชิก</h3>
<form method="POST">
<div class="mb-3">
    <label for="username" class="form-label">username</label>
    <input type="text" class="form-control"name="username" aria-describedby="username">
   
  
  <div class="mb-3">
    <label for="email" class="form-label">email</label>
    <input type="email" class="form-control" name="email"aria-describedby="email">
  
  </div>
  <div class="mb-3">
    <label for="Password" class="form-label">password</label>
    <input type="password" class="form-control" name="password" id="Password1">
  
  </div>
  <div class="mb-3">
    <label for="tel" class="form-label">tel</label>
    <input type="tel" class="form-control" name="tel" id="tel">
  
  </div>
  <button type="submit"name="signup" class="btn btn-primary">SignUp</button>
      </form>
      <hr>
    <p>เป็นสมาชิดแล้วใช้ไหม คลิกที่นี้ เพื่อ<a href="login.php">เข้าสู่ระบบ</a></p>
</div>

   
       </form>
    </div>
</body>
</html>
