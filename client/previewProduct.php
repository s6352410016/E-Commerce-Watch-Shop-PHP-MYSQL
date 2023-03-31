<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bunlung</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Demo styles -->
    <style>
        html,
        body {
            position: relative;
            height: 100%;
        }

        body {
            background: #eee;
            font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
            font-size: 14px;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .swiper {
            width: 100%;
            height: 100%;
        }

        .swiper-slide {
            background-position: center;
            background-size: cover;
        }

        .swiper-slide img {
            display: block;
            width: 100%;
        }
    </style>
</head>

<body>
    <?php
    require('../config/ConnectDB.php');
    require_once('../controllers/Product.php');
    ?>
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
                                <li><a class='dropdown-item active' href='#'>ประวัติการสั่งซื้อสินค้า</a></li>
                                <li><a class='dropdown-item' href='logout.php'>ออกจากระบบ</a></li>
                            </ul>
                        </div>";
                                                                }
                                                                    ?>
        </div>
    </nav>
    <div class="container d-flex flex-column justify-content-center" style="min-height: 85vh;">
        <h3 class="text-center">สั่งซื้อสินค้า</h3>
        <br>
        <br>
        <div class="container d-flex" style='height: 455px; width: 1000px;'>
            <div class="swiper mySwiper" style="border-radius: 5px; width: 50%;">
                <div class="swiper-wrapper">
                    <?php
                    require('../controllers/Carousel.php');
                    $connObj = new ConnectDB();
                    $conn = $connObj->connectDB();
                    $carouselObj = new Carousel($conn);
                    $carouselObj->productId = $_GET['id'];
                    $stmt = $carouselObj->getCarouselByProductId();

                    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($rows);
                        echo "<div class='swiper-slide'>
                            <img src='../images/previewProductImg/{$carouselImage}' style='height: 100%; width: 100%;'/>
                          </div>";
                    }
                    ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>
            <br>
            <form method="POST">
                <div class="d-flex justify-content-between">
                    <div class="mb-3" style='width: 50%;'>
                        <label class="form-label">ชื่อจริง:</label>
                        <input type="text" class="form-control" required name="userFirstname">
                    </div>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <div class="mb-3" style='width: 50%;'>
                        <label class="form-label">นามสกุล:</label>
                        <input type="text" class="form-control" required name="userLastname">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">ที่อยู่จัดส่ง:</label>
                    <input type="text" class="form-control" required name="userAddress">
                </div>
                <div class="mb-3">
                    <label class="form-label">รหัสไปรษณีย์:</label>
                    <input type="number" class="form-control" required min="0" pattern="\d*" name="userPostCode">
                </div>
                <div class="mb-3">
                    <label class="form-label">เบอร์โทรศัพท์:</label>
                    <input type="number" class="form-control" required min="0" pattern="\d*" name="userPhone">
                </div>
                <div class="mb-3">
                    <label class="form-label">จำนวน:</label>
                    <input type="number" class="form-control" required min="1" pattern="\d*" name="productQuantity">
                </div>
                <button name="buy" type="submit" class="btn btn-primary">สั่งซื้อสินค้า</button>
            </form>
            <?php
            require_once('../controllers/OrderDetail.php');
            $connObj = new ConnectDB();
            $conn = $connObj->connectDB();
            $productObj = new Product($conn);
            $productObj->productId = $_GET['id'];
            $stmt = $productObj->getProductById();
            $productData = $stmt->fetch(PDO::FETCH_ASSOC);
            $orderDetailObj = new OrderDetail($conn);

            if (isset($_POST['userFirstname']) && isset($_POST['userLastname']) && isset($_POST['userAddress']) && isset($_POST['userPostCode']) && isset($_POST['userPhone']) && isset($_POST['productQuantity']) && isset($_SESSION['userId'])) {
                $orderTotalPrice = $_POST['productQuantity'] * $productData['productPrice'];
                $orderDetailObj->orderTotalPrice = $orderTotalPrice;
                $orderDetailObj->userId = $_SESSION['userId'];
                $orderDetailObj->productId = $_GET['id'];
                $orderDetailObj->productQuantity = $_POST['productQuantity'];
                $orderDetailObj->productTotalPrice = $orderTotalPrice;
                $orderDetailObj->userFirstname = $_POST['userFirstname'];
                $orderDetailObj->userLastname = $_POST['userLastname'];
                $orderDetailObj->userAddress = $_POST['userAddress'];
                $orderDetailObj->userPostCode = $_POST['userPostCode'];
                $orderDetailObj->userPhone = $_POST['userPhone'];
                echo $_POST['userFirstname'];
                if ($orderDetailObj->createOrder()) {
                    echo "<script>
                        Swal.fire(
                            'สั่งซื้อสำเร็จแล้ว!',
                            'คุณสามารถดูรายการที่สั่งซื้อได้',
                            'success'
                        ).then(() => {
                            window.location.href = 'index.php';
                        });
                      </script>";
                }
            } else {
                if (isset($_POST['buy'])) {
                    if (!isset($_SESSION['userId'])) {
                        echo '<script>window.location.href = "login.php"</script>';
                    }
                }
            }
            ?>
        </div>
        <br>
        <br>
        <?php
        $connObj = new ConnectDB();
        $conn = $connObj->connectDB();
        $productObj = new Product($conn);
        $productObj->productId = $_GET['id'];
        $stmt = $productObj->getProductById();
        $productData = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<h5 class='text-center'>ชื่อสินค้า: {$productData['productName']} / ราคา: " . number_format($productData['productPrice']) . " บาท</h5>";
        ?>
    </div>
    <nav class="navbar navbar-light bg-light" style="position: fixed; bottom: 0; left: 0; right: 0;">
        <div class="container justify-content-center">
            <a class="navbar-brand">เพื่อการศึกษาเท่านั้น</a>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

    <!-- Initialize Swiper -->
    <script>
        var swiper = new Swiper(".mySwiper", {
            spaceBetween: 30,
            effect: "fade",
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });
    </script>
</body>

</html>