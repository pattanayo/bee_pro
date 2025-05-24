<?php 
session_start();
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$productIds=[];
foreach(($_SESSION['cart']?? []) as $cartId => $cartQty ){
    $productIds[]=$cartId;

}

$ids = 0 ;
if (count($productIds) > 0  ) {
    $ids = implode( ', ', $productIds);

}



// ดึงข้อมูลสินค้า
$query = mysqli_query($conn, "SELECT * FROM products WHERE id IN ($ids)");
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
  <title>Cart</title>

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
    <h4 class="card-title mb-5"> Cart </h4>
    <div class="row "></div>
   <div class = "col-12">
    <form action="<?php echo $base_url; ?>/cart-update.php"method="post">
    <table class="table table-bordered border-info">
          <thead>
            <tr>
              <th style="width: 100px;">Image</th>
              <th>Product Name</th>
              <th style="width: 200px;">Price</th>
              <th style="width: 100px;">Quantity</th>
              <th style="width: 200px;">Total</th>
              <th style="width: 120px;">Action</th>
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
                    <input type="number"
                          name="product[<?php echo $product['id']; ?>][quantity]"
                          value="<?php echo $_SESSION['cart'][$product['id']] ?? 0 ?>"
                          class="form-control">
                  </td>

                  <td><?php echo number_format($product['price'] * $_SESSION['cart'][$product['id']], 2); ?></td>
                  <td>
                    <a onclick="return confirm('Are you sure you want to delete this item?');"
                       role="button"
                       href="<?php echo $base_url; ?>/cart-delete.php?id=<?php echo $product['id']; ?>"
                       class="btn btn-outline-danger">
                      <i class="fa-solid fa-trash me-1"></i> Delete
                    </a>

                  </td>
                </tr>
              <?php endwhile; ?>
              <tr>
                <td colspan='6'class="text-end">
                  <button type="submit" class="btn btn-ig btn-success">Update Cart</button>
                  <a href="<?php echo $base_url ; ?>/ckeckout.php" class="btn btn-lg btn-primary">Checkout Order</a>

                </td>
              </tr>
            <?php else: ?>
              <tr>
                <td colspan="6">
                  <h4 class="text-center text-danger">ไม่มีรายการสินค้า</h4>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>

    </form>
 
        
   </div>
    </div>
  </div>

  <script src="<?php echo $base_url; ?>/assets/js/bootstrap.min.js"></script>

</body>
</html>
