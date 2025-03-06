<?php
session_start();
include('koneksi/koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $password = trim($_POST['password']);
    $namaLengkap = trim(mysqli_real_escape_string($conn, $_POST['nama_lengkap']));
    $alamat = trim(mysqli_real_escape_string($conn, $_POST['alamat']));

    if (empty($username) || empty($email) || empty($password)) {
        $error = "Semua kolom harus diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } else {
        // Cek apakah email sudah terdaftar
        $queryCheck = "SELECT Email FROM gallery_user WHERE Email = ?";
        $stmt = mysqli_prepare($conn, $queryCheck);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = "Email sudah digunakan!";
        } else {
            // Enkripsi password sebelum disimpan
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Masukkan data ke database
            $query = "INSERT INTO gallery_user (Username, Password, Email, NamaLengkap, Alamat) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "sssss", $username, $hashedPassword, $email, $namaLengkap, $alamat);

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['success'] = "Pendaftaran berhasil! Silakan login.";
                header("Location: login.php");
                exit();
            } else {
                $error = "Terjadi kesalahan saat mendaftar.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <style>
    body {
        background: linear-gradient(to right, #7DC4F0, #7DC4F0) !important;
        font-family: Arial, sans-serif;
    }
        </style>
    
</head>
<body class="bg-gradient-primary">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image">
                        <img src="img/register.jpg" alt="Login Image" class="img-fluid"
                        style="width: 600%; height: 84%; border-radius: 10px; box-shadow: 2px 2px 15px rgba(0, 0, 0, 0.2); margin-top: 50px; margin-left: 30px; ">
                    </div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Buat Akun!</h1>
                            </div>

                            <?php if (isset($error)) : ?>
                                <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
                            <?php endif; ?>

                            <form class="user" method="POST">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="username" placeholder="Username" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" name="email" placeholder="Email" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-user" name="password" placeholder="Password" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="nama_lengkap" placeholder="Nama Lengkap">
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control form-control-user" name="alamat" placeholder="Alamat"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">Daftar</button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="login.php">Sudah punya akun? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>
