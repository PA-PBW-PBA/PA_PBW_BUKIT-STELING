<?php
/**
 * views/admin/kelola_galeri.php
 * Halaman moderasi galeri foto — hanya tampilan HTML
 * Aksi setujui/hapus diteruskan ke file aksi masing-masing
 */

session_start();
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/GaleriController.php';

$controller    = new GaleriController($koneksi);
$data          = $controller->index();
$foto_pending  = $data['foto_pending'];
$foto_approved = $data['foto_approved'];

// Cek pesan dari aksi
$pesan_swal = "";
if (isset($_GET['msg'])) {
    $pesan = [
        'setujui_berhasil' => ["Disetujui!", "Foto kini tampil di galeri publik.", "success"],
        'hapus_berhasil'   => ["Berhasil!", "Foto telah dihapus.", "success"],
    ];
    if (isset($pesan[$_GET['msg']])) {
        [$title, $text, $icon] = $pesan[$_GET['msg']];
        $pesan_swal = "Swal.fire({ title: '$title', text: '$text', icon: '$icon', timer: 2000, showConfirmButton: false });";
    }
}

include '../templates/header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div id="app-galeri" class="container-fluid px-0">
    <div class="row g-0">
        <?php include '../templates/sidebar_admin.php'; ?>

        <div class="col-md-10 p-5 bg-light min-vh-100">
            <div class="mb-5 animate__animated animate__fadeInDown">
                <h3 class="fw-bold mb-1">Manajemen Galeri</h3>
                <p class="text-muted">Kelola foto masuk dan moderasi konten yang sudah terbit.</p>
            </div>

            <!-- Foto Pending -->
            <div class="mb-5">
                <h5 class="fw-bold mb-4 d-flex align-items-center animate__animated animate__fadeIn">
                    <span class="badge bg-warning me-2 text-dark px-3 rounded-pill">Pending</span>
                    Menunggu Persetujuan
                </h5>
                <div class="row g-4">
                    <?php if (empty($foto_pending)) : ?>
                        <div class="col-12">
                            <p class="text-muted small">Tidak ada antrean foto baru.</p>
                        </div>
                    <?php else : ?>
                        <?php foreach ($foto_pending as $p) :
                            $img_p = "../../assets/img/uploads/" . $p['file_foto'];
                        ?>
                        <div class="col-md-4 col-lg-3 animate__animated animate__fadeInUp">
                            <div class="card card-custom border-0 shadow-sm overflow-hidden h-100 bg-white">
                                <div class="position-relative">
                                    <img src="<?php echo htmlspecialchars($img_p); ?>"
                                         class="card-img-top object-fit-cover"
                                         style="height: 180px;"
                                         @click="openPreview('<?php echo htmlspecialchars($img_p); ?>', '<?php echo htmlspecialchars($p['nama_lengkap'], ENT_QUOTES); ?>')">
                                    <span class="position-absolute top-0 end-0 m-2 badge bg-primary-custom text-white shadow-sm">
                                        <?php echo htmlspecialchars($p['kategori']); ?>
                                    </span>
                                </div>
                                <div class="card-body p-3">
                                    <p class="small text-muted mb-2">Oleh: <span class="fw-bold text-dark"><?php echo htmlspecialchars($p['nama_lengkap']); ?></span></p>
                                    <div class="bg-light p-2 rounded-3 mb-3">
                                        <p class="small mb-0 text-dark">"<?php echo htmlspecialchars($p['caption']); ?>"</p>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="aksi_setujui_galeri.php?id=<?php echo $p['id_galeri']; ?>"
                                           class="btn btn-success btn-sm w-100 rounded-pill fw-bold">Setujui</a>
                                        <a href="aksi_hapus_galeri.php?id=<?php echo $p['id_galeri']; ?>"
                                           class="btn btn-outline-danger btn-sm w-100 rounded-pill fw-bold"
                                           onclick="return konfirmasiHapus(event, this.href, 'Tolak dan hapus foto dari <?php echo htmlspecialchars($p['nama_lengkap'], ENT_QUOTES); ?>?')">
                                           Tolak
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <hr class="my-5 border-secondary opacity-25">

            <!-- Foto Approved -->
            <div>
                <h5 class="fw-bold mb-4 d-flex align-items-center animate__animated animate__fadeIn">
                    <span class="badge bg-primary-custom me-2 px-3 rounded-pill text-white">Live</span>
                    Foto Terpublikasi
                </h5>
                <div class="row g-4">
                    <?php if (empty($foto_approved)) : ?>
                        <div class="col-12">
                            <p class="text-muted small">Belum ada foto yang disetujui.</p>
                        </div>
                    <?php else : ?>
                        <?php foreach ($foto_approved as $a) :
                            $img_a = "../../assets/img/uploads/" . $a['file_foto'];
                        ?>
                        <div class="col-md-4 col-lg-3 animate__animated animate__zoomIn">
                            <div class="card card-custom border-0 shadow-sm overflow-hidden h-100 bg-white">
                                <img src="<?php echo htmlspecialchars($img_a); ?>"
                                     class="card-img-top object-fit-cover"
                                     style="height: 150px;"
                                     @click="openPreview('<?php echo htmlspecialchars($img_a); ?>', '<?php echo htmlspecialchars($a['nama_lengkap'], ENT_QUOTES); ?>')">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-light text-dark small"><?php echo htmlspecialchars($a['kategori']); ?></span>
                                        <a href="aksi_hapus_galeri.php?id=<?php echo $a['id_galeri']; ?>"
                                           class="text-danger"
                                           onclick="return konfirmasiHapus(event, this.href, 'Hapus foto yang sudah live ini?')">
                                            <i class="bi bi-trash fs-5"></i>
                                        </a>
                                    </div>
                                    <p class="small text-muted mb-0">Oleh: <?php echo htmlspecialchars($a['nama_lengkap']); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Lightbox Preview Vue -->
    <transition name="fade">
        <div v-if="previewOpen"
             class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center"
             style="background: rgba(0,0,0,0.85); z-index: 1060;"
             @click="closePreview">
            <div class="position-relative animate__animated animate__zoomIn animate__faster text-center"
                 style="max-width: 90%; max-height: 90%;">
                <img :src="currentImage" class="img-fluid rounded-3 shadow-lg" style="max-height: 80vh; border: 3px solid white;">
                <h5 class="text-white mt-3 fw-bold mb-0">Fotografer: {{ currentAuthor }}</h5>
                <button class="btn btn-light position-absolute top-0 end-0 m-3 rounded-circle shadow-lg"
                        @click.stop="closePreview"
                        style="transform: translate(50%, -50%); width: 40px; height: 40px;">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>
    </transition>
</div>

<style>
    .fade-enter-active, .fade-leave-active { transition: opacity 0.3s; }
    .fade-enter-from, .fade-leave-to { opacity: 0; }
</style>

<script>
    function konfirmasiHapus(event, href, pesan) {
        event.preventDefault();
        Swal.fire({
            title: 'Yakin?', text: pesan, icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
        }).then((result) => { if (result.isConfirmed) window.location.href = href; });
        return false;
    }

    const { createApp } = Vue;
    createApp({
        data() {
            return { previewOpen: false, currentImage: '', currentAuthor: '' }
        },
        methods: {
            openPreview(imgUrl, authorName) {
                this.currentImage  = imgUrl;
                this.currentAuthor = authorName;
                this.previewOpen   = true;
                document.body.style.overflow = 'hidden';
            },
            closePreview() {
                this.previewOpen = false;
                document.body.style.overflow = '';
            }
        }
    }).mount('#app-galeri');

    <?php if (!empty($pesan_swal)) echo $pesan_swal; ?>
</script>

<?php include '../templates/footer.php'; ?>