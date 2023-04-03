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
                        <a class="nav-link active" aria-current="page" href="login.php">เข้าสู่ระบบ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="register.php">สมัครสมาชิก</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="loginAdmin.php">แอดมิน</a>
                    </li>
                </ul>
            </div>
            <?php
                if(isset($_SESSION['userFirstname']) && isset($_SESSION['userLastname'])){
                    echo "<div class='dropdown' style='margin-right: 1.3rem'>
                            <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton2' data-bs-toggle='dropdown' aria-expanded='false'>
                                ".$_SESSION['userFirstname'];?> <?php echo $_SESSION['userLastname']."
                            </button>
                            <ul class='dropdown-menu dropdown-menu-dark' aria-labelledby='dropdownMenuButton2'>
                                <li><a class='dropdown-item active' href='#'>ประวัติการสั่งซื้อสินค้า</a></li>
                                <li><a class='dropdown-item' href='logout.php'>ออกจากระบบ</a></li>
                            </ul>
                        </div>";
                }
            ?>
        </div>
    </nav>
    <div class="container" style="min-height: 100vh; width: 1200px;">
        <br>
        <h3>รายการ: สินค้าทั้งหมด</h3>
        <div class="d-flex">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    เลือกแบรนด์
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="index.php">แสดงทุกแบรนด์</a></li>
                    <li><a class="dropdown-item" href="showRolexWatch.php">แบรนด์ Rolex</a></li>
                    <li><a class="dropdown-item" href="showPatekWatch.php">แบรนด์ Patek</a></li>
                </ul>
            </div>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <form method="GET" class="d-flex" style="width: 100%;">
                <input name="query" class="form-control me-2" type="search" placeholder="ค้นหาสินค้า" aria-label="Search">
                <button class="btn btn-outline-primary" type="submit">ค้นหา</button>
            </form>
        </div>
        <br>
        <div class="row" style='gap: 1rem'>
            <?php
            require_once('../config/ConnectDB.php');
            require_once('../controllers/Product.php');
            $objCoeenctDB = new ConnectDB();
            $conn = $objCoeenctDB->connectDB();
            $objProduct = new Product($conn);

            if (isset($_GET['query']) && $_GET['query'] !== '') {
                $objProduct->productName = $_GET['query'];
                $stmt = $objProduct->getProductByName();
                if ($stmt->rowCount() !== 0) {
                    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($rows);
                        echo '<div class="card" style="width: 18rem;">
                                <img src="../images/' . $productImage . '" style="height: 250px" class="card-img-top">
                                <div class="card-body">
                                    <p class="card-title">' . $productName . '</p>
                                    <a href="previewProduct.php?id=' . $productId . '" class="btn btn-primary">ดูสินค้า</a>
                                </div>
                             </div>';
                    }
                } else {
                    echo '<h4 class="text-center">ไม่พบสินค้าที่ค้นหา</h4>';
                }
            } else {
                $objProduct->categoryId = 3;
                $stmt = $objProduct->getProductByCategoryId();
                while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($rows);
                    echo '<div class="card" style="width: 18rem;">
                            <img src="../images/' . $productImage . '" style="height: 250px" class="card-img-top">
                            <div class="card-body">
                                <p class="card-title">' . $productName . '</p>
                                <a href="previewProduct.php?id=' . $productId . '" class="btn btn-primary">ดูสินค้า</a>
                            </div>
                         </div>';
                }
            }

            ?>
        </div>
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