<?php 
session_start();
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ตรวจสอบว่าเข้าสู่ระบบหรือยัง
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// ดึงข้อมูลผู้ใช้
$user_id = $_SESSION['user_id'];
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($user_query);

// ประกอบชื่อเต็ม
$fullname = $user['first_name'] . ' ' . $user['last_name'];
$email = $user['email'];
$tel = $user['tel'] ?? ''; // เผื่อไม่มีในตาราง

// เตรียมตะกร้าสินค้า
$total = 0;
$cart_items = [];

if (!empty($_SESSION['cart'])) {
    $productIds = implode(",", array_keys($_SESSION['cart']));
    $result = mysqli_query($conn, "SELECT * FROM products WHERE id IN ($productIds)");
    while ($row = mysqli_fetch_assoc($result)) {
        $qty = $_SESSION['cart'][$row['id']];
        $subtotal = $row['price'] * $qty;
        $total += $subtotal;

        $cart_items[] = [
            'id' => $row['id'],
            'name' => $row['product_name'],
            'price' => $row['price'],
            'qty' => $qty
        ];
    }
}
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
  <div class="mb-3">
    <label>Phone</label>
    <input type="text" class="form-control" value="<?php echo htmlspecialchars($tel); ?>" readonly>
  </div>

  <!-- ที่อยู่ยังให้กรอกได้ -->
  <div class="mb-3">
    <label>Address</label>
    <input type="text" name="address" class="form-control" required>
  </div>
