<?php
    session_start();
    class User{
        public $userId;
        public $userFirstname;
        public $userLastname;
        public $userName;
        public $userPassword;
        public $modifyDate;

        private $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        public function getAllUser(){
            $strSql = 'SELECT * FROM user_tb';
            $stmt = $this->conn->prepare($strSql);
            $stmt->execute();
            return $stmt;
        }

        public function getUserById(){
            $strSql = 'SELECT * FROM user_tb WHERE userId = :userId';
            $stmt = $this->conn->prepare($strSql);
            $this->userId = intval(strip_tags(stripslashes($this->userId)));
            $stmt->bindParam(':userId', $this->userId);
            $stmt->execute();
            return $stmt;
        }

        // function to update user
        public function updateUserById(){
            $strSql = 'UPDATE user_tb SET userFirstname = :userFirstname, userLastname = :userLastname, userName = :userName, modifyDate = :modifyDate WHERE userId = :userId';
            $stmt = $this->conn->prepare($strSql);
            $this->userFirstname = htmlspecialchars(strip_tags(stripslashes($this->userFirstname)));
            $this->userLastname = htmlspecialchars(strip_tags(stripslashes($this->userLastname)));
            $this->userName = htmlspecialchars(strip_tags(stripslashes($this->userName)));
            $this->modifyDate = date('Y-m-d');
            $this->userId = intval(strip_tags(stripslashes($this->userId)));
            $stmt->bindParam(':userFirstname', $this->userFirstname);
            $stmt->bindParam(':userLastname', $this->userLastname);
            $stmt->bindParam(':userName', $this->userName);
            $stmt->bindParam(':modifyDate', $this->modifyDate);
            $stmt->bindParam(':userId', $this->userId);
            
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }  

        public function updateUserByPassword(){
            $strSql = 'UPDATE user_tb SET userFirstname = :userFirstname, userLastname = :userLastname, userName = :userName, userPassword = :userPassword, modifyDate = :modifyDate WHERE userId = :userId';
            $stmt = $this->conn->prepare($strSql);
            $password_hash = password_hash($this->userPassword, PASSWORD_DEFAULT);
            $this->userFirstname = htmlspecialchars(strip_tags(stripslashes($this->userFirstname)));
            $this->userLastname = htmlspecialchars(strip_tags(stripslashes($this->userLastname)));
            $this->userName = htmlspecialchars(strip_tags(stripslashes($this->userName)));
            $this->userPassword = htmlspecialchars(strip_tags(stripslashes($password_hash)));
            $this->modifyDate = date('Y-m-d');
            $this->userId = intval(strip_tags(stripslashes($this->userId)));
            $stmt->bindParam(':userFirstname', $this->userFirstname);
            $stmt->bindParam(':userLastname', $this->userLastname);
            $stmt->bindParam(':userName', $this->userName);
            $stmt->bindParam(':userPassword', $this->userPassword);
            $stmt->bindParam(':modifyDate', $this->modifyDate);
            $stmt->bindParam(':userId', $this->userId);
            
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }
        
        // function to delete user
        public function deleteUserById(){
            $strSql = 'DELETE FROM user_tb WHERE userId = :userId';
            $stmt = $this->conn->prepare($strSql);
            $this->userId = intval(strip_tags(stripslashes($this->userId)));
            $stmt->bindParam(':userId', $this->userId);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }

        public function registerUser(){
            $strSql = "INSERT INTO user_tb (userFirstname, userLastname, userName, userPassword , modifyDate) VALUES (:userFirstname, :userLastname, :userName, :userPassword , :modifyDate)";
            $stmt = $this->conn->prepare($strSql);
            $this->userFirstname = htmlspecialchars(strip_tags(stripslashes($this->userFirstname)));
            $this->userLastname = htmlspecialchars(strip_tags(stripslashes($this->userLastname)));
            $this->userName = htmlspecialchars(strip_tags(stripslashes($this->userName)));
            $this->userPassword = htmlspecialchars(strip_tags(stripslashes($this->userPassword)));
            $this->modifyDate = date('Y-m-d');
            $password_hash = password_hash($this->userPassword , PASSWORD_DEFAULT);
            $stmt->bindParam(':userFirstname', $this->userFirstname);
            $stmt->bindParam(':userLastname', $this->userLastname);
            $stmt->bindParam(':userName', $this->userName);
            $stmt->bindParam(':userPassword', $password_hash);
            $stmt->bindParam(':modifyDate', $this->modifyDate);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }

        public function loginUser(){
            $strSql1 = 'SELECT * FROM user_tb WHERE userName = :userName';
            $stmt1 = $this->conn->prepare($strSql1);
            $this->userName = htmlspecialchars(strip_tags(stripslashes($this->userName)));
            $this->userPassword = htmlspecialchars(strip_tags(stripslashes($this->userPassword)));
            $stmt1->bindParam(':userName', $this->userName);
            $stmt1->execute();
            $row = $stmt1->fetch(PDO::FETCH_ASSOC);
            if($stmt1->rowCount() > 0){
                if(password_verify($this->userPassword, $row['userPassword'])){
                    $_SESSION['userFirstname'] = $row['userFirstname'];
                    $_SESSION['userLastname'] = $row['userLastname'];
                    $_SESSION['userId'] = $row['userId'];
                    return true;    
                }else{
                    $_SESSION['errLogin'] = 'บัญชีผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
                    return false;
                }
            }else{
                $_SESSION['errLogin'] = 'บัญชีผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
                return false;
            }
        }
    }
?>