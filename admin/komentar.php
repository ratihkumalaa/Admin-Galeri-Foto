<?php
include '../koneksi/koneksi.php';

// Menangani pengiriman form untuk "Create" dan "Update" komentar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['IsiKomentar'], $_POST['AlbumID'], $_POST['UserID'])) {
        
        // Sanitasi input untuk menghindari serangan XSS
        $IsiKomentar = trim(htmlspecialchars($_POST['IsiKomentar']));
        $AlbumID = $_POST['AlbumID'];
        $UserID = $_POST['UserID'];
        $TanggalKomentar = date("Y-m-d H:i:s"); // Waktu saat ini
        $KomentarID = isset($_POST['KomentarID']) ? $_POST['KomentarID'] : null;

        // Jika KomentarID ada, lakukan update data
        if ($KomentarID) {
            $query = $conn->prepare("UPDATE gallery_komentarfoto SET IsiKomentar = ?, TanggalKomentar = ? WHERE KomentarID = ?");
            $query->bind_param("ssi", $IsiKomentar, $TanggalKomentar, $KomentarID);
        } else {
            // Jika tidak ada KomentarID, masukkan data baru
            $query = $conn->prepare("INSERT INTO gallery_komentarfoto (AlbumID, UserID, IsiKomentar, TanggalKomentar) VALUES (?, ?, ?, ?)");
            $query->bind_param("iiss", $AlbumID, $UserID, $IsiKomentar, $TanggalKomentar);
        }

        // Eksekusi query dan periksa hasilnya
        if ($query->execute()) {
            header("Location: tampilan-komentar.php?album_id=$AlbumID"); // Redirect ke halaman komentar
            exit();
        } else {
            echo "Error: " . $query->error;
        }
    } else {
        echo "Semua field harus diisi.";
    }
}

// Ambil data untuk edit jika KomentarID tersedia di URL
$edit_data = null;
if (isset($_GET['id'])) {
    $KomentarID = $_GET['id'];
    $sql_edit = "SELECT * FROM gallery_komentarfoto WHERE KomentarID = ?";
    $stmt_edit = $conn->prepare($sql_edit);
    $stmt_edit->bind_param("i", $KomentarID);
    $stmt_edit->execute();
    $result_edit = $stmt_edit->get_result();
    $edit_data = $result_edit->fetch_assoc();
}
?>

<?php
include 'header.php';
include 'menu.php';
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $edit_data ? 'Edit' : 'Tambah'; ?> Komentar</h1>

    <form class="user" action="" method="post">
        <input type="hidden" name="KomentarID" value="<?= $edit_data ? $edit_data['KomentarID'] : ''; ?>">
        <input type="hidden" name="AlbumID" value="<?= $_GET['album_id'] ?? ''; ?>">

        <div class="form-group">
            <textarea class="form-control  " name="IsiKomentar"
                      placeholder="Tulis komentar..." required><?= $edit_data ? htmlspecialchars($edit_data['IsiKomentar']) : ''; ?></textarea>
        </div>
        <div class="form-group">
            <input type="number" class="form-control " name="UserID"
                   placeholder="User ID" value="<?= $edit_data ? $edit_data['UserID'] : ''; ?>" required>
        </div>

        <button type="submit" class="btn btn-primary btn-user btn-block">
            <?= $edit_data ? 'Update' : 'Kirim'; ?>
        </button>
    </form>
</div>

<?php
include 'footer.php';
?>
