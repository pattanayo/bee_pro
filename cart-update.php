<?php
session_start();
include 'config.php';

if (isset($_POST['product']) && is_array($_POST['product'])) {
    foreach ($_POST['product'] as $productId => $item) {
        $qty = (int) ($item['quantity'] ?? 0);

        if ($qty > 0) {
            $_SESSION['cart'][$productId] = $qty;
        } else {
            unset($_SESSION['cart'][$productId]); // ลบสินค้าที่จำนวนเป็น 0
        }
    }
}

$_SESSION['message'] = 'อัปเดตตะกร้าเรียบร้อยแล้ว';
header('Location: ' . $base_url . '/cart.php');
exit;
