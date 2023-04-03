<?php
    require_once('../config/ConnectDB.php');
    require_once('../controllers/Category.php');
    $connObj = new ConnectDB();
    $conn = $connObj->connectDB();
    $categoryObj = new Category($conn);
    $categoryObj->categoryId = $_GET['categoryId'];
    if($categoryObj->deleteCategory()){
      header('Location: showCategoryProduct.php');  
    }
?>