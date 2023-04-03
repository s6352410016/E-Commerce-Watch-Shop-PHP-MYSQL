<?php
    require_once('../config/ConnectDB.php');
    require_once('../controllers/OrderDetail.php');
    $connObj = new ConnectDB();
    $conn = $connObj->connectDB();
    $orderDetailObj = new OrderDetail($conn);
    $orderDetailObj->orderId = $_GET['orderId'];
    if($orderDetailObj->deleteOrderProductByOrderId()){
      header('Location: showOrderDetail.php');  
    }
?>