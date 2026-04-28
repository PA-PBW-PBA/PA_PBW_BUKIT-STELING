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
require_once __DIR__ . '/../../controllers/AdminController.php';
require_once __DIR__ . '/../../controllers/UlasanController.php';

$controller = new AdminController($koneksi);
$data       = $controller->dashboard();

$ulasanController = new UlasanController($koneksi);
$data_ulasan = $ulasanController->index()['data_ulasan'];

$rating_final = $data['rating_final'];
$total_foto   = $data['total_foto'];
$user_baru    = $data['user_baru'];

include '../templates/header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div class="container-fluid px-0" id="app-dashboard">
    <div class="row g-0">
        <?php include '../templates/sidebar_admin.php'; ?>

        <div class="col-md-10 p-3 p-md-5 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center mb-4 mb-md-5 animate__animated animate__fadeIn">
                <div>
                    <h2 class="fw-bold text-dark mb-1 fs-3 fs-md-2">Dashboard Utama</h2>
                    <p class="text-muted mb-0 small">Halo <b><?php echo htmlspecialchars($_SESSION['user']); ?></b>, berikut ringkasan performa Puncak Steling.</p>
                </div>
            </div>

            <div class="row g-3 g-md-4 mb-5">
                <div class="col-6 col-md-3" v-for="(stat, index) in stats" :key="index">
                    <div class="card card-custom border-0 shadow-sm p-3 p-md-4 bg-white h-100 hover-up animate__animated animate__zoomIn"
                         :style="{ 'animation-delay': (index * 150) + 'ms' }">
                        <div class="d-flex flex-column flex-md-row align-items-center gap-2 gap-md-3 text-center text-md-start">
                            <div :class="['p-2 p-md-3 rounded-4', stat.bg]">
                                <i :class="['bi fs-4 fs-md-3', stat.icon, stat.text]"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-0 fs-5 fs-md-3">{{ stat.value }}</h3>
                                <p class="text-muted x-small-text mb-0">{{ stat.label }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-8 animate__animated animate__fadeInLeft">
                    <div class="card card-custom border-0 shadow-sm p-4 p-md-5 bg-primary-custom text-white mb-4 overflow-hidden position-relative">
                        <div style="position: relative; z-index: 2;">
                            <h4 class="fw-bold mb-3">Sistem Pengelolaan Puncak Steling</h4>
                            <p class="opacity-75 mb-4 small" style="max-width: 500px;">Gunakan panel ini untuk mengupdate harga tiket, menyetujui foto kiriman pengunjung, dan memantau masukan ulasan secara real-time.</p>
                            <a href="kelola_informasi.php" class="btn btn-light rounded-pill px-4 fw-bold text-primary-custom btn-sm">Update Info Wisata</a>
                        </div>
                        <i class="bi bi-mountain position-absolute opacity-25 d-none d-md-block" style="font-size: 15rem; bottom: -50px; right: -20px;"></i>
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
    .x-small-text { font-size: 0.75rem; }
</style>

<script>
    const { createApp } = Vue;
    createApp({
        data() {
            return {
                allUlasan: <?php echo json_encode($data_ulasan); ?>,
                keywords: [
                    'jelek', 'rusak', 'hancur', 'bobrok', 'kotor', 'bau', 'kumuh', 'licin', 
                    'gelap', 'pengap', 'panas', 'berdebu', 'usang', 'sempit', 'berisik',
                    'lambat', 'lelet', 'lama', 'antri', 'cuek', 'kasar', 'galak', 'buruk', 
                    'payah', 'mengecewakan', 'kecewa', 'parah', 'ngaco', 'mahal', 'rugi', 
                    'boros', 'pungli', 'getok', 'bahaya', 'rawan', 'seram', 'susah', 
                    'sulit', 'macet', 'jauh', 'nyesel', 'kapok', 'sedih', 'kesal', 
                    'kesel', 'marah', 'ogah'
                ]
            }
        },
        computed: {
            isuCount() {
                return this.allUlasan.filter(u => 
                    this.keywords.some(k => u.komentar.toLowerCase().includes(k))
                ).length;
            },
            stats() {
                return [
                    { label: 'Kepuasan', value: '<?= $rating_final ?> / 5.0', icon: 'bi-star-fill', bg: 'bg-warning bg-opacity-10', text: 'text-warning' },
                    { label: 'Foto Publik', value: '<?= $total_foto ?>', icon: 'bi-camera-fill', bg: 'bg-success bg-opacity-10', text: 'text-success' },
                    { label: 'User Baru', value: '+<?= $user_baru ?>', icon: 'bi-graph-up-arrow', bg: 'bg-info bg-opacity-10', text: 'text-info' },
                    { label: 'Isu Dilaporkan', value: this.isuCount, icon: 'bi-exclamation-triangle-fill', bg: 'bg-danger bg-opacity-10', text: 'text-danger' }
                ]
            }
        }
    }).mount('#app-dashboard');
</script>

<?php include '../templates/footer.php'; ?>