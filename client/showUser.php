
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
                        <a class="nav-link active" aria-current="page" href="showCarouselProduct.php">จัดการรูปสินค้าทั้งหมด</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="showOrderDetail.php">จัดการคำสั่งซื้อสินค้า</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="showAdmin.php">จัดการแอดมิน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">ออกจากระบบ</a>
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
    <div class="container mt-5" style="width: 1000px;">
        <h3 class="text-center">จัดการสมาชิกในระบบ</h3>
        <a href="createUser.php" class="btn btn-primary">เพิ่มสมาชิก</a>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th scope="col" style='width: 10%;'>ไอดีสมาชิก</th>
                    <th scope="col" style='width: 15%;'>ชื่อ-สกุล</th>
                    <th scope="col" style='width: 10%;'>บัญชีผู้ใช้</th>
                    <th scope="col" style='width: 20%;'>รหัสผ่าน</th>
                    <th scope="col" style='width: 10%;'>วันที่แก้ไข</th>
                    <th scope="col" style='width: 10%;'>แก้ไข</th>
                    <th scope="col" style='width: 10%;'>ลบ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once('../config/ConnectDB.php');
                require_once('../controllers/User.php');

                $connObj = new ConnectDB();
                $conn = $connObj->connectDB();
                $userObj = new User($conn);
                $stmt = $userObj->getAllUser();

                if ($stmt->rowCount() > 0) {
                    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($rows);
                        echo "<tr>
                                    <td>" . $userId  . "</td>
                                    <td>" . $userFirstname . " " . $userLastname . "</td>
                                    <td>" . $userName . "</td>
                                    <td><p style='word-break: break-word;'>" . $userPassword . "</p></td>
                                    <td>" . $modifyDate . "</td>
                                    <td><a class='btn btn-success' href='editUser.php?id=" . $userId . "'>แก้ไข</a></td>
                                    <td><a class='btn btn-danger' onclick='deleteUser({$userId});'>ลบ</a></td>
                                </tr>";
                    }
                } else {
                    echo "<tr>
                            <td colspan='5'>ไม่มีข้อมูล</td>
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
        function deleteUser(userId) {
            Swal.fire({
                title: "คุณต้องการลบสมาชิกนี้ไหม?",
                text: "หากลบแล้วจะไม่มีสมาชิกนี้อีกต่อไป!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire("ลบสมาชิกนี้สำเร็จแล้ว!",
                        "สมาชิกนี้ถูกลบออกแล้ว",
                        "success"
                    ).then(() => {
                        window.location.href = `deleteUser.php?userId=${userId}`;
                    });
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>