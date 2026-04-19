<?php
/**
 * FasilitasController.php
 * Controller untuk menangani CRUD fasilitas wisata
 *
 * Digunakan oleh: views/admin/kelola_fasilitas.php
 */

require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/FasilitasModel.php';

class FasilitasController {

    private $fasilitasModel;

    public function __construct($koneksi) {
        $this->fasilitasModel = new FasilitasModel($koneksi);
    }

    /**
     * Cek apakah user yang mengakses adalah admin
     * Jika bukan admin, redirect ke halaman login
     */
    private function cekAkses() {
        if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
            header("Location: ../auth/login.php");
            exit;
        }
    }

    /**
     * Konversi gambar (JPG/PNG) ke format WebP
     * Dipanggil saat tambah atau edit fasilitas dengan upload gambar baru
     */
    private function konversiKeWebp($source, $destination, $quality = 80) {
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

    /**
     * Tampilkan halaman kelola fasilitas
     * Dipanggil dari: views/admin/kelola_fasilitas.php
     */
    public function index() {
        $this->cekAkses();

        $fasilitas = $this->fasilitasModel->semuaFasilitas();

        return [
            'fasilitas' => $fasilitas
        ];
    }

    /**
     * Proses tambah fasilitas baru
     * Dipanggil dari: views/admin/aksi_tambah_fasilitas.php
     */
    public function tambah() {
        $this->cekAkses();

        if (!isset($_POST['tambah'])) {
            header("Location: kelola_fasilitas.php");
            exit;
        }

        $nama    = $_POST['nama_fasilitas'];
        $tmp     = $_FILES['foto']['tmp_name'];

        $nama_baru = time() . "_" . uniqid() . ".webp";
        $path      = "../../assets/img/fasilitas/" . $nama_baru;

        if ($this->konversiKeWebp($tmp, $path)) {
            $this->fasilitasModel->tambahFasilitas($nama, $nama_baru);
            $pesan = urlencode("Fasilitas baru berhasil ditambahkan.");
            header("Location: kelola_fasilitas.php?msg=tambah_berhasil&info=$pesan");
        } else {
            header("Location: kelola_fasilitas.php?msg=gagal");
        }
        exit;
    }

    /**
     * Proses edit fasilitas yang sudah ada
     * Dipanggil dari: views/admin/aksi_edit_fasilitas.php
     */
    public function edit() {
        $this->cekAkses();

        if (!isset($_POST['edit'])) {
            header("Location: kelola_fasilitas.php");
            exit;
        }

        $id        = $_POST['id_fasilitas'];
        $nama      = $_POST['nama_fasilitas'];
        $foto_lama = $_POST['foto_lama'];

        // Cek apakah ada file gambar baru yang diunggah
        if ($_FILES['foto']['name'] != "") {
            $tmp       = $_FILES['foto']['tmp_name'];
            $nama_baru = time() . "_" . uniqid() . ".webp";
            $path      = "../../assets/img/fasilitas/" . $nama_baru;

            if ($this->konversiKeWebp($tmp, $path)) {
                // Hapus file gambar lama jika ada
                $path_lama = "../../assets/img/fasilitas/" . $foto_lama;
                if (file_exists($path_lama)) {
                    @unlink($path_lama);
                }
                $foto_final = $nama_baru;
            } else {
                $foto_final = $foto_lama;
            }
        } else {
            // Tidak ada gambar baru, pakai gambar lama
            $foto_final = $foto_lama;
        }

        $this->fasilitasModel->editFasilitas($id, $nama, $foto_final);
        header("Location: kelola_fasilitas.php?msg=edit_berhasil");
        exit;
    }

    /**
     * Proses hapus fasilitas
     * Dipanggil dari: views/admin/aksi_hapus_fasilitas.php
     */
    public function hapus() {
        $this->cekAkses();

        if (!isset($_GET['id'])) {
            header("Location: kelola_fasilitas.php");
            exit;
        }

        $id   = $_GET['id'];
        $data = $this->fasilitasModel->getFasilitasById($id);

        // Hapus file fisik gambar dari server
        if ($data) {
            $path = "../../assets/img/fasilitas/" . $data['file_gambar'];
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        $this->fasilitasModel->hapusFasilitas($id);
        header("Location: kelola_fasilitas.php?msg=hapus_berhasil");
        exit;
    }
}