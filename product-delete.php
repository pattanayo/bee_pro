<?php
session_start();
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);


if(!empty($_GET['id'])){
    $query_product = mysqli_query($conn, "SELECT * FROM products WHERE id = '{$_GET['id']}'");
    $resul = mysqli_fetch_assoc($query_product);
    @unlink('uplad_image/'. $resul['image']);


    $query = mysqli_query($conn,"DELETE FROM products WHERE id='{$_GET[id]}'") or die('query failed');
    mysqli_close($conn);

    if($query){
        $_SESSION['message'] = 'Product Deleted Success';
       header("Location: " . $base_url . "/index.php");
    }else{
        $_SESSION['message'] = 'Product could not be deleted';
        header("Location: " . $base_url . "/index.php");
    }

}
