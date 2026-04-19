<?php
/**
 * views/admin/kelola_informasi.php
 * Halaman kelola informasi wisata (harga tiket, jam operasional)
 * Semua logika ditangani oleh AdminController
 */

session_start();
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/AdminController.php';

$controller = new AdminController($koneksi);
$data_page  = $controller->kelolaInformasi();

$data = $data_page['data'];

include '../templates/header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div id="app-info" class="container-fluid px-0">
    <div class="row g-0">
        <?php include '../templates/sidebar_admin.php'; ?>

        <div class="col-md-10 p-5 bg-light min-vh-100">
            <h3 class="fw-bold mb-5 animate__animated animate__fadeInDown">Kelola Informasi Wisata</h3>

            <div class="card card-custom border-0 shadow-sm p-5 bg-white animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <form action="" method="POST">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold small">Harga Tiket Masuk (Rp)</label>
                            <input type="number" name="harga_tiket" class="form-control rounded-3 transition-input"
                                   value="<?php echo htmlspecialchars($data['harga_tiket']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Jam Buka</label>
                            <input type="time" name="jam_buka" class="form-control rounded-3 transition-input"
                                   value="<?php echo htmlspecialchars($data['jam_buka']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Jam Tutup</label>
                            <input type="time" name="jam_tutup" class="form-control rounded-3 transition-input"
                                   value="<?php echo htmlspecialchars($data['jam_tutup']); ?>" required>
                        </div>
                        <div class="col-md-12 mt-5">
                            <button type="submit" name="update" class="btn btn-primary-custom px-5 rounded-pill shadow-sm hover-scale">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-input { transition: all 0.3s ease; }
    .transition-input:focus { border-color: var(--primary) !important; box-shadow: 0 0 0 0.25rem rgba(121, 174, 111, 0.25) !important; transform: translateY(-2px); }
    .hover-scale { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-scale:hover { transform: scale(1.02); box-shadow: 0 8px 15px rgba(121, 174, 111, 0.3) !important; }
</style>

<script>
    const { createApp } = Vue;
    createApp({
        mounted() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('msg') === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Diperbarui!',
                    text: 'Informasi wisata Puncak Steling telah diupdate.',
                    showConfirmButton: false,
                    timer: 2000,
                    showClass: { popup: 'animate__animated animate__fadeInDown' },
                    hideClass: { popup: 'animate__animated animate__fadeOutUp' }
                });
                window.history.replaceState(null, null, window.location.pathname);
            }
        }
    }).mount('#app-info');
</script>

<?php include '../templates/footer.php'; ?>