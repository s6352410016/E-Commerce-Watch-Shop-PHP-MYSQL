<?php
    class Product{
        public $productId;
        public $productName;
        public $productPrice;
        public $productImage;
        public $categoryId;
        public $modifyDate;

        private $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        // Get all products
        public function getAllProducts(){
            $strSql = 'SELECT * FROM product_tb';
            $stmt = $this->conn->prepare($strSql);
            $stmt->execute();
            return $stmt;
        }

        // Get product by id
        public function getProductById(){
            $strSql = 'SELECT * FROM product_tb WHERE productId = :productId';
            $stmt = $this->conn->prepare($strSql);
            $this->productId = intval(strip_tags(stripslashes($this->productId)));
            $stmt->bindParam(':productId', $this->productId);
            $stmt->execute();
            return $stmt;
        }

        // Get product by category id
        public function getProductByCategoryId(){
            $strSql = 'SELECT * FROM product_tb WHERE categoryId = :categoryId';
            $stmt = $this->conn->prepare($strSql);
            $this->categoryId = intval(strip_tags(stripslashes($this->categoryId)));
            $stmt->bindParam(':categoryId', $this->categoryId);
            $stmt->execute();
            return $stmt;
        }

        // Get product by name
        public function getProductByName(){
            $strSql = 'SELECT * FROM product_tb WHERE productName LIKE :productName';
            $stmt = $this->conn->prepare($strSql);
            $this->productName = htmlspecialchars(strip_tags(stripslashes($this->productName)));
            $stmt->bindValue(':productName', "%$this->productName%");
            $stmt->execute();
            return $stmt;
        }

        // create product
        public function createProduct(){
            $strSql = 'INSERT INTO product_tb (productName , productPrice , productImage , categoryId , modifyDate) VALUES(:productName , :productPrice , :productImage , :categoryId , :modifyDate)';
            $stmt = $this->conn->prepare($strSql);
            $this->productName = htmlspecialchars(strip_tags(stripslashes($this->productName)));
            $this->productPrice = intval(strip_tags(stripslashes($this->productPrice)));
            $this->productImage = htmlspecialchars(strip_tags(stripslashes($this->productImage)));
            $this->categoryId = intval(strip_tags(stripslashes($this->categoryId)));
            $this->modifyDate = date('Y-m-d');
            $stmt->bindParam(':productName', $this->productName);
            $stmt->bindParam(':productPrice', $this->productPrice);
            $stmt->bindParam(':productImage', $this->productImage);
            $stmt->bindParam(':categoryId', $this->categoryId);
            $stmt->bindParam(':modifyDate', $this->modifyDate);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }

        // update product
        public function updateProductWithImage(){
            $strSql = 'UPDATE product_tb SET productName = :productName , productPrice = :productPrice , productImage = :productImage , categoryId = :categoryId , modifyDate = :modifyDate WHERE productId = :productId';  
            $stmt = $this->conn->prepare($strSql);
            $this->productId = intval(strip_tags(stripslashes($this->productId)));
            $this->productName = htmlspecialchars(strip_tags(stripslashes($this->productName)));
            $this->productPrice = intval(strip_tags(stripslashes($this->productPrice)));
            $this->productImage = htmlspecialchars(strip_tags(stripslashes($this->productImage)));
            $this->categoryId = intval(strip_tags(stripslashes($this->categoryId)));
            $this->modifyDate = date('Y-m-d');
            $stmt->bindParam(':productId', $this->productId);
            $stmt->bindParam(':productName', $this->productName);
            $stmt->bindParam(':productPrice', $this->productPrice);
            $stmt->bindParam(':productImage', $this->productImage);
            $stmt->bindParam(':categoryId', $this->categoryId);
            $stmt->bindParam(':modifyDate', $this->modifyDate);

            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }  
        
        public function updateProduct(){
            $strSql = 'UPDATE product_tb SET productName = :productName , productPrice = :productPrice , categoryId = :categoryId , modifyDate = :modifyDate WHERE productId = :productId';
            $stmt = $this->conn->prepare($strSql);
            $this->productId = intval(strip_tags(stripslashes($this->productId)));
            $this->productName = htmlspecialchars(strip_tags(stripslashes($this->productName)));
            $this->productPrice = intval(strip_tags(stripslashes($this->productPrice)));
            $this->categoryId = intval(strip_tags(stripslashes($this->categoryId)));
            $this->modifyDate = date('Y-m-d');
            $stmt->bindParam(':productId', $this->productId);
            $stmt->bindParam(':productName', $this->productName);
            $stmt->bindParam(':productPrice', $this->productPrice);
            $stmt->bindParam(':categoryId', $this->categoryId);
            $stmt->bindParam(':modifyDate', $this->modifyDate);

            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }

        // delet product
        public function deleteProduct(){
            $strSql = 'DELETE FROM product_tb WHERE productId = :productId';
            $stmt = $this->conn->prepare($strSql);
            $this->productId = intval(strip_tags(stripslashes($this->productId)));
            $stmt->bindParam(':productId', $this->productId);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }
}
