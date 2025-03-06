<?php
include '../koneksi/koneksi.php';

// Mengambil data pengguna
$sql = "SELECT * FROM gallery_user";
$result_users = mysqli_query($conn, $sql);

// Hapus pengguna jika ada parameter "hapus" di URL
if (isset($_GET['hapus'])) {
    $user_id = $_GET['hapus'];

    $sql_hapus = "DELETE FROM gallery_user WHERE UserID = ?";
    $stmt_hapus = $conn->prepare($sql_hapus);
    $stmt_hapus->bind_param("i", $user_id);

    if ($stmt_hapus->execute()) {
        $msg = "Pengguna berhasil dihapus!";
    } else {
        $msg = "Gagal menghapus pengguna.";
    }
}

// Menampilkan header dan menu
include 'header.php';
include 'menu.php';
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h4 class="m-0 font-weight-bold text-primary">Daftar Pengguna</h4>
            <a href="user.php" class="btn btn-primary">Tambah Pengguna</a>
        </div>
        <div class="card-body">
            <?php if (isset($msg)): ?>
                <div class="alert alert-info"><?= htmlspecialchars($msg); ?></div>
            <?php endif; ?>
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Nama Lengkap</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($user = mysqli_fetch_assoc($result_users)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($user['Username']); ?></td>
                            <td><?= htmlspecialchars($user['Email']); ?></td>
                            <td><?= htmlspecialchars($user['NamaLengkap']); ?></td>
                            <td>
                                <a href="user.php?id=<?= $user['UserID'] ?>" class="btn btn-primary">Edit</a> 
                                <a href="?hapus=<?= $user['UserID'] ?>" onclick="return confirm('Yakin ingin menghapus?')" class="btn btn-danger">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
