<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bunlung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                        <a class="nav-link active" aria-current="page" href="register.php">สมัครสมาชิก</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">แอดมิน</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container" style="width: 800px;">
        <h3 class="text-center mt-5">เข้าสู่ระบบ</h3>
        <?php
        require_once('../config/ConnectDB.php');
        require_once('../controllers/User.php');
        $connObj = new ConnectDB();
        $conn = $connObj->connectDB();
        $userObj = new User($conn);
        if (isset($_POST['userName']) && isset($_POST['userPassword'])) {
            $userObj->userName = $_POST['userName'];
            $userObj->userPassword = $_POST['userPassword'];

            if ($userObj->loginUser()) {
                $_POST['userName'] = "";
                $_POST['userPassword'] = "";
                echo "<script>
                        Swal.fire(
                            'เข้าสู่ระบบสำเร็จ!',
                            'คุณสามารถสั่งซื้อสินค้าได้แล้ว',
                            'success'
                        ).then(() => {
                            window.location.href = 'index.php';
                        });
                      </script>";
            } else {
                echo "<div class='alert alert-danger' role='alert'>บัญชีผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!</div>";
            }
        }
        ?>
        <form method="POST" style="width: 100%;" class="mt-3">
            <div class="mb-3">
                <label class="form-label">ชื่อบัญชีผู้ใช้:</label>
                <input type="text" class="form-control" required name="userName">
            </div>
            <div class="mb-3">
                <label class="form-label">รหัสผ่าน:</label>
                <input type="password" class="form-control" required name="userPassword">
            </div>
            <button type="submit" class="btn btn-primary">เข้าสู่ระบบ</button>
        </form>
    </div>
    <script src="sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>