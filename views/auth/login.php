<?php
session_start();

if (isset($_SESSION['login'])) {
    $timeout = 3600;
    
    if (isset($_SESSION['last_activity'])) {
        $duration = time() - $_SESSION['last_activity'];
        if ($duration > $timeout) {
            session_unset();
            session_destroy();
            header("Location: ../auth/login.php?msg=session_expired");
            exit;
        }
    }
    $_SESSION['last_activity'] = time();
}

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/AuthController.php';

$controller = new AuthController($koneksi);
$data       = $controller->login();

// Ambil data dari controller untuk dipakai di view
$slider_photos = $data['slider_photos'];
$alert_script  = $data['alert_script'];
$error         = $data['error'];

include '../templates/header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div id="app-login" class="container-fluid vh-100 d-flex align-items-center justify-content-center hero-gradient position-relative overflow-hidden p-0">
    <div class="row g-0 w-100 h-100">

        <!-- Kolom kiri: Slider foto -->
        <div class="col-md-6 d-none d-md-block position-relative order-md-1 overflow-hidden">
            <transition name="slide-fade">
                <img :key="currentIdx" :src="photos[currentIdx]" class="img-fluid h-100 w-100 object-fit-cover position-absolute top-0 start-0">
            </transition>
            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end p-5 modal-gradient" style="z-index: 5;">
                <div class="text-white animate__animated animate__fadeInLeft">
                    <h2 class="fw-bold mb-2">Puncak Steling</h2>
                    <p class="opacity-75 mb-0">Nikmati pemandangan City Light Samarinda terbaik dari ketinggian.</p>
                </div>
            </div>
        </div>

        <!-- Kolom kanan: Form login -->
        <div class="col-md-6 p-4 p-md-5 bg-white d-flex flex-column justify-content-center animate__animated animate__fadeIn">
            <div class="mb-4 animate__animated animate__fadeInDown" style="animation-delay: 0.1s;">
                <a href="../public/beranda.php" class="text-decoration-none text-muted fw-semibold small d-inline-flex align-items-center gap-2 hover-scale">
                    <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>

            <div class="mb-4 animate__animated animate__fadeInDown" style="animation-delay: 0.2s;">
                <h3 class="fw-bold text-dark mb-2 text-letter-tight">Selamat Datang Kembali</h3>
                <p class="text-muted small">Silakan masuk menggunakan akun terdaftar kamu.</p>
            </div>

            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger py-2 px-3 small border-0 rounded-3 d-flex align-items-center gap-2 mb-4 animate__animated animate__shakeX">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <div><?php echo htmlspecialchars($error); ?></div>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-dark opacity-75">Alamat Email</label>
                    <div class="input-group shadow-sm-custom">
                        <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted border-0"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control form-control-lg fs-6 rounded-end-3 bg-light border-start-0 border-0" placeholder="Email Anda" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-dark opacity-75">Password</label>
                    <div class="input-group shadow-sm-custom">
                        <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted border-0"><i class="bi bi-lock"></i></span>
                        <input
                            :type="passwordVisible ? 'text' : 'password'"
                            name="password"
                            autocomplete="current-password"
                            class="form-control form-control-lg fs-6 bg-light border-start-0 border-0 hide-password-reveal"
                            placeholder="Password Anda"
                            required>
                        <span class="input-group-text bg-light border-start-0 rounded-end-3 text-muted border-0 shadow-none cursor-pointer" @click="passwordVisible = !passwordVisible">
                            <i class="bi" :class="passwordVisible ? 'bi-eye-slash' : 'bi-eye'"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" name="login" class="btn btn-primary-custom w-100 btn-lg rounded-3 fs-6 fw-bold shadow-sm py-3 mb-4 hover-up">
                    Masuk Sekarang
                </button>
            </form>

            <div class="text-center animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                <p class="small text-muted mb-0">Belum punya akun? <a href="register.php" class="text-primary-custom fw-bold text-decoration-none">Daftar sebagai Pengunjung</a></p>
            </div>
        </div>

    </div>
</div>

<style>
    .slide-fade-enter-active { transition: all 0.8s ease-out; }
    .slide-fade-leave-active { transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1); }
    .slide-fade-enter-from   { transform: translateX(50px); opacity: 0; }
    .slide-fade-leave-to     { transform: translateX(-50px); opacity: 0; }
    .hover-scale { transition: transform 0.2s ease; }
    .hover-scale:hover { transform: translateX(-5px); }

    /* Sembunyikan ikon mata bawaan browser (Chrome, Edge, IE) */
    .hide-password-reveal::-ms-reveal,
    .hide-password-reveal::-ms-clear {
        display: none !important;
    }
    .hide-password-reveal::-webkit-credentials-auto-fill-button {
        display: none !important;
    }
    input[type="password"].hide-password-reveal {
        -webkit-text-security: disc;
    }
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
            setInterval(() => {
                this.currentIdx = (this.currentIdx + 1) % this.photos.length;
            }, 1200);

            <?php if (!empty($alert_script)) echo $alert_script; ?>
        }
    }).mount('#app-login');
</script>