<?php
session_start();
require_once 'config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// รับค่า
$product_name = trim($_POST['product_name']);
$price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
$detail = isset($_POST['detail']) ? trim($_POST['detail']) : '';
$id = isset($_POST['id']) ? intval($_POST['id']) : null;

$folder = 'upload_image/';
$image_name = $_FILES['image']['name'];
$image_tmp = $_FILES['image']['tmp_name'];

$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
$ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
$unique_image_name = '';
$image_location = '';

// ตรวจสอบหมวดหมู่
if (!empty($_POST['new_category'])) {
    $new_cat = mysqli_real_escape_string($conn, $_POST['new_category']);
    $insert_cat = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
    $insert_cat->bind_param("s", $new_cat);
    if (!$insert_cat->execute()) {
        $_SESSION['message'] = 'Insert category failed: ' . $insert_cat->error;
        header("Location: " . $base_url . "/index.php");
        exit;
    }
    $category_id = $conn->insert_id;
    $insert_cat->close();
} else {
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : null;
}

// ตรวจสอบ category_id
if (!$category_id) {
    $_SESSION['message'] = 'Please select or create a category.';
    header("Location: " . $base_url . "/index.php");
    exit;
}

// ตรวจสอบไฟล์ภาพ
if (!empty($image_name)) {
    if (!in_array($ext, $allowed_extensions)) {
        $_SESSION['message'] = 'Invalid image file type';
        header("Location: " . $base_url . "/index.php");
        exit;
    }
    $unique_image_name = time() . '_' . basename($image_name);
    $image_location = $folder . $unique_image_name;
}

// INSERT หรือ UPDATE
if (empty($id)) {
    // INSERT
    $stmt = $conn->prepare("INSERT INTO products (product_name, price, image, detail, category_id) VALUES (?, ?, ?, ?, ?)");
    $image_to_save = $unique_image_name ?: ''; // ถ้าไม่มีไฟล์ภาพเลย
    $stmt->bind_param("sdssi", $product_name, $price, $image_to_save, $detail, $category_id);
    $success = $stmt->execute();
    $stmt->close();
} else {
    // UPDATE
    $query_product = $conn->prepare("SELECT image FROM products WHERE id = ?");
    $query_product->bind_param("i", $id);
    $query_product->execute();
    $result = $query_product->get_result();
    $old_product = $result->fetch_assoc();
    $query_product->close();

    if (empty($image_name)) {
        $unique_image_name = $old_product['image'];
    } else {
        @unlink($folder . $old_product['image']);
    }

    $stmt = $conn->prepare("UPDATE products SET product_name = ?, price = ?, image = ?, detail = ?, category_id = ? WHERE id = ?");
    $stmt->bind_param("sdssii", $product_name, $price, $unique_image_name, $detail, $category_id, $id);
    $success = $stmt->execute();
    $stmt->close();
}

// บันทึกไฟล์รูป
if ($success) {
    if (!empty($image_tmp) && !empty($image_location)) {
        if (!move_uploaded_file($image_tmp, $image_location)) {
            $_SESSION['message'] = 'Image upload failed';
        } else {
            $_SESSION['message'] = 'Product Saved Success';
        }
    } else {
        $_SESSION['message'] = 'Product Saved Success';
    }
} else {
    $_SESSION['message'] = 'Product Save Failed';
}

$conn->close();
header("Location: " . $base_url . "/index.php");
exit;
?>
