<?php 
session_start();
include '../../config/koneksi.php'; 
include '../templates/header.php'; 
include '../templates/navbar_public.php'; 

$query_avg = mysqli_query($koneksi, "SELECT AVG(rating) as rata_rata, COUNT(*) as total FROM tb_ulasan");
$data_avg = mysqli_fetch_assoc($query_avg);
$rata_rata = round($data_avg['rata_rata'], 1);
$total_ulasan = $data_avg['total'];
?>

<div id="app-ulasan" class="min-vh-100 bg-white">
    <section class="py-5 border-b border-light" style="background-color: #F0FAF5;">
        <div class="container py-3">
            <nav class="d-flex align-items-center gap-2 mb-4 text-muted small">
                <a href="beranda.php" class="text-decoration-none text-muted">Home</a>
                <i class="bi bi-chevron-right" style="font-size: 0.7rem;"></i>
                <span class="text-dark fw-bold">Ulasan</span>
            </nav>
            <h1 class="display-5 fw-bold text-dark mb-0">Ulasan Pengunjung</h1>
            <p class="text-muted mt-2 mb-0">Cerita dan pengalaman mereka di Puncak Steling</p>
        </div>
    </section>

    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 85px; z-index: 99;">
                    <div class="card card-custom border-0 shadow-sm p-4 mb-4 bg-white">
                        <h5 class="fw-bold mb-3">Statistik Penilaian</h5>
                        <div class="d-flex align-items-center gap-3">
                            <h1 class="display-4 fw-bold text-primary-custom mb-0"><?php echo $rata_rata ?: '0'; ?></h1>
                            <div>
                                <div class="text-warning mb-1">
                                    <?php 
                                    for($i=1; $i<=5; $i++) {
                                        if ($i <= $rata_rata) echo '<i class="bi bi-star-fill"></i>';
                                        else if ($i - 0.5 <= $rata_rata) echo '<i class="bi bi-star-half"></i>';
                                        else echo '<i class="bi bi-star text-muted"></i>';
                                    }
                                    ?>
                                </div>
                                <p class="text-muted small mb-0">Berdasarkan <?php echo $total_ulasan; ?> ulasan</p>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($_SESSION['login']) && $_SESSION['role'] === 'pengunjung') : ?>
                        <div class="card card-custom border-0 shadow-sm p-4 bg-white">
                            <h5 class="fw-bold mb-4">Tulis Ulasan</h5>
                            <form action="proses_ulasan.php" method="POST">
                                <div class="mb-3 text-center">
                                    <label class="form-label d-block small fw-bold text-muted text-uppercase">Beri Rating</label>
                                    <div class="d-flex justify-content-center gap-2 fs-2 text-warning">
                                        <input type="hidden" name="rating" :value="rating">
                                        <i v-for="n in 5" :key="n" 
                                           class="bi cursor-pointer star-icon transition-all" 
                                           :class="(n <= (hoverRating || rating)) ? 'bi-star-fill' : 'bi-star'"
                                           @click="setRating(n)" 
                                           @mouseover="setHover(n)" 
                                           @mouseleave="setHover(0)"></i>
                                    </div>
                                    <p v-if="rating === 0" class="small text-danger fw-bold mt-2">
                                        <i class="bi bi-exclamation-circle me-1"></i> Pilih bintang terlebih dahulu
                                    </p>
                                    <p v-else class="small text-success fw-bold mt-2">
                                        Terima kasih! Kamu memilih {{ rating }} bintang
                                    </p>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label small fw-bold text-muted text-uppercase">Komentar</label>
                                    <textarea name="komentar" class="form-control" rows="4" placeholder="Bagikan pengalamanmu..." required></textarea>
                                </div>
                                <button type="submit" name="kirim" class="btn btn-primary-custom w-100 shadow-sm fw-bold rounded-pill py-2" :disabled="rating === 0">
                                    Kirim Ulasan
                                </button>
                            </form>
                        </div>
                    <?php elseif (isset($_SESSION['login']) && $_SESSION['role'] === 'admin') : ?>
                        <div class="card card-custom border-0 shadow-sm p-5 text-center bg-white">
                            <div class="mb-3 text-primary-custom">
                                <i class="bi bi-shield-lock-fill" style="font-size: 3rem;"></i>
                            </div>
                            <h6 class="fw-bold text-dark">Panel Moderator</h6>
                            <p class="small text-muted mb-4">Anda login sebagai admin. Silakan kelola atau balas ulasan melalui panel admin.</p>
                            <a href="../admin/kelola_ulasan.php" class="btn btn-primary-custom w-100 rounded-pill fw-bold shadow-sm py-2">
                                Kelola Ulasan
                            </a>
                        </div>
                    <?php else : ?>
                        <div class="card card-custom border-0 shadow-sm p-5 text-center bg-white">
                            <div class="mb-3 text-muted opacity-50">
                                <i class="bi bi-lock-fill" style="font-size: 3rem;"></i>
                            </div>
                            <h6 class="fw-bold text-dark">Ingin Menulis Ulasan?</h6>
                            <p class="small text-muted mb-4">Silakan login sebagai <b>pengunjung</b> terlebih dahulu untuk berbagi pengalamanmu.</p>
                            <a href="../auth/login.php" class="btn btn-primary-custom w-100 rounded-pill fw-bold shadow-sm py-2">
                                Login Sekarang
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="row g-4">
                    <?php
                    $limit = 5; 
                    $page = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
                    $start = ($page > 1) ? ($page * $limit) - $limit : 0;
                    $query_ulasan = mysqli_query($koneksi, "SELECT tb_ulasan.*, tb_pengunjung.nama_lengkap FROM tb_ulasan JOIN tb_pengunjung ON tb_ulasan.id_pengunjung = tb_pengunjung.id_pengunjung ORDER BY id_ulasan DESC LIMIT $start, $limit");
                    
                    if(mysqli_num_rows($query_ulasan) > 0) :
                        while($u = mysqli_fetch_assoc($query_ulasan)) :
                    ?>
                    <div class="col-12">
                        <div class="card card-custom border-0 shadow-sm p-4 bg-white">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary-custom bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                        <i class="bi bi-person-fill text-primary-custom"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0"><?php echo $u['nama_lengkap']; ?></h6>
                                        <small class="text-muted"><?php echo date('d F Y', strtotime($u['tanggal_ulasan'])); ?></small>
                                    </div>
                                </div>
                                <div class="text-warning">
                                    <?php for($i=1;$i<=5;$i++) echo ($i<=$u['rating']) ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star text-muted"></i>'; ?>
                                </div>
                            </div>
                            <p class="mb-0 text-dark" style="line-height: 1.7;">"<?php echo $u['komentar']; ?>"</p>

                            <?php if(!empty($u['balasan_admin'])) : ?>
                                <div class="mt-4 p-3 rounded-4 border-start border-4 border-primary-custom" style="background-color: #F0FAF5;">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-patch-check-fill text-primary-custom"></i>
                                        <small class="fw-bold text-dark">Tanggapan Pengelola</small>
                                    </div>
                                    <p class="mb-0 small text-dark opacity-75"><?php echo $u['balasan_admin']; ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                    <?php else : ?>
                        <div class="text-center py-5">
                            <p class="text-muted">Belum ada ulasan untuk saat ini.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>