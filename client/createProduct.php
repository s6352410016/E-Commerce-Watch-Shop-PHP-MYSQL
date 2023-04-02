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
    <div class="container mt-5" style="width: 1500px;">
        <h3 class="text-center">เพิ่มสินค้า</h3>
        <form class="mt-3" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">ชื่อสินค้า:</label>
                <input type="text" class="form-control" name="productName" required>
            </div>
            <div class="mb-3">
                <label class="form-label">ราคาสินค้า:</label>
                <input type="number" class="form-control" name="productPrice" min="1" pattern="\d*" required>
            </div>
            <div class="mb-3">
                <label class="form-label">รูปสินค้า:</label>
                <input accept="image/png , image/jpeg , image/webp" id='fileImg' type="file" class="form-control" name="productImage" min="1" pattern="\d*" required>
            </div>
            <img id='previewImg'>
            <div class="mb-3">
                <label class="form-label">รหัสประเภทสินค้า:</label>
                <input type="number" class="form-control" name="categoryId" min="1" pattern="\d*" required>
            </div>
            <button type="submit" class="btn btn-primary">เพิ่มสินค้า</button>
            &nbsp;&nbsp;&nbsp;
            <a href="dashboard.php" class="btn btn-success">แสดงสินค้าทั้งหมด</a>
        </form>
    </div>
    <?php
    require_once('../config/ConnectDB.php');
    require_once('../controllers/Product.php');
    $connObj = new ConnectDB();
    $conn = $connObj->connectDB();
    $productObj = new Product($conn);

    if (isset($_POST['productName']) && isset($_POST['productPrice']) && isset($_FILES['productImage']) && isset($_POST['categoryId'])) {
        $productObj->productName = $_POST['productName'];
        $productObj->productPrice = $_POST['productPrice'];
        $productObj->categoryId = $_POST['categoryId'];

        $file = explode('.', $_FILES['productImage']['name']);
        $fileExtension = end($file);
        $newFileName = 'product_' . md5(uniqid()) . '.' . $fileExtension;

        if (move_uploaded_file($_FILES['productImage']['tmp_name'], '../images/' . $newFileName)) {
            $productObj->productImage = $newFileName;
            if ($productObj->createProduct()) {
                echo "<script>
                        Swal.fire(
                            'เพิ่มสินค้าสำเร็จแล้ว!',
                            'คุณสามารถดูสินค้าที่มีในระบบได้',
                            'success'
                        ).then(() => {
                            window.location.href = 'dashboard.php';
                        });
                      </script>";
            }
        }
    }
    ?>
    <br>
    <nav class="navbar navbar-light bg-light" style="position: fixed; bottom: 0; left: 0; right: 0;">
        <div class="container justify-content-center">
            <a class="navbar-brand">เพื่อการศึกษาเท่านั้น</a>
        </div>
    </nav>

    <script>
        const fileImg = document.getElementById('fileImg');
        const previewImg = document.getElementById('previewImg');

        fileImg.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                const ObjURLOfFileImg = URL.createObjectURL(e.target.files[0]);
                previewImg.src = ObjURLOfFileImg;
                previewImg.style.width = '200px';
                previewImg.style.height = '200px';
                previewImg.style.marginBottom = '1rem';
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>