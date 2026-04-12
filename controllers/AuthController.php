<?php
/**
 * AuthController.php
 * Controller untuk menangani autentikasi:
 * login, logout, dan registrasi pengunjung
 *
 * Digunakan oleh: views/auth/login.php, views/auth/logout.php, views/auth/register.php
 */

require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/GaleriModel.php';

class AuthController {

    private $userModel;
    private $galeriModel;

    public function __construct($koneksi) {
        $this->userModel   = new UserModel($koneksi);
        $this->galeriModel = new GaleriModel($koneksi);
    }

    /**
     * Tampilkan halaman login + proses form login
     * Dipanggil dari: views/auth/login.php
     */
    public function login() {
        // Jika sudah login, redirect sesuai role
        if (isset($_SESSION['login'])) {
            if ($_SESSION['role'] === 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../public/beranda.php");
            }
            exit;
        }

        // Ambil foto untuk slider background
        $foto_slider  = $this->galeriModel->getFotoSlider(10);
        $slider_photos = [];
        foreach ($foto_slider as $foto) {
            $slider_photos[] = "../../assets/img/uploads/" . $foto;
        }
        if (empty($slider_photos)) {
            $slider_photos[] = "../../assets/img/fasilitas/Puncak Steling.JPG";
        }

        $alert_script = "";
        $error        = "";

        // Proses form login jika ada POST
        if (isset($_POST['login'])) {
            $email    = $_POST['email'];
            $password = $_POST['password'];

            // Cek sebagai admin dulu
            $data_admin = $this->userModel->cariAdmin($email, $password);

            if ($data_admin) {
                session_regenerate_id(true);
                $_SESSION['login'] = true;
                $_SESSION['user']  = $data_admin['nama_lengkap'];
                $_SESSION['role']  = 'admin';
                $_SESSION['id']    = $data_admin['id_admin'];

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
                // Cek sebagai pengunjung
                $data_user = $this->userModel->cariPengunjung($email, $password);

                if ($data_user) {
                    session_regenerate_id(true);
                    $_SESSION['login'] = true;
                    $_SESSION['user']  = $data_user['nama_lengkap'];
                    $_SESSION['role']  = 'pengunjung';
                    $_SESSION['id']    = $data_user['id_pengunjung'];

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

        // Kirim data ke view
        return [
            'slider_photos' => $slider_photos,
            'alert_script'  => $alert_script,
            'error'         => $error
        ];
    }

    /**
     * Proses logout — hancurkan session lalu arahkan ke view logout
     * Dipanggil dari: views/auth/logout.php
     */
    public function logout() {
        session_unset();
        session_destroy();
        // Redirect ditangani langsung di views/auth/logout.php
    }

    /**
     * Tampilkan halaman register + proses form registrasi
     * Dipanggil dari: views/auth/register.php
     */
    public function register() {
        // Ambil foto untuk slider background
        $foto_slider  = $this->galeriModel->getFotoSlider(10);
        $slider_photos = [];
        foreach ($foto_slider as $foto) {
            $slider_photos[] = "../../assets/img/uploads/" . $foto;
        }
        if (empty($slider_photos)) {
            $slider_photos[] = "../../assets/img/fasilitas/Puncak Steling.JPG";
        }

        $pesan_swal = "";
        $error      = "";

        if (isset($_POST['register'])) {
            $nama     = trim($_POST['nama_lengkap']);
            $email    = trim($_POST['email']);
            $password = $_POST['password'];

            // Validasi domain email yang diizinkan
            $allowed_domains = ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com', 'icloud.com', 'mail.com'];
            $email_parts     = explode('@', $email);
            $domain          = strtolower(end($email_parts));
            $no_emoji        = "/^[a-zA-Z0-9!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?\\s]*$/";

            if (strlen($nama) < 5) {
                $error = "Nama minimal harus 5 karakter.";
            } elseif (!preg_match("/^[a-zA-Z\s]*$/", $nama)) {
                $error = "Nama hanya boleh berisi huruf dan spasi.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Format email tidak valid.";
            } elseif (!in_array($domain, $allowed_domains)) {
                $error = "Gunakan layanan email populer (Gmail, Yahoo, dll).";
            } elseif (strlen($password) < 8) {
                $error = "Password minimal harus 8 karakter.";
            } elseif (!preg_match($no_emoji, $email)) {
                $error = "Email tidak boleh berisi emoji.";
            } elseif (!preg_match($no_emoji, $password)) {
                $error = "Password tidak boleh berisi emoji.";
            } elseif ($this->userModel->cekEmailSudahAda($email)) {
                $error = "Email ini sudah terdaftar!";
            } else {
                $berhasil = $this->userModel->daftarPengunjung($nama, $email, $password);

                if ($berhasil) {
                    $pesan_swal = "
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Akun Anda telah dibuat. Silakan login.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location='login.php';
                        });
                    ";
                } else {
                    $error = "Terjadi kesalahan sistem. Coba lagi.";
                }
            }
        }

        // Kirim data ke view
        return [
            'slider_photos' => $slider_photos,
            'pesan_swal'    => $pesan_swal,
            'error'         => $error
        ];
    }
}