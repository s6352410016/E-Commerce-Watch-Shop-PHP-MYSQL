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
                                <li><a class='dropdown-item' href='logout.php'>ออกจากระบบ</a></li>
                            </ul>
                        </div>";
                                                                }
                                                                    ?>
        </div>
    </nav>
    <div class="container" style="width: 1500px;">
        <h3 class="text-center mt-5">ประวัติการสั่งซื้อสินค้า</h3>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th scope="col" style="width: 15%;">ชื่อจริง-นามสกุล:</th>
                    <th scope="col">ชื่อสินค้า:</th>
                    <th scope="col">รูปสินค้า:</th>
                    <th scope="col">จำนวน:</th>
                    <th scope="col">ราคาต่อชิ้น:</th>
                    <th scope="col">ราคารวม:</th>
                    <th scope="col">ที่อยู่จัดสั่ง:</th>
                    <th scope="col">รหัสไปรษณีย์:</th>
                    <th scope="col">เบอร์โทรศัพท์:</th>
                    <th scope="col">แก้ไข:</th>
                    <th scope="col">ลบ:</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once('../config/ConnectDB.php');
                require_once('../controllers/OrderDetail.php');
                $connObj = new ConnectDB();
                $conn = $connObj->connectDB();
                $orderDetail = new OrderDetail($conn);
                $orderDetail->userId = $_SESSION['userId'];
                $stmt = $orderDetail->getOrderProductByuserId();
                if ($stmt->rowCount() !== 0) {
                    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($rows);
                        echo "<tr>
                                <td style='width: 10%'>{$userFirstname} {$userLastname}</td>
                                <td>{$productName}</td>
                                <td><img style='width: 150px; height: 150px;' src='../images/{$productImage}'></td>
                                <td>{$productQuantity}</td>
                                <td style='width: 10%'>" . number_format($productPrice) . " บาท</td>
                                <td>" . number_format($productTotalPrice) . " บาท</td>
                                <td style='width: 10%'>{$userAddress}</td>
                                <td style='width: 10%'>{$userPostCode}</td>
                                <td style='width: 10%'>{$userPhone}</td>
                                <td><a class='btn btn-primary' href='updateOrderHistory.php?orderId={$orderId}'>แก้ไข</a></td>
                                <td><a onclick='deleteOrderProduct({$orderId});' class='btn btn-danger'>ลบ</a></td>
                              </tr>";
                    }
                } else {
                    echo "<tr>
                            <td colspan='12' class='text-center'>ไม่มีประวัติการสั่งซื้อสินค้า</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
        function deleteOrderProduct(orderId) {
            Swal.fire({
                title: "คุณต้องการลบรายการที่สั่งซื้อไหม?",
                text: "หากลบแล้วคุณจะไม่มีคำสั่งซื้อนี้อีกต่อไป!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire("ลบรายการที่สั่งซื้อสำเร็จแล้ว!",
                        "รายการสินค้าของคุณถูกออกลบแล้ว",
                        "success"
                    ).then(() => {
                        window.location.href = `deleteOrderHistory.php?orderId=${orderId}`;
                    });
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>