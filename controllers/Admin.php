<?php
    session_start();

    class Admin{
        public $adminId;
        public $adminFirstname;
        public $adminLastname;
        public $adminUsername;
        public $adminPassword;
        public $modifyDate;

        private $conn;

        public function __construct($conn){
            $this->conn = $conn;
        }

        public function loginAdmin(){
            $strSql1 = 'SELECT * FROM admin_tb WHERE adminUsername = :adminUsername AND adminPassword = :adminPassword';
            $stmt1 = $this->conn->prepare($strSql1);
            $this->adminUsername = htmlspecialchars(strip_tags(stripslashes($this->adminUsername)));
            $this->adminPassword = htmlspecialchars(strip_tags(stripslashes($this->adminPassword)));
            $stmt1->bindParam(':adminUsername', $this->adminUsername);
            $stmt1->bindParam(':adminPassword', $this->adminPassword);
            $stmt1->execute();
            $row = $stmt1->fetch(PDO::FETCH_ASSOC);
            if($stmt1->rowCount() > 0){
                $_SESSION['adminFirstname'] = $row['adminFirstname'];
                $_SESSION['adminLastname'] = $row['adminLastname'];
                return true;
            }else{
                $_SESSION['errLogin'] = 'บัญชีผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
                return false;
            }
        }
    }
?>