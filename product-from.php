<?php
session_start();
require_once 'config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$product_name = trim($_POST['product_name']);
$price = $_POST['price'] ?: 0;
$detail = isset($_POST['detail']) ? trim($_POST['detail']) : '';
$image_name = $_FILES['image']['name'];
$image_tmp = $_FILES['image']['tmp_name'];
$folder = 'upload_image/';
$image_location = $folder . $image_name;

// กำหนด category ก่อนใช้
if (!empty($_POST['new_category'])) {
    $new_cat = mysqli_real_escape_string($conn, $_POST['new_category']);
    mysqli_query($conn, "INSERT INTO categories (name) VALUES ('$new_cat')") or die('Insert category failed: ' . mysqli_error($conn));
    $category_id = mysqli_insert_id($conn);
} else {
    $category_id = $_POST['category_id'] ?? null;
}

if (empty($_POST['id'])) {
    // INSERT
    $query = mysqli_query($conn, "INSERT INTO products (product_name, price, image, detail, category_id) 
    VALUES ('{$product_name}', '{$price}', '{$image_name}', '{$detail}', '{$category_id}')") 
    or die('Insert failed: ' . mysqli_error($conn));
} else {
    // UPDATE
    $query_product = mysqli_query($conn, "SELECT * FROM products WHERE id = '{$_POST['id']}'") or die(mysqli_error($conn));
    $resul = mysqli_fetch_assoc($query_product);

    if (empty($image_name)) {
        $image_name = $resul['image'];
    } else {
        @unlink($folder . $resul['image']);
    }

    $query = mysqli_query($conn, "UPDATE products 
        SET product_name='{$product_name}', 
            price='{$price}', 
            image='{$image_name}', 
            detail='{$detail}', 
            category_id='{$category_id}' 
        WHERE id='{$_POST['id']}'") 
        or die('Update failed: ' . mysqli_error($conn));
}

if ($query) {
    if (!empty($image_tmp)) {
        move_uploaded_file($image_tmp, $image_location);
    }
    $_SESSION['message'] = 'Product Saved Success';
} else {
    $_SESSION['message'] = 'Product Save Failed';
}

mysqli_close($conn);
header("Location: " . $base_url . "/index.php");
exit;
?>
