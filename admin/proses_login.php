<?php
session_start();
require_once "../koneksi/koneksi.php"; 

// Periksa apakah pengguna sudah login
if (isset($_SESSION['UserID'])) {
    header("Location: hal_admin.php");
    exit();
}

// Periksa apakah form dikirimkan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['email']) || empty($_POST['password'])) {
        echo "<script>alert('Harap isi email dan password!'); window.location.href='index.php';</script>";
        exit();
    }

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Query dengan prepared statement untuk mencari email
    $query = "SELECT UserID, Username, Password FROM gallery_user WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Verifikasi password
            if (password_verify($password, $row['Password'])) {
                session_regenerate_id(true);
                $_SESSION['UserID'] = $row['UserID'];
                $_SESSION['Username'] = $row['Username'];

                header("Location: ../index.php");
                exit();
            } else {
                echo "<script>alert('Password salah!'); window.location.href='index.php';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Email tidak ditemukan!'); window.location.href='index.php';</script>";
            exit();
        }

        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($conn);
}
?>
