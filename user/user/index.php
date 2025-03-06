<!-- tulis script pemanggilan database koneksi dimulai dari disini -->
<?php
session_start(); // Mulai session
include('../koneksi/koneksi.php'); // Panggil koneksi database

// Proses login dan logout
$isLoggedIn = isset($_SESSION['username']);
$username = $isLoggedIn ? $_SESSION['username'] : 'Register';

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Proses penyimpanan komentar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['foto_id']) && !empty($_POST['user_id']) && !empty($_POST['comment'])) {
        $foto_id = trim($_POST['foto_id']);
        $user_id = trim($_POST['user_id']);
        $comment = htmlspecialchars(trim($_POST['comment'])); // Mencegah XSS
        $tanggal_komentar = date('Y-m-d H:i:s');

        $query = "INSERT INTO gallery_komentarfoto (FotoId, UserId, IsiKomentar, TanggalKomentar) VALUES (?, ?, ?, ?)";
        
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, 'iiss', $foto_id, $user_id, $comment, $tanggal_komentar);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                header('Location: index.php');
                exit();
            } else {
                echo "Terjadi kesalahan: " . mysqli_stmt_error($stmt);
            }
        } else {
            echo "Terjadi kesalahan dalam menyiapkan statement.";
        }
    } else {
        echo "Semua field harus diisi.";
    }
}
?>

<!-- tulis script pemanggilan database koneksi berakhir dari disini -->


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
    <link rel="stylesheet" href="../Bootstrap 5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"
        integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g=="
        crossorigin="anonymous" />
     
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat+Subrayada:wght@400;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        .dropdown:hover .dropdown-menu {
            display: block;
            margin-top: 0;
        }
        body {
            background-color:rgb(198, 224, 243);
        }
        /* Styling untuk setiap card */
        .shadow-sm {
            border-radius: 8px; /* Sudut card yang melengkung */
            overflow: hidden; /* Agar gambar tidak keluar dari batas card */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Efek bayangan ringan */
            background-color: #fff; /* Warna latar belakang putih untuk card */
            transition: box-shadow 0.3s ease; /* Efek transisi saat hover */
        }

        /* Efek saat hover pada card */
        .shadow-sm:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); /* Efek bayangan lebih kuat saat hover */
        }
        /* Responsiveness: Menyesuaikan ukuran untuk perangkat kecil */
        @media (max-width: 768px) {
            .col-md-4 {
                max-width: 100%; /* Membuat kolom kategori menjadi full-width pada layar kecil */
            }

            .category h2 {
                font-size: 28px; /* Mengurangi ukuran judul pada layar kecil */
            }

            .category p {
                font-size: 14px; /* Mengurangi ukuran deskripsi pada layar kecil */
            }
        }
    </style>
</head>

