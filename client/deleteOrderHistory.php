<?php
    require_once('../config/ConnectDB.php');
    require_once('../controllers/OrderDetail.php');
    $connObj = new ConnectDB();
    $conn = $connObj->connectDB();
    $orderDetail = new OrderDetail($conn);
    $orderDetail->orderId = $_GET['orderId'];
    if($orderDetail->deleteOrderProductByOrderId()){
      header('Location: orderhistory.php');  
    }
?>