<?php
    require_once('../config/ConnectDB.php');
    require_once('../controllers/User.php');
    $connObj = new ConnectDB();
    $conn = $connObj->connectDB();
    $userObj = new User($conn);
    $userObj->userId = $_GET['userId'];
    if($userObj->deleteUserById()){
      header('Location: showUser.php');  
    }
?>