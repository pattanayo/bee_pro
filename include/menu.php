<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_url = "http://" . $_SERVER['HTTP_HOST'] . "/bee_pro";
?>

<header class="d-flex justify-content-between align-items-center py-3 border-bottom bg-light shadow-sm px-3">
    <ul class="nav nav-pills">
        <li class="nav-item"><a href="<?php echo $base_url; ?>/index.php" class="nav-link">Home</a></li>
        <li class="nav-item"><a href="<?php echo $base_url;?>/product-list.php" class="nav-link">รายการสินค้า</a></li>
        <li class="nav-item">
            <a href="<?php echo $base_url;?>/cart.php" class="nav-link">
                🛒 ตะกร้า (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)
            </a>
        </li>
        <li class="nav-item"><a href="#" class="nav-link">FAQs</a></li>
        <li class="nav-item"><a href="#" class="nav-link">About</a></li>
    </ul>

    <div class="d-flex align-items-center">
        <?php if (isset($_SESSION['userid'])): ?>
            <!-- ถ้าล็อกอินแล้ว -->
            <span class="me-3 text-dark">👋 สวัสดี, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="<?php echo $base_url; ?>/profile.php" class="btn btn-outline-primary me-2">
                <i class="bi bi-person-circle"></i> โปรไฟล์
            </a>
            <a href="<?php echo $base_url; ?>/logout.php" class="btn btn-outline-danger">Logout</a>
        <?php else: ?>
            <!-- ถ้ายังไม่ได้ล็อกอิน -->
            <a href="<?php echo $base_url; ?>/login.php" class="text-dark me-3">Login</a>
            <a href="<?php echo $base_url; ?>/register.php" class="btn btn-primary">Sign up</a>
        <?php endif; ?>
    </div>
</header>