<body>
<div class="container-fluid">
        <section class="header-section">
            <div class="container">
                <!-- Navbar -->
                <section class="navigation-section">
                    <nav class="navbar navbar-expand-lg fixed-top">
                        <div class="container p-0">
                            <a class="navbar-brand ms-2" href="#">
                                <img src="../img/logo1.png" alt="Logo"
                                style="
                                 height: 45px;
                                 ">
                            </a>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                                <img src="img/Toggler.png" alt="">
                            </button>
                            <div class="collapse navbar-collapse justify-content-end" id="mynavbar">
                                <ul class="navbar-nav ms-auto">
                                    <li class="nav-item ms-4"><a class="nav-link" href="#">Halaman Utama</a></li>
                                    <li class="nav-item ms-4"><a class="nav-link" href="#why-us-section">Galeri</a></li>
                                    <li class="nav-item ms-4"><a class="nav-link" href="#testimony-section">Ulasan</a></li>
                                    <li class="nav-item ms-4">
                                    <!-- Tombol Tambah Data -->
                                    <?php if ($isLoggedIn): ?>
                                        <li class="nav-item ms-4"><a class="nav-link" href="tambah-data.php">Tambah Gambar</a></li>
                                    <?php endif; ?>

                                    <!-- Nama User atau Register -->
                                    <li class="nav-item dropdown ms-4">
                                        <?php if ($isLoggedIn): ?>
                                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <?= htmlspecialchars($username); ?>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown"> 
                                                <li><a class="dropdown-item text-danger" href="?action=logout">Keluar</a></li>
                                            </ul>
                                        <?php else: ?>
                                            <a class="nav-link" href="../register.php">Daftar</a>
                                        <?php endif; ?>
                                    </li> 
                                </ul>
                            </div>
                        </div>
                    </nav>
                </section>


                <!-- Hero Section -->
                <section class="hero-section">
                    <p class="judul">Selamat Datang di gallery online Saya!</p>
                    <p 
                    style="
                        font-family: 'Helvetica', serif;
                        ">Di sini, Anda akan menemukan beragam karya seni hasil dari perjalanan kreatif saya, penuh inspirasi, imajinasi, dan makna. Saya mengundang Anda untuk menjelajahi dan 
                        menikmati setiap karya yang telah saya ciptakan dengan penuh semangat. Setiap karya memiliki cerita dan makna tersendiri, siap untuk dijelajahi dan dinikmati. Jangan ragu untuk mengeksplorasi, 
                        karena ada begitu banyak hal menarik, unik, dan mengesankan yang bisa Anda temukan di sini. Semoga karya-karya ini menginspirasi Anda!</p>
                        <a class="nav-link p-0" href="#why-us-section"><button class="btn-rent-now"> Lihatlah! <i class="fas fa-arrow-right"></i></button></a>
                </section>
            </div>
        </section>
    </div>


    <!-- section kategori -->

    <div class="container text-center mt-5 mb-5" id="category">
        <div class="category mb-4 ">
            <h2>Kategori</h2>
            <p>Kategori Galeri foto</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-4 mb-3">
                <div class="shadow-sm">
                    <img src="../img/gunung1.jpeg" class="card-img-top" alt="Category 1">
                    <div class="card-body">
                        <h5 class="card-title">Pegunungan</h5>
                        <p class="card-text">Formasi tanah yang menjulang tinggi dengan puncak yang sering tertutup salju, 
                            menawarkan panorama yang megah dan menantang.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class=" shadow-sm">
                    <img src="../img/produk/pantai.jpg" class="card-img-top" alt="Category 2">
                    <div class="card-body">
                        <h5 class="card-title">Perairan</h5>
                        <p class="card-text">Meliputi lautan, dan air terjun menambah keindahan serta kesejukan pada lanskap alam.
                            Air danau yang jernih kehijauan terasa begitu dingin.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class=" shadow-sm">
                    <img src="../img/lautbanda.jpg" class="card-img-top" alt="Category 3">
                    <div class="card-body">
                        <h5 class="card-title">Langit</h5>
                        <p class="card-text">Langit, dengan fenomena seperti matahari terbit, matahari terbenam, awan, dan pelangi, memperkaya keindahan lanskap alam.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- section kategori End-->

    <!-- section produk start -->
    <div class="container">
    <section class="why-us-section" id="why-us-section">
        <h2>Galeri</h2>
        <p>Cari foto pemandangan alam yang kamu sukai!</p> 
        <!-- Form Pencarian dan Urutkan -->
        <form action="" method="GET">
    <div class="input-group">
        <input type="text" name="search" class="form-control"
            placeholder="Cari gambar..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">

        <div class="input-group-append">
            <button class="btn btn-search" type="submit">
                <i class="fas fa-search"></i> Cari
            </button>
        </div>
    </div>

                <div class="col-md-5 filter">
                    <!-- Dropdown Urutkan -->
                    <select name="sort" class="form-control" onchange="this.form.submit()">
                        <option value="" disabled selected>Urutkan berdasarkan</option>
                        <option value="tanggal_asc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'tanggal_asc' ? 'selected' : ''; ?>>Tanggal (ASC)</option>
                        <option value="tanggal_desc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'tanggal_desc' ? 'selected' : ''; ?>>Tanggal (DESC)</option>
                        <option value="judul_asc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'judul_asc' ? 'selected' : ''; ?>>(A-Z)</option>
                        <option value="deskripsi_desc" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'deskripsi_desc' ? 'selected' : ''; ?>>Deskripsi (Z-A)</option>
                    </select>
                </div>
            </div>
        </form>

        <div class="row">
            <?php
            // Include file koneksi database
            include('../koneksi/koneksi.php');

            // Menangani input pencarian
            $search_query = "";
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $search_query = mysqli_real_escape_string($conn, $_GET['search']);
            }

            // Menangani pengurutan
            $sort_query = "";
            if (isset($_GET['sort'])) {
                switch ($_GET['sort']) {
                    case 'tanggal_asc':
                        $sort_query = "ORDER BY TanggalUnggah ASC";
                        break;
                    case 'tanggal_desc':
                        $sort_query = "ORDER BY TanggalUnggah DESC";
                        break;
                    case 'judul_asc':
                        $sort_query = "ORDER BY DeskripsiFoto ASC";
                        break;
                    case 'deskripsi_desc':
                        $sort_query = "ORDER BY DeskripsiFoto DESC";
                        break;
                }
            }

            // Query untuk mengambil data gambar dari tabel gallery_foto dengan batasan 4 item
            $query = "SELECT * FROM gallery_foto WHERE DeskripsiFoto LIKE ? $sort_query LIMIT 4";
            $stmt = $conn->prepare($query);
            $like_search = "%" . $search_query . "%";
            $stmt->bind_param("s", $like_search);
            $stmt->execute();
            $result = $stmt->get_result();

            // Cek apakah ada data
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $image_url = $row['LokasiFile'];
                    $description = $row['JudulFoto'];
                    ?>
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                        <div class="card">                            
                            <!-- Link ke halaman detail gambar -->
                            <a href="detail.php?FotoID=<?php echo urlencode($row['FotoID']); ?>">
                                <img src="../img/produk/<?php echo $image_url; ?>" class="icon-why-us rounded-top" style="width:270px" alt="Gambar Gallery">
                            </a>
                            <div class="card-body text-center">
                                <h4><?php echo $description; ?></h4>
                                <a href="https://wa.me/6281234567890?text=Halo%20!%20Saya%20tertarik%20dengan%20produk%20Anda."
                                target="_blank" class="btn btn-primary mt-3">
                                    <i class="fab fa-whatsapp"></i> Hubungi Kami
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='text-center'>Tidak ada gambar ditemukan.</p>";
            }

            // Menutup statement dan koneksi
            $stmt->close();
            $conn->close();
            ?>
        </div>
    </section>
