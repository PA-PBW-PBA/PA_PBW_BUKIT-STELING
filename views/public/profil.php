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
require_once __DIR__ . '/../../controllers/AuthController.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'pengunjung') {
    header("Location: ../auth/login.php");
    exit;
}

$authController = new AuthController($koneksi);
$id_user = $_SESSION['id'];
$nama_user = $_SESSION['user'];

if (isset($_POST['ganti_password'])) {
    $hasil = $authController->gantiPassword($id_user, $_POST['pass_lama'], $_POST['pass_baru'], $_POST['pass_konf']);
    $_SESSION['alert'] = ['icon' => $hasil['status'], 'title' => ($hasil['status'] == 'success' ? 'Berhasil!' : 'Gagal!'), 'text' => $hasil['message']];
    header("Location: profil.php");
    exit;
}

if (isset($_GET['proses_hapus'])) {
    $id_ulasan_hapus = mysqli_real_escape_string($koneksi, $_GET['proses_hapus']);
    $sql_hapus = "DELETE FROM tb_ulasan WHERE id_ulasan = '$id_ulasan_hapus' AND id_pengunjung = '$id_user'";
    $eksekusi_hapus = mysqli_query($koneksi, $sql_hapus);
    if ($eksekusi_hapus) {
        $_SESSION['alert'] = ['icon' => 'success', 'title' => 'Dihapus!', 'text' => 'Ulasan berhasil dihapus.'];
    } else {
        $_SESSION['alert'] = ['icon' => 'error', 'title' => 'Gagal!', 'text' => 'Gagal menghapus ulasan.'];
    }
    header("Location: profil.php");
    exit;
}

$count_foto = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_galeri FROM tb_galeri WHERE id_pengunjung = '$id_user'"));
$count_ulasan = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_ulasan FROM tb_ulasan WHERE id_pengunjung = '$id_user'"));

include '../templates/header.php'; 
include '../templates/navbar_public.php'; 
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

