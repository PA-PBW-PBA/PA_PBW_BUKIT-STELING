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

// Ambil ulasan terbaru (Limit 3)
$ulasanController = new UlasanController($koneksi);
$data_ulasan = $ulasanController->index()['data_ulasan'];
$ulasan_terbaru = array_slice($data_ulasan, 0, 3);

// Ambil foto pending (Menunggu Persetujuan)
$query_pending = mysqli_query($koneksi, "SELECT g.*, p.nama_lengkap FROM tb_galeri g 
                                         JOIN tb_pengunjung p ON g.id_pengunjung = p.id_pengunjung 
                                         WHERE g.status = 'pending' ORDER BY g.id_galeri DESC LIMIT 3");
$foto_pending = [];
while($row = mysqli_fetch_assoc($query_pending)) { $foto_pending[] = $row; }

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
                    <p class="text-muted mb-0 small">Ringkasan performa Puncak Steling hari ini.</p>
                </div>
            </div>

            <div class="row g-3 g-md-4 mb-4 mb-md-5">
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
                <div class="col-md-8">
                    <div class="card card-custom border-0 shadow-sm p-4 bg-white mb-4 animate__animated animate__fadeInLeft">
                        <h6 class="fw-bold mb-4 d-flex align-items-center">
                            <i class="bi bi-lightning-charge-fill text-warning me-2"></i> Aktivitas Terbaru
                        </h6>
                        
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="small fw-bold text-muted text-uppercase">Persetujuan Foto</span>
                                <a href="kelola_galeri.php" class="x-small-text text-primary-custom text-decoration-none">Lihat Semua</a>
                            </div>
                            <?php if(empty($foto_pending)): ?>
                                <p class="small text-muted italic">Tidak ada foto menunggu persetujuan.</p>
                            <?php else: foreach($foto_pending as $fp): ?>
                                <div class="d-flex align-items-center gap-3 p-2 rounded-3 border-bottom mb-2 transition-row">
                                    <img src="../../assets/img/uploads/<?= $fp['file_foto'] ?>" class="rounded-3 object-fit-cover" style="width: 50px; height: 50px;">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 small fw-bold"><?= htmlspecialchars($fp['nama_lengkap']) ?></h6>
                                        <p class="mb-0 x-small-text text-muted text-truncate" style="max-width: 250px;">"<?= htmlspecialchars($fp['caption']) ?>"</p>
                                    </div>
                                    <span class="badge bg-warning-subtle text-warning x-small-text px-2 py-1">Pending</span>
                                </div>
                            <?php endforeach; endif; ?>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="small fw-bold text-muted text-uppercase">Ulasan Masuk</span>
                                <a href="kelola_ulasan.php" class="x-small-text text-primary-custom text-decoration-none">Lihat Semua</a>
                            </div>
                            <?php foreach($ulasan_terbaru as $ut): ?>
                                <div class="d-flex align-items-center gap-3 p-2 rounded-3 border-bottom mb-2 transition-row">
                                    <div class="bg-light rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-chat-dots text-secondary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="mb-0 small fw-bold"><?= htmlspecialchars($ut['nama_lengkap']) ?></h6>
                                            <div class="text-warning x-small-text">
                                                <?= $ut['rating'] ?> <i class="bi bi-star-fill"></i>
                                            </div>
                                        </div>
                                        <p class="mb-0 x-small-text text-muted text-truncate" style="max-width: 300px;"><?= htmlspecialchars($ut['komentar']) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-custom border-0 shadow-sm p-4 bg-primary-custom text-white mb-4 animate__animated animate__fadeInRight">
                        <h5 class="fw-bold mb-3">Kelola Wisata</h5>
                        <p class="small opacity-75 mb-4">Pastikan informasi harga tiket dan fasilitas selalu diperbarui untuk kenyamanan pengunjung.</p>
                        <a href="kelola_informasi.php" class="btn btn-light rounded-pill w-100 fw-bold text-primary-custom btn-sm py-2">Update Info</a>
                    </div>
                    
                    <div class="card card-custom border-0 shadow-sm p-4 bg-white animate__animated animate__fadeInRight" style="animation-delay: 0.2s;">
                        <h6 class="fw-bold mb-3 border-bottom pb-2 x-small-text text-uppercase">Tips Admin</h6>
                        <ul class="list-unstyled x-small-text text-muted mb-0">
                            <li class="mb-3 d-flex gap-2"><i class="bi bi-lightning text-warning"></i> Segera balas ulasan negatif untuk menjaga reputasi.</li>
                            <li class="mb-3 d-flex gap-2"><i class="bi bi-image text-success"></i> Setujui foto yang estetik untuk menarik pengunjung baru.</li>
                            <li class="d-flex gap-2"><i class="bi bi-shield-lock text-info"></i> Ganti password admin secara berkala di menu profil.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-up:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; transition: all 0.3s ease; }
    .transition-row { transition: background 0.2s; cursor: default; }
    .transition-row:hover { background: #f8f9fa; }
    .bg-primary-custom { background-color: var(--primary) !important; }
    .text-primary-custom { color: var(--primary) !important; }
    .x-small-text { font-size: 0.72rem; }
    .italic { font-style: italic; }
</style>

<script>
    const { createApp } = Vue;
    createApp({
        data() {
            return {
                allUlasan: <?php echo json_encode($data_ulasan); ?>,
                keywords: ['jelek', 'rusak', 'kotor', 'licin', 'mahal', 'kecewa', 'parah', 'rawan', 'pungli']
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
                    { label: 'Isu Terdeteksi', value: this.isuCount, icon: 'bi-exclamation-triangle-fill', bg: 'bg-danger bg-opacity-10', text: 'text-danger' }
                ]
            }
        }
    }).mount('#app-dashboard');
</script>

<?php include '../templates/footer.php'; ?>