</div>


                <!-- Button untuk masuk ke halaman detail -->
                <div class="col-12 text-center mt-4">
                    <a
                    style="
                    background-color:rgb(46, 146, 228);"
                    href="daftar-produk.php" class="btn btn-secondary">
                        Lihat Semua Produk
                    </a>
                </div>
                <!-- Button untuk masuk ke halaman detail end -->

            </div>
        </section>
    </div>
    <!-- section produk end -->


    <!-- Tulis disini untuk section Testimoni start -->
    <div class="container-fluid">
        <section class="testimony-section" id="testimony-section">
            <div class="opening-text-testimony text-center">
                <h2>Ulasan</h2>
                <p>Berbagai review positif dari para pelanggan kami</p>
            </div>

            <div class="owl-carousel">
                <?php
                // Include koneksi database
                include('../koneksi/koneksi.php');

                // Query untuk mengambil data komentar, gambar, dan nama pengguna
                $com = "
                    SELECT
                        gf.LokasiFile AS image_url,
                        gu.Username,
                        gk.IsiKomentar,
                        gk.TanggalKomentar
                    FROM gallery_komentarfoto gk
                    JOIN gallery_foto gf ON gk.FotoID = gf.FotoID
                    JOIN gallery_user gu ON gk.UserID = gu.UserID
                ";
                $result = mysqli_query($conn, $com);

                // Cek apakah ada data
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Ambil data dari hasil query
                        $image_url = $row['image_url']; // Lokasi gambar
                        $username = $row['Username']; // Nama pengguna
                        $IsiKomentar = $row['IsiKomentar']; // Isi komentar
                        $TanggalKomentar = $row['TanggalKomentar']; // Tanggal komentar
                ?>
                        <div class="item">
                            <div class="testi-box">
                                <div class="row">
                                    <div class="col-lg-3 vh-center py-4">
                                        <img src="../img/person2.png" alt="person image">
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="text-review">
                                            <h4><?php echo $username; ?></h4>
                                            <p>“<?php echo $IsiKomentar; ?>”</p>
                                            <h5><?php echo $TanggalKomentar; ?></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "Tidak ada komentar ditemukan.";
                }
                ?>
            </div>
        </section>
    </div>
    <!-- section Testimoni end -->

    <div class="container">
        <section class="cta-banner-section">
            <div class="row text-center">
                <div class="col-12 text-center">
                    <div class="call-to-action text-center">
                    <div class="content">
                        <h1 class="poppins-semibold mt-5"
                        style="
                        font-family: 'Garamond',Bookman Old Style;
                        color: #f1c40f;
                        ">
                            Pemandangan Alam</h1>
                        <p style="
                            font-family: 'Garamond', serif;
                            font-weight: 600;
                            font-style: normal;
                       color: #f1c40f;
                        background: rgba(0, 0, 0, 0.3); /* Latar belakang semi-transparan */
                        padding: 10px;
                        border-radius: 5px;
                        ">
                            Nikmati keindahan alam yang menakjubkan dengan pemandangan pegunungan yang memukau, pantai dengan pasir putih yang lembut dan laut biru yang jernih, serta
                            langit biru yang luas dan cerah, menciptakan panorama yang sempurna untuk menenangkan jiwa dan menyegarkan pikiran. 
                        </p> 
                    </div> 
                        </div>
                    </div>
                </div>
            </div>
        </section> 
    </div>
            
    <section class="footer-section text-dark py-5">
    <hr>
        <div class="container py-4">
            <div class="row">
                <!-- Address Section -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="address">
                        <h5>Jalan pangeran puger No. 128 kabupaten grobogan 58152</h5>
                        <h5>galleryfoto143@gmail.com</h5>
                        <h5>081-233-334-808</h5>
                    </div>
                </div>

                <!-- Navigation Links Section -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <div class="navigation">
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-dark">Halaman Utama</a></li>
                            <li><a href="#why-us-section" class="text-dark">Galeri</a></li>
                            <li><a href="#testimony-section" class="text-dark">Ulasan</a></li>
                            <li><a href="#tambah-data.php" class="text-dark">Tambah Gambar</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Social Media Section -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="navigation">
                        <h5>Connect with Us</h5>
                        <ul class="list-unstyled d-flex">
                            <li class="me-3"><a href="#"><img src="../img/icon_facebook.png" alt="Facebook"></a></li>
                            <li class="me-3"><a href="#"><img src="../img/icon_instagram.png" alt="Instagram"></a></li>
                            <li class="me-3"><a href="#"><img src="../img/icon_twitter.png" alt="Twitter"></a></li>
                            <li class="me-3"><a href="#"><img src="../img/icon_mail.png" alt="Email"></a></li>
                            <li class="me-3"><a href="#"><img src="../img/icon_twitch.png" alt="Twitch"></a></li>
                        </ul>
                    </div>
                </div>

                <!-- Comment Form Section -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="comment-form">
                        <h5>Tinggalkan Komentar Untuk Gambar</h5>
                        <form action="#testimony-section" method="POST">
                            <div class="mb-3">
                                <label for="foto_id" class="form-label">Foto</label>
                                <select id="foto_id" name="foto_id" class="form-control" required>
                                    <?php
                                    include('../koneksi/koneksi.php');
                                    $query = "SELECT FotoID, JudulFoto FROM gallery_foto";
                                    $result = mysqli_query($conn, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value='{$row['FotoID']}'>{$row['JudulFoto']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="user_id" class="form-label">ID pengguna</label>
                                <input type="number" id="user_id" name="user_id" class="form-control" required placeholder="Masukkan ID Pengguna Anda...">
                            </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label">Komentar</label>
                                <textarea id="comment" name="comment" class="form-control" rows="3" required placeholder="Tulis komentar Anda di sini..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim Komentar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
 
    <!-- Script Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript untuk Efek Scroll -->
    <script>
        window.addEventListener("scroll", function() {
            let navbar = document.querySelector(".navbar");
            if (window.scrollY > 50) {
                navbar.classList.add("scrolled");
            } else {
                navbar.classList.remove("scrolled");
            }
        });
    </script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (window.location.search.includes("search=")) {
            const section = document.getElementById("why-us-section");
            if (section) {
                section.scrollIntoView({ behavior: "smooth" });
            }
        }
    });
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
    integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw=="
    crossorigin="anonymous"></script>
<script src="../Bootstrap 5/js/bootstrap.min.js"></script>
<script src="../js/script.js"></script>
<script src="https://kit.fontawesome.com/7441451cf7.js" crossorigin="anonymous"></script>

</body>
</html>