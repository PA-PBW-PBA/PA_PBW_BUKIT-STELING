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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div id="app-info" class="container-fluid px-0">
    <div class="row g-0">
        <?php include '../templates/sidebar_admin.php'; ?>

        <div class="col-md-10 p-5 bg-light min-vh-100">
            <h3 class="fw-bold mb-5 animate__animated animate__fadeInDown">Kelola Informasi Wisata</h3>
            
            <div class="card card-custom border-0 shadow-sm p-5 bg-white animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <form action="" method="POST">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold small">Harga Tiket Masuk (Rp)</label>
                            <input type="number" name="harga_tiket" class="form-control rounded-3 transition-input" value="<?php echo $data['harga_tiket']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Jam Buka</label>
                            <input type="time" name="jam_buka" class="form-control rounded-3 transition-input" value="<?php echo $data['jam_buka']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small">Jam Tutup</label>
                            <input type="time" name="jam_tutup" class="form-control rounded-3 transition-input" value="<?php echo $data['jam_tutup']; ?>" required>
                        </div>
                        <div class="col-md-12 mt-5">
                            <button type="submit" name="update" class="btn btn-primary-custom px-5 rounded-pill shadow-sm hover-scale">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const { createApp } = Vue;

    createApp({
        mounted() {
            // Logika Vue: Mengecek apakah ada parameter ?msg=success di URL
            // Jika ada, munculkan SweetAlert animasi dan bersihkan URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('msg') === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Diperbarui!',
                    text: 'Informasi wisata Puncak Steling telah diupdate.',
                    showConfirmButton: false,
                    timer: 2000,
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
                
                // Membersihkan parameter ?msg=success dari URL bar tanpa reload
                window.history.replaceState(null, null, window.location.pathname);
            }
        }
    }).mount('#app-info');
</script>

<style>
    /* Transisi halus saat input di-klik/fokus */
    .transition-input {
        transition: all 0.3s ease;
    }
    .transition-input:focus {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 0.25rem rgba(121, 174, 111, 0.25) !important;
        transform: translateY(-2px);
    }
    /* Animasi sedikit membesar saat tombol di hover */
    .hover-scale {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-scale:hover {
        transform: scale(1.02);
        box-shadow: 0 8px 15px rgba(121, 174, 111, 0.3) !important;
    }
</style>

<?php include '../templates/footer.php'; ?>