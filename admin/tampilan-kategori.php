<?php
include '../koneksi/koneksi.php';
// Mengambil data untuk "Read"
$result = mysqli_query($conn, "SELECT * FROM gallery_album");

// Ambil data untuk edit jika ada AlbumID di URL
$edit_data = null;

// Cek apakah ada parameter "id" di URL
if (isset($_GET['id'])) {
    $foto_id = $_GET['id'];
    $sql_edit = "SELECT * FROM gallery_album WHERE AlbumID = ?";
    $stmt_edit = $conn->prepare($sql_edit);
    $stmt_edit->bind_param("i", $foto_id);
    $stmt_edit->execute();
    $result_edit = $stmt_edit->get_result();
    $edit_data = $result_edit->fetch_assoc();
}

if (isset($_GET['hapus'])) {
    $foto_id = $_GET['hapus'];

    $sql_hapus = "DELETE FROM gallery_album WHERE AlbumID = ?";
    $stmt_hapus = $conn->prepare($sql_hapus);
    $stmt_hapus->bind_param("i", $foto_id);
    $stmt_hapus->execute();

    header("Location: tampilan-kategori.php");
    exit();
}
?>

<?php
include 'header.php';
include 'menu.php';
?>

<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading --> 
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-0 font-weight-bold text-primary">Gallery</h4>
        <a href="kategori.php" class="btn btn-primary btn-user " role="button">Tambah</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Tanggal Dibuat</th> 
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Pemanggilan data untuk mengisi tabel -->
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['NamaAlbum']); ?></td>
                        <td><?= htmlspecialchars($row['Deskripsi']); ?></td>
                        <td><?= htmlspecialchars($row['TanggalDibuat']); ?></td>

                        <td>
                            <div class="d-flex gap-4">
                                <a href="kategori.php?id=<?= $row['AlbumID'] ?>" class="btn btn-primary">Edit</a>
                                <a href="tampilan-kategori.php?hapus=<?= $row['AlbumID'] ?>" onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-danger">Hapus</a>
                            </div>
                        </td>
                    </tr> 
                    <?php endwhile; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->


<?php
include 'footer.php';
?>