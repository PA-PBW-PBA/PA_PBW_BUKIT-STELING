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

include '../../config/koneksi.php'; 
include '../templates/header.php'; 
include '../templates/navbar_public.php'; 

$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_informasi LIMIT 1"));
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

<div id="app-informasi" class="min-vh-100 bg-white overflow-hidden">
    <section class="py-5 border-b border-light bg-light-green animate__animated animate__fadeIn">
        <div class="container py-3">
            <nav class="breadcrumb-custom mb-4">
                <a href="beranda.php" class="breadcrumb-link">Home</a>
                <i class="bi bi-chevron-right breadcrumb-separator"></i>
                <span class="breadcrumb-current">Informasi</span>
            </nav>
            <h1 class="display-5 fw-bold text-dark mb-0 animate__animated animate__fadeInDown">Informasi Kunjungan</h1>
            <p class="text-muted mt-2 mb-0 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">Persiapkan perjalananmu ke Puncak Steling</p>
        </div>
    </section>

    <div class="container py-5">
        <div class="row g-4 mb-5">
            <div class="col-md-6 animate__animated animate__fadeInLeft" style="animation-delay: 0.3s;">
                <div class="card card-custom p-5 bg-white shadow-sm border-0 h-100 hover-up">
                    <h6 class="text-muted text-uppercase fw-bold mb-4 info-label">Tiket & Jam Operasional</h6>
                    <div class="mb-4">
                        <h1 class="fw-800 text-primary-custom mb-1">Rp <?php echo number_format($data['harga_tiket'], 0, ',', '.'); ?></h1>
                        <p class="text-muted">Biaya retribusi per orang</p>
                    </div>
                    <div class="d-flex align-items-center gap-3 bg-light p-3 rounded-4">
                        <i class="bi bi-calendar-check fs-4 text-primary-custom"></i>
                        <div>
                            <p class="mb-0 fw-bold"><?php echo substr($data['jam_buka'], 0, 5); ?> - <?php echo substr($data['jam_tutup'], 0, 5); ?> WITA</p>
                            <p class="mb-0 small text-muted">Operasional Setiap Hari</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 animate__animated animate__fadeInRight" style="animation-delay: 0.4s;">
                <div class="card card-custom p-5 bg-primary-custom text-white shadow-sm border-0 h-100 hover-up">
                    <h6 class="text-white-50 text-uppercase fw-bold mb-4 info-label">Deskripsi Kawasan</h6>
                    <p class="fs-5 text-relaxed-op">
                        <?php echo $data['deskripsi']; ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="row gy-5 align-items-center mb-5">
            <div class="col-lg-5 animate__animated animate__fadeInLeft" style="animation-delay: 0.5s;">
                <h6 class="text-primary-custom fw-bold text-uppercase small mb-3 ls-widest">Lokasi Objek Wisata</h6>
                <h2 class="fw-bold text-dark mb-4">Titik Kumpul & Jalur Pendakian</h2>
                <div class="d-flex gap-3 mb-3">
                    <i class="bi bi-geo-alt-fill text-primary-custom fs-4"></i>
                    <p class="text-muted mb-0">Selili, Kec. Samarinda Ilir, Kota Samarinda, Kalimantan Timur 75251</p>
                </div>
                <div class="d-flex gap-3 mb-4">
                    <i class="bi bi-info-circle-fill text-primary-custom fs-4"></i>
                    <p class="text-muted mb-0">Gunakan peta di samping untuk melihat ulasan, foto terbaru, dan detail lokasi langsung dari Google Maps.</p>
                </div>
                <a href="https://www.google.com/maps/search/?api=1&query=Puncak+Steling+Samarinda&query_place_id=ChIJd8TuR1l_9i0RWUL2OPYPubI" target="_blank" class="btn btn-primary-custom rounded-pill px-5 py-3 fw-bold shadow hover-scale">
                    <i class="bi bi-cursor-fill me-2"></i> Buka di Aplikasi Maps
                </a>
            </div>

            <div class="col-lg-7 animate__animated animate__fadeInRight" style="animation-delay: 0.6s;">
                <div class="card border-0 shadow-lg overflow-hidden rounded-4 hover-up">
                    <iframe 
                        src="https://www.google.com/maps?q=-0.5093,117.1618&output=embed" 
                        width="100%" 
                        height="500" 
                        class="border-0-custom"
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4 pt-4 animate__animated animate__fadeInUp" style="animation-delay: 0.7s;">
            <h4 class="fw-bold mb-0">Fasilitas yang Tersedia</h4>
            <?php if (isset($_SESSION['login']) && $_SESSION['role'] === 'admin') : ?>
                <a href="../admin/kelola_fasilitas.php" class="btn btn-primary-custom rounded-pill px-4 py-2 shadow-sm d-inline-flex align-items-center gap-2 text-white hover-scale">
                    <i class="bi bi-gear-fill"></i>
                    <span class="fw-bold small text-uppercase text-nowrap">Kelola Fasilitas</span>
                </a>
            <?php endif; ?>
        </div>
        <div class="row g-4">
            <?php
            $fasilitas = mysqli_query($koneksi, "SELECT * FROM tb_fasilitas");
            $delay = 0.8; // Set delay awal untuk animasi fasilitas
            while($f = mysqli_fetch_assoc($fasilitas)) :
            ?>
            <div class="col-md-3 col-6 animate__animated animate__zoomIn" style="animation-delay: <?php echo $delay; ?>s;">
                <div class="card card-custom overflow-hidden shadow-sm border-0 bg-white cursor-pointer hover-up" 
                     @click="openLightbox('../../assets/img/fasilitas/<?php echo $f['file_gambar']; ?>', '<?php echo htmlspecialchars($f['nama_fasilitas'], ENT_QUOTES); ?>')">
                    <div class="ratio ratio-4x3 overflow-hidden">
                        <img src="../../assets/img/fasilitas/<?php echo $f['file_gambar']; ?>" class="object-fit-cover hover-zoom" alt="img">
                    </div>
                    <div class="card-body p-3 text-center">
                        <p class="mb-0 fw-bold small text-uppercase fasilitas-label"><?php echo $f['nama_fasilitas']; ?></p>
                    </div>
                </div>
            </div>
            <?php 
            $delay += 0.1; // Tambah delay 0.1 detik untuk setiap item berikutnya
            endwhile; 
            ?>
        </div>
    </div>

    <transition name="fade">
        <div v-if="lightboxOpen" class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.85); z-index: 1060;" @click="closeLightbox">
            <div class="position-relative animate__animated animate__zoomIn animate__faster text-center" style="max-width: 90%; max-height: 90%;">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 shadow-none z-3 bg-dark rounded-circle p-2" @click.stop="closeLightbox" style="transform: translate(50%, -50%); filter: drop-shadow(0 0 5px rgba(0,0,0,0.5));"></button>
                <div class="p-4 text-center overflow-hidden rounded-4 bg-white d-flex flex-column align-items-center justify-content-center shadow-lg">
                    <h5 class="fw-bold mb-3 text-dark text-uppercase">{{ currentTitle }}</h5>
                    <img :src="currentLightboxImg" class="img-fluid rounded-3 shadow-sm" style="max-height: 65vh; object-fit: contain;" alt="Fasilitas">
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
                currentLightboxImg: '',
                currentTitle: ''
            }
        },
        methods: {
            openLightbox(imgUrl, title) {
                this.currentLightboxImg = imgUrl;
                this.currentTitle = title;
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
    }).mount('#app-informasi');
</script>

<style>
    /* Transisi Vue Lightbox */
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
    
    .cursor-pointer { cursor: pointer; }
</style>

<?php include '../templates/footer.php'; ?>