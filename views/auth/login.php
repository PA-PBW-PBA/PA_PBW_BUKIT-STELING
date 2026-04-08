<?php
session_start();
include '../../config/koneksi.php';

if (isset($_SESSION['login'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: ../public/beranda.php");
    }
    exit;
}

$alert_script = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    $query_admin = mysqli_query($koneksi, "SELECT * FROM tb_admin WHERE email = '$email' AND password = '$password'");
    $data_admin = mysqli_fetch_assoc($query_admin);

    if (mysqli_num_rows($query_admin) > 0) {
        session_regenerate_id(true);
        $_SESSION['login'] = true;
        $_SESSION['user'] = $data_admin['nama_lengkap'];
        $_SESSION['role'] = 'admin';
        $_SESSION['id'] = $data_admin['id_admin'];
        
        $alert_script = "
            Swal.fire({
                title: 'Login Berhasil!',
                text: 'Selamat datang kembali, Admin.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.href='../admin/dashboard.php';
            });
        ";
    } else {
        $query_user = mysqli_query($koneksi, "SELECT * FROM tb_pengunjung WHERE email = '$email' AND password = '$password'");
        $data_user = mysqli_fetch_assoc($query_user);

        if (mysqli_num_rows($query_user) > 0) {
            session_regenerate_id(true);
            $_SESSION['login'] = true;
            $_SESSION['user'] = $data_user['nama_lengkap'];
            $_SESSION['role'] = 'pengunjung';
            $_SESSION['id'] = $data_user['id_pengunjung'];
            
            $alert_script = "
                Swal.fire({
                    title: 'Login Berhasil!',
                    text: 'Selamat menikmati layanan Puncak Steling.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href='../public/beranda.php';
                });
            ";
        } else {
            $error = "Email atau Password salah!";
        }
    }
}

include '../templates/header.php';
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid vh-100 d-flex align-items-center justify-content-center" style="background: radial-gradient(circle at top right, #ecfdf5, #f8fafc);">
    <div class="card border-0 shadow-xl rounded-4 overflow-hidden shadow-2xl page-transition" style="max-width: 1000px; width: 100%; min-height: 650px;">
        <div class="row g-0 h-100" style="min-height: 650px;">
            
            <div class="col-md-6 d-none d-md-block position-relative">
                <img src="../../assets/img/fasilitas/Puncak Steling.JPG" class="img-fluid h-100 w-100 object-fit-cover position-absolute" alt="Login Image" style="top:0; left:0;">
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end p-5" style="background: linear-gradient(to top, rgba(15, 23, 42, 0.8), transparent);">
                    <div class="text-white">
                        <h2 class="fw-bold mb-2">Puncak Steling</h2>
                        <p class="opacity-75 mb-0">Nikmati pemandangan City Light Samarinda terbaik dari ketinggian.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 p-5 bg-white d-flex flex-column justify-content-center">
                <div class="mb-4">
                    <a href="../public/beranda.php" class="text-decoration-none text-muted fw-semibold small d-inline-flex align-items-center gap-2 hover-primary">
                        <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                    </a>
                </div>

                <div class="mb-4">
                    <h3 class="fw-bold text-dark mb-2" style="letter-spacing: -0.5px;">Selamat Datang Kembali</h3>
                    <p class="text-muted small">Silakan masuk menggunakan akun terdaftar kamu.</p>
                </div>

                <?php if (isset($error)) : ?>
                    <div class="alert alert-danger py-2 px-3 small border-0 rounded-3 d-flex align-items-center gap-2 mb-4">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <div><?php echo $error; ?></div>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark opacity-75">Alamat Email</label>
                        <div class="input-group shadow-sm-custom">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted border-0"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control form-control-lg fs-6 rounded-end-3 bg-light border-start-0 border-0" placeholder="Email Anda" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark opacity-75">Password</label>
                        <div class="input-group shadow-sm-custom">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted border-0"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control form-control-lg fs-6 bg-light border-start-0 border-0" placeholder="Password Anda" required>
                            <span class="input-group-text bg-light border-start-0 rounded-end-3 text-muted border-0 shadow-none" id="togglePassword" style="cursor: pointer;">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" name="login" class="btn btn-primary-custom w-100 btn-lg rounded-3 fs-6 fw-bold shadow-sm py-3 mb-4">
                        Masuk Sekarang
                    </button>
                </form>

                <div class="text-center">
                    <p class="small text-muted mb-0">Belum punya akun? <a href="register.php" class="text-primary-custom fw-bold text-decoration-none">Daftar sebagai Pengunjung</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Efek transisi tetap ada
        const transitionElement = document.querySelector('.page-transition');
        if (transitionElement) { transitionElement.classList.add('loaded'); }
        
        <?php if (!empty($alert_script)) echo $alert_script; ?>
    });
</script>

<?php include '../templates/footer.php'; ?>