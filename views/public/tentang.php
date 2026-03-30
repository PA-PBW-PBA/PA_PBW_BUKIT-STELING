<?php 
session_start();
include '../../config/koneksi.php'; 
include '../templates/header.php'; 
include '../templates/navbar_public.php'; 

$query_info = mysqli_query($koneksi, "SELECT * FROM tb_informasi LIMIT 1");
$info = mysqli_fetch_assoc($query_info);
?>

<div class="min-vh-100 bg-white">
    <section class="py-5 text-center" style="background-color: #F0FAF5; border-bottom: 1px solid #E2E8F0;">
        <div class="container py-5">
            <h1 class="display-4 fw-bold text-dark mb-3">Tentang Puncak Steling</h1>
            <nav class="d-flex justify-content-center gap-2 small text-muted">
                <a href="beranda.php" class="text-decoration-none text-muted hover-primary">Beranda</a>
                <span>/</span>
                <span class="text-dark fw-bold">Tentang Kami</span>
            </nav>
        </div>
    </section>

    <section class="py-5 lg:py-20">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <div class="position-relative">
                        <img src="../../assets/img/fasilitas/spot_foto.jpg" class="img-fluid rounded-4 shadow-lg w-100" alt="Puncak Steling View" onerror="this.src='https://via.placeholder.com/800x600?text=Indahnya+Puncak+Steling'">
                        <div class="position-absolute bottom-0 start-0 bg-primary-custom text-white p-4 rounded-end-4 d-none d-md-block" style="margin-bottom: -20px;">
                            <h4 class="fw-bold mb-0">150+ mdpl</h4>
                            <p class="small mb-0">Ketinggian dari permukaan laut</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 px-lg-5">
                    <h2 class="fw-bold text-dark mb-4">Ikon Wisata Alam <br>Kota Samarinda</h2>
                    <p class="text-muted mb-4" style="line-height: 1.8;">
                        Puncak Steling merupakan destinasi wisata alam yang terletak di Kelurahan Selili, Samarinda Ilir. Dikenal dengan panorama "City Light" yang memukau di malam hari dan kabut pagi yang menyegarkan, tempat ini menjadi pelarian favorit warga kota dari kebisingan rutinitas.
                    </p>
                    <p class="text-muted mb-5" style="line-height: 1.8;">
                        Dikelola dengan semangat pemberdayaan masyarakat lokal, kami berkomitmen untuk menjaga kelestarian alam sambil terus meningkatkan fasilitas kenyamanan bagi para pendaki dan wisatawan.
                    </p>
                    
                    <div class="row g-4">
                        <div class="col-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="text-primary-custom fs-3"><i class="bi bi-geo-alt"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-1">Lokasi Strategis</h6>
                                    <p class="small text-muted mb-0">Akses mudah dari pusat kota Samarinda.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-start gap-3">
                                <div class="text-primary-custom fs-3"><i class="bi bi-camera"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-1">Spot Estetik</h6>
                                    <p class="small text-muted mb-0">Beragam titik foto dengan latar panorama.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h3 class="fw-bold">Fasilitas Wisata</h3>
                <div class="bg-primary-custom mx-auto mb-3" style="width: 60px; height: 3px;"></div>
                <p class="text-muted">Fasilitas penunjang kenyamanan pengunjung di kawasan Puncak Steling.</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card card-custom p-4 border-0 shadow-sm h-100 bg-white text-center">
                        <div class="mb-3 text-primary-custom">
                            <i class="bi bi-p-square-fill fs-1"></i>
                        </div>
                        <h5 class="fw-bold small">Area Parkir</h5>
                        <p class="small text-muted mb-0">Tersedia lahan parkir yang luas dan aman untuk kendaraan roda dua maupun roda empat.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-custom p-4 border-0 shadow-sm h-100 bg-white text-center">
                        <div class="mb-3 text-primary-custom">
                            <i class="bi bi-house-heart-fill fs-1"></i>
                        </div>
                        <h5 class="fw-bold small">Gazebo & Area Santai</h5>
                        <p class="small text-muted mb-0">Tersedia beberapa titik gazebo untuk beristirahat sambil menikmati angin sejuk.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-custom p-4 border-0 shadow-sm h-100 bg-white text-center">
                        <div class="mb-3 text-primary-custom">
                            <i class="bi bi-moisture fs-1"></i>
                        </div>
                        <h5 class="fw-bold small">Toilet Umum</h5>
                        <p class="small text-muted mb-0">Fasilitas sanitasi yang bersih tersedia bagi pengunjung di area pendakian utama.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-custom p-4 border-0 shadow-sm h-100 bg-white text-center">
                        <div class="mb-3 text-primary-custom">
                            <i class="bi bi-cup-hot-fill fs-1"></i>
                        </div>
                        <h5 class="fw-bold small">Warung Kopi & Snack</h5>
                        <p class="small text-muted mb-0">Warung lokal yang menyediakan minuman hangat dan makanan ringan.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-custom p-4 border-0 shadow-sm h-100 bg-white text-center">
                        <div class="mb-3 text-primary-custom">
                            <i class="bi bi-camera-fill fs-1"></i>
                        </div>
                        <h5 class="fw-bold small">Spot Foto Estetik</h5>
                        <p class="small text-muted mb-0">Berbagai titik foto dengan latar Sungai Mahakam dan City Light Samarinda.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-custom p-4 border-0 shadow-sm h-100 bg-white text-center">
                        <div class="mb-3 text-primary-custom">
                            <i class="bi bi-moon-stars-fill fs-1"></i>
                        </div>
                        <h5 class="fw-bold small">Tempat Ibadah</h5>
                        <p class="small text-muted mb-0">Tersedia area musholla sederhana untuk memudahkan ibadah pengunjung.</p>
                    </div>
                </div>
            </div>

            <div class="mt-5 p-4 rounded-4 bg-primary-custom text-white text-center shadow">
                <div class="row align-items-center">
                    <div class="col-md-4 border-end-md">
                        <h6 class="text-uppercase small fw-bold opacity-75 mb-1">Tiket Masuk</h6>
                        <h4 class="fw-bold mb-0">Rp <?php echo number_format($info['harga_tiket'], 0, ',', '.'); ?></h4>
                    </div>
                    <div class="col-md-4 border-end-md">
                        <h6 class="text-uppercase small fw-bold opacity-75 mb-1">Jam Operasional</h6>
                        <h4 class="fw-bold mb-0"><?php echo date('H:i', strtotime($info['jam_buka'])); ?> - <?php echo date('H:i', strtotime($info['jam_tutup'])); ?> WITA</h4>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-uppercase small fw-bold opacity-75 mb-1">Status</h6>
                        <h4 class="fw-bold mb-0">Buka Setiap Hari</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include '../templates/footer.php'; ?>