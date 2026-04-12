<?php
/**
 * views/admin/dashboard.php
 * Halaman dashboard admin — hanya berisi tampilan HTML
 * Semua logika ditangani oleh AdminController
 */

session_start();
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/AdminController.php';

$controller = new AdminController($koneksi);
$data       = $controller->dashboard();

$rating_final = $data['rating_final'];
$total_foto   = $data['total_foto'];
$user_baru    = $data['user_baru'];

include '../templates/header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div class="container-fluid px-0" id="app-dashboard">
    <div class="row g-0">
        <?php include '../templates/sidebar_admin.php'; ?>

        <div class="col-md-10 p-5 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center mb-5 animate__animated animate__fadeIn">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Dashboard Utama</h2>
                    <p class="text-muted mb-0">Halo <b><?php echo htmlspecialchars($_SESSION['user']); ?></b>, berikut ringkasan performa Puncak Steling.</p>
                </div>
            </div>

            <!-- Kartu Statistik -->
            <div class="row g-4 mb-5">
                <div class="col-md-4" v-for="(stat, index) in stats" :key="index">
                    <div class="card card-custom border-0 shadow-sm p-4 bg-white h-100 hover-up animate__animated animate__zoomIn"
                         :style="{ 'animation-delay': (index * 150) + 'ms' }">
                        <div class="d-flex align-items-center gap-3">
                            <div :class="['p-3 rounded-4', stat.bg]">
                                <i :class="['bi fs-3', stat.icon, stat.text]"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-0">{{ stat.value }}</h3>
                                <p class="text-muted small mb-0">{{ stat.label }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Banner & Tips -->
            <div class="row">
                <div class="col-md-8 animate__animated animate__fadeInLeft">
                    <div class="card card-custom border-0 shadow-sm p-5 bg-primary-custom text-white mb-4 overflow-hidden position-relative">
                        <div style="position: relative; z-index: 2;">
                            <h4 class="fw-bold mb-3">Sistem Pengelolaan Puncak Steling</h4>
                            <p class="opacity-75 mb-4" style="max-width: 500px;">Gunakan panel ini untuk mengupdate harga tiket, menyetujui foto kiriman pengunjung, dan memantau masukan ulasan secara real-time.</p>
                            <a href="kelola_informasi.php" class="btn btn-light rounded-pill px-4 fw-bold text-primary-custom">Update Info Wisata</a>
                        </div>
                        <i class="bi bi-mountain position-absolute opacity-25" style="font-size: 15rem; bottom: -50px; right: -20px;"></i>
                    </div>
                </div>
                <div class="col-md-4 animate__animated animate__fadeInRight">
                    <div class="card card-custom border-0 shadow-sm p-4 bg-white h-100">
                        <h6 class="fw-bold mb-3 border-bottom pb-2">Bantuan Admin</h6>
                        <ul class="list-unstyled small text-muted">
                            <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i> Rating tinggi meningkatkan visibilitas wisata.</li>
                            <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i> Foto publik adalah aset promosi gratis terbaik.</li>
                            <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i> Pantau user baru untuk melihat tren popularitas.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-up:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; transition: all 0.3s ease; }
    .bg-primary-custom { background-color: var(--primary) !important; }
    .text-primary-custom { color: var(--primary) !important; }
</style>

<script>
    const { createApp } = Vue;
    createApp({
        data() {
            return {
                stats: [
                    { label: 'Indeks Kepuasan',     value: '<?= $rating_final ?> / 5.0', icon: 'bi-star-fill',    bg: 'bg-warning bg-opacity-10', text: 'text-warning' },
                    { label: 'Koleksi Foto Publik', value: '<?= $total_foto ?>',          icon: 'bi-camera-fill',  bg: 'bg-success bg-opacity-10', text: 'text-success' },
                    { label: 'User Baru (Bulan Ini)', value: '+<?= $user_baru ?>',        icon: 'bi-graph-up-arrow', bg: 'bg-info bg-opacity-10',  text: 'text-info'    }
                ]
            }
        }
    }).mount('#app-dashboard');
</script>

<?php include '../templates/footer.php'; ?>