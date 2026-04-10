<?php
session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php"); exit;
}

if (isset($_GET['aksi'])) {
    $id = $_GET['id'];
    if ($_GET['aksi'] == 'setujui') {
        mysqli_query($koneksi, "UPDATE tb_galeri SET status = 'approved' WHERE id_galeri = '$id'");
        echo "<script>
            Swal.fire({
                title: 'Disetujui!',
                text: 'Foto kini tampil di galeri publik.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location='kelola_galeri.php';
            });
        </script>";
    } elseif ($_GET['aksi'] == 'hapus') {
        
        $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT file_foto FROM tb_galeri WHERE id_galeri = '$id'"));
        $path = "../../assets/img/uploads/" . $data['file_foto'];
        if (file_exists($path)) { @unlink($path); }
        
        mysqli_query($koneksi, "DELETE FROM tb_galeri WHERE id_galeri = '$id'");
        echo "<script>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Foto telah dihapus.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location='kelola_galeri.php';
            });
        </script>";
    }
}

include '../templates/header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

<div id="app-galeri" class="container-fluid px-0">
    <div class="row g-0">
        <?php include '../templates/sidebar_admin.php'; ?>

        <div class="col-md-10 p-5 bg-light min-vh-100">
            <div class="mb-5 animate__animated animate__fadeInDown">
                <h3 class="fw-bold mb-1">Manajemen Galeri</h3>
                <p class="text-muted">Kelola foto masuk dan moderasi konten yang sudah terbit.</p>
            </div>

            <div class="mb-5">
                <h5 class="fw-bold mb-4 d-flex align-items-center animate__animated animate__fadeIn">
                    <span class="badge bg-warning me-2 text-dark px-3 rounded-pill">Pending</span> 
                    Menunggu Persetujuan
                </h5>
                <div class="row g-4">
                    <?php
                    $q_pending = mysqli_query($koneksi, "SELECT tb_galeri.*, tb_pengunjung.nama_lengkap FROM tb_galeri JOIN tb_pengunjung ON tb_galeri.id_pengunjung = tb_pengunjung.id_pengunjung WHERE status = 'pending' ORDER BY id_galeri DESC");
                    if(mysqli_num_rows($q_pending) > 0) :
                        $delay_p = 0;
                        while($p = mysqli_fetch_assoc($q_pending)) :
                            $img_p = (strpos($p['file_foto'], 'http') !== false) ? $p['file_foto'] : "../../assets/img/uploads/" . $p['file_foto'];
                    ?>
                    <div class="col-md-4 col-lg-3 animate__animated animate__fadeInUp" style="animation-delay: <?php echo $delay_p; ?>s;">
                        <div class="card card-custom border-0 shadow-sm overflow-hidden h-100 bg-white">
                            <div class="position-relative">
                                <img src="<?php echo $img_p; ?>" class="card-img-top object-fit-cover h-180px img-hover-zoom" @click="openPreview('<?php echo $img_p; ?>', '<?php echo htmlspecialchars($p['nama_lengkap'], ENT_QUOTES); ?>')">
                                <span class="position-absolute top-0 end-0 m-2 badge bg-primary-custom text-white shadow-sm"><?php echo $p['kategori']; ?></span>
                            </div>
                            <div class="card-body p-3">
                                <p class="small text-muted mb-2">Oleh: <span class="fw-bold text-dark"><?php echo $p['nama_lengkap']; ?></span></p>
                                <div class="bg-light p-2 rounded-3 mb-3">
                                    <p class="small mb-0 text-dark h-1-4-line">"<?php echo $p['caption']; ?>"</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="?aksi=setujui&id=<?php echo $p['id_galeri']; ?>" class="btn btn-success btn-sm w-100 rounded-pill fw-bold hover-scale">Setujui</a>
                                    <a href="?aksi=hapus&id=<?php echo $p['id_galeri']; ?>" class="btn btn-outline-danger btn-sm w-100 rounded-pill fw-bold hover-scale" onclick="konfirmasiHapus(event, this.href, 'Tolak dan hapus foto dari <?php echo $p['nama_lengkap']; ?>?')">Tolak</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                        $delay_p += 0.1;
                        endwhile; 
                    else: 
                    ?>
                        <div class="col-12 animate__animated animate__fadeIn"><p class="text-muted italic small">Tidak ada antrean foto baru.</p></div>
                    <?php endif; ?>
                </div>
            </div>

            <hr class="my-5 border-secondary opacity-25">

            <div>
                <h5 class="fw-bold mb-4 d-flex align-items-center animate__animated animate__fadeIn">
                    <span class="badge bg-primary-custom me-2 px-3 rounded-pill text-white">Live</span> 
                    Foto Terpublikasi
                </h5>
                <div class="row g-4">
                    <?php
                    $q_approved = mysqli_query($koneksi, "SELECT tb_galeri.*, tb_pengunjung.nama_lengkap FROM tb_galeri JOIN tb_pengunjung ON tb_galeri.id_pengunjung = tb_pengunjung.id_pengunjung WHERE status = 'approved' ORDER BY id_galeri DESC");
                    if(mysqli_num_rows($q_approved) > 0) :
                        $delay_a = 0;
                        while($a = mysqli_fetch_assoc($q_approved)) :
                            $img_a = (strpos($a['file_foto'], 'http') !== false) ? $a['file_foto'] : "../../assets/img/uploads/" . $a['file_foto'];
                    ?>
                    <div class="col-md-4 col-lg-3 animate__animated animate__zoomIn" style="animation-delay: <?php echo $delay_a; ?>s;">
                        <div class="card card-custom border-0 shadow-sm overflow-hidden h-100 bg-white opacity-75 hover-opacity-100">
                            <img src="<?php echo $img_a; ?>" class="card-img-top object-fit-cover h-150px img-hover-zoom" @click="openPreview('<?php echo $img_a; ?>', '<?php echo htmlspecialchars($a['nama_lengkap'], ENT_QUOTES); ?>')">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-light text-dark small fs-65"><?php echo $a['kategori']; ?></span>
                                    <a href="?aksi=hapus&id=<?php echo $a['id_galeri']; ?>" class="text-danger hover-scale d-inline-block" onclick="konfirmasiHapus(event, this.href, 'Hapus foto yang sudah live ini?')">
                                        <i class="bi bi-trash fs-5"></i>
                                    </a>
                                </div>
                                <p class="small text-muted mb-0 fs-sm-75">Oleh: <?php echo $a['nama_lengkap']; ?></p>
                            </div>
                        </div>
                    </div>
                    <?php 
                        $delay_a += 0.05;
                        endwhile; 
                    else: 
                    ?>
                        <div class="col-12 animate__animated animate__fadeIn"><p class="text-muted italic small">Belum ada foto yang disetujui.</p></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <transition name="fade">
        <div v-if="previewOpen" class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center" style="background: rgba(0,0,0,0.85); z-index: 1060;" @click="closePreview">
            <div class="position-relative animate__animated animate__zoomIn animate__faster text-center" style="max-width: 90%; max-height: 90%;">
                <img :src="currentImage" class="img-fluid rounded-3 shadow-lg" style="max-height: 80vh; border: 3px solid white;">
                <h5 class="text-white mt-3 fw-bold mb-0">Fotografer: {{ currentAuthor }}</h5>
                <button class="btn btn-light position-absolute top-0 end-0 m-3 rounded-circle shadow-lg" @click.stop="closePreview" style="transform: translate(50%, -50%); width: 40px; height: 40px; line-height: 1;">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    </transition>
</div>

<script>
    const { createApp } = Vue;

    createApp({
        data() {
            return {
                previewOpen: false,
                currentImage: '',
                currentAuthor: ''
            }
        },
        methods: {
            openPreview(imgUrl, authorName) {
                this.currentImage = imgUrl;
                this.currentAuthor = authorName;
                this.previewOpen = true;
                // Mencegah scroll pada body saat lightbox terbuka
                document.body.style.overflow = 'hidden';
            },
            closePreview() {
                this.previewOpen = false;
                // Mengembalikan scroll
                document.body.style.overflow = '';
            }
        }
    }).mount('#app-galeri');
</script>

<style>
    .img-hover-zoom {
        cursor: pointer;
        transition: transform 0.3s ease, filter 0.3s ease;
    }
    .img-hover-zoom:hover {
        transform: scale(1.05);
        filter: brightness(0.85);
    }
    .hover-scale {
        transition: transform 0.2s ease;
    }
    .hover-scale:hover {
        transform: scale(1.05);
    }
    
    /* Vue Transition System */
    .fade-enter-active, .fade-leave-active {
        transition: opacity 0.3s;
    }
    .fade-enter-from, .fade-leave-to {
        opacity: 0;
    }
</style>

<?php include '../templates/footer.php'; ?>