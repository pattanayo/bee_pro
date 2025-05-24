<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['userid'] = $user['userid'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: profile.php");
    } else {
        echo "Invalid login credentials.";
    }
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
    <title?>Signin</title>
    <link href="<?php echo $base_url; ?>/assets/css/bootstrap.min.css" rel="stylesheet">

    <div class="container">
    <h3 class="mt-4">เข้าสู่ระบบ</h3>
<form method="POST">
<div class="mb-3">
    <label for="email" class="form-label">email</label>
    <input type="email" class="form-control" name="email"aria-describedby="email">
  
  </div>
  <div class="mb-3">
    <label for="Password" class="form-label">password</label>
    <input type="password" class="form-control" name="password" id="Password1">
  </div>
  <button type="submit"name="login" class="btn btn-primary">Login</button>
  
</form>
 </div> 
</html>