<?php
session_start();
require 'config.php';



$userid = $_SESSION['userid'] ?? 0;
$fullname = $_SESSION['username'] ?? 'Guest';
$order_date = date('Y-m-d H:i:s');

// ดึงข้อมูลจากตาราง users
$sql = "SELECT email, tel FROM users WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$email = $user['email'] ?? '';
$tel = $user['tel'] ?? '';


$sql = "INSERT INTO orders (userid, order_date, tel, fullname, email, grand_total) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL Prepare Error: " . $conn->error);
}

$stmt->bind_param("issssd", $userid, $order_date, $tel, $fullname, $email, $grand_total);
$stmt->execute();
$order_id = $stmt->insert_id;

// เก็บ order_details
foreach ($_SESSION['cart'] as $item) {
    if (is_array($item)) {
        $sql = "INSERT INTO order_details (order_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("SQL Prepare Error (details): " . $conn->error);
        }
        $stmt->bind_param("iisdi", $order_id, $item['id'], $item['name'], $item['price'], $item['quantity']);
        $stmt->execute();
    }
}

unset($_SESSION['cart']);
echo "✅ สั่งซื้อเรียบร้อยแล้ว";


?>


<!-- HTML ด้านล่าง -->
<form action="checkout-save.php" method="post">
  <!-- ซ่อนไว้เพื่อส่ง -->
  <input type="hidden" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>">
  <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
  <input type="hidden" name="tel" value="<?php echo htmlspecialchars($tel); ?>">

  <!-- แสดงเพื่อให้ผู้ใช้เห็นข้อมูล (readonly) -->
  <div class="mb-3">
    <label>Full Name</label>
    <input type="text" class="form-control" value="<?php echo htmlspecialchars($fullname); ?>" readonly>
  </div>
  <div class="mb-3">
    <label>Email</label>
    <input type="text" class="form-control" value="<?php echo htmlspecialchars($email); ?>" readonly>
  </div>
  <label for="tel">เบอร์โทร</label>
<input type="text" name="tel" class="form-control" required>


  <!-- ที่อยู่ยังให้กรอกได้ -->
  <div class="mb-3">
    <label>Address</label>
    <input type="text" name="address" class="form-control" required>
  </div>
