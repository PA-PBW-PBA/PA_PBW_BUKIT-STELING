<?php
/**
 * views/admin/kelola_fasilitas.php
 * Halaman kelola fasilitas — hanya tampilan HTML & tabel data
 * Aksi CRUD diteruskan ke file aksi masing-masing
 */

session_start();
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/FasilitasController.php';

$controller = new FasilitasController($koneksi);
$data       = $controller->index();
$fasilitas  = $data['fasilitas'];

// Cek pesan dari aksi
$pesan_swal = "";
if (isset($_GET['msg'])) {
    $pesan = [
        'tambah_berhasil' => ["Berhasil!", "Fasilitas baru telah ditambahkan.", "success"],
        'edit_berhasil'   => ["Berhasil!", "Data fasilitas telah diperbarui.", "success"],
        'hapus_berhasil'  => ["Berhasil!", "Fasilitas telah dihapus.", "success"],
        'gagal'           => ["Gagal!", "Terjadi kesalahan, coba lagi.", "error"],
    ];
    if (isset($pesan[$_GET['msg']])) {
        [$title, $text, $icon] = $pesan[$_GET['msg']];
        $pesan_swal = "Swal.fire({ title: '$title', text: '$text', icon: '$icon', timer: 2000, showConfirmButton: false });";
    }
}

