<?php
session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$query_rating = mysqli_query($koneksi, "SELECT AVG(rating) as rata_rata FROM tb_ulasan");
$data_rating = mysqli_fetch_assoc($query_rating);
$rating_final = number_format($data_rating['rata_rata'], 1);

$total_approved = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_galeri FROM tb_galeri WHERE status = 'approved'"));

$bulan_ini = date('m');
$tahun_ini = date('Y');
$query_user_baru = mysqli_query($koneksi, "SELECT id_pengunjung FROM tb_pengunjung WHERE MONTH(created_at) = '$bulan_ini' AND YEAR(created_at) = '$tahun_ini'");
$user_baru = mysqli_num_rows($query_user_baru);

include '../templates/header.php';
?>

<div class="container-fluid px-0">
    <div class="row g-0">
        <?php include '../templates/sidebar_admin.php'; ?>

        <div class="col-md-10 p-5 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Dashboard Utama</h2>
                    <p class="text-muted mb-0">Halo <b>Admin</b>, berikut adalah ringkasan performa Puncak Steling periode ini.</p>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card card-custom border-0 shadow-sm p-4 bg-white h-100 hover-up">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-4">
                                <i class="bi bi-star-fill text-warning fs-3"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-0"><?php echo $rating_final; ?> / 5.0</h3>
                                <p class="text-muted small mb-0">Indeks Kepuasan</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-custom border-0 shadow-sm p-4 bg-white h-100 hover-up">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success bg-opacity-10 p-3 rounded-4">
                                <i class="bi bi-camera-fill text-success fs-3"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-0 text-success"><?php echo $total_approved; ?></h3>
                                <p class="text-muted small mb-0">Koleksi Foto Publik</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-custom border-0 shadow-sm p-4 bg-white h-100 hover-up">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-info bg-opacity-10 p-3 rounded-4">
                                <i class="bi bi-graph-up-arrow text-info fs-3"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-0 text-info">+<?php echo $user_baru; ?></h3>
                                <p class="text-muted small mb-0">User Baru (Bulan Ini)</p>
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
    .hover-up:hover { 
        transform: translateY(-5px); 
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; 
        transition: all 0.3s ease; 
    }
    .bg-primary-custom {
        background-color: var(--primary) !important;
    }
    .text-primary-custom {
        color: var(--primary) !important;
    }
</style>