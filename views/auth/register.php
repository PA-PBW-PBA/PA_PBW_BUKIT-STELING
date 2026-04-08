<?php
session_start();
include '../../config/koneksi.php';

$pesan_swal = '';

if (isset($_POST['register'])) {
    $nama = trim(mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']));
    $email = trim(mysqli_real_escape_string($koneksi, $_POST['email']));
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $allowed_domains = ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com', 'icloud.com', 'mail.com'];
    $email_parts = explode('@', $email);
    $domain = end($email_parts);

    // Regex untuk melarang emoji
    $no_emoji = "/^[a-zA-Z0-9!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?\s]*$/";

    if (strlen($nama) < 5) {
        $error = "Nama minimal harus 5 karakter.";
    } elseif (!preg_match("/^[a-zA-Z\s]*$/", $nama)) {
        $error = "Nama hanya boleh berisi huruf dan spasi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif (!in_array(strtolower($domain), $allowed_domains)) {
        $error = "Gunakan layanan email populer (Gmail, Yahoo, dll).";
    } elseif (strlen($password) < 8) { // VALIDASI PHP: Minimal 8 Karakter
        $error = "Password minimal harus 8 karakter.";
    } elseif (!preg_match($no_emoji, $email)) {
        $error = "Email tidak boleh berisi emoji.";
    } elseif (!preg_match($no_emoji, $password)) {
        $error = "Password tidak boleh berisi emoji.";
    } else {
        $cek_email = mysqli_query($koneksi, "SELECT email FROM tb_pengunjung WHERE email = '$email'");
        
        if (mysqli_num_rows($cek_email) > 0) {
            $error = "Email ini sudah terdaftar!";
        } else {
            // Sebaiknya gunakan password_hash() untuk keamanan, namun ini sesuai struktur database Anda
            $query = "INSERT INTO tb_pengunjung (nama_lengkap, email, password) VALUES ('$nama', '$email', '$password')";

            if (mysqli_query($koneksi, $query)) {
                $pesan_swal = "
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Akun Anda telah dibuat. Silakan login.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location='login.php';
                    });
                ";
            } else {
                $error = "Terjadi kesalahan sistem.";
            }
        }
    }
}

include '../templates/header.php';
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid vh-100 d-flex align-items-center justify-content-center" style="background: radial-gradient(circle at top right, #ecfdf5, #f8fafc);">
    <div class="card border-0 shadow-xl rounded-4 overflow-hidden shadow-2xl page-transition" style="max-width: 1000px; width: 100%; min-height: 650px;">
        <div class="row g-0 h-100" style="min-height: 650px;">
            <div class="col-md-6 d-none d-md-block position-relative">
                <img src="../../assets/img/fasilitas/Puncak Steling.JPG" class="img-fluid h-100 w-100 object-fit-cover position-absolute" alt="Register Image" style="top:0; left:0;">
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end p-5" style="background: linear-gradient(to top, rgba(15, 23, 42, 0.8), transparent);">
                    <div class="text-white">
                        <h2 class="fw-bold mb-2">Puncak Steling</h2>
                        <p class="opacity-75 mb-0">Bergabunglah dengan ribuan pengunjung lainnya untuk pengalaman wisata tak terlupakan.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 p-5 bg-white d-flex flex-column justify-content-center">
                <div class="mb-4">
                    <a href="../public/beranda.php" class="text-decoration-none text-muted fw-semibold small d-inline-flex align-items-center gap-2 hover-primary">
                        <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                    </a>
                </div>

                <div class="mb-4">
                    <h3 class="fw-bold text-dark mb-2" style="letter-spacing: -0.5px;">Buat Akun Baru</h3>
                    <p class="text-muted small">Daftar menggunakan email aktif kamu untuk mulai menjelajah.</p>
                </div>

                <?php if (isset($error)) : ?>
                    <div class="alert alert-danger py-2 px-3 small border-0 rounded-3 d-flex align-items-center gap-2 mb-4">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <div><?php echo $error; ?></div>
                    </div>
                <?php endif; ?>

                <form action="" method="POST" id="registerForm">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark opacity-75">Nama Lengkap</label>
                        <div class="input-group shadow-sm-custom transition-all" id="nama-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted border-0 shadow-none"><i class="bi bi-person"></i></span>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control form-control-lg fs-6 rounded-end-3 bg-light border-start-0 border-0" placeholder="Nama Anda" required value="<?php echo isset($_POST['nama_lengkap']) ? htmlspecialchars($_POST['nama_lengkap']) : ''; ?>">
                        </div>
                        <div id="nama-error" class="invalid-feedback-custom"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark opacity-75">Alamat Email</label>
                        <div class="input-group shadow-sm-custom transition-all" id="email-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted border-0 shadow-none"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" id="email" class="form-control form-control-lg fs-6 rounded-end-3 bg-light border-start-0 border-0" placeholder="Email Anda" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                        <div id="email-error" class="invalid-feedback-custom"></div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark opacity-75">Password</label>
                        <div class="input-group shadow-sm-custom transition-all" id="password-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted border-0 shadow-none"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control form-control-lg fs-6 bg-light border-start-0 border-0" placeholder="Password Anda" required>
                            <span class="input-group-text bg-light border-start-0 rounded-end-3 text-muted border-0 shadow-none" id="togglePassword" style="cursor: pointer;">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </span>
                        </div>
                        <div id="password-error" class="invalid-feedback-custom"></div>
                    </div>

                    <button type="submit" name="register" class="btn btn-primary-custom w-100 btn-lg rounded-3 fs-6 fw-bold shadow-sm py-3 mb-4">
                        Daftar Akun
                    </button>
                </form>

                <div class="text-center">
                    <p class="small text-muted mb-0">Sudah punya akun? <a href="login.php" class="text-primary-custom fw-bold text-decoration-none">Masuk di Sini</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (!empty($pesan_swal)) echo $pesan_swal; ?>
    });
</script>

<?php include '../templates/footer.php'; ?>