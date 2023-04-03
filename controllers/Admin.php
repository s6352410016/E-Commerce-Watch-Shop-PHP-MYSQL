<?php
    session_start();
class Admin
{
    public $adminId;
    public $adminFirstname;
    public $adminLastname;
    public $adminUsername;
    public $adminPassword;
    public $modifyDate;

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllAdmin()
    {
        $strSql = 'SELECT * FROM admin_tb';
        $stmt = $this->conn->prepare($strSql);
        $stmt->execute();
        return $stmt;
    }

    public function getAdminById()
    {
        $strSql = 'SELECT * FROM admin_tb WHERE adminId = :adminId';
        $stmt = $this->conn->prepare($strSql);
        $this->adminId = intval(strip_tags(stripslashes($this->adminId)));
        $stmt->bindParam(':adminId', $this->adminId);
        $stmt->execute();
        return $stmt;
    }

    public function updateAdminById()
    {
        $strSql = 'UPDATE admin_tb SET adminFirstname = :adminFirstname, adminLastname = :adminLastname, adminUsername = :adminUsername, modifyDate = :modifyDate WHERE adminId = :adminId';
        $stmt = $this->conn->prepare($strSql);
        $this->adminFirstname = htmlspecialchars(strip_tags(stripslashes($this->adminFirstname)));
        $this->adminLastname = htmlspecialchars(strip_tags(stripslashes($this->adminLastname)));
        $this->adminUsername = htmlspecialchars(strip_tags(stripslashes($this->adminUsername)));
        $this->modifyDate = date('Y-m-d');
        $this->adminId = intval(strip_tags(stripslashes($this->adminId)));
        $stmt->bindParam(':adminFirstname', $this->adminFirstname);
        $stmt->bindParam(':adminLastname', $this->adminLastname);
        $stmt->bindParam(':adminUsername', $this->adminUsername);
        $stmt->bindParam(':modifyDate', $this->modifyDate);
        $stmt->bindParam(':adminId', $this->adminId);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteAdminById()
    {
        $strSql = 'DELETE FROM admin_tb WHERE adminId = :adminId';
        $stmt = $this->conn->prepare($strSql);
        $this->adminId = intval(strip_tags(stripslashes($this->adminId)));
        $stmt->bindParam(':adminId', $this->adminId);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function registerAdmin()
    {
        $strSql = 'INSERT INTO admin_tb (adminFirstname , adminLastname , adminUsername , adminPassword , modifyDate) VALUES (:adminFirstname , :adminLastname , :adminUsername , :adminPassword , :modifyDate)';
        $stmt = $this->conn->prepare($strSql);
        $password_hash = password_hash($this->adminPassword, PASSWORD_DEFAULT);
        $this->adminFirstname = htmlspecialchars(strip_tags(stripslashes($this->adminFirstname)));
        $this->adminLastname = htmlspecialchars(strip_tags(stripslashes($this->adminLastname)));
        $this->adminUsername = htmlspecialchars(strip_tags(stripslashes($this->adminUsername)));
        $this->adminPassword = htmlspecialchars(strip_tags(stripslashes($password_hash)));
        $this->modifyDate = date('Y-m-d');
        $stmt->bindParam(':adminFirstname', $this->adminFirstname);
        $stmt->bindParam(':adminLastname', $this->adminLastname);
        $stmt->bindParam(':adminUsername', $this->adminUsername);
        $stmt->bindParam(':adminPassword', $this->adminPassword);
        $stmt->bindParam(':modifyDate', $this->modifyDate);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function loginAdmin()
    {
        $strSql = 'SELECT * FROM admin_tb WHERE adminUsername = :adminUsername';
        $stmt = $this->conn->prepare($strSql);
        $this->adminUsername = htmlspecialchars(strip_tags(stripslashes($this->adminUsername)));
        $stmt->bindParam(':adminUsername', $this->adminUsername);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            if (password_verify($this->adminPassword, $row['adminPassword'])) {
                $_SESSION['adminFirstname'] = $row['adminFirstname'];
                $_SESSION['adminLastname'] = $row['adminLastname'];
                return true;
            } else {
                return false;
            }
        }
    }
}
