<?php
/**
 * GaleriModel.php
 * Model untuk menangani semua query terkait galeri foto
 * 
 * Digunakan oleh: GaleriController.php, PublicController.php
 */

require_once __DIR__ . '/../config/koneksi.php';

class GaleriModel {

    private $koneksi;

    public function __construct($koneksi) {
        $this->koneksi = $koneksi;
    }

    /**
     * Ambil semua foto dengan status 'approved'
     * Digunakan di halaman galeri publik
     */
    public function getFotoApproved() {
        $query = mysqli_query(
            $this->koneksi,
            "SELECT tb_galeri.*, tb_pengunjung.nama_lengkap 
             FROM tb_galeri 
             JOIN tb_pengunjung ON tb_galeri.id_pengunjung = tb_pengunjung.id_pengunjung 
             WHERE tb_galeri.status = 'approved' 
             ORDER BY tb_galeri.id_galeri DESC"
        );

        $hasil = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $hasil[] = $row;
        }
        return $hasil;
    }

    /**
     * Ambil semua foto dengan status 'pending'
     * Digunakan di halaman kelola galeri admin
     */
    public function getFotoPending() {
        $query = mysqli_query(
            $this->koneksi,
            "SELECT tb_galeri.*, tb_pengunjung.nama_lengkap 
             FROM tb_galeri 
             JOIN tb_pengunjung ON tb_galeri.id_pengunjung = tb_pengunjung.id_pengunjung 
             WHERE tb_galeri.status = 'pending' 
             ORDER BY tb_galeri.id_galeri DESC"
        );

        $hasil = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $hasil[] = $row;
        }
        return $hasil;
    }

    /**
     * Ambil foto random untuk slider (login & register)
     * Digunakan di halaman auth sebagai background slider
     */
    public function getFotoSlider($limit = 10) {
        $limit = (int) $limit;
        $query = mysqli_query(
            $this->koneksi,
            "SELECT file_foto FROM tb_galeri 
             WHERE status = 'approved' 
             ORDER BY RAND() 
             LIMIT $limit"
        );

        $hasil = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $hasil[] = $row['file_foto'];
        }
        return $hasil;
    }

    /**
     * Hitung total foto yang sudah approved
     * Digunakan di dashboard admin untuk statistik
     */
    public function hitungFotoApproved() {
        $query = mysqli_query(
            $this->koneksi,
            "SELECT COUNT(id_galeri) as total 
             FROM tb_galeri 
             WHERE status = 'approved'"
        );

        $data = mysqli_fetch_assoc($query);
        return (int) ($data['total'] ?? 0);
    }

    /**
     * Setujui foto (ubah status dari pending ke approved)
     * Digunakan oleh admin di halaman kelola galeri
     */
    public function setujuiFoto($id_galeri) {
        $id_galeri = (int) $id_galeri;

        return mysqli_query(
            $this->koneksi,
            "UPDATE tb_galeri SET status = 'approved' 
             WHERE id_galeri = $id_galeri"
        );
    }

    /**
     * Hapus foto dari database berdasarkan ID
     * Digunakan oleh admin di halaman kelola galeri
     */
    public function hapusFoto($id_galeri) {
        $id_galeri = (int) $id_galeri;

        return mysqli_query(
            $this->koneksi,
            "DELETE FROM tb_galeri WHERE id_galeri = $id_galeri"
        );
    }

    /**
     * Ambil satu data foto berdasarkan ID
     * Digunakan saat akan menghapus file fisik sebelum hapus dari DB
     */
    public function getFotoById($id_galeri) {
        $id_galeri = (int) $id_galeri;

        $query = mysqli_query(
            $this->koneksi,
            "SELECT * FROM tb_galeri WHERE id_galeri = $id_galeri LIMIT 1"
        );

        return mysqli_fetch_assoc($query);
    }

    /**
     * Simpan data foto yang diunggah pengunjung (status: pending)
     * Digunakan saat pengunjung mengunggah foto
     */
    public function unggahFoto($id_pengunjung, $kategori, $caption, $nama_file) {
        $id_pengunjung = (int) $id_pengunjung;
        $kategori      = mysqli_real_escape_string($this->koneksi, $kategori);
        $caption       = mysqli_real_escape_string($this->koneksi, $caption);
        $nama_file     = mysqli_real_escape_string($this->koneksi, $nama_file);

        return mysqli_query(
            $this->koneksi,
            "INSERT INTO tb_galeri (id_pengunjung, kategori, caption, file_foto, status) 
             VALUES ('$id_pengunjung', '$kategori', '$caption', '$nama_file', 'pending')"
        );
    }

    /**
     * Toggle like/unlike sebuah foto
     * Digunakan di galeri publik saat pengunjung klik like
     */
    public function toggleLike($id_galeri, $id_pengunjung) {
        $id_galeri    = (int) $id_galeri;
        $id_pengunjung = (int) $id_pengunjung;

        $cek = mysqli_query(
            $this->koneksi,
            "SELECT id_like FROM tb_like 
             WHERE id_galeri = $id_galeri AND id_pengunjung = $id_pengunjung"
        );

        if (mysqli_num_rows($cek) > 0) {
            mysqli_query(
                $this->koneksi,
                "DELETE FROM tb_like 
                 WHERE id_galeri = $id_galeri AND id_pengunjung = $id_pengunjung"
            );
            $aksi = 'unliked';
        } else {
            mysqli_query(
                $this->koneksi,
                "INSERT INTO tb_like (id_galeri, id_pengunjung) 
                 VALUES ($id_galeri, $id_pengunjung)"
            );
            $aksi = 'liked';
        }

        // Hitung total like terbaru setelah toggle
        $res   = mysqli_query(
            $this->koneksi,
            "SELECT COUNT(*) as total FROM tb_like WHERE id_galeri = $id_galeri"
        );
        $total = mysqli_fetch_assoc($res);

        return [
            'aksi'      => $aksi,
            'new_count' => (int) $total['total']
        ];
    }
}