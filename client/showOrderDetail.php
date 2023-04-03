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
            <a class="navbar-brand">Bunlung</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="dashboard.php">จัดการสินค้า</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="showCategoryProduct.php">จัดการประเภทสินค้า</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="showCarouselProduct.php">จัดการรูปสินค้าทั้งหมด</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="showUser.php">จัดการสมาชิก</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="showAdmin.php">จัดการแอดมิน</a>
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
        <h3 class="text-center">จัดการคำสั่งซื้อสินค้า</h3>
        <a href="createOrderDetail.php" class="btn btn-primary">เพิ่มรายการสั่งซื้อสินค้า</a>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th scope="col" style="width: 10%;">รหัสคำสั่งซื้อ</th>
                    <th scope="col" style="width: 15%;">ชื่อผู้ใช้ที่สั่งซื้อ</th>
                    <th scope="col" style="width: 15%;">ชื่อสินค้าที่สั่งซื้อ</th>
                    <th scope="col" style="width: 15%;">รูปสินค้าที่สั่งซื้อ</th>
                    <th scope="col" style="width: 15%;">จำนวนสินค้าที่สั่งซื้อ</th>
                    <th scope="col" style="width: 10%;">ราคารวม</th>
                    <th scope="col" style="width: 10%;">ที่อยู่จัดส่ง</th>
                    <th scope="col" style="width: 10%;">เบอร์โทรศัพท์</th>
                    <th scope="col" style="width: 10%;">ป/ด/ว</th>
                    <th scope="col">แก้ไข</th>
                    <th scope="col">ลบ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once('../config/ConnectDB.php');
                require_once('../controllers/OrderDetail.php');
                require_once('../controllers/Product.php');

                $connObj = new ConnectDB();
                $conn = $connObj->connectDB();
                $productObj = new Product($conn);
                $orderDetailObj = new OrderDetail($conn);
                $stmt = $orderDetailObj->getAllOrderDetail();

                if ($stmt->rowCount() > 0) {
                    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($rows);

                        $productObj->productId = $productId;
                        $stmt1 = $productObj->getProductById();
                        $rows1 = $stmt1->fetch(PDO::FETCH_ASSOC);
                        extract($rows1);

                        echo "<tr>
                                    <td>" . $orderId  . "</td>
                                    <td>" . $userFirstname . " " . $userLastname . "</td>
                                    <td>" . $productName . "</td>
                                    <td><img src='../images/" . $productImage . "' style='width: 150px; height: 150px'></td>
                                    <td>" . $productQuantity . "</td>
                                    <td>" . number_format($productTotalPrice) . " บาท</td>
                                    <td>" . $userAddress . " " . $userPostCode . "</td>
                                    <td>" . $userPhone . "</td>
                                    <td>" . $modifyDate . "</td>
                                    <td><a class='btn btn-success' href='editOrderDetail.php?id=" . $orderId . "'>แก้ไข</a></td>
                                    <td><a class='btn btn-danger' onclick='deleteOrderDetail({$orderId});'>ลบ</a></td>
                                </tr>";
                    }
                } else {
                    echo "<tr>
                            <td colspan='11' class='text-center'>ไม่มีข้อมูล</td>
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
    <script>
        function deleteOrderDetail(orderId) {
            Swal.fire({
                title: "คุณต้องการลบคำสั่งซื้อนี้ไหม?",
                text: "หากลบแล้วจะไม่มีคำสั่งซื้อนี้อีกต่อไป!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire("ลบคำสั่งซื้อนี้สำเร็จแล้ว!",
                        "คำสั่งซื้อนี้ถูกลบออกแล้ว",
                        "success"
                    ).then(() => {
                        window.location.href = `deleteOrderDetail.php?orderId=${orderId}`;
                    });
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>