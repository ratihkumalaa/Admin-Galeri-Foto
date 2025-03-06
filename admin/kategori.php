<?php
include '../koneksi/koneksi.php';

// Menangani pengiriman form untuk "Create" dan "Update"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['NamaAlbum'], $_POST['Deskripsi'], $_POST['TanggalDibuat'], $_POST['UserID'])) {
        // Sanitasi input
        $NamaAlbum = trim(htmlspecialchars($_POST['NamaAlbum']));
        $Deskripsi = trim(htmlspecialchars($_POST['Deskripsi']));
        $TanggalDibuat = $_POST['TanggalDibuat'];
        $UserID = $_POST['UserID']; // UserID harus diberikan dalam form
        $AlbumID = isset($_POST['id']) ? $_POST['id'] : null;

        // Jika AlbumID ada, maka update data
        if ($AlbumID) {
            $query = $conn->prepare("UPDATE gallery_album SET NamaAlbum = ?, Deskripsi = ?, TanggalDibuat = ?, UserID = ? WHERE AlbumID = ?");
            $query->bind_param("sssii", $NamaAlbum, $Deskripsi, $TanggalDibuat, $UserID, $AlbumID);
        } else {
            // Insert data baru
            $query = $conn->prepare("INSERT INTO gallery_album (NamaAlbum, Deskripsi, TanggalDibuat, UserID) VALUES (?, ?, ?, ?)");
            $query->bind_param("sssi", $NamaAlbum, $Deskripsi, $TanggalDibuat, $UserID);
        }

        if ($query->execute()) {
            header("Location: tampilan-kategori.php");
            exit();
        } else {
            echo "Error: " . $query->error;
        }
    } else {
        echo "Semua field harus diisi.";
    }
}

// Ambil data untuk edit jika ada AlbumID di URL
$edit_data = null;
if (isset($_GET['id'])) {
    $foto_id = $_GET['id'];
    $sql_edit = "SELECT * FROM gallery_album WHERE AlbumID = ?";
    $stmt_edit = $conn->prepare($sql_edit);
    $stmt_edit->bind_param("i", $foto_id);
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
    <h1 class="h3 mb-4 text-gray-800"><?= $edit_data ? 'Edit' : 'Tambah'; ?> Gallery</h1>

    <form class="user" action="" method="post">
        <input type="hidden" name="id" value="<?= $edit_data ? $edit_data['AlbumID'] : ''; ?>">

        <div class="form-group">
            <input type="text" class="form-control  " name="NamaAlbum"
                   placeholder="Kategori" value="<?= $edit_data ? htmlspecialchars($edit_data['NamaAlbum']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control  " name="Deskripsi"
                   placeholder="Deskripsi Kategori" value="<?= $edit_data ? htmlspecialchars($edit_data['Deskripsi']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <input type="date" class="form-control  " name="TanggalDibuat"
                   value="<?= $edit_data ? $edit_data['TanggalDibuat'] : ''; ?>" required>
        </div>
        <div class="form-group">
            <input type="number" class="form-control  " name="UserID"
                   placeholder="User ID" value="<?= $edit_data ? $edit_data['UserID'] : ''; ?>" required>
        </div>

        <button type="submit" class="btn btn-primary ">
            <?= $edit_data ? 'Update' : 'Simpan'; ?>
        </button> 
        <a href="tampilan-kategori.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php
include 'footer.php';
?>
