<?php
session_start();
include('koneksi/koneksi.php');

// Cek koneksi database
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Jika form dikirimkan (metode POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; 

    // Query untuk mendapatkan data pengguna berdasarkan email
    $query = "SELECT UserID, Email, Username, Password FROM gallery_user WHERE Email = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        // Cek apakah email ditemukan
        if (!$user) {
            $error = "Email tidak ditemukan!";
        } else {
            // Cek apakah password cocok
            if (password_verify($password, $user['Password'])) {
                // Simpan data ke sesi
                $_SESSION['user_id'] = $user['UserID'];
                $_SESSION['email'] = $user['Email'];
                $_SESSION['username'] = $user['Username'];

                // Redirect ke halaman index.php
                header("Location: index.php");
                exit(); // Pastikan script berhenti setelah redirect
            } else {
                $error = "Password salah!";
            }
        }
    } else {
        $error = "Terjadi kesalahan pada sistem.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
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
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image">
                                <img src="img/login.jpg" alt="Login Image" class="img-fluid"
                                style="max-width: 100%; height: 80%; border-radius: 10px; box-shadow: 2px 2px 15px rgba(0, 0, 0, 0.2); margin-top: 60px; margin-left: 30px; ">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Selamat Datang!</h1>
                                    </div>

                                    <?php if (isset($error)) : ?>
                                        <div class="alert alert-danger">
                                            <?= htmlspecialchars($error); ?>
                                        </div>
                                    <?php endif; ?>

                                    <form class="user" method="POST">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" name="email" placeholder="Masukkan Email..." required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" name="password" placeholder="Password" required>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Remember
                                                    Me</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Masuk
                                        </button>
                                    </form>
                                    <hr>
                                        <a href="index.php" class="btn btn-google btn-user btn-block">
                                            <i class="fab fa-google fa-fw"></i> Login with Google
                                        </a>
                                        <a href="https://www.facebook.com/login.php/?lang=en-US" class="btn btn-facebook btn-user btn-block">
                                            <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                                        </a>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.php">Lupa Kata Sandi?</a>
                                    </div>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Buat Akun Baru!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>
