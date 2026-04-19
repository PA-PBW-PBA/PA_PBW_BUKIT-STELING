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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

<div id="app-beranda" class="main-content">
    <section class="hero-section text-center text-white">
        <div class="hero-bg"></div>
        <div class="container hero-content">
            <h1 class="display-3 fw-bold mb-3 animate__animated animate__fadeInDown">Nikmati Senja di <br><span class="text-primary-custom">Puncak Steling</span></h1>
            <p class="lead mb-5 opacity-75 mx-auto max-w-600px animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">Rasakan udara segar dan pemandangan City Light Samarinda yang memukau dari ketinggian 150+ mdpl.</p>
            <div class="d-flex justify-content-center gap-3 animate__animated animate__zoomIn" style="animation-delay: 0.4s;">
                <a href="informasi.php" class="btn btn-primary-custom rounded-pill px-5 py-3 fw-bold shadow-lg hover-scale">Jelajahi Sekarang</a>
                <a href="galeri.php" class="btn btn-outline-light rounded-pill px-5 py-3 fw-bold hover-scale">Lihat Galeri</a>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container py-5">
            <div class="d-flex justify-content-between align-items-end mb-5 animate__animated animate__fadeIn">
                <div>
                    <h6 class="text-primary-custom fw-bold text-uppercase small text-letter-2px">Momen Terbaru</h6>
                    <h2 class="fw-bold text-dark">Lensa Pengunjung</h2>
                </div>
                <a href="galeri.php" class="text-primary-custom fw-bold text-decoration-none small hover-slide">Lihat Semua <i class="bi bi-arrow-right ms-1"></i></a>
            </div>

            <?php if (count($galeri_items) > 0) : ?>
            <div class="galeri-marquee-wrapper animate__animated animate__fadeIn" style="animation-delay: 0.2s;">
                <div class="galeri-marquee">
                    <?php 
                    $marquee_items = array_merge($galeri_items, $galeri_items);
                    foreach ($marquee_items as $index => $g) : 
                        $img = (strpos($g['file_foto'], 'http') !== false) ? $g['file_foto'] : "../../assets/img/uploads/" . $g['file_foto'];
                    ?>
                    <div class="galeri-marquee-item">
                        <div class="card card-custom overflow-hidden border-0 shadow-sm cursor-pointer" @click="openLightbox('<?php echo $img; ?>')">
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
                    <h6 class="text-primary-custom fw-bold text-uppercase small text-letter-2px">Testimoni</h6>
                    <h2 class="fw-bold text-dark">Apa Kata Mereka?</h2>
                </div>
                <a href="ulasan.php" class="text-primary-custom fw-bold text-decoration-none small hover-slide">Lihat Semua <i class="bi bi-arrow-right ms-1"></i></a>
            </div>

            <div class="row g-4 text-start">
                <?php 
                mysqli_data_seek($query_ulasan, 0); 
                $delay = 0;
                while ($u = mysqli_fetch_assoc($query_ulasan)) : 
                ?>
                <div class="col-md-4 animate__animated animate__fadeInUp" style="animation-delay: <?php echo $delay; ?>s;">
                    <div class="card card-custom p-4 h-100 border-0 shadow-sm bg-white hover-up">
                        <div class="text-warning mb-3">
                            <?php for($i=1;$i<=5;$i++) echo ($i<=$u['rating']) ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star text-muted"></i>'; ?>
                        </div>
                        <p class="text-dark fw-medium mb-4 fst-italic">"<?php echo (strlen($u['komentar']) > 100) ? substr($u['komentar'], 0, 100) . '...' : $u['komentar']; ?>"</p>
                        <div class="d-flex align-items-center gap-2 mt-auto pt-3 border-top border-light">
                            <div class="bg-primary-custom rounded-circle d-flex align-items-center justify-content-center avatar-sm">
                                <i class="bi bi-person-fill text-white small"></i>
                            </div>
                            <span class="fw-bold small text-dark"><?php echo htmlspecialchars($u['nama_lengkap']); ?></span>
                        </div>
                    </div>
                </div>
                <?php 
                $delay += 0.15;
                endwhile; 
                ?>
            </div>
        </div>
    </section>

    <section class="py-5 bg-primary-custom text-white text-center">
        <div class="container py-4 animate__animated animate__zoomIn" style="animation-delay: 0.3s;">
            <h2 class="fw-bold mb-4">Siap Menikmati Keindahan Puncak Steling?</h2>
            <a href="informasi.php" class="btn btn-light rounded-pill px-5 py-3 fw-bold text-primary-custom shadow hover-scale">Cek Harga Tiket</a>
        </div>
    </section>

    <transition name="fade">
        <div v-if="lightboxOpen" class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.85); z-index: 1060;" @click="closeLightbox">
            <div class="position-relative animate__animated animate__zoomIn animate__faster text-center" style="max-width: 90%; max-height: 90%; width: 1000px;">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 shadow-none z-3" @click.stop="closeLightbox" style="filter: drop-shadow(0 0 5px rgba(0,0,0,0.5));"></button>
                <div class="p-0 text-center overflow-hidden rounded-4 bg-black d-flex align-items-center justify-content-center shadow-lg" style="height: 80vh;">
                    <img :src="currentLightboxImg" class="img-fluid w-100 h-100 object-fit-contain" alt="Fullscreen">
                </div>
            </div>
        </div>
    </transition>
</div>

<script>
    const { createApp } = Vue;

    createApp({
        data() {
            return {
                lightboxOpen: false,
                currentLightboxImg: ''
            }
        },
        methods: {
            openLightbox(imgUrl) {
                this.currentLightboxImg = imgUrl;
                this.lightboxOpen = true;
                // Mencegah background scroll saat lightbox aktif
                document.body.style.overflow = 'hidden';
            },
            closeLightbox() {
                this.lightboxOpen = false;
                // Mengembalikan background scroll
                document.body.style.overflow = '';
            }
        }
    }).mount('#app-beranda');
</script>

<style>
    /* Transisi Vue */
    .fade-enter-active, .fade-leave-active {
        transition: opacity 0.3s ease;
    }
    .fade-enter-from, .fade-leave-to {
        opacity: 0;
    }
    
    /* Styling interaksi */
    .hover-scale { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-scale:hover { transform: scale(1.05); box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important; }
    
    .hover-up { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .hover-up:hover { transform: translateY(-8px); box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important; }
    
    .hover-slide { transition: padding-left 0.2s ease; }
    .hover-slide:hover { padding-left: 5px; }
    
    /* Memastikan cursor pointer pada gallery */
    .cursor-pointer { cursor: pointer; }
</style>

<?php include '../templates/footer.php'; ?>