<?php
session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

$pesan_swal = '';

if (isset($_POST['upload'])) {
    if ($_SESSION['role'] !== 'pengunjung') {
        $pesan_swal = "
            Swal.fire({
                title: 'Akses Dibatasi!',
                text: 'Admin tidak diizinkan mengunggah foto dari sini.',
                icon: 'warning',
                confirmButtonColor: '#10b981'
            }).then(() => {
                window.location='galeri.php';
            });
        ";
    } else {
        $id_user = $_SESSION['id'];
        $caption = mysqli_real_escape_string($koneksi, $_POST['caption']);
        $kategori = $_POST['kategori'];
        
        $nama_file = $_FILES['foto']['name'];
        $tmp_file = $_FILES['foto']['tmp_name'];
        $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        
        $nama_baru = time() . "_" . $id_user . ".webp";
        $folder_tujuan = "../../assets/img/uploads/" . $nama_baru;

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($ekstensi, $allowed)) {
            if ($ekstensi == 'jpg' || $ekstensi == 'jpeg') {
                $image = imagecreatefromjpeg($tmp_file);
            } elseif ($ekstensi == 'png') {
                $image = imagecreatefrompng($tmp_file);
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
            } elseif ($ekstensi == 'webp') {
                $image = imagecreatefromwebp($tmp_file);
            }

            if (imagewebp($image, $folder_tujuan, 60)) {
                imagedestroy($image);
                $query = "INSERT INTO tb_galeri (id_pengunjung, kategori, caption, file_foto, status) 
                        VALUES ('$id_user', '$kategori', '$caption', '$nama_baru', 'pending')";
                
                if (mysqli_query($koneksi, $query)) {
                    $pesan_swal = "
                        Swal.fire({
                            title: 'Berhasil Diunggah!',
                            text: 'Foto Anda telah berhasil diunggah dan menunggu moderasi admin.',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true
                        }).then(() => {
                            window.location='galeri.php';
                        });
                    ";
                }
            } else {
                $pesan_swal = "Swal.fire({ title: 'Gagal!', text: 'Gagal memproses kompresi gambar.', icon: 'error' });";
            }
        } else {
            $pesan_swal = "Swal.fire({ title: 'Format Salah!', text: 'Hanya mendukung JPG, PNG, dan WebP.', icon: 'warning' });";
        }
    }
}

include '../templates/header.php';
include '../templates/navbar_public.php';
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="min-h-screen bg-white">
    <section class="py-10" style="background-color: #F0FAF5;">
        <div class="container py-4">
            <nav class="d-flex align-items-center gap-2 mb-3 text-muted small">
                <a href="beranda.php" class="text-decoration-none text-muted">Home</a>
                <i class="bi bi-chevron-right"></i>
                <a href="galeri.php" class="text-decoration-none text-muted">Galeri</a>
                <i class="bi bi-chevron-right"></i>
                <span class="text-dark fw-bold">Unggah</span>
            </nav>
            <h2 class="fw-bold mb-0">Unggah Momen Steling</h2>
        </div>
    </section>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-custom border shadow-sm p-4">
                    <?php if ($_SESSION['role'] === 'pengunjung') : ?>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Pilih Foto (JPG/PNG)</label>
                                <input type="file" name="foto" class="form-control rounded-3" accept="image/png, image/jpeg" required>
                                <div class="form-text text-muted" style="font-size: 0.75rem;">
                                    </i> Format foto yang didukung adalah <b>JPG, PNG, WebP</b>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Kategori Waktu</label>
                                <select name="kategori" class="form-select rounded-3" required>
                                    <option value="" disabled selected>Pilih Waktu...</option>
                                    <option value="Pagi Hari">Pagi Hari</option>
                                    <option value="Sore & Sunset">Sore & Sunset</option>
                                    <option value="Malam Hari">Malam Hari</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold small">Keterangan</label>
                                <textarea name="caption" class="form-control rounded-3" rows="3" placeholder="Tuliskan cerita singkat momen ini..." required></textarea>
                            </div>
                            <button type="submit" name="upload" class="btn btn-primary-custom w-100 rounded-pill py-2 fw-bold shadow-sm">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i> Unggah Sekarang
                            </button>
                        </form>
                    <?php else : ?>
                        <div class="text-center py-4">
                            <i class="bi bi-shield-lock fs-1 text-danger mb-3"></i>
                            <h6 class="fw-bold text-danger">Akses Dibatasi</h6>
                            <p class="small text-muted mb-4">Hanya akun pengunjung yang dapat berbagi momen di galeri.</p>
                            <a href="../admin/kelola_galeri.php" class="btn btn-outline-primary-custom rounded-pill px-4 py-2 small fw-bold">Ke Moderator Panel</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($pesan_swal)) : ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php echo $pesan_swal; ?>
        });
    </script>
<?php endif; ?>

<?php include '../templates/footer.php'; ?>