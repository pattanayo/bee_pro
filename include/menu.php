<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'config.php';


$sql = "
    SELECT c.category_id, c.name AS category_name, g.group_id, g.group_name
    FROM categories c
    LEFT JOIN category_groups g ON c.group_id = g.group_id
    ORDER BY g.group_name, c.name
";

$result = $conn->query($sql);
$categories_by_group = [];
while ($row = $result->fetch_assoc()) {
  $group = $row['group_name'] ? trim($row['group_name']) : 'ไม่ระบุกลุ่ม';
    $categories_by_group[$group][] = $row;
}


?>


<header class="d-flex justify-content-between align-items-center py-3 border-bottom bg-light shadow-sm px-3">
  <ul class="nav nav-pills">
                <style>
                  .dropdown-submenu {
                  position: relative;
                }

                .dropdown-submenu > .dropdown-menu {
                  top: 0;
                  left: 100%;
                  margin-top: -1px;
                }
            </style>

    <li class="nav-item"><a href="<?= $base_url; ?>/index.php" class="nav-link">Home</a></li>
    <li class="nav-item"><a href="<?= $base_url; ?>/product-list.php" class="nav-link">รายการสินค้า</a></li>

    <li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
    หมวดหมู่สินค้า
  </a>
  <ul class="dropdown-menu">
    <?php foreach ($categories_by_group as $group_name => $categories): ?>
      <li class="dropdown-submenu">
        <a class="dropdown-item dropdown-toggle" href="#"><?= htmlspecialchars($group_name); ?></a>
        <ul class="dropdown-menu">
          <?php foreach ($categories as $cat): ?>
            <li>
              <a class="dropdown-item" href="<?= $base_url; ?>/product-list.php?category_id=<?= $cat['category_id']; ?>">
                <?= htmlspecialchars($cat['category_name']); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </li>
    <?php endforeach; ?>
  </ul>
</li>

    <li class="nav-item"><a href="#" class="nav-link">About</a></li>
    <li class="nav-item">
      <a href="<?= $base_url; ?>/cart.php" class="nav-link">
        🛒 ตะกร้า (<?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)
      </a>
    </li>
  </ul>

  <div class="d-flex align-items-center">
    <?php if (isset($_SESSION['userid'])): ?>
      <span class="me-3 text-dark">👋 สวัสดี, <?= htmlspecialchars($_SESSION['username']); ?></span>
      <a href="<?= $base_url; ?>/profile.php" class="btn btn-outline-primary me-2">
        <i class="bi bi-person-circle"></i> โปรไฟล์
      </a>
      <a href="<?= $base_url; ?>/logout.php" class="btn btn-outline-danger">Logout</a>
    <?php else: ?>
      <a href="<?= $base_url; ?>/login.php" class="text-dark me-3">Login</a>
      <a href="<?= $base_url; ?>/register.php" class="btn btn-primary">Sign up</a>
    <?php endif; ?>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.dropdown-submenu > a').forEach(function (element) {
      element.addEventListener('mouseenter', function (e) {
        let submenu = this.nextElementSibling;
        if (submenu) {
          submenu.classList.add('show');
        }
      });
      element.parentElement.addEventListener('mouseleave', function () {
        let submenu = this.querySelector('.dropdown-menu');
        if (submenu) {
          submenu.classList.remove('show');
        }
      });
    });
  });
</script>

</header>
