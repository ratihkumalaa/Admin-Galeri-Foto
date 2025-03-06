<?php
include '../koneksi/koneksi.php';

// Ambil data kategori untuk dropdown
$kategori_options = [];
$query_kategori = "SELECT AlbumID, NamaAlbum FROM gallery_album";
$result_kategori = mysqli_query($conn, $query_kategori);

if ($result_kategori) {
    while ($row = mysqli_fetch_assoc($result_kategori)) {
        $kategori_options[] = $row;
    }
}

// Ambil data untuk edit jika ada ID di URL
$edit_data = null;
if (isset($_GET['id'])) {
    $foto_id = $_GET['id'];
    $sql_edit = "SELECT * FROM gallery_foto WHERE FotoID = ?";
    $stmt_edit = $conn->prepare($sql_edit);
    $stmt_edit->bind_param("i", $foto_id);
    $stmt_edit->execute();
    $result_edit = $stmt_edit->get_result();
    $edit_data = $result_edit->fetch_assoc();
}

// Menyimpan atau memperbarui data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $JudulFoto = $_POST['JudulFoto'];
    $DeskripsiFoto = $_POST['DeskripsiFoto'];
    $TanggalUnggah = $_POST['TanggalUnggah'];
    $KategoriID = $_POST['KategoriID'];

    // Cek apakah ada file yang diunggah
    $target_file = isset($edit_data['LokasiFile']) ? $edit_data['LokasiFile'] : '';
    if (!empty($_FILES['foto']['name'])) {
        $target_dir = "assets/post/";
        $target_file = $target_dir . basename($_FILES["foto"]["name"]);
        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
    }

    if (isset($_POST['FotoID'])) {
        // Update data jika ID sudah ada
        $foto_id = $_POST['FotoID'];
        $query = "UPDATE gallery_foto SET JudulFoto=?, DeskripsiFoto=?, TanggalUnggah=?, LokasiFile=?, AlbumID=? WHERE FotoID=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssii", $JudulFoto, $DeskripsiFoto, $TanggalUnggah, $target_file, $KategoriID, $foto_id);
    } else {
        // Tambah data baru
        $query = "INSERT INTO gallery_foto (JudulFoto, DeskripsiFoto, TanggalUnggah, LokasiFile, AlbumID) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $JudulFoto, $DeskripsiFoto, $TanggalUnggah, $target_file, $KategoriID);
    }

    if ($stmt->execute()) {
        header("Location: tampilan.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

include 'header.php';
include 'menu.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $edit_data ? 'Edit' : 'Tambah'; ?> Gallery</h1>

    <form class="user" action="" method="post" enctype="multipart/form-data">
        <?php if ($edit_data): ?>
            <input type="hidden" name="FotoID" value="<?= htmlspecialchars($edit_data['FotoID']) ?>">
        <?php endif; ?>

        <div class="form-group">
            <input type="text" class="form-control  " name="JudulFoto" placeholder="Judul Foto" 
                   value="<?= $edit_data ? htmlspecialchars($edit_data['JudulFoto']) : ''; ?>" required>
        </div>

        <div class="form-group">
            <input type="text" class="form-control  " name="DeskripsiFoto" placeholder="Deskripsi Foto" 
                   value="<?= $edit_data ? htmlspecialchars($edit_data['DeskripsiFoto']) : ''; ?>" required>
        </div>

        <div class="form-group">
            <input type="date" class="form-control  " name="TanggalUnggah" 
                   value="<?= $edit_data ? htmlspecialchars($edit_data['TanggalUnggah']) : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="KategoriID" class="font-weight-bold">Kategori</label>
            <select name="KategoriID" class="form-control" required>
                <option value="">Pilih Kategori</option>
                <?php foreach ($kategori_options as $kategori) : ?>
                    <option value="<?= htmlspecialchars($kategori['AlbumID']); ?>" 
                            <?= isset($edit_data) && $edit_data['AlbumID'] == $kategori['AlbumID'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($kategori['NamaAlbum']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="foto" class="font-weight-bold">Upload Foto</label>
            <input type="file" class="form-control" id="foto" name="foto">
            <?php if ($edit_data && !empty($edit_data['LokasiFile'])): ?>
                <img src="<?= htmlspecialchars($edit_data['LokasiFile']); ?>" alt="Foto Sebelumnya" width="100">
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">
            <?= $edit_data ? 'Update' : 'Simpan'; ?>
        </button>
        <a href="tampilan.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php include 'footer.php'; ?>
