<?php
session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$pesan_swal = "";

if (isset($_POST['kirim_balasan'])) {
    $id_ulasan = $_POST['id_ulasan'];
    $balasan = mysqli_real_escape_string($koneksi, $_POST['balasan_admin']);
    $query_update = mysqli_query($koneksi, "UPDATE tb_ulasan SET balasan_admin = '$balasan' WHERE id_ulasan = '$id_ulasan'");
    if ($query_update) {
        $pesan_swal = "
            Swal.fire({
                title: 'Terkirim!',
                text: 'Balasan ulasan berhasil disimpan.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location='kelola_ulasan.php';
            });
        ";
    }
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query_hapus = mysqli_query($koneksi, "DELETE FROM tb_ulasan WHERE id_ulasan = '$id'");
    if ($query_hapus) {
        $pesan_swal = "
            Swal.fire({
                title: 'Dihapus!',
                text: 'Ulasan telah berhasil dihapus.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location='kelola_ulasan.php';
            });
        ";
    }
}

include '../templates/header.php';
?>

<div class="container-fluid px-0">
    <div class="row g-0">
        <?php include '../templates/sidebar_admin.php'; ?>

        <div class="col-md-10 p-5 bg-light min-vh-100">
            <div class="mb-5">
                <h3 class="fw-bold mb-1">Manajemen Ulasan</h3>
                <p class="text-muted">Pantau dan kelola feedback dari pengunjung Puncak Steling.</p>
            </div>

            <div class="card card-custom border-0 shadow-sm overflow-hidden bg-white" style="border-radius: 15px;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 border-0 small text-uppercase fw-bold text-muted">Pengunjung</th>
                                <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Rating</th>
                                <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Komentar</th>
                                <th class="py-3 border-0 small text-uppercase fw-bold text-muted text-center">Status</th>
                                <th class="py-3 border-0 small text-uppercase fw-bold text-muted text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($koneksi, "SELECT tb_ulasan.*, tb_pengunjung.nama_lengkap 
                                                        FROM tb_ulasan 
                                                        JOIN tb_pengunjung ON tb_ulasan.id_pengunjung = tb_pengunjung.id_pengunjung 
                                                        ORDER BY tanggal_ulasan DESC");
                            
                            $modals = ""; 
                            if (mysqli_num_rows($query) > 0) :
                                while ($u = mysqli_fetch_assoc($query)) :
                            ?>
                            <tr>
                                <td class="px-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="bi bi-person text-secondary"></i>
                                        </div>
                                        <span class="fw-bold small"><?php echo $u['nama_lengkap']; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-warning small text-nowrap">
                                        <?php for($i=1; $i<=5; $i++) echo ($i <= $u['rating']) ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star text-muted"></i>'; ?>
                                    </div>
                                </td>
                                <td class="text-muted small" style="max-width: 250px;">
                                    <?php echo (strlen($u['komentar']) > 60) ? substr($u['komentar'], 0, 60) . '...' : $u['komentar']; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($u['balasan_admin']): ?>
                                        <span class="badge bg-success-subtle text-success px-3 border-0">Dibalas</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted px-3 border-0">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold small" data-bs-toggle="modal" data-bs-target="#modalBalas<?php echo $u['id_ulasan']; ?>">
                                            <i class="bi bi-reply-fill"></i> Balas
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="confirmDelete(<?php echo $u['id_ulasan']; ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <?php 
                            $modals .= '
                            <div class="modal fade" id="modalBalas'.$u['id_ulasan'].'" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                                        <form action="" method="POST">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="fw-bold mb-0">Tanggapi Ulasan</h5>
                                                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body py-4">
                                                <input type="hidden" name="id_ulasan" value="'.$u['id_ulasan'].'">
                                                <div class="mb-4">
                                                    <label class="small fw-bold text-muted text-uppercase mb-2 d-block">Ulasan Pengunjung</label>
                                                    <div class="p-3 rounded-3 bg-light border">
                                                        <p class="mb-0 text-dark small" style="line-height: 1.6; font-style: italic;">"'.$u['komentar'].'"</p>
                                                    </div>
                                                </div>
                                                <div class="mb-0">
                                                    <label class="small fw-bold text-muted text-uppercase mb-2 d-block">Balasan Admin</label>
                                                    <textarea name="balasan_admin" class="form-control border-0 bg-light p-3 shadow-none" rows="4" placeholder="Tulis jawaban resmi..." style="border-radius: 12px; resize: none;" required>'.$u['balasan_admin'].'</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pb-4">
                                                <button type="submit" name="kirim_balasan" class="btn btn-primary-custom rounded-pill px-4 fw-bold w-100">Simpan Balasan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>';
                                endwhile; 
                            else : 
                            ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted small">Belum ada ulasan masuk.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $modals; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Ulasan?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'kelola_ulasan.php?hapus=' + id;
        }
    })
}

<?php if($pesan_swal != "") echo $pesan_swal; ?>
</script>

<style>
    .btn-primary-custom { background-color: #10b981; color: white; border: none; }
    .btn-primary-custom:hover { background-color: #059669; color: white; }
    .bg-success-subtle { background-color: #f0faf5 !important; }
    .text-primary-custom { color: #10b981 !important; }
</style>

<?php include '../templates/footer.php'; ?>