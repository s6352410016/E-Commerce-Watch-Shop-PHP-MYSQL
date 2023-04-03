<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bunlung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Bunlung</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">หน้าหลัก</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="login.php">เข้าสู่ระบบ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="register.php">สมัครสมาชิก</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">แอดมิน</a>
                    </li>
                </ul>
            </div>
            <?php
            if (isset($_SESSION['userFirstname']) && isset($_SESSION['userLastname'])) {
                echo "<div class='dropdown' style='margin-right: 1.3rem'>
                            <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton2' data-bs-toggle='dropdown' aria-expanded='false'>
                                " . $_SESSION['userFirstname']; ?> <?php echo $_SESSION['userLastname'] . "
                            </button>
                            <ul class='dropdown-menu dropdown-menu-dark' aria-labelledby='dropdownMenuButton2'>
                                <li><a class='dropdown-item active' href='orderhistory.php'>ประวัติการสั่งซื้อสินค้า</a></li>
                                <li><a class='dropdown-item' href='logout.php'>ออกจากระบบ</a></li>
                            </ul>
                        </div>";
                                                                }
                                                                    ?>
        </div>
    </nav>
    <?php
    require_once('../config/ConnectDB.php');
    require_once('../controllers/OrderDetail.php');
    require_once('../controllers/Product.php');
    $connObj = new ConnectDB();
    $conn = $connObj->connectDB();
    $orderDetailObj = new OrderDetail($conn);
    $productObj = new Product($conn);
    $orderDetailObj->userId = $_SESSION['userId'];
    $orderDetailObj->orderId = $_GET['orderId'];
    $stmt = $orderDetailObj->getOrderProductByOrderId();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $productObj->productId = $row['productId'];
    $stmt2 = $productObj->getProductById();
    $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    echo '<div class="container mt-5">
                <h3 class="text-center">แก้ไขรายการสินค้าที่สั่งซื้อ</h3>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">ชื่อจริง:</label>
                        <input name="userFirstname" type="text" class="form-control" required value="' . $row['userFirstname'] . '">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">นามสกุล:</label>
                        <input name="userLastname" type="text" class="form-control" required value="' . $row['userLastname'] . '">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">จำนวน:</label>
                        <input name="productQuantity" type="number" class="form-control" required min="1" pattern="\d*" value="' . $row['productQuantity'] . '">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ที่อยู่จัดส่ง:</label>
                        <input name="userAddress" type="text" class="form-control" required value="' . $row['userAddress'] . '">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">รหัสไปรษณีย์:</label>
                        <input name="userPostCode" type="number" class="form-control" required min="0" pattern="\d*" value="' . $row['userPostCode'] . '">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">เบอร์โทรศัพท์:</label>
                        <input name="userPhone" type="number" class="form-control" required min="0" pattern="\d*" value="' . $row['userPhone'] . '">
                    </div>
                    <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
                    &nbsp;&nbsp;
                    <a href="orderhistory.php" class="btn btn-success">ประวัติการสั่งซื้อสินค้า</a>
                </form>
             </div>';

    if (isset($_POST['userFirstname']) && isset($_POST['userLastname']) && isset($_POST['productQuantity']) && isset($_POST['userAddress']) && isset($_POST['userPostCode']) && isset($_POST['userPhone'])) {
        $orderDetailObj->orderId = $_GET['orderId'];
        $orderDetailObj->userFirstname = $_POST['userFirstname'];
        $orderDetailObj->userLastname = $_POST['userLastname'];
        $orderDetailObj->productQuantity = $_POST['productQuantity'];
        $orderDetailObj->productTotalPrice = $_POST['productQuantity'] * $row2['productPrice'];
        $orderDetailObj->userAddress = $_POST['userAddress'];
        $orderDetailObj->userPostCode = $_POST['userPostCode'];
        $orderDetailObj->userPhone = $_POST['userPhone'];
        $orderDetailObj->orderTotalPrice = $_POST['productQuantity'] * $row2['productPrice'];
        if ($orderDetailObj->updateOrderProductByOrderId()) {
            echo "<script>
                    Swal.fire(
                        'แก้ไขรายการสั่งซื้อสำเร็จ!',
                        'คุณสามารถตรวจสอบรายการสั่งซื้อได้ที่ประวัติการสั่งซื้อสินค้า',
                        'success'
                    ).then(() => {
                        window.location.href = 'orderhistory.php';
                    });
                 </script>";
        }
    }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>