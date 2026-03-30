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
        echo "<script>alert('Foto disetujui!'); window.location='kelola_galeri.php';</script>";
    } elseif ($_GET['aksi'] == 'hapus') {
        // Ambil nama file untuk dihapus dari folder assets
        $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT file_foto FROM tb_galeri WHERE id_galeri = '$id'"));
        $path = "../../assets/img/uploads/" . $data['file_foto'];
        if (file_exists($path)) { @unlink($path); }
        
        mysqli_query($koneksi, "DELETE FROM tb_galeri WHERE id_galeri = '$id'");
        echo "<script>alert('Foto berhasil dihapus!'); window.location='kelola_galeri.php';</script>";
    }
}

include '../templates/header.php';
?>

<div class="container-fluid px-0">
    <div class="row g-0">
        <?php include '../templates/sidebar_admin.php'; ?>

        <div class="col-md-10 p-5 bg-light min-vh-100">
            <div class="mb-5">
                <h3 class="fw-bold mb-1">Manajemen Galeri</h3>
                <p class="text-muted">Kelola foto masuk dan moderasi konten yang sudah terbit.</p>
            </div>

            <div class="mb-5">
                <h5 class="fw-bold mb-4 d-flex align-items-center">
                    <span class="badge bg-warning me-2 text-dark px-3 rounded-pill">Pending</span> 
                    Menunggu Persetujuan
                </h5>
                <div class="row g-4">
                    <?php
                    $q_pending = mysqli_query($koneksi, "SELECT tb_galeri.*, tb_pengunjung.nama_lengkap FROM tb_galeri JOIN tb_pengunjung ON tb_galeri.id_pengunjung = tb_pengunjung.id_pengunjung WHERE status = 'pending' ORDER BY id_galeri DESC");
                    if(mysqli_num_rows($q_pending) > 0) :
                        while($p = mysqli_fetch_assoc($q_pending)) :
                            $img_p = (strpos($p['file_foto'], 'http') !== false) ? $p['file_foto'] : "../../assets/img/uploads/" . $p['file_foto'];
                    ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="card card-custom border-0 shadow-sm overflow-hidden h-100 bg-white">
                            <img src="<?php echo $img_p; ?>" class="card-img-top object-fit-cover" style="height: 180px;">
                            <div class="card-body p-3">
                                <p class="small text-muted mb-1">Oleh: <b><?php echo $p['nama_lengkap']; ?></b></p>
                                <p class="small mb-3 text-dark">"<?php echo $p['caption']; ?>"</p>
                                <div class="d-flex gap-2">
                                    <a href="?aksi=setujui&id=<?php echo $p['id_galeri']; ?>" class="btn btn-success btn-sm w-100 rounded-pill">Setujui</a>
                                    <a href="?aksi=hapus&id=<?php echo $p['id_galeri']; ?>" class="btn btn-outline-danger btn-sm w-100 rounded-pill" onclick="return confirm('Tolak dan hapus foto ini?')">Tolak</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; else: ?>
                        <div class="col-12"><p class="text-muted italic small">Tidak ada antrean foto baru.</p></div>
                    <?php endif; ?>
                </div>
            </div>

            <hr class="my-5 border-secondary opacity-25">

            <div>
                <h5 class="fw-bold mb-4 d-flex align-items-center">
                    <span class="badge bg-primary-custom me-2 px-3 rounded-pill text-white">Live</span> 
                    Foto Terpublikasi
                </h5>
                <div class="row g-4">
                    <?php
                    $q_approved = mysqli_query($koneksi, "SELECT tb_galeri.*, tb_pengunjung.nama_lengkap FROM tb_galeri JOIN tb_pengunjung ON tb_galeri.id_pengunjung = tb_pengunjung.id_pengunjung WHERE status = 'approved' ORDER BY id_galeri DESC");
                    if(mysqli_num_rows($q_approved) > 0) :
                        while($a = mysqli_fetch_assoc($q_approved)) :
                            $img_a = (strpos($a['file_foto'], 'http') !== false) ? $a['file_foto'] : "../../assets/img/uploads/" . $a['file_foto'];
                    ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="card card-custom border-0 shadow-sm overflow-hidden h-100 bg-white opacity-75 hover-opacity-100">
                            <img src="<?php echo $img_a; ?>" class="card-img-top object-fit-cover" style="height: 150px;">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-light text-dark small" style="font-size: 0.65rem;"><?php echo $a['kategori']; ?></span>
                                    <a href="?aksi=hapus&id=<?php echo $a['id_galeri']; ?>" class="text-danger" onclick="return confirm('Hapus foto yang sudah live ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                                <p class="small text-muted mb-0" style="font-size: 0.75rem;">Oleh: <?php echo $a['nama_lengkap']; ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; else: ?>
                        <div class="col-12"><p class="text-muted italic small">Belum ada foto yang disetujui.</p></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>