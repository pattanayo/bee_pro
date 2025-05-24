<?php 
session_start();
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ดึงข้อมูลสินค้า
$search = $_GET['search'] ?? '';
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_name LIKE ?");
    $search_param = "%" . $search . "%";
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $query = $stmt->get_result();
} else {
    $query = mysqli_query($conn, "SELECT * FROM products");
}
$rows = mysqli_num_rows($query);



$base_url = "http://" . $_SERVER['HTTP_HOST'] . "/bee_pro";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>List Product</title>

  <link href="<?php echo $base_url; ?>/assets/css/bootstrap.min.css" rel="stylesheet">
  <script src="<?php echo $base_url; ?>/assets/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
    <h4 class="card-title mb-5">Product List</h4>
    <form class="mb-4" method="get" action="">
    <div class="input-group">
      <input type="text" name="search" class="form-control" placeholder="ค้นหาสินค้า..." value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES); ?>">
      <button type="submit" class="btn btn-primary">ค้นหา</button>
    </div>
  </form>

    <div class="row">
        <?php if($rows > 0):?>
            <?php while ($product = mysqli_fetch_assoc($query)): ?>
                <div class="col-md-3 mb-4 d-flex">
                    <div class="card w-100 d-flex flex-column">
                        <img src="<?php echo $base_url; ?>/upload_image/<?php echo $product['image'] ?: 'no-image.png'; ?>"
                            class="card-img-top"
                            style="height: 200px; object-fit: cover; cursor: pointer;"
                            alt="Product Image"
                            data-bs-toggle="modal"
                            data-bs-target="#productModal<?php echo $product['id']; ?>">

                        <div class="card-body">
                            <h5 class="card-title"><?php echo $product['product_name']; ?></h5>
                            <p class="card-text text-success fw-bold mb-0"><?php echo number_format($product['price'], 2); ?> บาท</p>
                            <p class="card-text text-muted"><?php echo nl2br($product['detail']); ?></p>
                            <a href="<?php echo $base_url ?>/cart-add.php?id=<?php echo $product['id'] ?>" class="btn btn-primary w-100">
                              <i class="fa-solid fa-cart-plus me-1"></i>Cart Add
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="productModal<?php echo $product['id']; ?>" tabindex="-1" aria-labelledby="productModalLabel<?php echo $product['id']; ?>" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="productModalLabel<?php echo $product['id']; ?>">
                          <?php echo $product['product_name']; ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body text-center">
                        <img src="<?php echo $base_url; ?>/upload_image/<?php echo $product['image']; ?>"
                            class="img-fluid mb-3" style="max-height: 400px;" alt="Product Image">
                        <p><strong>ราคา:</strong> <?php echo number_format($product['price'], 2); ?> บาท</p>
                        <p><strong>รายละเอียด:</strong><br><?php echo nl2br($product['detail']); ?></p>
                      </div>
                      <div class="modal-footer">
                        <a href="<?php echo $base_url ?>/cart-add.php?id=<?php echo $product['id'] ?>" class="btn btn-success">
                          <i class="fa-solid fa-cart-plus me-1"></i> เพิ่มลงตะกร้า
                        </a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                      </div>
                    </div>
                  </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
              <h4 class="text-danger">ไม่มีรายการสินค้า</h4>
            </div>
        <?php endif; ?>
    </div>
  </div>

</body>
</html>
