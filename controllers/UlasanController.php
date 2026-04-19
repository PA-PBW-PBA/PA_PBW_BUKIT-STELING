<?php
/**
 * UlasanController.php
 * Controller untuk menangani semua aksi terkait ulasan:
 * kirim ulasan (pengunjung), balas & hapus (admin)
 *
 * Digunakan oleh: views/admin/kelola_ulasan.php,
 *                 views/public/proses_ulasan.php
 */

require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/UlasanModel.php';

class UlasanController {

    private $ulasanModel;

    public function __construct($koneksi) {
        $this->ulasanModel = new UlasanModel($koneksi);
    }

    /**
     * Cek akses admin — redirect jika bukan admin
     */
    private function cekAksesAdmin() {
        if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
            header("Location: ../auth/login.php");
            exit;
        }
    }

    /**
     * Cek apakah sudah login
     */
    private function cekLogin() {
        if (!isset($_SESSION['login'])) {
            header("Location: ../auth/login.php");
            exit;
        }
    }

    /**
     * Tampilkan halaman kelola ulasan (admin)
     * Dipanggil dari: views/admin/kelola_ulasan.php
     */
    public function index() {
        $this->cekAksesAdmin();

        $data_ulasan = $this->ulasanModel->semuaUlasan();

        return [
            'data_ulasan' => $data_ulasan
        ];
    }

    /**
     * Proses balasan admin untuk ulasan pengunjung
     * Dipanggil dari: views/admin/aksi_balas_ulasan.php
     */
    public function balas() {
        $this->cekAksesAdmin();

        $pesan_swal = "";

        if (isset($_POST['kirim_balasan'])) {
            $id_ulasan = $_POST['id_ulasan'];
            $balasan   = $_POST['balasan_admin'];

            $berhasil = $this->ulasanModel->balasUlasan($id_ulasan, $balasan);

            if ($berhasil) {
                $pesan_swal = "
                    Swal.fire({
                        title: 'Terkirim!',
                        text: 'Balasan ulasan berhasil disimpan.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location='kelola_ulasan.php';
                    });
                ";
            }
        }

        return ['pesan_swal' => $pesan_swal];
    }

    /**
     * Proses hapus ulasan (admin)
     * Dipanggil dari: views/admin/aksi_hapus_ulasan.php
     */
    public function hapus() {
        $this->cekAksesAdmin();

        if (!isset($_GET['id'])) {
            header("Location: kelola_ulasan.php");
            exit;
        }

        $berhasil = $this->ulasanModel->hapusUlasan($_GET['id']);

        if ($berhasil) {
            $pesan_swal = "
                Swal.fire({
                    title: 'Dihapus!',
                    text: 'Ulasan telah berhasil dihapus.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location='kelola_ulasan.php';
                });
            ";
        }

        return ['pesan_swal' => $pesan_swal ?? ""];
    }

    /**
     * Proses kirim ulasan dari pengunjung
     * Dipanggil dari: views/public/proses_ulasan.php
     */
    public function kirim() {
        $this->cekLogin();

        // Admin tidak boleh kirim ulasan
        if ($_SESSION['role'] !== 'pengunjung') {
            echo "<script>alert('Admin tidak diizinkan mengirim ulasan!'); window.location='ulasan.php';</script>";
            exit;
        }

        if (isset($_POST['kirim'])) {
            $id_user  = $_SESSION['id'];
            $rating   = $_POST['rating'];
            $komentar = $_POST['komentar'];

            $berhasil = $this->ulasanModel->kirimUlasan($id_user, $rating, $komentar);

            if ($berhasil) {
                echo "
                <!DOCTYPE html>
                <html>
                <head>
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    <link href='https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap' rel='stylesheet'>
                    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
                </head>
                <body>
                    <script>
                        Swal.fire({
                            title: 'Terima Kasih!',
                            text: 'Ulasan Anda telah kami terima.',
                            icon: 'success',
                            timer: 2500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = 'ulasan.php';
                        });
                    </script>
                </body>
                </html>";
                exit;
            }
        }

        header("Location: ulasan.php");
        exit;
    }
}