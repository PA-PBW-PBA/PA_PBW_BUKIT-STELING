<?php
/**
 * views/public/unggah_foto.php
 * Halaman unggah foto pengunjung — hanya tampilan HTML
 * Semua logika ditangani oleh GaleriController
 */

session_start();
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/GaleriController.php';

$controller = new GaleriController($koneksi);
$data       = $controller->unggah();
$pesan_swal = $data['pesan_swal'];

include '../templates/header.php';
include '../templates/navbar_public.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div id="app-upload" class="min-vh-100 bg-white overflow-hidden">
    <section class="py-5 border-b border-light bg-light-green animate__animated animate__fadeIn">
        <div class="container py-3">
            <nav class="breadcrumb-custom mb-4">
                <a href="beranda.php" class="breadcrumb-link">Home</a>
                <i class="bi bi-chevron-right breadcrumb-separator"></i>
                <a href="galeri.php" class="breadcrumb-link">Galeri</a>
                <i class="bi bi-chevron-right breadcrumb-separator"></i>
                <span class="breadcrumb-current">Unggah</span>
            </nav>
            <h1 class="display-5 fw-bold text-dark mb-0 animate__animated animate__fadeInDown">Unggah Momen Steling</h1>
            <p class="text-muted mt-2 mb-0 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                Bagikan jepretan terbaikmu kepada pengunjung lainnya
            </p>
        </div>
    </section>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                <div class="card card-custom border-0 shadow p-4 p-md-5 bg-white">

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'pengunjung') : ?>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark small mb-2">Pilih Foto Terbaikmu</label>
                                <input type="file" name="foto" @change="previewImage" id="inputFoto"
                                       class="form-control rounded-3 border-0 bg-light py-2"
                                       accept="image/png, image/jpeg" required>
                                <div class="form-text text-muted x-small mt-2">
                                    <i class="bi bi-info-circle me-1"></i> Mendukung format <b>JPG, JPEG, PNG</b>.
                                </div>
                            </div>

                            <transition name="fade">
                                <div v-if="imagePreview" class="mb-4 text-center">
                                    <div class="position-relative d-inline-block animate__animated animate__zoomIn animate__faster">
                                        <img :src="imagePreview" class="img-thumbnail rounded-4 shadow-sm border-0"
                                             style="max-height: 300px; object-fit: contain;">
                                        <button type="button" @click="removeImage"
                                                class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle shadow">
                                            <i class="bi bi-x fs-5"></i>
                                        </button>
                                    </div>
                                </div>
                            </transition>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark small mb-2">Waktu Pengambilan Foto</label>
                                <select name="kategori" class="form-select rounded-3 border-0 bg-light py-2" required>
                                    <option value="" disabled selected>Pilih Kategori Waktu...</option>
                                    <option value="Pagi Hari">Pagi Hari (Sunrise & Embun)</option>
                                    <option value="Sore & Sunset">Sore & Sunset (Senja)</option>
                                    <option value="Malam Hari">Malam Hari (City Light)</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark small d-flex justify-content-between mb-2">
                                    <span>Keterangan Foto</span>
                                    <span class="x-small" :class="caption.length >= 50 ? 'text-danger fw-bold' : 'text-muted'">
                                        {{ caption.length }}/50
                                    </span>
                                </label>
                                <textarea name="caption" v-model="caption"
                                          class="form-control rounded-3 border-0 bg-light py-2"
                                          rows="3" placeholder="Tuliskan cerita singkat tentang foto ini..."
                                          maxlength="50" required></textarea>
                            </div>

                            <button type="submit" name="upload"
                                    class="btn btn-primary-custom w-100 rounded-pill py-3 fw-bold shadow hover-scale"
                                    :disabled="caption.length > 50">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i> Kirim untuk Moderasi
                            </button>
                        </form>

                    <?php else : ?>
                        <div class="text-center py-4">
                            <div class="mb-3 text-danger opacity-50"><i class="bi bi-shield-lock" style="font-size: 4rem;"></i></div>
                            <h5 class="fw-bold text-dark">Akses Terbatas</h5>
                            <p class="text-muted small mb-4">Hanya pengunjung yang dapat mengunggah momen ke galeri publik.</p>
                            <a href="../admin/kelola_galeri.php" class="btn btn-primary-custom rounded-pill px-5 py-2 fw-bold shadow">
                                Kelola Galeri
                            </a>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
    .fade-enter-from, .fade-leave-to { opacity: 0; }
    .hover-scale { transition: transform 0.2s ease; }
    .hover-scale:hover { transform: scale(1.02); }
</style>

<script>
    const { createApp } = Vue;
    createApp({
        data() {
            return { caption: '', imagePreview: null }
        },
        methods: {
            previewImage(e) {
                const file         = e.target.files[0];
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (file) {
                    if (!allowedTypes.includes(file.type)) {
                        Swal.fire({ title: 'Format Salah!', text: 'Gunakan format JPG, JPEG, atau PNG.', icon: 'warning', confirmButtonColor: '#79AE6F' });
                        this.removeImage();
                        return;
                    }
                    this.imagePreview = URL.createObjectURL(file);
                }
            },
            removeImage() {
                this.imagePreview = null;
                const input = document.getElementById('inputFoto');
                if (input) input.value = '';
            }
        }
    }).mount('#app-upload');

    <?php if (!empty($pesan_swal)) : ?>
    document.addEventListener('DOMContentLoaded', function() {
        <?php echo $pesan_swal; ?>
    });
    <?php endif; ?>
</script>

<?php include '../templates/footer.php'; ?>