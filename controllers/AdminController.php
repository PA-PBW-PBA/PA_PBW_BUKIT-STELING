<?php
/**
 * AdminController.php
 * Controller untuk halaman dashboard dan statistik admin
 *
 * Digunakan oleh: views/admin/dashboard.php, views/admin/statistik.php,
 *                 views/admin/api_stats.php, views/admin/kelola_informasi.php
 */

require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/GaleriModel.php';
require_once __DIR__ . '/../models/UlasanModel.php';
require_once __DIR__ . '/../models/InformasiModel.php';
require_once __DIR__ . '/../models/StatistikModel.php';

class AdminController {

    private $userModel;
    private $galeriModel;
    private $ulasanModel;
    private $informasiModel;
    private $statistikModel;

    public function __construct($koneksi) {
        $this->userModel      = new UserModel($koneksi);
        $this->galeriModel    = new GaleriModel($koneksi);
        $this->ulasanModel    = new UlasanModel($koneksi);
        $this->informasiModel = new InformasiModel($koneksi);
        $this->statistikModel = new StatistikModel($koneksi);
    }

    /**
     * Cek apakah user yang mengakses adalah admin
     * Jika bukan admin, redirect ke halaman login
     * Dipanggil di awal setiap method admin
     */
    private function cekAkses() {
        if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
            header("Location: ../auth/login.php");
            exit;
        }
    }

    /**
     * Siapkan data untuk halaman dashboard admin
     * Dipanggil dari: views/admin/dashboard.php
     */
    public function dashboard() {
        $this->cekAkses();

        $rating_final  = $this->ulasanModel->getRataRataRating();
        $total_foto    = $this->galeriModel->hitungFotoApproved();
        $user_baru     = $this->userModel->hitungUserBaruBulanIni();

        return [
            'rating_final' => $rating_final,
            'total_foto'   => $total_foto,
            'user_baru'    => $user_baru
        ];
    }

    /**
     * Siapkan data untuk halaman statistik admin
     * Dipanggil dari: views/admin/statistik.php
     */
    public function statistik() {
        $this->cekAkses();

        $rating_counts = $this->ulasanModel->getDistribusiRating();

        return [
            'rating_counts' => $rating_counts
        ];
    }

    /**
     * Endpoint JSON untuk chart statistik (AJAX dari Vue)
     * Dipanggil dari: views/admin/api_stats.php
     */
    public function apiStats() {
        $this->cekAkses();

        header('Content-Type: application/json');

        $filter = $_GET['filter'] ?? 'bulanan';

        if ($filter === 'harian') {
            $data = $this->statistikModel->getDataHarian();
        } elseif ($filter === 'mingguan') {
            $data = $this->statistikModel->getDataMingguan();
        } else {
            $data = $this->statistikModel->getDataBulanan();
        }

        echo json_encode($data);
        exit;
    }

    /**
     * Tampilkan & proses form kelola informasi wisata
     * Dipanggil dari: views/admin/kelola_informasi.php
     */
    public function kelolaInformasi() {
        $this->cekAkses();

        $data = $this->informasiModel->getInformasi();

        if (isset($_POST['update'])) {
            $this->informasiModel->updateInformasi(
                $data['id_info'],
                $_POST['harga_tiket'],
                $_POST['jam_buka'],
                $_POST['jam_tutup']
            );
            header("Location: kelola_informasi.php?msg=success");
            exit;
        }

        return [
            'data' => $data
        ];
    }
}