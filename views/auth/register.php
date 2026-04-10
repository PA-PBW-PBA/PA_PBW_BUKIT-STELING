<?php
session_start();
include '../../config/koneksi.php';

$pesan_swal = '';

// Ambil foto dari galeri untuk slider background register
$query_slider = mysqli_query($koneksi, "SELECT file_foto FROM tb_galeri WHERE status = 'approved' ORDER BY RAND() LIMIT 10");
$slider_photos = [];
while ($row = mysqli_fetch_assoc($query_slider)) {
    $slider_photos[] = "../../assets/img/uploads/" . $row['file_foto'];
}

if (empty($slider_photos)) {
    $slider_photos[] = "../../assets/img/fasilitas/Puncak Steling.JPG";
}

if (isset($_POST['register'])) {
    $nama = trim(mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']));
    $email = trim(mysqli_real_escape_string($koneksi, $_POST['email']));
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $allowed_domains = ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com', 'icloud.com', 'mail.com'];
    $email_parts = explode('@', $email);
    $domain = end($email_parts);
    $no_emoji = "/^[a-zA-Z0-9!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?\s]*$/";

    if (strlen($nama) < 5) {
        $error = "Nama minimal harus 5 karakter.";
    } elseif (!preg_match("/^[a-zA-Z\s]*$/", $nama)) {
        $error = "Nama hanya boleh berisi huruf dan spasi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif (!in_array(strtolower($domain), $allowed_domains)) {
        $error = "Gunakan layanan email populer (Gmail, Yahoo, dll).";
    } elseif (strlen($password) < 8) {
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div id="app-register" class="container-fluid vh-100 d-flex align-items-center justify-content-center hero-gradient position-relative overflow-hidden p-0">
    <div class="row g-0 w-100 h-100">
        <div class="col-md-6 d-none d-md-block position-relative order-md-1 overflow-hidden">
            <transition name="slide-fade">
                <img :key="currentIdx" :src="photos[currentIdx]" class="img-fluid h-100 w-100 object-fit-cover position-absolute top-0 start-0">
            </transition>
            
            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end p-5 modal-gradient" style="z-index: 5;">
                <div class="text-white animate__animated animate__fadeInLeft">
                    <h2 class="fw-bold mb-2">Puncak Steling</h2>
                    <p class="opacity-75 mb-0">Bergabunglah dengan ribuan pengunjung lainnya untuk pengalaman wisata tak terlupakan.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 p-4 p-md-5 bg-white d-flex flex-column justify-content-center animate__animated animate__fadeIn">
            <div class="mb-4 animate__animated animate__fadeInDown" style="animation-delay: 0.1s;">
                <a href="../public/beranda.php" class="text-decoration-none text-muted fw-semibold small d-inline-flex align-items-center gap-2 hover-scale">
                    <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>

            <div class="mb-4 animate__animated animate__fadeInDown" style="animation-delay: 0.2s;">
                <h3 class="fw-bold text-dark mb-2 text-letter-tight">Buat Akun Baru</h3>
                <p class="text-muted small">Daftar menggunakan email aktif kamu untuk mulai menjelajah.</p>
            </div>

            <?php if (isset($error)) : ?>
                <div class="alert alert-danger py-2 px-3 small border-0 rounded-3 d-flex align-items-center gap-2 mb-4 animate__animated animate__shakeX">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <div><?php echo $error; ?></div>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark opacity-75">Nama Lengkap</label>
                    <div class="input-group shadow-sm-custom transition-all">
                        <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted border-0"><i class="bi bi-person"></i></span>
                        <input type="text" name="nama_lengkap" class="form-control form-control-lg fs-6 rounded-end-3 bg-light border-start-0 border-0" placeholder="Nama Anda" required value="<?php echo isset($_POST['nama_lengkap']) ? htmlspecialchars($_POST['nama_lengkap']) : ''; ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark opacity-75">Alamat Email</label>
                    <div class="input-group shadow-sm-custom transition-all">
                        <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted border-0"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control form-control-lg fs-6 rounded-end-3 bg-light border-start-0 border-0" placeholder="Email Anda" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-dark opacity-75">Password</label>
                    <div class="input-group shadow-sm-custom transition-all">
                        <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted border-0"><i class="bi bi-lock"></i></span>
                        <input :type="passwordVisible ? 'text' : 'password'" name="password" class="form-control form-control-lg fs-6 bg-light border-start-0 border-0" placeholder="Password Anda" required>
                        <span class="input-group-text bg-light border-start-0 rounded-end-3 text-muted border-0 shadow-none cursor-pointer" @click="passwordVisible = !passwordVisible">
                            <i class="bi" :class="passwordVisible ? 'bi-eye-slash' : 'bi-eye'"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" name="register" class="btn btn-primary-custom w-100 btn-lg rounded-3 fs-6 fw-bold shadow-sm py-3 mb-4 hover-up">
                    Daftar Akun
                </button>
            </form>

            <div class="text-center animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                <p class="small text-muted mb-0">Sudah punya akun? <a href="login.php" class="text-primary-custom fw-bold text-decoration-none">Masuk di Sini</a></p>
            </div>
        </div>
    </div>
</div>

<style>
    .slide-fade-enter-active { transition: all 0.8s ease-out; }
    .slide-fade-leave-active { transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1); }
    .slide-fade-enter-from { transform: translateX(50px); opacity: 0; }
    .slide-fade-leave-to { transform: translateX(-50px); opacity: 0; }
    .hover-scale { transition: transform 0.2s ease; }
    .hover-scale:hover { transform: translateX(-5px); }
</style>

<script>
    const { createApp } = Vue;
    createApp({
        data() {
            return {
                photos: <?php echo json_encode($slider_photos); ?>,
                currentIdx: 0,
                passwordVisible: false
            }
        },
        mounted() {
            // Slider berganti setiap 1.2 detik
            setInterval(() => {
                this.currentIdx = (this.currentIdx + 1) % this.photos.length;
            }, 1200);

            // Munculkan SweetAlert jika ada pesan dari PHP
            <?php if (!empty($pesan_swal)) echo $pesan_swal; ?>
        }
    }).mount('#app-register');
</script>

<?php include '../templates/footer.php'; ?>