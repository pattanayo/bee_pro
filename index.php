<?php 
session_start();
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ดึงข้อมูลสินค้า
$query = mysqli_query($conn, 'SELECT * FROM products');
$rows = mysqli_num_rows($query);

// กำหนดค่าเริ่มต้นสำหรับแบบฟอร์มแก้ไข
$result = [
  'id' => '',
  'product_name' => '',
  'price' => '',
  'detail' => '',
  'image' => '',
  'category_id' => ''
];

// ดึงข้อมูลหมวดหมู่
$categories_query = mysqli_query($conn, "SELECT * FROM categories") or die(mysqli_error($conn));
$debug_categories = mysqli_fetch_all($categories_query, MYSQLI_ASSOC);

// ดึงอีกครั้งสำหรับแสดงใน select
$categories = mysqli_query($conn, "SELECT * FROM categories") or die(mysqli_error($conn));

$category_id = $_POST['category_id'] ?? '';
$id = $_GET['id'] ?? '';

if (!empty($id)) {
  $id = mysqli_real_escape_string($conn, $id); // ป้องกัน SQL Injection
  $query_product = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'");

  if (mysqli_num_rows($query_product) === 0) {
    header("Location: $base_url/index.php");
    exit;
  }

  $result = mysqli_fetch_assoc($query_product);
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
  <title>List Product</title>

  <link href="<?php echo $base_url; ?>/assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo $base_url; ?>/assets/css/fontawesome/css/fontawesome.min.css" rel="stylesheet">
  <link href="<?php echo $base_url; ?>/assets/css/fontawesome/css/brands.min.css" rel="stylesheet">
  <link href="<?php echo $base_url; ?>/assets/css/fontawesome/css/solid.min.css" rel="stylesheet">
</head>
<body class="bg-body-tertiary">

  <?php require_once "include/menu.php"; ?>

  <?php if (!empty($_SESSION['message'])): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      <?php echo $_SESSION['message']; ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['message']); ?>
  <?php endif; ?>

  <div class="container" style="margin-top: 30px">
    <h4 class="card-title mb-5">Home - Manage Product</h4>

    <div class="card shadow mb-4">
      <div class="card-body">
        <form action="<?php echo $base_url; ?>/product-from.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?php echo $result['id']; ?>">

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">Product name</label>
              <input type="text" name="product_name" class="form-control" value="<?php echo $result['product_name']; ?>">
            </div>

            <div class="col-md-6">
              <label class="form-label">Price</label>
              <input type="text" name="price" class="form-control" value="<?php echo $result['price']; ?>">
            </div>

            <div class="col-md-6">
              <label class="form-label">Image</label>
              <?php if (!empty($result['image'])): ?>
                <div class="mb-2">
                  <img src="<?php echo $base_url; ?>/upload_image/<?php echo htmlspecialchars($result['image']); ?>" width="120" alt="Current Image">
                </div>
              <?php endif; ?>
              <input type="file" name="image" class="form-control" accept="image/png, image/jpg, image/jpeg">
            </div>

            <div class="col-md-6">
              <label class="form-label">Category</label>
              <select name="category_id" class="form-select" id="categorySelect" onchange="toggleNewCategoryInput(this)">
                <option value="">-- เลือกหมวดหมู่ --</option>
                <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                  <?php 
                    if (!isset($cat['category_id'], $cat['name'])) continue; 
                    $selected = ($cat['category_id'] == $result['category_id']) ? 'selected' : '';
                  ?>
                  <option value="<?php echo $cat['category_id']; ?>" <?php echo $selected; ?>>
                    <?php echo htmlspecialchars($cat['name']); ?>
                  </option>
                <?php endwhile; ?>
                <option value="__new__">+ เพิ่มหมวดหมู่ใหม่...</option>
              </select>

              <div id="newCategoryWrapper" class="mt-2" style="display:none;">
                <label class="form-label">ชื่อหมวดหมู่ใหม่:</label>
                <div class="input-group">
                  <input type="text" name="new_category" id="newCategoryInput" class="form-control">
                  
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <label class="form-label">Detail</label>
              <textarea name="detail" class="form-control" rows="3"><?php echo htmlspecialchars($result['detail']); ?></textarea>
            </div>
          </div>

          <button class="btn btn-primary" type="submit">
            <i class="fa-regular fa-floppy-disk me-1"></i>
            <?php echo empty($result['id']) ? 'Create' : 'Update'; ?>
          </button>

          <a class="btn btn-secondary" href="<?php echo $base_url . '/index.php'; ?>">
            <i class="fa-solid fa-rotate-left me-1"></i> Cancel
          </a>
        </form>
      </div>
    </div>

    <div class="row">
      <div class="col-12">
        <table class="table table-bordered border-info">
          <thead>
            <tr>
              <th style="width: 100px;">Image</th>
              <th>Product Name</th>
              <th style="width: 100px;">Price</th>
              <th style="width: 150px;">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($rows > 0): ?>
              <?php while ($product = mysqli_fetch_assoc($query)): ?>
                <tr>
                  <td>
                    <?php if (!empty($product['image'])): ?>
                      <img src="<?php echo $base_url; ?>/upload_image/<?php echo $product['image']; ?>" width="100" alt="Product Image">
                    <?php else: ?>
                      <img src="<?php echo $base_url; ?>/assets/images/no-image.png" width="100" alt="No Image">
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php echo $product['product_name']; ?>
                    <div><small class="text-muted"><?php echo nl2br($product['detail']); ?></small></div>
                  </td>
                  <td><?php echo number_format($product['price'], 2); ?></td>
                  <td>
                    <a role="button" href="<?php echo $base_url; ?>/index.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-dark mb-1">
                      <i class="fa-regular fa-pen-to-square me-1"></i> Edit
                    </a>
                    <a onclick="return confirm('Are you sure you want to delete this item?');"
                       role="button"
                       href="<?php echo $base_url; ?>/product-delete.php?id=<?php echo $product['id']; ?>"
                       class="btn btn-outline-danger">
                      <i class="fa-solid fa-trash me-1"></i> Delete
                    </a>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="4">
                  <h4 class="text-center text-danger">ไม่มีรายการสินค้า</h4>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="<?php echo $base_url; ?>/assets/js/bootstrap.min.js"></script>
  <script>
    function toggleNewCategoryInput(select) {
      const wrapper = document.getElementById('newCategoryWrapper');
      wrapper.style.display = (select.value === '__new__') ? 'block' : 'none';
    }
  </script>

</body>
</html>
