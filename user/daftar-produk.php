<?php
session_start(); // Memulai session

// Cek apakah user sudah login atau belum
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php"); // Redirect ke halaman login jika belum login
    exit();
}

// Fungsi Logout (jika tombol logout diklik)
if (isset($_GET['logout']) && isset($_SESSION['user_id'])) {
    unset($_SESSION['user_id']); // Hapus session user_id
    session_destroy(); // Hancurkan semua session
    session_write_close(); // Pastikan tidak ada modifikasi lebih lanjut
    header("Location: login.php"); // Redirect ke halaman login
    exit();
}

// Koneksi database
include('../koneksi/koneksi.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
    <link rel="stylesheet" href="../Bootstrap 5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat+Subrayada:wght@400;700&family=Poppins:wght@100;400;700&display=swap" rel="stylesheet">
</head>
<body>
    <style>
    .card {
        width: 270px;
        height: 300px; 
        border: 2px solid #D0D0D0;
        border-radius: 8px; 
        padding: 24px;
        justify-content: center;
        background-color: #c4f0f6; /* Warna biru muda */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Efek shadow */
        transition: transform 0.3s ease-in-out;
        position: sticky;
    }

    .card img {
        padding: 10px;
        margin-left: 2px; /* Geser gambar ke kiri */
    }
    /* Tombol "Keluar" dengan warna merah */
    .btn-danger {
        background: linear-gradient(to right, #42A5F5, #1E88E5); /* Gradasi biru terang */
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        font-weight: bold;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease-in-out;
    }

    /* Efek hover */
    .btn-danger:hover {
        background: linear-gradient(to right, #64B5F6, #2196F3);
        transform: scale(1.05);
    }

    /* Efek ketika ditekan */
    .btn-danger:active {
        transform: scale(0.95);
    }

    /* Media query for smaller screens */
@media (max-width: 768px) {
    .card {
        width: 90%; /* Make card wider on smaller screens */
        height: auto; /* Let height adjust to content */
        margin: 10px auto; /* Add some margin top and bottom */
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .card img {
    height: 200px; /* Adjust image height on smaller screens */
  }
}

/* Responsive adjustments */
@media (max-width: 992px) { /* Adjust breakpoint as needed */
  .navbar-toggler {
    display: block; /* Show the toggler button */
  }

  .navbar-collapse {
    display: none; /* Hide the navbar links by default */
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    background-color: #fff; /* Match your navbar background color */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add a subtle shadow */
    z-index: 1000; /* Ensure it's above other content */
  }

  .navbar-collapse.show {
    display: block; /* Show the links when the toggler is clicked */
  }

  .navbar-nav {
    flex-direction: column; /* Stack the links vertically */
    padding: 20px;
    text-align: center; /* Center the navbar links on smaller screens */
  }

  .navbar-nav .nav-item {
    margin: 10px 0; /* Add vertical margin between links */
  }
}

/* Optional: Style the buttons to look better on mobile */
@media (max-width: 576px) {
  .btn {
    width: 100%; /* Make buttons full-width on small screens */
    margin-bottom: 10px; /* Add some space below the buttons */
  }
}
    </style>
    <nav class="navbar navbar-expand-lg fixed-top bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand me-3" href="#">
                <img src="../img/logo1.png" alt="Logo" height="40">
            </a>
            <form class="d-flex flex-grow-1" method="GET" action="">
                <div class="input-group w-100">
                    <span class="input-group-text bg-light border-0">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control border-0 shadow-none" name="search" placeholder="Cari Gambar..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                </div>
            </form>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="mynavbar">
                <ul class="navbar-nav">
                    <li class="nav-item me-3"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item me-3"><a class="nav-link" href="#why-us-section">Gallery</a></li>
                    <li class="nav-item me-3"><a class="nav-link" href="#testimony-section">Testimonial</a></li>
                    <li class="nav-item me-3"><a class="nav-link" href="#faq-section">FAQ</a></li>
                    <li class="nav-item">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="btn btn-outline-dark" href="login.php">Masuk</a>
                            <a class="btn btn-dark ms-2" href="register.php">Sign Up</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="btn btn-danger" href="index.php">Keluar</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5 pt-4">
        <section id="why-us-section">
            <h2>Gallery</h2>
            <div class="row">
                <?php
                include('../koneksi/koneksi.php');
                $search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
                $query = "SELECT * FROM gallery_foto WHERE DeskripsiFoto LIKE '%$search_query%'";
                $result = mysqli_query($conn, $query);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='col-lg-3 col-md-6 col-sm-12 mb-4'>
                            <div class='card'> 
                                <a href='detail.php?FotoID=" . $row['FotoID'] . "'>
                                    <img src='../img/produk/" . $row['LokasiFile'] . "' class='img-fluid rounded-top' alt='Gambar Gallery'>
                                </a>
                                <div class='card-body text-center'>
                                    <h4>" . $row['JudulFoto'] . "</h4>
                                    <a href='https://wa.me/6281234567890?text=Halo!%20Saya%20tertarik%20dengan%20gambar%20ini.' target='_blank' class='btn btn-primary mt-3'>
                                        <i class='fab fa-whatsapp'></i> Hubungi Kami
                                    </a>
                                </div>
                            </div>
                        </div>";
                    }
                } else {
                    echo "<p class='text-center'>Tidak ada gambar ditemukan.</p>";
                }
                ?>
            </div>
        </section>
    </div>
    <section class="footer-section text-dark py-3 text-center">
        <hr>
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> Selvi Yanti</p>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/7441451cf7.js" crossorigin="anonymous"></script>
</body>
</html>