<div id="app-profil" class="min-vh-100 bg-light pb-5 overflow-hidden">
    <section class="py-5 bg-primary-custom text-white shadow animate__animated animate__fadeIn">
        <div class="container py-3">
            <div class="d-flex align-items-center gap-4 animate__animated animate__fadeInLeft">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-lg avatar-lg hover-scale">
                    <i class="bi bi-person-fill text-primary-custom icon-xl"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-0"><?php echo $nama_user; ?></h2>
                    <p class="mb-0 opacity-75 small text-uppercase fw-bold ls-normal">Kontributor Wisata</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-3 animate__animated animate__fadeInUp">
                <div class="card card-custom border-0 shadow-sm p-4 bg-white text-center hover-up mb-4">
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
                <button class="btn btn-white w-100 rounded-pill shadow-sm fw-bold py-2 hover-up" data-bs-toggle="modal" data-bs-target="#modalPassword">
                    <i class="bi bi-shield-lock me-2"></i> Ganti Password
                </button>
            </div>

            <div class="col-lg-9">
                <ul class="nav nav-pills gap-2 mb-4 profile-tab-pills animate__animated animate__fadeIn" style="animation-delay: 0.2s;" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active rounded-pill px-4 fw-bold shadow-sm" id="pills-foto-tab" data-bs-toggle="pill" data-bs-target="#pills-foto" type="button">Foto Saya</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link rounded-pill px-4 fw-bold shadow-sm" id="pills-ulasan-tab" data-bs-toggle="pill" data-bs-target="#pills-ulasan" type="button">Ulasan Saya</button>
                    </li>
                </ul>

                <div class="tab-content profile-tab-content animate__animated animate__fadeIn" style="animation-delay: 0.4s;" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-foto" role="tabpanel">
                        <div class="row g-3">
                            <?php 
                            $q_foto = mysqli_query($koneksi, "SELECT * FROM tb_galeri WHERE id_pengunjung = '$id_user' ORDER BY id_galeri DESC");
                            if(mysqli_num_rows($q_foto) > 0):
                                $delay = 0;
                                while($f = mysqli_fetch_assoc($q_foto)):
                                    $img = "../../assets/img/uploads/" . $f['file_foto'];
                            ?>
                            <div class="col-md-4 animate__animated animate__zoomIn" style="animation-delay: <?php echo $delay; ?>s;">
                                <div class="card card-custom profile-card-photo border-0 shadow-sm overflow-hidden h-100 bg-white hover-up">
                                    <div class="ratio ratio-4x3 overflow-hidden">
                                        <img src="<?php echo $img; ?>" class="object-fit-cover hover-zoom" onerror="this.src='https://via.placeholder.com/400x300'">
                                    </div>
                                    <div class="p-3">
                                        <div class="mb-2">
                                            <?php if($f['status'] == 'pending'): ?>
                                                <span class="badge bg-warning text-dark small fw-normal rounded-pill">Menunggu</span>
                                            <?php else: ?>
                                                <span class="badge bg-success small fw-normal rounded-pill">Terbit</span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="small text-muted mb-0 text-truncate">"<?php echo htmlspecialchars($f['caption']); ?>"</p>
                                    </div>
                                </div>
                            </div>
                            <?php $delay += 0.1; endwhile; else: ?>
                                <div class="col-12 py-5 text-center bg-white rounded-4 shadow-sm animate__animated animate__fadeIn">
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
                                $delay = 0;
                                while($u = mysqli_fetch_assoc($q_ulasan)):
                            ?>
                            <div class="col-12 animate__animated animate__fadeInUp" style="animation-delay: <?php echo $delay; ?>s;">
                                <div class="card card-custom profile-card-review p-4 shadow-sm border-0 bg-white hover-up">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="text-warning small">
                                            <?php for($i=1;$i<=5;$i++) echo ($i<=$u['rating']) ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star text-muted"></i>'; ?>
                                            <span class="ms-2 text-muted fs-xs"><?php echo date('d M Y', strtotime($u['tanggal_ulasan'])); ?></span>
                                        </div>
                                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 hover-scale" @click="hapusUlasan(<?php echo $u['id_ulasan']; ?>)">
                                            <i class="bi bi-trash me-1"></i> Hapus
                                        </button>
                                    </div>
                                    <p class="mb-0 text-dark">"<?php echo htmlspecialchars($u['komentar']); ?>"</p>
                                </div>
                            </div>
                            <?php $delay += 0.1; endwhile; else: ?>
                                <div class="col-12 py-5 text-center bg-white rounded-4 shadow-sm">
                                    <p class="text-muted">Belum ada ulasan yang ditulis.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPassword" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 p-4">
                <h5 class="fw-bold mb-0">Ganti Password Akun</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body p-4 pt-0">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Password Lama</label>
                        <input type="password" name="pass_lama" class="form-control rounded-3 bg-light border-0 py-2" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Password Baru</label>
                        <input type="password" name="pass_baru" class="form-control rounded-3 bg-light border-0 py-2" placeholder="Minimal 8 karakter" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold text-muted">Konfirmasi Password Baru</label>
                        <input type="password" name="pass_konf" class="form-control rounded-3 bg-light border-0 py-2" required>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="ganti_password" class="btn btn-primary-custom rounded-pill px-4 fw-bold shadow-sm">Simpan Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const { createApp } = Vue;
    createApp({
        data() {
            return {
                activeTab: 'foto'
            }
        },
        methods: {
            hapusUlasan(id) {
                Swal.fire({
                    title: 'Hapus ulasan?',
                    text: "Data yang dihapus tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#79AE6F',
                    cancelButtonColor: '#f43f5e',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'profil.php?proses_hapus=' + id;
                    }
                });
            }
        },
        mounted() {
            <?php if(isset($_SESSION['alert'])): ?>
                Swal.fire({
                    icon: '<?php echo $_SESSION['alert']['icon']; ?>',
                    title: '<?php echo $_SESSION['alert']['title']; ?>',
                    text: '<?php echo $_SESSION['alert']['text']; ?>',
                    timer: 2500,
                    showConfirmButton: false,
                    timerProgressBar: true
                });
            <?php unset($_SESSION['alert']); endif; ?>
        }
    }).mount('#app-profil');
</script>

<?php include '../templates/footer.php'; ?>