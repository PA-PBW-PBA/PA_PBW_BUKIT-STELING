<?php
session_start();
include '../../config/koneksi.php';
include '../templates/header.php';

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    $query_admin = mysqli_query($koneksi, "SELECT * FROM tb_admin WHERE email = '$email' AND password = '$password'");
    $data_admin = mysqli_fetch_assoc($query_admin);

    if (mysqli_num_rows($query_admin) > 0) {
        $_SESSION['login'] = true;
        $_SESSION['user'] = $data_admin['nama_lengkap'];
        $_SESSION['role'] = 'admin';
        $_SESSION['id'] = $data_admin['id_admin'];
        echo "<script>alert('Login Admin Berhasil!'); window.location='../admin/dashboard.php';</script>";
    } else {
        $query_user = mysqli_query($koneksi, "SELECT * FROM tb_pengunjung WHERE email = '$email' AND password = '$password'");
        $data_user = mysqli_fetch_assoc($query_user);

        if (mysqli_num_rows($query_user) > 0) {
            $_SESSION['login'] = true;
            $_SESSION['user'] = $data_user['nama_lengkap'];
            $_SESSION['role'] = 'pengunjung';
            $_SESSION['id'] = $data_user['id_pengunjung'];
            echo "<script>alert('Login Berhasil!'); window.location='../public/beranda.php';</script>";
        } else {
            $error = "Email atau Password salah!";
        }
    }
}
?>

<div class="container-fluid vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="max-width: 900px; width: 100%;">
        <div class="row g-0">
            <div class="col-md-6 d-none d-md-block">
                <img src="https://images.unsplash.com/photo-1622469662002-4e84fdfe32ad?q=80&w=1080" class="img-fluid h-100 object-fit-cover" alt="Login Image">
            </div>
            <div class="col-md-6 p-5 bg-white">
                <div class="mb-4">
                    <a href="../public/beranda.php" class="text-decoration-none text-primary-custom fw-bold small">
                        <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                    </a>
                </div>
                <h3 class="fw-bold text-dark mb-1">Selamat Datang</h3>
                <p class="text-muted mb-4 small">Silakan masuk untuk melanjutkan akses kamu.</p>

                <?php if (isset($error)) : ?>
                    <div class="alert alert-danger py-2 small border-0"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control form-control-lg fs-6 rounded-3" placeholder="nama@email.com" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg fs-6 rounded-3" placeholder="••••••••" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary-custom w-100 btn-lg rounded-3 fs-6 fw-bold shadow-sm">
                        Masuk Sekarang
                    </button>
                </form>

                <div class="text-center mt-4">
                    <p class="small text-muted">Belum punya akun? <a href="register.php" class="text-primary-custom fw-bold text-decoration-none">Daftar Pengunjung</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>