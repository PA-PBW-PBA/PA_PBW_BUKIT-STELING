<?php
session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php"); exit;
}

function convertToWebp($source, $destination, $quality = 80) {
    $info = getimagesize($source);
    $mime = $info['mime'];

    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source);
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
            break;
        case 'image/webp':
            $image = imagecreatefromwebp($source);
            break;
        default:
            return false;
    }

    $result = imagewebp($image, $destination, $quality);
    imagedestroy($image);
    return $result;
}

if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_fasilitas']);
    $tmp = $_FILES['foto']['tmp_name'];
    
    $nama_baru = time() . "_" . uniqid() . ".webp";
    $path = "../../assets/img/fasilitas/" . $nama_baru;

    if (convertToWebp($tmp, $path)) {
        mysqli_query($koneksi, "INSERT INTO tb_fasilitas (nama_fasilitas, file_gambar) VALUES ('$nama', '$nama_baru')");
        echo "<script>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Fasilitas baru telah ditambahkan (Format WebP).',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location='kelola_fasilitas.php';
            });
        </script>";
    }
}

if (isset($_POST['edit'])) {
    $id = $_POST['id_fasilitas'];
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_fasilitas']);
    $foto_lama = $_POST['foto_lama'];

    if ($_FILES['foto']['name'] != "") {
        $tmp = $_FILES['foto']['tmp_name'];
        $nama_baru = time() . "_" . uniqid() . ".webp";
        $path = "../../assets/img/fasilitas/" . $nama_baru;
        
        if (convertToWebp($tmp, $path)) {
            if (file_exists("../../assets/img/fasilitas/" . $foto_lama)) {
                @unlink("../../assets/img/fasilitas/" . $foto_lama);
            }
            $foto = $nama_baru;
        } else {
            $foto = $foto_lama;
        }
    } else {
        $foto = $foto_lama;
    }

    mysqli_query($koneksi, "UPDATE tb_fasilitas SET nama_fasilitas = '$nama', file_gambar = '$foto' WHERE id_fasilitas = '$id'");
    echo "<script>
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data fasilitas telah diperbarui.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            window.location='kelola_fasilitas.php';
        });
    </script>";
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT file_gambar FROM tb_fasilitas WHERE id_fasilitas = '$id'"));
    
    if ($data && file_exists("../../assets/img/fasilitas/" . $data['file_gambar'])) {
        @unlink("../../assets/img/fasilitas/" . $data['file_gambar']);
    }

    mysqli_query($koneksi, "DELETE FROM tb_fasilitas WHERE id_fasilitas = '$id'");
    echo "<script>
        Swal.fire({
            title: 'Berhasil!',
            text: 'Fasilitas telah dihapus.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            window.location='kelola_fasilitas.php';
        });
    </script>";
}

include '../templates/header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

