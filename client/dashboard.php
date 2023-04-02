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
    <div class="container mt-5" style="width: 1500px;">
        <h3 class="text-center">จัดการสินค้า</h3>
        <a href="createProduct.php" class="btn btn-primary">เพิ่มสินค้า</a>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th scope="col" style="width: 10%;">ไอดีสินค้า</th>
                    <th scope="col">ชื่อสินค้า</th>
                    <th scope="col" style="width: 10%;">ราคาสินค้า</th>
                    <th scope="col">รูปสินค้า</th>
                    <th scope="col" style="width: 15%;">รหัสประเภทสินค้า</th>
                    <th scope="col" style="width: 10%;">วันที่แก้ไข</th>
                    <th scope="col">แก้ไข</th>
                    <th scope="col">ลบ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once('../config/ConnectDB.php');
                require_once('../controllers/Product.php');

                $connObj = new ConnectDB();
                $conn = $connObj->connectDB();
                $productObj = new Product($conn);
                $stmt = $productObj->getAllProducts();
                while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($rows);
                    echo "<tr>
                                <td>" . $productId . "</td>
                                <td>" . $productName . "</td>
                                <td>" . number_format($productPrice) . " บาท</td>
                                <td><img src='../images/" . $productImage . "' style='width: 150px; height: 150px'></td>
                                <td>" . $categoryId . "</td>
                                <td>" . $modifyDate . "</td>
                                <td><a class='btn btn-success' href='editProduct.php?id=" . $productId . "'>แก้ไข</a></td>
                                <td><a class='btn btn-danger' href='deleteProduct.php?id=" . $productId . "'>ลบ</a></td>
                            </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <br>
    <nav class="navbar navbar-light bg-light" style="position: fixed; bottom: 0; left: 0; right: 0;">
        <div class="container justify-content-center">
            <a class="navbar-brand">เพื่อการศึกษาเท่านั้น</a>
        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>