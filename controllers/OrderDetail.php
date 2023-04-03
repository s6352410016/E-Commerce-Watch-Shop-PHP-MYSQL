<?php
    class OrderDetail{
        public $orderId;
        public $orderTotalPrice;
        public $userId;
        public $modifyDate;
        
        public $orderDetailId;
        public $productId;
        public $productQuantity;
        public $productTotalPrice;

        public $orderHistoryId;
        public $userFirstname;
        public $userLastname;
        public $userAddress;
        public $userPostCode;
        public $userPhone;

        private $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        // function to get all order
        public function getAllOrderDetail(){
            $strSql = 'SELECT * FROM order_tb 
            INNER JOIN order_detail_tb ON order_tb.orderId = order_detail_tb.orderId
            INNER JOIN order_history_tb ON order_tb.orderId = order_history_tb.orderId';
            $stmt = $this->conn->prepare($strSql);
            $stmt->execute();
            return $stmt;
        }

        // function to create order
        public function createOrder(){
            $strSql1 = "INSERT INTO order_tb (orderTotalPrice, userId, modifyDate) VALUES (:orderTotalPrice, :userId, :modifyDate)";
            $stmt = $this->conn->prepare($strSql1);
            $this->orderTotalPrice = intval(strip_tags(stripslashes($this->orderTotalPrice)));
            $this->userId = intval(strip_tags(stripslashes($this->userId)));
            $this->modifyDate = date('Y-m-d');
            $stmt->bindParam(':orderTotalPrice', $this->orderTotalPrice);
            $stmt->bindParam(':userId', $this->userId);
            $stmt->bindParam(':modifyDate', $this->modifyDate);
            if($stmt->execute()){
                $strSql2 = 'SELECT orderId FROM order_tb WHERE userId = :userId ORDER BY orderId DESC LIMIT 1';
                $stmt = $this->conn->prepare($strSql2);
                $this->userId = intval(strip_tags(stripslashes($this->userId)));
                $stmt->bindParam(':userId', $this->userId);
                if($stmt->execute()){
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    $strSql3 = 'INSERT INTO order_detail_tb (orderId, productId, productQuantity, productTotalPrice , modifyDate) VALUES (:orderId, :productId, :productQuantity, :productTotalPrice , :modifyDate)';
                    $stmt = $this->conn->prepare($strSql3);
                    $this->orderId = intval(strip_tags(stripslashes($row['orderId'])));
                    $this->productId = intval(strip_tags(stripslashes($this->productId)));
                    $this->productQuantity = intval(strip_tags(stripslashes($this->productQuantity)));
                    $this->productTotalPrice = intval(strip_tags(stripslashes($this->productTotalPrice)));
                    $stmt->bindParam(':orderId', $this->orderId);
                    $stmt->bindParam(':productId', $this->productId);
                    $stmt->bindParam(':productQuantity', $this->productQuantity);
                    $stmt->bindParam(':productTotalPrice', $this->productTotalPrice);
                    $stmt->bindParam(':modifyDate', $this->modifyDate);
                    if($stmt->execute()){   
                        $strSql5 = 'UPDATE order_tb SET orderTotalPrice = :orderTotalPrice WHERE orderId = :orderId';
                        $stmt = $this->conn->prepare($strSql5);
                        $this->orderId = intval(strip_tags(stripslashes($row['orderId'])));
                        $this->orderTotalPrice = intval(strip_tags(stripslashes($this->productTotalPrice)));
                        $stmt->bindParam(':orderId', $this->orderId);
                        $stmt->bindParam(':orderTotalPrice', $this->orderTotalPrice);
                        $stmt->execute();

                        $strSql4 = 'INSERT INTO order_history_tb (orderId, userFirstname, userLastname, userAddress, userPostCode, userPhone, modifyDate) VALUES (:orderId, :userFirstname, :userLastname, :userAddress, :userPostCode, :userPhone, :modifyDate)';
                        $stmt = $this->conn->prepare($strSql4);
                        $this->orderId = intval(strip_tags(stripslashes($row['orderId'])));
                        $this->userFirstname = htmlspecialchars(strip_tags(stripslashes($this->userFirstname)));
                        $this->userLastname = htmlspecialchars(strip_tags(stripslashes($this->userLastname)));
                        $this->userAddress = htmlspecialchars(strip_tags(stripslashes($this->userAddress)));
                        $this->userPostCode = intval(strip_tags(stripslashes($this->userPostCode)));
                        $this->userPhone = htmlspecialchars(strip_tags(stripslashes($this->userPhone)));
                        $stmt->bindParam(':orderId', $this->orderId);
                        $stmt->bindParam(':userFirstname', $this->userFirstname);
                        $stmt->bindParam(':userLastname', $this->userLastname);
                        $stmt->bindParam(':userAddress', $this->userAddress);
                        $stmt->bindParam(':userPostCode', $this->userPostCode);
                        $stmt->bindParam(':userPhone', $this->userPhone);
                        $stmt->bindParam(':modifyDate', $this->modifyDate);
                        if($stmt->execute()){
                            return true;
                        }else{
                            return false;
                        }
                    }else{
                        return false;
                    }
                }
            }else{
                return false;
            }
        }

        public function getOrderProductByuserId(){
            $strSql = 'SELECT * FROM order_detail_tb 
            INNER JOIN order_tb ON order_detail_tb.orderId = order_tb.orderId 
            INNER JOIN order_history_tb ON order_detail_tb.orderId = order_history_tb.orderId 
            INNER JOIN product_tb ON order_detail_tb.productId = product_tb.productId 
            WHERE order_tb.userId = :userId';

            $stmt = $this->conn->prepare($strSql);
            $this->userId = intval(strip_tags(stripslashes($this->userId)));
            $stmt->bindParam(':userId', $this->userId);
            $stmt->execute();
            return $stmt;
        }

        public function getOrderProductByOrderId(){
            $strSql = 'SELECT * FROM order_detail_tb 
            INNER JOIN order_tb ON order_detail_tb.orderId = order_tb.orderId 
            INNER JOIN order_history_tb ON order_detail_tb.orderId = order_history_tb.orderId 
            INNER JOIN product_tb ON order_detail_tb.productId = product_tb.productId 
            WHERE order_tb.userId = :userId AND order_detail_tb.orderId = :orderId';

            $stmt = $this->conn->prepare($strSql);
            $this->userId = intval(strip_tags(stripslashes($this->userId)));
            $this->orderId = intval(strip_tags(stripslashes($this->orderId)));
            $stmt->bindParam(':userId', $this->userId);
            $stmt->bindParam(':orderId', $this->orderId);
            $stmt->execute();
            return $stmt;
        }

        public function getOrderDetailByOrderId(){
            $strSql = 'SELECT * FROM order_detail_tb 
            INNER JOIN order_tb ON order_detail_tb.orderId = order_tb.orderId 
            INNER JOIN order_history_tb ON order_detail_tb.orderId = order_history_tb.orderId 
            INNER JOIN product_tb ON order_detail_tb.productId = product_tb.productId 
            WHERE order_detail_tb.orderId = :orderId';

            $stmt = $this->conn->prepare($strSql);
            $this->userId = intval(strip_tags(stripslashes($this->userId)));
            $this->orderId = intval(strip_tags(stripslashes($this->orderId)));
            $stmt->bindParam(':orderId', $this->orderId);
            $stmt->execute();
            return $stmt;
        }

        public function updateOrderProductByOrderId(){
            $strSql1 = 'UPDATE order_history_tb SET userFirstname = :userFirstname , userLastname = :userLastname , userAddress = :userAddress , userPostCode = :userPostCode , userPhone = :userPhone WHERE orderId = :orderId';
            $stmt = $this->conn->prepare($strSql1);
            $this->orderId = intval(strip_tags(stripslashes($this->orderId)));
            $this->userFirstname = htmlspecialchars(strip_tags(stripslashes($this->userFirstname)));
            $this->userLastname = htmlspecialchars(strip_tags(stripslashes($this->userLastname)));
            $this->userAddress = htmlspecialchars(strip_tags(stripslashes($this->userAddress)));
            $this->userPostCode = intval(strip_tags(stripslashes($this->userPostCode)));
            $this->userPhone = htmlspecialchars(strip_tags(stripslashes($this->userPhone)));
            $stmt->bindParam(':orderId', $this->orderId);
            $stmt->bindParam(':userFirstname', $this->userFirstname);
            $stmt->bindParam(':userLastname', $this->userLastname);
            $stmt->bindParam(':userAddress', $this->userAddress);
            $stmt->bindParam(':userPostCode', $this->userPostCode);
            $stmt->bindParam(':userPhone', $this->userPhone);
            if($stmt->execute()){
                $strSql2 = 'UPDATE order_detail_tb SET productQuantity = :productQuantity , productTotalPrice = :productTotalPrice WHERE orderId = :orderId';
                $stmt = $this->conn->prepare($strSql2);
                $this->orderId = intval(strip_tags(stripslashes($this->orderId)));
                $this->productQuantity = intval(strip_tags(stripslashes($this->productQuantity)));
                $this->productTotalPrice = intval(strip_tags(stripslashes($this->productTotalPrice)));
                $stmt->bindParam(':orderId', $this->orderId);
                $stmt->bindParam(':productQuantity', $this->productQuantity);
                $stmt->bindParam(':productTotalPrice', $this->productTotalPrice);
                if($stmt->execute()){
                    $strSql3 = 'UPDATE order_tb SET orderTotalPrice = :orderTotalPrice WHERE orderId = :orderId';
                    $stmt = $this->conn->prepare($strSql3);
                    $this->orderId = intval(strip_tags(stripslashes($this->orderId)));
                    $this->orderTotalPrice = intval(strip_tags(stripslashes($this->orderTotalPrice)));
                    $stmt->bindParam(':orderId', $this->orderId);
                    $stmt->bindParam(':orderTotalPrice', $this->orderTotalPrice);
                    if($stmt->execute()){
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function updateOrderProductByOrderIdAdmin(){
            $strSql1 = 'UPDATE order_history_tb SET userFirstname = :userFirstname , userLastname = :userLastname , userAddress = :userAddress , userPostCode = :userPostCode , userPhone = :userPhone WHERE orderId = :orderId';
            $stmt = $this->conn->prepare($strSql1);
            $this->orderId = intval(strip_tags(stripslashes($this->orderId)));
            $this->userFirstname = htmlspecialchars(strip_tags(stripslashes($this->userFirstname)));
            $this->userLastname = htmlspecialchars(strip_tags(stripslashes($this->userLastname)));
            $this->userAddress = htmlspecialchars(strip_tags(stripslashes($this->userAddress)));
            $this->userPostCode = intval(strip_tags(stripslashes($this->userPostCode)));
            $this->userPhone = htmlspecialchars(strip_tags(stripslashes($this->userPhone)));
            $stmt->bindParam(':orderId', $this->orderId);
            $stmt->bindParam(':userFirstname', $this->userFirstname);
            $stmt->bindParam(':userLastname', $this->userLastname);
            $stmt->bindParam(':userAddress', $this->userAddress);
            $stmt->bindParam(':userPostCode', $this->userPostCode);
            $stmt->bindParam(':userPhone', $this->userPhone);
            if($stmt->execute()){
                $strSql2 = 'UPDATE order_detail_tb SET productId = :productId , productQuantity = :productQuantity , productTotalPrice = :productTotalPrice WHERE orderId = :orderId';
                $stmt = $this->conn->prepare($strSql2);
                $this->orderId = intval(strip_tags(stripslashes($this->orderId)));
                $this->productId = intval(strip_tags(stripslashes($this->productId)));
                $this->productQuantity = intval(strip_tags(stripslashes($this->productQuantity)));
                $this->productTotalPrice = intval(strip_tags(stripslashes($this->productTotalPrice)));
                $stmt->bindParam(':orderId', $this->orderId);
                $stmt->bindParam(':productId', $this->productId);
                $stmt->bindParam(':productQuantity', $this->productQuantity);
                $stmt->bindParam(':productTotalPrice', $this->productTotalPrice);
                if($stmt->execute()){
                    $strSql3 = 'UPDATE order_tb SET orderTotalPrice = :orderTotalPrice WHERE orderId = :orderId';
                    $stmt = $this->conn->prepare($strSql3);
                    $this->orderId = intval(strip_tags(stripslashes($this->orderId)));
                    $this->orderTotalPrice = intval(strip_tags(stripslashes($this->orderTotalPrice)));
                    $stmt->bindParam(':orderId', $this->orderId);
                    $stmt->bindParam(':orderTotalPrice', $this->orderTotalPrice);
                    if($stmt->execute()){
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        public function deleteOrderProductByOrderId(){
            $strSql1 = 'DELETE FROM order_tb WHERE orderId = :orderId';
            $stmt = $this->conn->prepare($strSql1);
            $this->orderId = intval(strip_tags(stripslashes($this->orderId)));
            $stmt->bindParam(':orderId', $this->orderId);
            if($stmt->execute()){
                $strSql2 = 'DELETE FROM order_detail_tb WHERE orderId = :orderId';
                $stmt = $this->conn->prepare($strSql2);
                $stmt->bindParam(':orderId', $this->orderId);
                if($stmt->execute()){
                    $strSql3 = 'DELETE FROM order_history_tb WHERE orderId = :orderId';
                    $stmt = $this->conn->prepare($strSql3);
                    $stmt->bindParam(':orderId', $this->orderId);
                    if($stmt->execute()){
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    }
