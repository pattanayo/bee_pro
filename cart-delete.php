<?php
session_start();
include 'config.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);


if(!empty($_GET['id'])){
    unset($_SESSION['cart'][$_GET['id']]);
  $_SESSION['message']='Cart deleted success';
}

header('Location: ' . $base_url . '/cart.php');
exit;
?>