include '../templates/header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div id="app-fasilitas" class="container-fluid px-0">
    <div class="row g-0">
        <?php include '../templates/sidebar_admin.php'; ?>

        <div class="col-md-10 p-5 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center mb-5 animate__animated animate__fadeInDown">
                <div>
                    <h3 class="fw-bold mb-1 text-dark">Manajemen Fasilitas</h3>
                    <p class="text-muted">Kelola daftar fasilitas yang tersedia di Puncak Steling.</p>
                </div>
                <button class="btn btn-primary-custom rounded-pill px-4 shadow-sm fw-bold hover-scale"
                        data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus-lg me-2"></i> Tambah Fasilitas
                </button>
            </div>

            <!-- Tabel Fasilitas -->
            <div class="card card-custom border-0 shadow-sm overflow-hidden bg-white animate__animated animate__fadeInUp">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 border-0 small fw-bold text-muted text-uppercase">Gambar</th>
                                <th class="py-3 border-0 small fw-bold text-muted text-uppercase">Nama Fasilitas</th>
                                <th class="py-3 border-0 small fw-bold text-muted text-uppercase text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($fasilitas)) : ?>
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted small">Belum ada fasilitas.</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($fasilitas as $f) : ?>
                                <tr class="transition-row">
                                    <td class="ps-4 py-3">
                                        <img src="../../assets/img/fasilitas/<?php echo htmlspecialchars($f['file_gambar']); ?>"
                                             class="rounded-3 shadow-sm object-fit-cover" style="width: 80px; height: 60px;">
                                    </td>
                                    <td class="py-3">
                                        <h6 class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($f['nama_fasilitas']); ?></h6>
                                    </td>
                                    <td class="py-3 text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <!-- Tombol Edit — pakai Vue bukaEdit() -->
                                            <button class="btn btn-light btn-sm rounded-circle p-2 shadow-sm text-primary-custom hover-btn"
                                                    @click="bukaEdit('<?php echo $f['id_fasilitas']; ?>', '<?php echo htmlspecialchars($f['nama_fasilitas'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($f['file_gambar'], ENT_QUOTES); ?>')">
                                                <i class="bi bi-pencil-square fs-6"></i>
                                            </button>
                                            <!-- Tombol Hapus -->
                                            <button class="btn btn-light btn-sm rounded-circle p-2 shadow-sm text-danger hover-btn"
                                               @click="konfirmasiHapus($event, 'aksi_hapus_fasilitas.php?id=<?php echo $f['id_fasilitas']; ?>', 'Hapus fasilitas <?php echo htmlspecialchars($f['nama_fasilitas'], ENT_QUOTES); ?>?')">
                                                <i class="bi bi-trash fs-6"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah — dengan preview gambar Vue -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold text-dark">Tambah Fasilitas Baru</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <form action="aksi_tambah_fasilitas.php" method="POST" enctype="multipart/form-data" @submit="submitTambah">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nama Fasilitas</label>
                        <input type="text" name="nama_fasilitas" v-model="namaFasilitasBaru"
                               class="form-control shadow-none focus-ring" placeholder="Contoh: Musholla Baru" required>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-danger" v-if="namaFasilitasBaru.length > 0 && !namaBaruValid">Minimal 3 karakter.</small>
                            <small class="text-muted nama-counter ms-auto">{{ namaFasilitasBaru.length }}/50</small>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-muted">Gambar Fasilitas</label>
                        <input type="file" name="foto" class="form-control shadow-none focus-ring"
                               accept="image/png, image/jpeg, image/webp" @change="onFotoTambahChange" required>
                        <small class="text-danger d-block mt-1" v-if="errorTambah">{{ errorTambah }}</small>
                    </div>
                    <div class="img-preview-box mt-3" v-if="previewTambah">
                        <img :src="previewTambah" alt="Preview">
                        <p class="text-center small text-muted py-1 mb-0">Preview gambar</p>
                    </div>
                    <div class="img-preview-box mt-3" v-else>
                        <div class="img-preview-placeholder">
                            <i class="bi bi-image fs-5"></i> Preview gambar akan muncul di sini
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-primary-custom rounded-pill px-4 fw-bold shadow-sm"
                            :disabled="!namaBaruValid">Tambah Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit — SATU modal reaktif Vue, diisi via bukaEdit() -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold text-dark">Edit Fasilitas</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <form action="aksi_edit_fasilitas.php" method="POST" enctype="multipart/form-data" @submit="submitEdit">
                <div class="modal-body p-4">
                    <input type="hidden" name="id_fasilitas" :value="editId">
                    <input type="hidden" name="foto_lama"    :value="editFotoLama">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nama Fasilitas</label>
                        <input type="text" name="nama_fasilitas" v-model="editNama"
                               class="form-control shadow-none focus-ring" required>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-danger" v-if="editNama.length > 0 && !namaEditValid">Minimal 3 karakter.</small>
                            <small class="text-muted nama-counter ms-auto">{{ editNama.length }}/50</small>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-muted">Ganti Gambar <span class="fw-normal text-muted">(Opsional)</span></label>
                        <input type="file" name="foto" class="form-control shadow-none focus-ring"
                               accept="image/png, image/jpeg, image/webp" @change="onFotoEditChange">
                        <small class="text-danger d-block mt-1" v-if="errorEdit">{{ errorEdit }}</small>
                    </div>
                    <!-- Preview: gambar baru jika dipilih, gambar lama jika tidak -->
                    <div class="img-preview-box mt-3">
                        <img v-if="previewEdit" :src="previewEdit" alt="Preview Baru">
                        <img v-else :src="'../../assets/img/fasilitas/' + editFotoLama" alt="Gambar Saat Ini">
                        <p class="text-center small py-1 mb-0"
                           :class="previewEdit ? 'text-primary-custom fw-bold' : 'text-muted'">
                            {{ previewEdit ? 'Gambar baru (belum disimpan)' : 'Gambar saat ini' }}
                        </p>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="edit" class="btn btn-primary-custom rounded-pill px-4 fw-bold shadow-sm"
                            :disabled="!namaEditValid">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .hover-scale  { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-scale:hover { transform: scale(1.03); box-shadow: 0 8px 15px rgba(0,0,0,0.1) !important; }
    .hover-btn    { transition: all 0.2s; }
    .hover-btn:hover { background-color: var(--primary) !important; color: white !important; }
    .hover-btn.text-danger:hover { background-color: #dc3545 !important; color: white !important; }
    .transition-row { transition: background-color 0.2s ease; }
    .transition-row:hover { background-color: #f8f9fa; }
    .focus-ring:focus { border-color: var(--primary) !important; box-shadow: 0 0 0 0.25rem rgba(121, 174, 111, 0.25) !important; }
    .img-preview-box { border: 2px dashed #dee2e6; border-radius: 12px; overflow: hidden; background: #f8f9fa; }
    .img-preview-box img { width: 100%; height: 160px; object-fit: cover; display: block; }
    .img-preview-placeholder { height: 80px; display: flex; align-items: center; justify-content: center; color: #adb5bd; font-size: 13px; gap: 8px; }
    .nama-counter { font-size: 12px; }
</style>

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script>
    const { createApp, ref, computed } = Vue;

    // Data fasilitas dari PHP untuk Vue
    const dataFasilitas = <?php echo json_encode($fasilitas); ?>;

    createApp({
        setup() {
            // ── State modal tambah ──
            const namaFasilitasBaru  = ref('');
            const previewTambah      = ref('');
            const errorTambah        = ref('');

            // ── State modal edit (single modal reaktif) ──
            const editId        = ref('');
            const editNama      = ref('');
            const editFotoLama  = ref('');
            const previewEdit   = ref('');
            const errorEdit     = ref('');

            // ── Computed validasi ──
            const namaBaruValid = computed(() => namaFasilitasBaru.value.trim().length >= 3);
            const namaEditValid = computed(() => editNama.value.trim().length >= 3);

            // ── Preview gambar tambah ──
            function onFotoTambahChange(e) {
                const file = e.target.files[0];
                if (!file) { previewTambah.value = ''; return; }
                if (!['image/jpeg','image/png','image/webp'].includes(file.type)) {
                    errorTambah.value = 'Format harus JPG, PNG, atau WebP.';
                    previewTambah.value = '';
                    return;
                }
                errorTambah.value = '';
                const reader = new FileReader();
                reader.onload = e => previewTambah.value = e.target.result;
                reader.readAsDataURL(file);
            }

            // ── Preview gambar edit ──
            function onFotoEditChange(e) {
                const file = e.target.files[0];
                if (!file) { previewEdit.value = ''; return; }
                if (!['image/jpeg','image/png','image/webp'].includes(file.type)) {
                    errorEdit.value = 'Format harus JPG, PNG, atau WebP.';
                    previewEdit.value = '';
                    return;
                }
                errorEdit.value = '';
                const reader = new FileReader();
                reader.onload = e => previewEdit.value = e.target.result;
                reader.readAsDataURL(file);
            }

            // ── Buka modal edit dan isi data ──
            function bukaEdit(id, nama, fotoLama) {
                editId.value       = id;
                editNama.value     = nama;
                editFotoLama.value = fotoLama;
                previewEdit.value  = '';
                errorEdit.value    = '';
                const modal = new bootstrap.Modal(document.getElementById('modalEdit'));
                modal.show();
            }

            // ── Submit tambah — validasi Vue sebelum kirim ──
            function submitTambah(e) {
                if (!namaBaruValid.value) {
                    e.preventDefault();
                    errorTambah.value = 'Nama fasilitas minimal 3 karakter.';
                    return;
                }
                if (!previewTambah.value) {
                    e.preventDefault();
                    errorTambah.value = 'Pilih gambar terlebih dahulu.';
                    return;
                }
            }

            // ── Submit edit — validasi Vue sebelum kirim ──
            function submitEdit(e) {
                if (!namaEditValid.value) {
                    e.preventDefault();
                    errorEdit.value = 'Nama fasilitas minimal 3 karakter.';
                }
            }

            // ── Konfirmasi hapus ──
            function konfirmasiHapus(event, href, pesan) {
                event.preventDefault();
                Swal.fire({
                    title: 'Yakin?',
                    text: pesan,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then(r => { if (r.isConfirmed) window.location.href = href; });
                return false;
            }

            return {
                namaFasilitasBaru, previewTambah, errorTambah, namaBaruValid,
                editId, editNama, editFotoLama, previewEdit, errorEdit, namaEditValid,
                onFotoTambahChange, onFotoEditChange, bukaEdit,
                submitTambah, submitEdit, konfirmasiHapus,
                dataFasilitas
            };
        }
    }).mount('#app-fasilitas');

    <?php if (!empty($pesan_swal)) echo $pesan_swal; ?>
</script>

<?php include '../templates/footer.php'; ?>