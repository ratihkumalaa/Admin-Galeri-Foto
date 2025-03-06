<?php
include '../koneksi/koneksi.php';

// Menangani pengiriman form untuk Create & Update User
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['Username'], $_POST['Password'], $_POST['ConfirmPassword'], $_POST['Email'], $_POST['NamaLengkap'], $_POST['Alamat'], $_POST['Role'])) {
        
        $Username = trim(htmlspecialchars($_POST['Username']));
        $Email = trim(htmlspecialchars($_POST['Email']));
        $NamaLengkap = trim(htmlspecialchars($_POST['NamaLengkap']));
        $Alamat = trim(htmlspecialchars($_POST['Alamat']));
        $Role = ($_POST['Role'] == 'admin') ? 'admin' : 'user';
        $UserID = isset($_POST['id']) ? $_POST['id'] : null;
        
        // Validasi konfirmasi password
        if ($_POST['Password'] !== $_POST['ConfirmPassword']) {
            echo "<script>alert('Password dan konfirmasi password tidak sama!'); history.back();</script>";
            exit();
        }
        
        $Password = password_hash($_POST['Password'], PASSWORD_DEFAULT);
        
        if ($UserID) {
            $query = $conn->prepare("UPDATE gallery_user SET Username=?, Email=?, NamaLengkap=?, Alamat=?, Role=? WHERE UserID=?");
            $query->bind_param("sssssi", $Username, $Email, $NamaLengkap, $Alamat, $Role, $UserID);
        } else {
            $query = $conn->prepare("INSERT INTO gallery_user (Username, Password, Email, NamaLengkap, Alamat, Role) VALUES (?, ?, ?, ?, ?, ?)");
            $query->bind_param("ssssss", $Username, $Password, $Email, $NamaLengkap, $Alamat, $Role);
        }

        if ($query->execute()) {
            header("Location: tampil-user.php");
            exit();
        } else {
            echo "Error: " . $query->error;
        }
    } else {
        echo "Semua field harus diisi.";
    }
}

// Ambil data user untuk edit jika ada UserID di URL
$edit_data = null;
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $stmt_edit = $conn->prepare("SELECT * FROM gallery_user WHERE UserID=?");
    $stmt_edit->bind_param("i", $user_id);
    $stmt_edit->execute();
    $result_edit = $stmt_edit->get_result();
    $edit_data = $result_edit->fetch_assoc();
}
?>

<?php include 'header.php'; ?>
<?php include 'menu.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $edit_data ? 'Edit' : 'Tambah'; ?> Pengguna</h1>

    <form action="" method="post" onsubmit="return validatePassword();">
        <input type="hidden" name="id" value="<?= $edit_data ? $edit_data['UserID'] : ''; ?>">

        <div class="form-group">
            <input type="text" class="form-control" name="Username" placeholder="Username" value="<?= $edit_data ? htmlspecialchars($edit_data['Username']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="Password" name="Password" placeholder="Password" <?= $edit_data ? '' : 'required'; ?>>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="ConfirmPassword" name="ConfirmPassword" placeholder="Konfirmasi Password" <?= $edit_data ? '' : 'required'; ?>>
        </div>
        <div class="form-group">
            <input type="email" class="form-control" name="Email" placeholder="Email" value="<?= $edit_data ? htmlspecialchars($edit_data['Email']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="NamaLengkap" placeholder="Nama Lengkap" value="<?= $edit_data ? htmlspecialchars($edit_data['NamaLengkap']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <textarea class="form-control" name="Alamat" placeholder="Alamat" required><?= $edit_data ? htmlspecialchars($edit_data['Alamat']) : ''; ?></textarea>
        </div>
        <div class="form-group">
            <select class="form-control" name="Role" required>
                <option value="user" <?= ($edit_data && $edit_data['Role'] == 'user') ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?= ($edit_data && $edit_data['Role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            <?= $edit_data ? 'Update' : 'Simpan'; ?>
        </button>
        <a href="tampil-user.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<script>
function validatePassword() {
    var password = document.getElementById("Password").value;
    var confirmPassword = document.getElementById("ConfirmPassword").value;
    if (password !== confirmPassword) {
        alert("Password dan konfirmasi password tidak sama!");
        return false;
    }
    return true;
}
</script>

<?php include 'footer.php'; ?>