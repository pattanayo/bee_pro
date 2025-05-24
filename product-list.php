<?php 
session_start();
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ดึงข้อมูลสินค้า
$query = mysqli_query($conn, 'SELECT * FROM products');
$rows = mysqli_num_rows($query);



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
    <h4 class="card-title mb-5"> Product List</h4>
    <div class="row d-flex justify-content-center"></div>
    <div class="row">
        <?php if($rows > 0):?>
            <?php while ($product = mysqli_fetch_assoc($query)): ?>
                <div class="col-md-3 mb-4 d-flex">
                    <div class="card w-100 d-flex flex-column">
                        <?php if (!empty($product['image'])): ?>
                        <img src="<?php echo $base_url; ?>/upload_image/<?php echo $product['image']; ?>"
                            class="card-img-top"
                            style="height: 200px; object-fit: cover;"
                            alt="Product Image">
                        <?php else: ?>
                        <img src="<?php echo $base_url; ?>/assets/images/no-image.png"
                            class="card-img-top"
                            style="height: 200px; object-fit: cover;"
                            alt="No Image">
                        <?php endif; ?>
            <div class="card-body">
                <h5 class="card-title"><?php echo $product['product_name']; ?></h5>
                <p class="card-text test-success fw-bold mb-0"><?php echo number_format($product['price'], 2); ?> บาท </p>
                <p class="card-text test-muted"><?php echo nl2br($product['detail']); ?></p>
                <a href="<?php echo $base_url?>/cart-add.php?id=<?php echo $product['id']?>" class="btn btn-primary w-100"><i class="fa-solid fa-cart-plus me-1"></i>Cart Add </a>
            </div>
        </div>
      </div>
         <?php endwhile; ?>
      <?php else: ?>
        <div class="col-12" >
          <h4 class = "text-danger">ไม่มีรายการสินค้า</h4>
        </div> 
      <?php endif; ?>
    </div>
  </div>

  <script src="<?php echo $base_url; ?>/assets/js/bootstrap.min.js"></script>

</body>
</html>
