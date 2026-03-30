<?php
session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_POST['upload'])) {
    $id_user = $_SESSION['id'];
    $caption = mysqli_real_escape_string($koneksi, $_POST['caption']);
    $kategori = $_POST['kategori'];
    
    // Konfigurasi Upload
    $nama_file = $_FILES['foto']['name'];
    $tmp_file = $_FILES['foto']['tmp_name'];
    $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
    $nama_baru = time() . "_" . $id_user . "." . $ekstensi;
    $folder_tujuan = "../../assets/img/uploads/" . $nama_baru;

    // Validasi Ekstensi
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    if (in_array($ekstensi, $allowed)) {
        if (move_uploaded_file($tmp_file, $folder_tujuan)) {
            // Simpan ke Database dengan status 'pending'
            $query = "INSERT INTO tb_galeri (id_pengunjung, kategori, caption, file_foto, status) 
                    VALUES ('$id_user', '$kategori', '$caption', '$nama_baru', 'pending')";
            
            if (mysqli_query($koneksi, $query)) {
                echo "<script>alert('Berhasil! Foto Anda akan ditinjau admin.'); window.location='galeri.php';</script>";
            } else {
                echo "<script>alert('Gagal SQL: " . mysqli_error($koneksi) . "');</script>";
            }
        } else {
            echo "<script>alert('Gagal memindahkan file ke folder uploads. Cek izin folder!');</script>";
        }
    } else {
        echo "<script>alert('Format file tidak didukung! Gunakan JPG/PNG.');</script>";
    }
}

include '../../config/koneksi.php';
include '../templates/header.php';
include '../templates/navbar_public.php';
?>

<div class="min-h-screen bg-white">
    <section class="py-10" style="background-color: #F0FAF5;">
        <div class="container">
            <nav class="d-flex align-items-center gap-2 mb-3 text-muted small">
                <a href="beranda.php" class="text-decoration-none text-muted">Home</a>
                <i class="bi bi-chevron-right"></i>
                <a href="galeri.php" class="text-decoration-none text-muted">Galeri</a>
                <i class="bi bi-chevron-right"></i>
                <span class="text-dark fw-bold">Unggah</span>
            </nav>
            <h2 class="fw-bold">Unggah Foto Kamu</h2>
        </div>
    </section>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-custom border shadow-sm p-4">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Pilih Foto</label>
                            <input type="file" name="foto" class="form-control rounded-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Kategori Waktu</label>
                            <select name="kategori" class="form-select rounded-3">
                                <option value="Pagi Hari">Pagi Hari</option>
                                <option value="Sore & Sunset">Sore & Sunset</option>
                                <option value="Malam Hari">Malam Hari</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold small">Keterangan</label>
                            <textarea name="caption" class="form-control rounded-3" rows="3" placeholder="Ceritakan momen ini..." required></textarea>
                        </div>
                        <button type="submit" name="upload" class="btn btn-primary-custom w-100 rounded-pill py-2 fw-bold">Kirim ke Admin</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>