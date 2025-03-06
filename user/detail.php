<?php
// Memasukkan koneksi ke database
include('../koneksi/koneksi.php');

// Mengambil parameter FotoID dari URL
$foto_id = isset($_GET['FotoID']) ? (int)$_GET['FotoID'] : 0;

// Cek apakah FotoID ada dan valid
if ($foto_id > 0) {
    // Query untuk mengambil data gambar berdasarkan FotoID
    $query = "SELECT * FROM gallery_foto WHERE FotoID = $foto_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Mengambil data gambar dari database
        $row = mysqli_fetch_assoc($result);
    } else {
        // Jika foto tidak ditemukan
        $error_message = "Gambar tidak ditemukan!";
    }
} else {
    // Jika FotoID tidak ada atau tidak valid
    $error_message = "ID gambar tidak valid!";
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Gambar</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .card-custom {
            max-width: 1100px;
            margin: 50px auto;
            background: white;
            border-radius: 12px; 
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        } 

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px 0 0 12px;
        }

        .card-body {
            padding: 30px;
        }

        .detail-title {
            font-size: 1.8rem;
            font-weight: 600;
        }

        .detail-description {
            font-size: 1rem;
            color: #555;
            line-height: 1.6;
        }

        .btn-like {
            background-color: #ff4757;
            color: white;
            font-weight: 600;
            border-radius: 25px;
            padding: 10px 18px;
            transition: 0.3s;
        }

        .btn-like.liked {
            background-color: #ff6b81;
        }

        .btn-like:hover {
            background-color: #c2001a;
        }

        .back-button {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 8px 12px;
            border-radius: 50%;
            text-decoration: none;
            font-size: 20px;
            transition: background 0.3s;
        }

        .back-button:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        .footer {
            text-align: center;
            padding: 15px;
            font-size: 14px;
            color: #777;
        }

        /* Responsive Design */
@media (max-width: 768px) {
    .col-md-6 {
        flex: 0 0 100%; /* Stack columns on small screens */
        max-width: 100%;
    }

    .image-container img {
        height: auto; /* Let image height adjust */
    }
}
    </style>
</head>
<body>

    <div class="container">
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger text-center mt-4">
                <?php echo $error_message; ?>
            </div>
        <?php else: ?>
            <div class="card card-custom">
                <a href="javascript:history.back()" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                </a>

                <div class="row g-0">
                    <div class="col-md-6">
                        <div class="image-container">
                            <img src="../img/produk/<?php echo htmlspecialchars($row['LokasiFile']); ?>" alt="Gambar">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card-body">
                            <h2 class="detail-title"><?php echo htmlspecialchars($row['JudulFoto']); ?></h2>
                            <p class="detail-description"><?php echo nl2br(htmlspecialchars($row['DeskripsiFoto'])); ?></p>

                            <!-- Tombol Like -->
                            <button class="btn btn-like" id="likeBtn" data-fotoid="<?php echo $foto_id; ?>">
                                <i class="fas fa-heart"></i> Like
                            </button>

                            
                            <!-- Tombol Hubungi -->
                            <a href="https://wa.me/6281234567890?text=Halo!%20Saya%20tertarik%20dengan%20gambar%20ini." 
                               class="btn btn-success">
                                <i class="fab fa-whatsapp"></i> Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        <?php endif; ?>
    </div>

    <div class="footer">
        &copy; <?php echo date("Y"); ?> Selvi Yanti
    </div>

    <!-- AJAX Like -->
    <script>
        $(document).ready(function() {
        $('#likeBtn').click(function() {
            var fotoID = $(this).data('fotoid');

            $.ajax({
                url: 'like.php',
                type: 'POST',
                data: { FotoID: fotoID },
                dataType: 'json',
                success: function(response) {
                    if (response.status === "liked") {
                        $('#likeBtn').addClass('liked');
                        $('#likeBtn').html('<i class="fas fa-heart"></i> Liked');
                    } else if (response.status === "unliked") {
                        $('#likeBtn').removeClass('liked');
                        $('#likeBtn').html('<i class="fas fa-heart"></i> Like');
                    }
                }
            });
        });
    });

    </script>

</body>
</html>
