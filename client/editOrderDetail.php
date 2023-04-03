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
                        <a class="nav-link active" aria-current="page" href="login.php">จัดการสินค้า</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="register.php">จัดการประเภทสินค้า</a>
                    </li>
                </ul>
            </div>
            <?php
            if (isset($_SESSION['adminFirstname']) && isset($_SESSION['adminLastname'])) {
                echo "<div class='dropdown' style='margin-right: 1.3rem'>
                            <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton2' data-bs-toggle='dropdown' aria-expanded='false'>
                                " . $_SESSION['adminFirstname']; ?> <?php echo $_SESSION['adminLastname'] . "
                            </button>
                            <ul class='dropdown-menu dropdown-menu-dark' aria-labelledby='dropdownMenuButton2'>
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
    $connObj = new ConnectDB();
    $conn = $connObj->connectDB();
    $orderDetailObj = new OrderDetail($conn);
    $orderDetailObj->orderId = $_GET['id'];
    $stmt = $orderDetailObj->getOrderDetailByOrderId();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
    <div class="container mt-5" style="width: 800px;">
        <h3 class="text-center">แก้ไขรายการสั่งซื้อสินค้า</h3>
        <form class="mt-3" method="POST">
            <div class="mb-3">
                <label class="form-label">ไอดีผู้ใช้ที่สั่งซื้อ:</label>
                <input type="number" class="form-control" name="userId" required min="1" pattern="\d*" value="<?php echo $row['userId']; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">ไอดีสินค้าที่สั่งซื้อ:</label>
                <input type="number" class="form-control" name="productId" required min="1" pattern="\d*" value="<?php echo $row['productId']; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">จำนวนสินค้าที่สั่งซื้อ:</label>
                <input type="number" class="form-control" name="productQuantity" required min="1" pattern="\d*" value="<?php echo $row['productQuantity']; ?>">
            </div>
            <div class="d-flex" style="gap: 1rem">
                <div class="mb-3" style="width: 50%;">
                    <label class="form-label">ชื่อจริง:</label>
                    <input type="text" class="form-control" name="userFirstname" required value="<?php echo $row['userFirstname']; ?>">
                </div>
                <div class="mb-3" style="width: 50%;">
                    <label class="form-label">นามสกุล:</label>
                    <input type="text" class="form-control" name="userLastname" required value="<?php echo $row['userLastname']; ?>">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">ที่อยู่ในการจัดส่ง:</label>
                <input type="text" class="form-control" name="userAddress" required value="<?php echo $row['userAddress']; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">รหัสไปรษณีย์:</label>
                <input type="text" class="form-control" name="userPostCode" required value="<?php echo $row['userPostCode']; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">เบอร์โทรศัพท์:</label>
                <input type="text" class="form-control" name="userPhone" required value="<?php echo $row['userPhone']; ?>">
            </div>
            <button name="editOrderDetail" type="submit" class="btn btn-primary">แก้ไขรายการสั่งซื้อสินค้า</button>
            &nbsp;&nbsp;&nbsp;
            <a href="showOrderDetail.php" class="btn btn-success">แสดงคำสั่งซื้อสินค้า</a>
        </form>
    </div>
    <?php
    require_once('../config/ConnectDB.php');
    require_once('../controllers/OrderDetail.php');
    require_once('../controllers/Product.php');
    $connObj = new ConnectDB();
    $conn = $connObj->connectDB();
    $productObj = new Product($conn);
    $orderDetailObj = new OrderDetail($conn);

    if (isset($_POST['editOrderDetail'])) {
        $productObj->productId = $_POST['productId'];
        $stmt = $productObj->getProductById();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $orderDetailObj->orderId = $_GET['id'];
        $orderDetailObj->orderTotalPrice = $row['productPrice'] * $_POST['productQuantity'];
        $orderDetailObj->userId = $_POST['userId'];
        $orderDetailObj->productId = $_POST['productId'];
        $orderDetailObj->productQuantity = $_POST['productQuantity'];
        $orderDetailObj->productTotalPrice = $row['productPrice'] * $_POST['productQuantity'];
        $orderDetailObj->userFirstname = $_POST['userFirstname'];
        $orderDetailObj->userLastname = $_POST['userLastname'];
        $orderDetailObj->userAddress = $_POST['userAddress'];
        $orderDetailObj->userPostCode = $_POST['userPostCode'];
        $orderDetailObj->userPhone = $_POST['userPhone'];

        if ($orderDetailObj->updateOrderProductByOrderIdAdmin()) {
            echo "<script>
                    Swal.fire(
                        'แก้ไขรายการสั่งซื้อสินค้าสำเร็จแล้ว!',
                        'คุณสามารถดูคำสั่งซื้อสินค้าที่มีในระบบได้',
                        'success'
                    ).then(() => {
                        window.location.href = 'showOrderDetail.php';
                    });
                  </script>";
        }
    }
    ?>
    <br>
    <nav class="navbar navbar-light bg-light" style="position: fixed; bottom: 0; left: 0; right: 0;">
        <div class="container justify-content-center">
            <a class="navbar-brand">เพื่อการศึกษาเท่านั้น</a>
        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>