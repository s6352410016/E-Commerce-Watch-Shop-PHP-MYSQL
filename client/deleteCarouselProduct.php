<?php
    require_once('../config/ConnectDB.php');
    require_once('../controllers/Carousel.php');
    $connObj = new ConnectDB();
    $conn = $connObj->connectDB();
    $carouselObj = new Carousel($conn);
    $carouselObj->carouselId = $_GET['carouselId'];
    if($carouselObj->deleteCarousel()){
      header('Location: showCarouselProduct.php');  
    }
?>