<?php 
session_start();
include '../../config/koneksi.php'; 
include '../templates/header.php'; 
include '../templates/navbar_public.php'; 

$query_galeri = mysqli_query($koneksi, "SELECT * FROM tb_galeri WHERE status = 'approved' ORDER BY id_galeri DESC LIMIT 3");

$query_ulasan = mysqli_query($koneksi, "SELECT tb_ulasan.*, tb_pengunjung.nama_lengkap 
                                       FROM tb_ulasan 
                                       JOIN tb_pengunjung ON tb_ulasan.id_pengunjung = tb_pengunjung.id_pengunjung 
                                       WHERE rating >= 4 
                                       ORDER BY id_ulasan DESC LIMIT 3");
?>

<div class="min-vh-100 bg-white">
    <section class="py-5 lg:py-24 text-center" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../../assets/img/fasilitas/spot_foto.jpg'); background-size: cover; background-position: center; min-height: 80vh; display: flex; align-items: center;">
        <div class="container py-5 text-white">
            <h1 class="display-3 fw-bold mb-3">Nikmati Senja di <br><span class="text-primary-custom" style="color: #4ade80 !important;">Puncak Steling</span></h1>
            <p class="lead mb-5 opacity-75 mx-auto" style="max-width: 600px;">Rasakan udara segar dan pemandangan City Light Samarinda yang memukau dari ketinggian 150+ mdpl.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="informasi.php" class="btn btn-primary-custom rounded-pill px-5 py-3 fw-bold shadow-lg">Jelajahi Sekarang</a>
                <a href="galeri.php" class="btn btn-outline-light rounded-pill px-5 py-3 fw-bold">Lihat Galeri</a>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container py-5">
            <div class="d-flex justify-content-between align-items-end mb-5">
                <div>
                    <h6 class="text-primary-custom fw-bold text-uppercase small" style="letter-spacing: 2px;">Momen Terbaru</h6>
                    <h2 class="fw-bold text-dark">Lensa Pengunjung</h2>
                </div>
                <a href="galeri.php" class="text-primary-custom fw-bold text-decoration-none small">Lihat Semua <i class="bi bi-arrow-right ms-1"></i></a>
            </div>

            <div class="row g-4">
                <?php while ($g = mysqli_fetch_assoc($query_galeri)) : 
                    $img = (strpos($g['file_foto'], 'http') !== false) ? $g['file_foto'] : "../../assets/img/uploads/" . $g['file_foto'];
                ?>
                <div class="col-md-4">
                    <div class="card card-custom border-0 shadow-sm overflow-hidden h-100 bg-white">
                        <div class="ratio ratio-4x3 overflow-hidden">
                            <img src="<?php echo $img; ?>" class="object-fit-cover transition-all hover-zoom" alt="Galeri">
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container py-5 text-center">
            <h6 class="text-primary-custom fw-bold text-uppercase small mb-3" style="letter-spacing: 2px;">Testimoni</h6>
            <h2 class="fw-bold text-dark mb-5">Apa Kata Mereka?</h2>

            <div class="row g-4 text-start">
                <?php while ($u = mysqli_fetch_assoc($query_ulasan)) : ?>
                <div class="col-md-4">
                    <div class="card card-custom border-0 shadow-sm p-4 bg-white h-100">
                        <div class="text-warning mb-3">
                            <?php for($i=1;$i<=5;$i++) echo ($i<=$u['rating']) ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star text-muted"></i>'; ?>
                        </div>
                        <p class="text-dark fw-medium mb-4 italic">"<?php echo (strlen($u['komentar']) > 100) ? substr($u['komentar'], 0, 100) . '...' : $u['komentar']; ?>"</p>
                        <div class="d-flex align-items-center gap-2 mt-auto">
                            <div class="bg-primary-custom rounded-circle p-1" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-person text-white small"></i>
                            </div>
                            <span class="fw-bold small text-dark"><?php echo $u['nama_lengkap']; ?></span>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <section class="py-5 bg-primary-custom text-white text-center">
        <div class="container py-4">
            <h2 class="fw-bold mb-4">Siap Menikmati Keindahan Puncak Steling?</h2>
            <a href="informasi.php" class="btn btn-light rounded-pill px-5 py-3 fw-bold text-primary-custom shadow">Cek Harga Tiket</a>
        </div>
    </section>
</div>

<style>
    .hover-zoom:hover { transform: scale(1.1); transition: 0.5s; }
    .italic { font-style: italic; }
</style>

<?php include '../templates/footer.php'; ?>