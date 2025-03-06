<?php
include '../koneksi/koneksi.php';

// Mengambil data komentar untuk "Read"
$result = mysqli_query($conn, "SELECT gallery_komentarfoto.*, gallery_user.Username, gallery_foto.JudulFoto 
                               FROM gallery_komentarfoto 
                               JOIN gallery_user ON gallery_komentarfoto.UserID = gallery_user.UserID 
                               JOIN gallery_foto ON gallery_komentarfoto.FotoID = gallery_foto.FotoID");

// Ambil data untuk edit jika ada KomentarID di URL
$edit_data = null;
if (isset($_GET['id'])) {
    $komentar_id = $_GET['id'];
    $sql_edit = "SELECT * FROM gallery_komentarfoto WHERE KomentarID = ?";
    $stmt_edit = $conn->prepare($sql_edit);
    $stmt_edit->bind_param("i", $komentar_id);
    $stmt_edit->execute();
    $result_edit = $stmt_edit->get_result();
    $edit_data = $result_edit->fetch_assoc();
}

// Hapus komentar jika ada parameter "hapus" di URL
if (isset($_GET['hapus'])) {
    $komentar_id = $_GET['hapus'];
    $sql_hapus = "DELETE FROM gallery_komentarfoto WHERE KomentarID = ?";
    $stmt_hapus = $conn->prepare($sql_hapus);
    $stmt_hapus->bind_param("i", $komentar_id);
    $stmt_hapus->execute();
    header("Location: komentar-tampilan.php");
    exit();
}
?>

<?php
include 'header.php';
include 'menu.php';
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h4 class="m-0 font-weight-bold text-primary">Komentar</h4> 
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Foto</th>
                            <th>Pengguna</th>
                            <th>Isi Komentar</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['JudulFoto']); ?></td>
                            <td><?= htmlspecialchars($row['Username']); ?></td>
                            <td><?= htmlspecialchars($row['IsiKomentar']); ?></td>
                            <td><?= htmlspecialchars($row['TanggalKomentar']); ?></td>
                            <td> 
                                <a href="komentar-tampilan.php?hapus=<?= $row['KomentarID'] ?>" onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-danger">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>