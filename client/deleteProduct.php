<?php
    require_once('../config/ConnectDB.php');
    require_once('../controllers/Product.php');
    $connObj = new ConnectDB();
    $conn = $connObj->connectDB();
    $productObj = new Product($conn);
    $productObj->productId = $_GET['productId'];
    if($productObj->deleteProduct()){
      header('Location: dashboard.php');  
    }
?>