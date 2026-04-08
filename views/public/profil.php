<?php 
session_start();
include '../../config/koneksi.php'; 

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'pengunjung') {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id'];
$nama_user = $_SESSION['user'];

if (isset($_GET['hapus_ulasan'])) {
    $id_ulasan_hapus = mysqli_real_escape_string($koneksi, $_GET['hapus_ulasan']);
    
    $sql_hapus = "DELETE FROM tb_ulasan WHERE id_ulasan = '$id_ulasan_hapus' AND id_pengunjung = '$id_user'";
    $eksekusi_hapus = mysqli_query($koneksi, $sql_hapus);

    if ($eksekusi_hapus) {
        echo "<script>
            Swal.fire({
                title: 'Dihapus!',
                text: 'Ulasan Anda telah berhasil dihapus.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location='profil.php';
            });
        </script>";
        exit;
    } else {
        echo "<script>
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat menghapus ulasan.',
                icon: 'error'
            });
        </script>";
    }
}

$count_foto = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_galeri FROM tb_galeri WHERE id_pengunjung = '$id_user'"));
$count_ulasan = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_ulasan FROM tb_ulasan WHERE id_pengunjung = '$id_user'"));

include '../templates/header.php'; 
include '../templates/navbar_public.php'; 
?>

<div class="min-vh-100 bg-light pb-5">
    <section class="py-5 bg-primary-custom text-white shadow">
        <div class="container py-3">
            <div class="d-flex align-items-center gap-4">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-lg" style="width: 90px; height: 90px;">
                    <i class="bi bi-person-fill text-primary-custom" style="font-size: 2.5rem;"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-0"><?php echo $nama_user; ?></h2>
                    <p class="mb-0 opacity-75 small text-uppercase fw-bold" style="letter-spacing: 1px;">Kontributor Wisata</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="card card-custom border-0 shadow-sm p-4 bg-white text-center">
                    <h6 class="fw-bold text-muted small text-uppercase mb-4">Statistik Saya</h6>
                    <div class="row">
                        <div class="col-6 border-end">
                            <h4 class="fw-bold mb-0"><?php echo $count_foto; ?></h4>
                            <p class="text-muted small mb-0">Foto</p>
                        </div>
                        <div class="col-6">
                            <h4 class="fw-bold mb-0"><?php echo $count_ulasan; ?></h4>
                            <p class="text-muted small mb-0">Ulasan</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <ul class="nav nav-pills gap-2 mb-4" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active rounded-pill px-4 fw-bold" id="pills-foto-tab" data-bs-toggle="pill" data-bs-target="#pills-foto" type="button">Foto Saya</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link rounded-pill px-4 fw-bold" id="pills-ulasan-tab" data-bs-toggle="pill" data-bs-target="#pills-ulasan" type="button">Ulasan Saya</button>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-foto" role="tabpanel">
                        <div class="row g-3">
                            <?php 
                            $q_foto = mysqli_query($koneksi, "SELECT * FROM tb_galeri WHERE id_pengunjung = '$id_user' ORDER BY id_galeri DESC");
                            if(mysqli_num_rows($q_foto) > 0):
                                while($f = mysqli_fetch_assoc($q_foto)):
                                    $img = "../../assets/img/uploads/" . $f['file_foto'];
                            ?>
                            <div class="col-md-4">
                                <div class="card card-custom border-0 shadow-sm overflow-hidden h-100 bg-white">
                                    <div class="ratio ratio-4x3">
                                        <img src="<?php echo $img; ?>" class="object-fit-cover" onerror="this.src='https://via.placeholder.com/400x300'">
                                    </div>
                                    <div class="p-3">
                                        <div class="mb-2">
                                            <?php if($f['status'] == 'pending'): ?>
                                                <span class="badge bg-warning text-dark small fw-normal"><i class="bi bi-hourglass-split me-1"></i> Menunggu Moderasi</span>
                                            <?php else: ?>
                                                <span class="badge bg-success small fw-normal"><i class="bi bi-check-circle me-1"></i> Sudah Terbit</span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="small text-muted mb-0">"<?php echo $f['caption']; ?>"</p>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; else: ?>
                                <div class="col-12 py-5 text-center bg-white rounded-4 shadow-sm">
                                    <i class="bi bi-images fs-1 text-muted opacity-25 mb-3 d-block"></i>
                                    <p class="text-muted">Belum ada foto yang diunggah.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-ulasan" role="tabpanel">
                        <div class="row g-3">
                            <?php 
                            $q_ulasan = mysqli_query($koneksi, "SELECT * FROM tb_ulasan WHERE id_pengunjung = '$id_user' ORDER BY id_ulasan DESC");
                            if(mysqli_num_rows($q_ulasan) > 0):
                                while($u = mysqli_fetch_assoc($q_ulasan)):
                            ?>
                            <div class="col-12">
                                <div class="card card-custom p-4 shadow-sm border-0 bg-white">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="text-warning small">
                                            <?php for($i=1;$i<=5;$i++) echo ($i<=$u['rating']) ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star text-muted"></i>'; ?>
                                            <span class="ms-2 text-muted" style="font-size: 0.7rem;"><?php echo date('d M Y', strtotime($u['tanggal_ulasan'])); ?></span>
                                        </div>
                                        <a href="profil.php?hapus_ulasan=<?php echo $u['id_ulasan']; ?>" 
                                        class="btn btn-outline-danger btn-sm rounded-pill px-3" 
                                        onclick="return confirm('Hapus ulasan ini secara permanen?')">
                                            <i class="bi bi-trash me-1"></i> Hapus
                                        </a>
                                    </div>
                                    <p class="mb-2 text-dark">"<?php echo $u['komentar']; ?>"</p>
                                    
                                    <?php if(!empty($u['balasan_admin'])) : ?>
                                        <div class="mt-3 p-3 rounded-4 border-start border-4 border-primary-custom" style="background-color: #F0FAF5;">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <i class="bi bi-patch-check-fill text-primary-custom"></i>
                                                <small class="fw-bold text-dark">Tanggapan Pengelola</small>
                                            </div>
                                            <p class="mb-0 small text-dark opacity-75"><?php echo $u['balasan_admin']; ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endwhile; else: ?>
                                <div class="col-12 py-5 text-center bg-white rounded-4 shadow-sm">
                                    <i class="bi bi-chat-left-dots fs-1 text-muted opacity-25 mb-3 d-block"></i>
                                    <p class="text-muted">Belum ada ulasan yang diberikan.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-primary-custom { border-color: var(--primary) !important; }
    .text-primary-custom { color: var(--primary) !important; }
    .bg-primary-custom { background-color: var(--primary) !important; }
    .nav-pills .nav-link.active { background-color: var(--primary) !important; }
    .nav-pills .nav-link { color: #6c757d; }
</style>

<?php include '../templates/footer.php'; ?>