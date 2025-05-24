<?php
session_start();
include 'config.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die('ไม่มีสินค้าในตะกร้า');
}

$now = date('Y-m-d H:i:s');

// ดึงข้อมูลผู้ใช้จาก session
$user_id = $_SESSION['user_id'] ?? null;
if ($user_id) {
    $user_query = mysqli_query($conn, "SELECT * FROM users WHERE id = '{$user_id}'");
    $user = mysqli_fetch_assoc($user_query);
    $fullname = $user['first_name'] . ' ' . $user['last_name'];
    $email = $user['email'];
    $tel = $user['tel']; // สมมุติว่ามีในตาราง
} else {
    // fallback ถ้าไม่ได้ล็อกอิน
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $tel = mysqli_real_escape_string($conn, $_POST['tel']);
}

$grand_total = 0;
$product_data = [];

foreach ($_SESSION['cart'] as $productId => $qty) {
    $product_query = mysqli_query($conn, "SELECT * FROM products WHERE id = '{$productId}'");
    $product = mysqli_fetch_assoc($product_query);
    $line_total = $product['price'] * $qty;
    $grand_total += $line_total;
    $product_data[] = [
        'id' => $productId,
        'name' => $product['product_name'],
        'price' => $product['price'],
        'qty' => $qty,
        'total' => $line_total
    ];
}

// บันทึก orders
$query = mysqli_query($conn, "INSERT INTO orders (orders_date, fullname, email, tel, grand_total) 
VALUES ('{$now}', '{$fullname}', '{$email}', '{$tel}', '{$grand_total}')") or die('order insert failed');

if ($query) {
    $last_id = mysqli_insert_id($conn);

    foreach ($product_data as $p) {
        $query = mysqli_query($conn, "INSERT INTO orders_details 
        (orders_id, product_id, product_name, price, quantity, total) 
        VALUES (
            '{$last_id}', 
            '{$p['id']}', 
            '{$p['name']}', 
            '{$p['price']}', 
            '{$p['qty']}', 
            '{$p['total']}')") or die('order detail insert failed');
    }

    // ล้างตะกร้า
    unset($_SESSION['cart']);

    // ส่งผู้ใช้ไปหน้าสำเร็จ
    header("Location: order-success.php");
    exit();
}
?>
