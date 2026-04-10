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
                confirmButtonColor: '#79AE6F'
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

        $allowed = ['jpg', 'jpeg', 'png']; 
        
        if (in_array($ekstensi, $allowed)) {
            if ($ekstensi == 'jpg' || $ekstensi == 'jpeg') {
                $image = imagecreatefromjpeg($tmp_file);
            } elseif ($ekstensi == 'png') {
                $image = imagecreatefrompng($tmp_file);
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
            }

            // Kompresi ke WebP untuk performa website yang lebih cepat
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
            $pesan_swal = "Swal.fire({ title: 'Format Salah!', text: 'Hanya mendukung format JPG, JPEG, dan PNG.', icon: 'warning' });";
        }
    }
}

include '../templates/header.php';
include '../templates/navbar_public.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

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
            <p class="text-muted mt-2 mb-0 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">Bagikan jepretan terbaikmu kepada pengunjung lainnya</p>
        </div>
    </section>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
                <div class="card card-custom border-0 shadow p-4 p-md-5 bg-white">
                    <?php if ($_SESSION['role'] === 'pengunjung') : ?>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark small mb-2">Pilih Foto Terbaikmu</label>
                                <div class="input-group shadow-sm-custom">
                                    <input type="file" name="foto" @change="previewImage" id="inputFoto" class="form-control rounded-3 border-0 bg-light py-2" accept="image/png, image/jpeg" required>
                                </div>
                                <div class="form-text text-muted x-small mt-2">
                                    <i class="bi bi-info-circle me-1"></i> Mendukung format <b>JPG, JPEG, PNG</b>.
                                </div>
                            </div>

                            <transition name="fade">
                                <div v-if="imagePreview" class="mb-4 text-center">
                                    <div class="position-relative d-inline-block animate__animated animate__zoomIn animate__faster">
                                        <img :src="imagePreview" class="img-thumbnail rounded-4 shadow-sm border-0" style="max-height: 300px; object-fit: contain;">
                                        <button type="button" @click="removeImage" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle shadow">
                                            <i class="bi bi-x fs-5"></i>
                                        </button>
                                    </div>
                                </div>
                            </transition>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark small mb-2">Waktu Pengambilan Foto</label>
                                <select name="kategori" class="form-select rounded-3 border-0 bg-light py-2 shadow-sm-custom" required>
                                    <option value="" disabled selected>Pilih Kategori Waktu...</option>
                                    <option value="Pagi Hari">Pagi Hari (Sunrise & Embun)</option>
                                    <option value="Sore & Sunset">Sore & Sunset (Senja)</option>
                                    <option value="Malam Hari">Malam Hari (City Light)</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark small d-flex justify-content-between mb-2">
                                    <span>Keterangan Foto</span>
                                    <span class="x-small" :class="caption.length >= 50 ? 'text-danger fw-bold' : 'text-muted'">{{ caption.length }}/50</span>
                                </label>
                                <textarea name="caption" v-model="caption" class="form-control rounded-3 border-0 bg-light py-2 shadow-sm-custom" rows="3" placeholder="Tuliskan cerita singkat tentang foto ini..." maxlength="50" required></textarea>
                            </div>

                            <button type="submit" name="upload" class="btn btn-primary-custom w-100 rounded-pill py-3 fw-bold shadow hover-scale" :disabled="caption.length > 50">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i> Kirim untuk Moderasi
                            </button>
                        </form>
                    <?php else : ?>
                        <div class="text-center py-4">
                            <div class="mb-3 text-danger opacity-50"><i class="bi bi-shield-lock icon-xl"></i></div>
                            <h5 class="fw-bold text-dark">Akses Terbatas</h5>
                            <p class="text-muted small mb-4">Hanya pengunjung yang dapat mengunggah momen ke galeri publik.</p>
                            <a href="../admin/kelola_galeri.php" class="btn btn-primary-custom rounded-pill px-5 py-2 fw-bold shadow">Kelola Galeri</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const { createApp } = Vue;
    createApp({
        data() {
            return {
                caption: '',
                imagePreview: null
            }
        },
        methods: {
            previewImage(e) {
                const file = e.target.files[0];
                if (file) {
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (!allowedTypes.includes(file.type)) {
                        Swal.fire({ 
                            title: 'Format Salah!', 
                            text: 'Gunakan format gambar JPG, JPEG, atau PNG.', 
                            icon: 'warning',
                            confirmButtonColor: '#79AE6F'
                        });
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
</script>

<?php if (!empty($pesan_swal)) : ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php echo $pesan_swal; ?>
        });
    </script>
<?php endif; ?>

<style>
    /* Transisi sederhana untuk preview gambar */
    .fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
    .fade-enter-from, .fade-leave-to { opacity: 0; }
    
    .shadow-sm-custom { box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
</style>

<?php include '../templates/footer.php'; ?>