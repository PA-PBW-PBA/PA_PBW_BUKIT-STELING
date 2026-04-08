<?php 
session_start();
include '../../config/koneksi.php'; 
include '../templates/header.php'; 
include '../templates/navbar_public.php'; 

$query_galeri = mysqli_query($koneksi, "SELECT * FROM tb_galeri WHERE status = 'approved' ORDER BY id_galeri DESC LIMIT 6");

$query_ulasan = mysqli_query($koneksi, "SELECT tb_ulasan.*, tb_pengunjung.nama_lengkap 
                                        FROM tb_ulasan 
                                        JOIN tb_pengunjung ON tb_ulasan.id_pengunjung = tb_pengunjung.id_pengunjung 
                                        WHERE rating >= 4 
                                        ORDER BY id_ulasan DESC LIMIT 6");

$galeri_items = [];
while ($g = mysqli_fetch_assoc($query_galeri)) {
    $galeri_items[] = $g;
}
?>

<div class="min-vh-100 bg-white">
    <section class="py-5 lg:py-24 text-center" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('../../assets/img/fasilitas/Puncak Steling.JPG'); background-size: cover; background-position: center; min-height: 80vh; display: flex; align-items: center;">
        <div class="container py-5 text-white">
            <h1 class="display-3 fw-bold mb-3">Nikmati Senja di <br><span style="color: var(--primary) !important;">Puncak Steling</span></h1>
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
                <a href="galeri.php" class="text-primary-custom fw-bold text-decoration-none small transition-all">Lihat Semua <i class="bi bi-arrow-right ms-1"></i></a>
            </div>

            <?php if (count($galeri_items) > 0) : ?>
            <div class="galeri-marquee-wrapper">
                <div class="galeri-marquee">
                    <?php foreach ($galeri_items as $g) : 
                        $img = (strpos($g['file_foto'], 'http') !== false) ? $g['file_foto'] : "../../assets/img/uploads/" . $g['file_foto'];
                    ?>
                    <div class="galeri-marquee-item">
                        <div class="card card-custom overflow-hidden border-0"
                             style="cursor: pointer;"
                             data-bs-toggle="modal"
                             data-bs-target="#modalBeranda<?php echo $g['id_galeri']; ?>">
                            <div class="ratio ratio-4x3 overflow-hidden">
                                <img src="<?php echo $img; ?>" class="object-fit-cover hover-zoom" alt="Galeri">
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php foreach ($galeri_items as $g) : 
                        $img = (strpos($g['file_foto'], 'http') !== false) ? $g['file_foto'] : "../../assets/img/uploads/" . $g['file_foto'];
                    ?>
                    <div class="galeri-marquee-item">
                        <div class="card card-custom overflow-hidden border-0"
                             style="cursor: pointer;"
                             data-bs-toggle="modal"
                             data-bs-target="#modalBeranda<?php echo $g['id_galeri']; ?>">
                            <div class="ratio ratio-4x3 overflow-hidden">
                                <img src="<?php echo $img; ?>" class="object-fit-cover hover-zoom" alt="Galeri">
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container py-5">
            <div class="d-flex justify-content-between align-items-end mb-5">
                <div>
                    <h6 class="text-primary-custom fw-bold text-uppercase small" style="letter-spacing: 2px;">Testimoni</h6>
                    <h2 class="fw-bold text-dark">Apa Kata Mereka?</h2>
                </div>
                <a href="ulasan.php" class="text-primary-custom fw-bold text-decoration-none small transition-all">Lihat Semua <i class="bi bi-arrow-right ms-1"></i></a>
            </div>

            <div class="row g-4 text-start">
                <?php while ($u = mysqli_fetch_assoc($query_ulasan)) : ?>
                <div class="col-md-4">
                    <div class="card card-custom p-4 h-100 border-0 shadow-sm bg-white">
                        <div class="text-warning mb-3">
                            <?php for($i=1;$i<=5;$i++) echo ($i<=$u['rating']) ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star text-muted"></i>'; ?>
                        </div>
                        <p class="text-dark fw-medium mb-4 fst-italic">"<?php echo (strlen($u['komentar']) > 100) ? substr($u['komentar'], 0, 100) . '...' : $u['komentar']; ?>"</p>
                        <div class="d-flex align-items-center gap-2 mt-auto pt-3 border-top border-light">
                            <div class="bg-primary-custom rounded-circle" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-person-fill text-white small"></i>
                            </div>
                            <span class="fw-bold small text-dark"><?php echo htmlspecialchars($u['nama_lengkap']); ?></span>
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
            <a href="informasi.php" class="btn btn-light rounded-pill px-5 py-3 fw-bold text-primary-custom shadow hover-up">Cek Harga Tiket</a>
        </div>
    </section>
</div>

<?php foreach ($galeri_items as $g) : 
    $img = (strpos($g['file_foto'], 'http') !== false) ? $g['file_foto'] : "../../assets/img/uploads/" . $g['file_foto'];
?>
<div class="modal fade" id="modalBeranda<?php echo $g['id_galeri']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0 shadow-none position-relative">
            <button type="button" 
                    class="btn-close btn-close-white position-absolute rounded-circle" 
                    data-bs-dismiss="modal" 
                    aria-label="Close"
                    style="top: 15px; right: 15px; z-index: 1070; background-color: rgba(0,0,0,0.5); padding: 10px;">
            </button>
            <div class="modal-body p-0 text-center overflow-hidden rounded-4 bg-black d-flex align-items-center justify-content-center shadow-lg" style="max-height: 85vh;">
                <img src="<?php echo $img; ?>" class="img-fluid w-100 h-100 object-fit-contain" alt="Fullscreen">
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php include '../templates/footer.php'; ?>