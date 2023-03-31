<?php 
    class Category{
        public $categoryId;
        public $categoryBrand;
        public $modifyDate;

        private $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        // Get all categories
        public function getAllCategories(){
            $strSql = 'SELECT * FROM category_tb';
            $stmt = $this->conn->prepare($strSql);
            $stmt->execute();
            return $stmt;
        }

        // Get category by id
        public function getCategoryById(){
            $strSql = 'SELECT * FROM category_tb WHERE categoryId = :categoryId';
            $stmt = $this->conn->prepare($strSql);
            $this->categoryId = intval(strip_tags(stripslashes($this->categoryId)));
            $stmt->bindParam(':categoryId', $this->categoryId);
            $stmt->execute();
            return $stmt;
        }

        // create category
        public function createCategory(){
            $strSql = 'INSERT INTO category_tb (categoryBrand , modifyDate) VALUES(:categoryBrand , :modifyDate)';
            $stmt = $this->conn->prepare($strSql);
            $this->categoryBrand = htmlspecialchars(strip_tags(stripslashes($this->categoryBrand)));
            $this->modifyDate = date('Y-m-d');
            $stmt->bindParam(':categoryBrand', $this->categoryBrand);
            $stmt->bindParam(':modifyDate', $this->modifyDate);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }

        // update category
        public function updateCategory(){
            $strSql = 'UPDATE category_tb SET categoryBrand = :categoryBrand , modifyDate = :modifyDate WHERE categoryId = :categoryId';
            $stmt = $this->conn->prepare($strSql);
            $this->categoryBrand = htmlspecialchars(strip_tags(stripslashes($this->categoryBrand)));
            $this->modifyDate = date('Y-m-d');
            $this->categoryId = intval(strip_tags(stripslashes($this->categoryId)));
            $stmt->bindParam(':categoryBrand', $this->categoryBrand);
            $stmt->bindParam(':modifyDate', $this->modifyDate);
            $stmt->bindParam(':categoryId', $this->categoryId);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }

        // delete category
        public function deleteCategory(){
            $strSql = 'DELETE FROM category_tb WHERE categoryId = :categoryId';
            $stmt = $this->conn->prepare($strSql);
            $this->categoryId = intval(strip_tags(stripslashes($this->categoryId)));
            $stmt->bindParam(':categoryId', $this->categoryId);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }
    }
?>