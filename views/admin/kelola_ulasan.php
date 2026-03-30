<?php
session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query_hapus = mysqli_query($koneksi, "DELETE FROM tb_ulasan WHERE id_ulasan = '$id'");
    if ($query_hapus) {
        echo "<script>alert('Ulasan berhasil dihapus!'); window.location='kelola_ulasan.php';</script>";
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

            <div class="card card-custom border-0 shadow-sm overflow-hidden bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 border-0 small text-uppercase fw-bold text-muted">Pengunjung</th>
                                <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Rating</th>
                                <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Komentar</th>
                                <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Tanggal</th>
                                <th class="py-3 border-0 small text-uppercase fw-bold text-muted text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($koneksi, "SELECT tb_ulasan.*, tb_pengunjung.nama_lengkap 
                                                        FROM tb_ulasan 
                                                        JOIN tb_pengunjung ON tb_ulasan.id_pengunjung = tb_pengunjung.id_pengunjung 
                                                        ORDER BY tanggal_ulasan DESC");
                            
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
                                    <div class="text-warning small">
                                        <?php 
                                        for($i=1; $i<=5; $i++) {
                                            echo ($i <= $u['rating']) ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star text-muted"></i>';
                                        }
                                        ?>
                                        <span class="ms-1 text-dark fw-bold"><?php echo $u['rating']; ?></span>
                                    </div>
                                </td>
                                <td class="text-muted small" style="max-width: 300px;">
                                    <?php echo (strlen($u['komentar']) > 100) ? substr($u['komentar'], 0, 100) . '...' : $u['komentar']; ?>
                                </td>
                                <td class="text-muted small">
                                    <?php echo date('d M Y', strtotime($u['tanggal_ulasan'])); ?>
                                </td>
                                <td class="text-center">
                                    <a href="?hapus=<?php echo $u['id_ulasan']; ?>" 
                                    class="btn btn-outline-danger btn-sm rounded-pill px-3" 
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?')">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php 
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

<?php include '../templates/footer.php'; ?>