<div id="app-fasilitas" class="container-fluid px-0">
    <div class="row g-0">
        <?php include '../templates/sidebar_admin.php'; ?>

        <div class="col-md-10 p-5 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center mb-5 animate__animated animate__fadeInDown">
                <div>
                    <h3 class="fw-bold mb-1 text-dark">Manajemen Fasilitas</h3>
                    <p class="text-muted">Kelola daftar fasilitas yang tersedia di Puncak Steling.</p>
                </div>
                <button class="btn btn-primary-custom rounded-pill px-4 shadow-sm fw-bold hover-scale" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus-lg me-2"></i> Tambah Fasilitas
                </button>
            </div>

            <div class="card card-custom border-0 shadow-sm overflow-hidden bg-white animate__animated animate__fadeInUp">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <header class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 border-0 small fw-bold text-muted text-uppercase">Gambar</th>
                                <th class="py-3 border-0 small fw-bold text-muted text-uppercase">Nama Fasilitas</th>
                                <th class="py-3 border-0 small fw-bold text-muted text-uppercase text-center">Aksi</th>
                            </tr>
                        </header>
                        <tbody>
                            <?php
                            $q = mysqli_query($koneksi, "SELECT * FROM tb_fasilitas ORDER BY id_fasilitas DESC");
                            while($f = mysqli_fetch_assoc($q)) :
                            ?>
                            <tr class="transition-row">
                                <td class="ps-4 py-3">
                                    <img src="../../assets/img/fasilitas/<?php echo $f['file_gambar']; ?>" class="rounded-3 shadow-sm object-fit-cover" style="width: 80px; height: 60px;">
                                </td>
                                <td class="py-3">
                                    <h6 class="fw-bold text-dark mb-0"><?php echo $f['nama_fasilitas']; ?></h6>
                                </td>
                                <td class="py-3 text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-light btn-sm rounded-circle p-2 shadow-sm text-primary-custom hover-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEdit<?php echo $f['id_fasilitas']; ?>">
                                            <i class="bi bi-pencil-square fs-6"></i>
                                        </button>
                                        <a href="?hapus=<?php echo $f['id_fasilitas']; ?>" 
                                           class="btn btn-light btn-sm rounded-circle p-2 shadow-sm text-danger hover-btn" 
                                           onclick="konfirmasiHapus(event, this.href, 'Hapus fasilitas <?php echo $f['nama_fasilitas']; ?>?')">
                                            <i class="bi bi-trash fs-6"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold text-dark" id="modalTambahLabel">Tambah Fasilitas Baru</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nama Fasilitas</label>
                        <input type="text" name="nama_fasilitas" class="form-control shadow-none focus-ring" placeholder="Contoh: Musholla Baru" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold text-muted">Gambar Fasilitas</label>
                        <input type="file" name="foto" class="form-control shadow-none focus-ring" accept="image/png, image/jpeg, image/jpg" required>
                        <p class="small text-muted mb-0 mt-1 italic">JPG/PNG akan dikompres otomatis ke WebP.</p>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-primary-custom rounded-pill px-4 fw-bold shadow-sm hover-scale">Tambah Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$q_modal = mysqli_query($koneksi, "SELECT * FROM tb_fasilitas");
while($fm = mysqli_fetch_assoc($q_modal)) :
?>
<div class="modal fade" id="modalEdit<?php echo $fm['id_fasilitas']; ?>" tabindex="-1" aria-labelledby="modalEditLabel<?php echo $fm['id_fasilitas']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold text-dark" id="modalEditLabel<?php echo $fm['id_fasilitas']; ?>">Edit Fasilitas</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <input type="hidden" name="id_fasilitas" value="<?php echo $fm['id_fasilitas']; ?>">
                    <input type="hidden" name="foto_lama" value="<?php echo $fm['file_gambar']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nama Fasilitas</label>
                        <input type="text" name="nama_fasilitas" class="form-control shadow-none focus-ring" value="<?php echo $fm['nama_fasilitas']; ?>" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-muted">Ganti Gambar (Opsional)</label>
                        <input type="file" name="foto" class="form-control shadow-none focus-ring" accept="image/png, image/jpeg, image/jpg">
                    </div>
                    <p class="small text-muted italic mb-0">Kosongkan jika tidak ingin mengubah gambar.</p>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="edit" class="btn btn-primary-custom rounded-pill px-4 fw-bold shadow-sm hover-scale">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endwhile; ?>

<script>
    const { createApp } = Vue;
    createApp({
        data() {
            return {
                mounted: true
            }
        }
    }).mount('#app-fasilitas');
</script>

<style>
    /* Styling interaksi tombol dan input */
    .hover-scale { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-scale:hover { transform: scale(1.03); box-shadow: 0 8px 15px rgba(0,0,0,0.1) !important; }
    
    .hover-btn { transition: all 0.2s; }
    .hover-btn:hover { background-color: var(--primary) !important; color: white !important; }
    .hover-btn.text-danger:hover { background-color: #dc3545 !important; color: white !important; }
    
    .transition-row { transition: background-color 0.2s ease; }
    .transition-row:hover { background-color: #f8f9fa; }
    
    .focus-ring:focus { border-color: var(--primary) !important; box-shadow: 0 0 0 0.25rem rgba(121, 174, 111, 0.25) !important; }
</style>

<?php include '../templates/footer.php'; ?>