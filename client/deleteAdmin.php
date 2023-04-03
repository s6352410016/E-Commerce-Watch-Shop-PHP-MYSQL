<?php
    require_once('../config/ConnectDB.php');
    require_once('../controllers/Admin.php');
    $connObj = new ConnectDB();
    $conn = $connObj->connectDB();
    $adminObj = new Admin($conn);
    $adminObj->adminId = $_GET['adminId'];
    if($adminObj->deleteAdminById()){
      header('Location: showAdmin.php');  
    }
?>