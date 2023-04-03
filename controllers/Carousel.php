<?php
    class Carousel{
        public $carouselId; 
        public $productId; 
        public $carouselImage; 
        public $modifyDate; 

        private $conn;

        public function __construct($conn){
            $this->conn = $conn;
        }

        // function to get all carousel
        public function getAllCarousel(){
            $strSql = "SELECT * FROM carousel_tb";
            $stmt = $this->conn->prepare($strSql);
            $stmt->execute();
            return $stmt;
        }

        // function to get carousel by product id
        public function getCarouselByProductId(){
            $strSql = "SELECT * FROM carousel_tb WHERE productId = :productId";
            $stmt = $this->conn->prepare($strSql);
            $this->productId = intval(strip_tags(stripslashes($this->productId)));
            $stmt->bindParam(':productId', $this->productId);
            $stmt->execute();
            return $stmt;
        }

        
        public function getCarouselByCatrouselId(){
            $strSql = "SELECT * FROM carousel_tb WHERE carouselId = :carouselId";
            $stmt = $this->conn->prepare($strSql);
            $this->carouselId = intval(strip_tags(stripslashes($this->carouselId)));
            $stmt->bindParam(':carouselId', $this->carouselId);
            $stmt->execute();
            return $stmt;
        }

        // function to create carousel
        public function createCarousel(){
            $strSql = "INSERT INTO carousel_tb (productId, carouselImage, modifyDate) VALUES (:productId, :carouselImage, :modifyDate)";
            $stmt = $this->conn->prepare($strSql);
            $this->productId = intval(strip_tags(stripslashes($this->productId)));
            $this->carouselImage = htmlspecialchars(strip_tags(stripslashes($this->carouselImage)));
            $this->modifyDate = date('Y-m-d');
            $stmt->bindParam(':productId', $this->productId);
            $stmt->bindParam(':carouselImage', $this->carouselImage);
            $stmt->bindParam(':modifyDate', $this->modifyDate);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }

        // function to update carousel
        public function updateCarouselWithImage(){
            $strSql = "UPDATE carousel_tb SET productId = :productId , carouselImage = :carouselImage, modifyDate = :modifyDate WHERE carouselId = :carouselId";
            $stmt = $this->conn->prepare($strSql);
            $this->carouselId = intval(strip_tags(stripslashes($this->carouselId)));
            $this->productId = intval(strip_tags(stripslashes($this->productId)));
            $this->carouselImage = htmlspecialchars(strip_tags(stripslashes($this->carouselImage)));
            $this->modifyDate = date('Y-m-d');
            $stmt->bindParam(':carouselId', $this->carouselId);
            $stmt->bindParam(':productId', $this->productId);
            $stmt->bindParam(':carouselImage', $this->carouselImage);
            $stmt->bindParam(':modifyDate', $this->modifyDate);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }

        public function updateCarousel(){
            $strSql = "UPDATE carousel_tb SET productId = :productId , modifyDate = :modifyDate WHERE carouselId = :carouselId";
            $stmt = $this->conn->prepare($strSql);
            $this->carouselId = intval(strip_tags(stripslashes($this->carouselId)));
            $this->productId = intval(strip_tags(stripslashes($this->productId)));
            $this->modifyDate = date('Y-m-d');
            $stmt->bindParam(':carouselId', $this->carouselId);
            $stmt->bindParam(':productId', $this->productId);
            $stmt->bindParam(':modifyDate', $this->modifyDate);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            } 
        }

        // function to delete carousel
        public function deleteCarousel(){
            $strSql = "DELETE FROM carousel_tb WHERE carouselId = :carouselId";
            $stmt = $this->conn->prepare($strSql);
            $this->carouselId = intval(strip_tags(stripslashes($this->carouselId)));
            $stmt->bindParam(':carouselId', $this->carouselId);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }
    }
?>