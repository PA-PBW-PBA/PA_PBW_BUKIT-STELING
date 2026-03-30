<?php
session_start();
include '../../config/koneksi.php';

if (isset($_POST['register'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $cek_email = mysqli_query($koneksi, "SELECT email FROM tb_pengunjung WHERE email = '$email'");
    
    if (mysqli_num_rows($cek_email) > 0) {
        $error = "Email ini sudah terdaftar!";
    } else {
        $query = "INSERT INTO tb_pengunjung (nama_lengkap, email, password) VALUES ('$nama', '$email', '$password')";
        
        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('Akun berhasil dibuat! Silakan login.'); window.location='login.php';</script>";
            exit;
        } else {
            $error = "Terjadi kesalahan: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Puncak Steling</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100 py-5">
        <div class="card card-auth p-4 p-md-5 w-100" style="max-width: 480px;">
            <div class="text-center mb-4">
                <div class="icon-box">
                    <i class="bi bi-person-plus-fill fs-3"></i>
                </div>
                <h3 class="fw-bold text-dark">Daftar Akun</h3>
                <p class="text-muted small">Buat akun untuk mulai berbagi momen indah di Puncak Steling</p>
            </div>

            <?php if(isset($error)) : ?>
                <div class="alert alert-danger border-0 small text-center py-2 mb-4 rounded-3">
                    <i class="bi bi-exclamation-circle me-1"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control form-control-custom" placeholder="Masukkan nama Anda" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark">Alamat Email</label>
                    <input type="email" name="email" class="form-control form-control-custom" placeholder="nama@email.com" required>
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-bold text-dark">Password</label>
                    <input type="password" name="password" class="form-control form-control-custom" placeholder="••••••••" required>
                </div>
                <button type="submit" name="register" class="btn btn-primary-custom w-100 shadow-sm">Daftar Sekarang</button>
            </form>

            <div class="text-center mt-4 pt-3 border-top">
                <p class="small text-muted mb-0">Sudah punya akun? <a href="login.php" class="text-primary-custom fw-bold text-decoration-none">Masuk di sini</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>