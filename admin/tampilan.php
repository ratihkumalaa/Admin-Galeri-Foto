<?php
include '../koneksi/koneksi.php';
// Mengambil data untuk "Read"
$result = mysqli_query($conn, "SELECT * FROM gallery_foto");

// Ambil data untuk edit jika ada FotoID di URL
$edit_data = null;

// Cek apakah ada parameter "id" di URL
if (isset($_GET['id'])) {
    $foto_id = $_GET['id'];
    $sql_edit = "SELECT * FROM gallery_foto WHERE FotoID = ?";
    $stmt_edit = $conn->prepare($sql_edit);
    $stmt_edit->bind_param("i", $foto_id);
    $stmt_edit->execute();
    $result_edit = $stmt_edit->get_result();
    $edit_data = $result_edit->fetch_assoc();
}

if (isset($_GET['hapus'])) {
    $foto_id = $_GET['hapus'];

    $sql_hapus = "DELETE FROM gallery_foto WHERE FotoID = ?";
    $stmt_hapus = $conn->prepare($sql_hapus);
    $stmt_hapus->bind_param("i", $foto_id);
    $stmt_hapus->execute();

    header("Location: tampilan.php");
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
        <a href="galery.php" class="btn btn-primary btn-user " role="button">Tambah</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Foto</th>
                        <th>Deskripsi</th>
                        <th>Tanggal Unggah</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Pemanggilan data untuk mengisi tabel -->
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['JudulFoto']); ?></td>
                        <td><?= htmlspecialchars($row['DeskripsiFoto']); ?></td>
                        <td><?= htmlspecialchars($row['TanggalUnggah']); ?></td>
                      

                        <!-- Tampilkan Foto -->
                        <td>
                            <img src="<?= htmlspecialchars($row['LokasiFile']); ?>" alt="Foto" width="100">
                        </td>

                        <td>
                            <div class="d-flex gap-4">
                                <a href="galery.php?id=<?= $row['FotoID'] ?>" class="btn btn-primary">Edit</a>
                                <a href="tampilan.php?hapus=<?= $row['FotoID'] ?>" onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-danger">Hapus</a>
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