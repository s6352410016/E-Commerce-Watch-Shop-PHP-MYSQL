<?php
    class ConnectDB {
        private $host = 'localhost';
        private $username = 'root';
        private $password = '';
        private $dbName = 'e_commerce_db';
        private $conn;

        public function connectDB(){
            $this->conn = null;
            try{
                $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbName, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->conn;
            }catch(PDOException $e){
                echo 'Connection Error: ' . $e->getMessage();
            }
        }
    }
?>