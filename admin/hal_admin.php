<?php
include 'header.php';
include 'menu.php';
include '../koneksi/koneksi.php'; 
// Mengambil data dari database
$album = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM gallery_album"))['total'];
$foto = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM gallery_foto"))['total'];
$komentar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM gallery_komentarfoto"))['total'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM gallery_user"))['total'];
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800 text-center">📸 Halaman Galeri</h1>

    <!-- Statistik Cards -->
    <div class="row text-center">
        <?php
        $stats = [
            ['title' => 'Total Album', 'count' => $album, 'icon' => 'images', 'color' => 'primary'],
            ['title' => 'Total Foto', 'count' => $foto, 'icon' => 'camera', 'color' => 'success'],
            ['title' => 'Total Komentar', 'count' => $komentar, 'icon' => 'comments', 'color' => 'warning'],
            ['title' => 'Total Pengguna', 'count' => $user, 'icon' => 'users', 'color' => 'danger']
        ];
        foreach ($stats as $stat) { ?>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card shadow-sm border-left-<?= $stat['color']; ?>">
                    <div class="card-body">
                        <i class="fas fa-<?= $stat['icon']; ?> fa-2x text-<?= $stat['color']; ?> mb-2"></i>
                        <h5 class="font-weight-bold text-<?= $stat['color']; ?>"> <?= $stat['title']; ?> </h5>
                        <p class="display-4 font-weight-bold text-dark"> <?= $stat['count']; ?> </p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- Grafik Statistik -->
    <div class="card mt-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title text-center font-weight-bold">📊 Statistik Galeri</h5>
            <canvas id="myChart"></canvas>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="d-flex justify-content-center mt-4">
        <a href="galery.php" class="btn btn-primary mx-2">
            <i class="fas fa-plus"></i> Tambah Album
        </a>
        <a href="galery.php" class="btn btn-success mx-2">
            <i class="fas fa-camera"></i> Tambah Foto
        </a>
        <a href="index.php" class="btn btn-danger mx-2">
            <i class="fas fa-users"></i> Lihat Pengguna
        </a>
    </div>
</div>
<!-- End of Main Content -->

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('myChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Album', 'Foto', 'Komentar', 'Pengguna'],
            datasets: [{
                label: 'Jumlah Data',
                data: [<?= $album; ?>, <?= $foto; ?>, <?= $komentar; ?>, <?= $user; ?>],
                backgroundColor: ['#4e73df', '#1cc88a', '#f6c23e', '#e74a3b'],
                borderWidth: 1
            }]
        }
    });
</script>

<!-- FontAwesome for Icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<?php
include 'footer.php';
?>