<?php
session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php"); exit;
}

$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_informasi LIMIT 1"));

if (isset($_POST['update'])) {
    $id = $data['id_info'];
    $harga = $_POST['harga_tiket'];
    $buka = $_POST['jam_buka'];
    $tutup = $_POST['jam_tutup'];

    mysqli_query($koneksi, "UPDATE tb_informasi SET harga_tiket='$harga', jam_buka='$buka', jam_tutup='$tutup' WHERE id_info='$id'");
    header("Location: kelola_informasi.php?msg=success"); exit;
}

include '../templates/header.php';
?>

<div class="container-fluid px-0">
    <div class="row g-0">
        <?php include '../templates/sidebar_admin.php'; ?>

        <div class="col-md-10 p-5 bg-light min-vh-100">
            <h3 class="fw-bold mb-5">Kelola Informasi Wisata</h3>
            
            <div class="card card-custom border-0 shadow-sm p-5 bg-white">
                <form action="" method="POST">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold small">Harga Tiket Masuk (Rp)</label>
                            <input type="number" name="harga_tiket" class="form-control rounded-3" value="<?php echo $data['harga_tiket']; ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Jam Buka</label>
                            <input type="time" name="jam_buka" class="form-control rounded-3" value="<?php echo $data['jam_buka']; ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Jam Tutup</label>
                            <input type="time" name="jam_tutup" class="form-control rounded-3" value="<?php echo $data['jam_tutup']; ?>">
                        </div>
                        <div class="col-md-12">
                            <button type="submit" name="update" class="btn btn-primary-custom px-5 rounded-pill shadow-sm">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>