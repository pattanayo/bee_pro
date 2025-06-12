<?php
session_start();
require 'config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$userid = $_SESSION['userid'] ?? 0;
$fullname = $_SESSION['username'] ?? 'Guest';

// ดึงข้อมูลผู้ใช้
$email = '';
$tel = '';
if ($userid) {
    $sql = "SELECT email, tel FROM users WHERE userid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $email = $user['email'] ?? '';
    $tel = $user['tel'] ?? '';
}

// สรุปยอดรวม
$cart = $_SESSION['cart'] ?? [];
$grand_total = 0;
$cart_items = [];

if (!empty($cart)) {
    $productIds = implode(',', array_keys($cart));
    $query = mysqli_query($conn, "SELECT * FROM products WHERE id IN ($productIds)");

    while ($row = mysqli_fetch_assoc($query)) {
        $product_id = $row['id'];
        $row['quantity'] = $cart[$product_id];
        $row['total'] = $row['price'] * $row['quantity'];
        $grand_total += $row['total'];
        $cart_items[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>Checkout</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <main>
    <div class="row g-5">
      <div class="col-md-7 col-lg-8">
        <h4 class="mb-3">Checkout</h4>
        <form action="checkout-form.php" method="POST">
          <div class="row g-3">
            <div class="col-sm-6">
              <label class="form-label">Full Name</label>
              <input type="text" class="form-control" name="fullname" value="<?= htmlspecialchars($fullname) ?>" readonly>
            </div>
            <div class="col-sm-6">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($email) ?>" readonly>
            </div>
            <div class="col-sm-6">
              <label class="form-label">Phone</label>
              <input type="text" class="form-control" name="tel" value="<?= htmlspecialchars($tel) ?>" required>
            </div>
            
          </div>

       

          

          <hr class="my-4">
          <button class="w-100 btn btn-primary btn-lg" type="submit">Confirm Order</button>
        </form>
      </div>

      <!-- สรุปสินค้าในตะกร้า -->
      <div class="col-md-5 col-lg-4 order-md-last">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-primary">Your cart</span>
          <span class="badge bg-primary rounded-pill"><?= count($_SESSION['cart'] ?? []) ?></span>
        </h4>
        <ul class="list-group mb-3">
          <?php foreach ($cart_items as $item): ?>
            <li class="list-group-item d-flex justify-content-between lh-sm">
              <div>
                <h6 class="my-0"><?= htmlspecialchars($item['product_name']) ?></h6>
                <small class="text-muted">x<?= $item['quantity'] ?></small>
              </div>
              <span class="text-muted">$<?= number_format($item['total'], 2) ?></span>
            </li>
          <?php endforeach; ?>


          <<li class="list-group-item d-flex justify-content-between">
            <span>Total (บาท)</span>
            <strong>$<?= number_format($grand_total, 2) ?></strong>
          </li>

      </div>
    </div>
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
