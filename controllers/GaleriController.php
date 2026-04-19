<?php
/**
 * GaleriController.php
 * Controller untuk menangani moderasi galeri (admin)
 * dan aksi publik: unggah foto & toggle like (pengunjung)
 *
 * Digunakan oleh: views/admin/kelola_galeri.php,
 *                 views/public/unggah_foto.php,
 *                 views/public/proses_like.php
 */

require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/GaleriModel.php';

class GaleriController {

    private $galeriModel;

    public function __construct($koneksi) {
        $this->galeriModel = new GaleriModel($koneksi);
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
     * Cek apakah sudah login (untuk pengunjung & admin)
     */
    private function cekLogin() {
        if (!isset($_SESSION['login'])) {
            header("Location: ../auth/login.php");
            exit;
        }
    }

    /**
     * Tampilkan halaman kelola galeri admin
     * Dipanggil dari: views/admin/kelola_galeri.php
     */
    public function index() {
        $this->cekAksesAdmin();

        $foto_pending  = $this->galeriModel->getFotoPending();
        $foto_approved = $this->galeriModel->getFotoApproved();

        return [
            'foto_pending'  => $foto_pending,
            'foto_approved' => $foto_approved
        ];
    }

    /**
     * Setujui foto pengunjung (pending → approved)
     * Dipanggil dari: views/admin/aksi_setujui_galeri.php
     */
    public function setujui() {
        $this->cekAksesAdmin();

        if (!isset($_GET['id'])) {
            header("Location: kelola_galeri.php");
            exit;
        }

        $this->galeriModel->setujuiFoto($_GET['id']);
        header("Location: kelola_galeri.php?msg=setujui_berhasil");
        exit;
    }

    /**
     * Hapus foto dari galeri (admin)
     * Dipanggil dari: views/admin/aksi_hapus_galeri.php
     */
    public function hapus() {
        $this->cekAksesAdmin();

        if (!isset($_GET['id'])) {
            header("Location: kelola_galeri.php");
            exit;
        }

        $id   = $_GET['id'];
        $data = $this->galeriModel->getFotoById($id);

        // Hapus file fisik dari server
        if ($data) {
            $path = "../../assets/img/uploads/" . $data['file_foto'];
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        $this->galeriModel->hapusFoto($id);
        header("Location: kelola_galeri.php?msg=hapus_berhasil");
        exit;
    }

    /**
     * Proses unggah foto oleh pengunjung
     * Dipanggil dari: views/public/unggah_foto.php
     */
    public function unggah() {
        $this->cekLogin();

        $pesan_swal = "";

        if (isset($_POST['upload'])) {
            // Hanya pengunjung yang boleh unggah foto
            if ($_SESSION['role'] !== 'pengunjung') {
                $pesan_swal = "Swal.fire({
                    title: 'Akses Dibatasi!',
                    text: 'Admin tidak diizinkan mengunggah foto dari sini.',
                    icon: 'warning',
                    confirmButtonColor: '#79AE6F'
                }).then(() => { window.location='galeri.php'; });";
                return ['pesan_swal' => $pesan_swal];
            }

            $id_user  = $_SESSION['id'];
            $caption  = $_POST['caption'];
            $kategori = $_POST['kategori'];

            $nama_file = $_FILES['foto']['name'];
            $tmp_file  = $_FILES['foto']['tmp_name'];
            $ekstensi  = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
            $allowed   = ['jpg', 'jpeg', 'png'];

            if (!in_array($ekstensi, $allowed)) {
                $pesan_swal = "Swal.fire({
                    title: 'Format Salah!',
                    text: 'Hanya mendukung format JPG, JPEG, dan PNG.',
                    icon: 'warning'
                });";
                return ['pesan_swal' => $pesan_swal];
            }

            // Konversi ke WebP
            $nama_baru      = time() . "_" . $id_user . ".webp";
            $folder_tujuan  = "../../assets/img/uploads/" . $nama_baru;

            if ($ekstensi === 'png') {
                $image = imagecreatefrompng($tmp_file);
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
            } else {
                $image = imagecreatefromjpeg($tmp_file);
            }

            if (imagewebp($image, $folder_tujuan, 60)) {
                imagedestroy($image);
                $berhasil = $this->galeriModel->unggahFoto($id_user, $kategori, $caption, $nama_baru);

                if ($berhasil) {
                    $pesan_swal = "Swal.fire({
                        title: 'Berhasil Diunggah!',
                        text: 'Foto Anda menunggu moderasi admin.',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true
                    }).then(() => { window.location='galeri.php'; });";
                }
            } else {
                $pesan_swal = "Swal.fire({
                    title: 'Gagal!',
                    text: 'Gagal memproses kompresi gambar.',
                    icon: 'error'
                });";
            }
        }

        return ['pesan_swal' => $pesan_swal];
    }

    /**
     * Toggle like/unlike foto — response JSON untuk Vue
     * Dipanggil dari: views/public/proses_like.php
     */
    public function like() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['login'])) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit;
        }

        $input      = json_decode(file_get_contents('php://input'), true);
        $id_galeri  = isset($input['id_galeri']) ? (int) $input['id_galeri'] : 0;
        $id_user    = (int) $_SESSION['id'];

        if ($id_galeri === 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
            exit;
        }

        $hasil = $this->galeriModel->toggleLike($id_galeri, $id_user);

        echo json_encode([
            'status'    => 'success',
            'action'    => $hasil['aksi'],
            'new_count' => $hasil['new_count']
        ]);
        exit;
    }
}