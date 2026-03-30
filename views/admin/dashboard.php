<?php
session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$total_ulasan = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_ulasan FROM tb_ulasan"));
$foto_pending = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_galeri FROM tb_galeri WHERE status = 'pending'"));
$total_user = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_pengunjung FROM tb_pengunjung"));

include '../templates/header.php';
?>

<div class="container-fluid px-0">
    <div class="row g-0">
        <?php include '../templates/sidebar_admin.php'; ?>

        <div class="col-md-10 p-5 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Dashboard Utama</h2>
                    <p class="text-muted mb-0">Halo <b>Admin</b>, berikut adalah ringkasan aktivitas Puncak Steling hari ini.</p>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card card-custom border-0 shadow-sm p-4 bg-white h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary-custom bg-opacity-10 p-3 rounded-4">
                                <i class="bi bi-chat-dots-fill text-light fs-3"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-0"><?php echo $total_ulasan; ?></h3>
                                <p class="text-muted small mb-0">Total Ulasan</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-custom border-0 shadow-sm p-4 bg-white h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-4">
                                <i class="bi bi-hourglass-split text-warning fs-3"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-0 text-warning"><?php echo $foto_pending; ?></h3>
                                <p class="text-muted small mb-0">Menunggu Moderasi</p>
                            </div>
                        </div>
                        <a href="kelola_galeri.php" class="stretched-link"></a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-custom border-0 shadow-sm p-4 bg-white h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-info bg-opacity-10 p-3 rounded-4">
                                <i class="bi bi-people-fill text-info fs-3"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-0 text-info"><?php echo $total_user; ?></h3>
                                <p class="text-muted small mb-0">Akun Terdaftar</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card card-custom border-0 shadow-sm p-5 bg-primary-custom text-white mb-4 overflow-hidden position-relative">
                        <div class="position-relative" style="z-index: 2;">
                            <h4 class="fw-bold mb-3">Sistem Pengelolaan Puncak Steling</h4>
                            <p class="opacity-75 mb-4" style="max-width: 500px;">Gunakan panel ini untuk mengupdate harga tiket, menyetujui foto kiriman pengunjung, dan memantau masukan ulasan secara real-time.</p>
                            <a href="kelola_informasi.php" class="btn btn-light rounded-pill px-4 fw-bold text-primary-custom">Update Info Wisata</a>
                        </div>
                        <i class="bi bi-mountain position-absolute opacity-25" style="font-size: 15rem; bottom: -50px; right: -30px; z-index: 1;"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-custom border-0 shadow-sm p-4 bg-white h-100">
                        <h6 class="fw-bold mb-3 border-bottom pb-2">Bantuan Admin</h6>
                        <ul class="list-unstyled small text-muted">
                            <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i> Foto status 'pending' tidak akan muncul di website.</li>
                            <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i> Ulasan dengan kata-kata kasar bisa dihapus permanen.</li>
                            <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i> Selalu pratinjau website setelah mengubah info tiket.</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .hover-up:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; transition: 0.3s; }
</style>

<?php include '../templates/footer.php'; ?>