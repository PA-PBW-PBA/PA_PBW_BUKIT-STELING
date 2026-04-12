<?php
/**
 * UlasanModel.php
 * Model untuk menangani semua query terkait ulasan pengunjung
 * 
 * Digunakan oleh: UlasanController.php
 */

require_once __DIR__ . '/../config/koneksi.php';

class UlasanModel {

    private $koneksi;

    public function __construct($koneksi) {
        $this->koneksi = $koneksi;
    }

    /**
     * Ambil semua ulasan beserta nama pengunjung, urut terbaru
     * Digunakan di halaman kelola ulasan admin & halaman publik ulasan
     */
    public function semuaUlasan() {
        $query = mysqli_query(
            $this->koneksi,
            "SELECT tb_ulasan.*, tb_pengunjung.nama_lengkap 
             FROM tb_ulasan 
             JOIN tb_pengunjung ON tb_ulasan.id_pengunjung = tb_pengunjung.id_pengunjung 
             ORDER BY tanggal_ulasan DESC"
        );

        $hasil = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $hasil[] = $row;
        }
        return $hasil;
    }

    /**
     * Hitung rata-rata rating dari semua ulasan
     * Digunakan di dashboard admin untuk statistik kepuasan
     */
    public function getRataRataRating() {
        $query = mysqli_query(
            $this->koneksi,
            "SELECT AVG(rating) as rata_rata FROM tb_ulasan"
        );

        $data = mysqli_fetch_assoc($query);
        return number_format($data['rata_rata'] ?? 0, 1);
    }

    /**
     * Hitung jumlah ulasan per bintang (1-5)
     * Digunakan di halaman statistik admin untuk chart distribusi
     */
    public function getDistribusiRating() {
        $hasil = [];
        for ($i = 1; $i <= 5; $i++) {
            $query = mysqli_query(
                $this->koneksi,
                "SELECT COUNT(id_ulasan) as total FROM tb_ulasan WHERE rating = $i"
            );
            $data    = mysqli_fetch_assoc($query);
            $hasil[] = (int) $data['total'];
        }
        return $hasil;
    }

    /**
     * Simpan ulasan baru dari pengunjung
     * Digunakan saat pengunjung mengirimkan ulasan
     */
    public function kirimUlasan($id_pengunjung, $rating, $komentar) {
        $id_pengunjung = (int) $id_pengunjung;
        $rating        = (int) $rating;
        $komentar      = mysqli_real_escape_string($this->koneksi, $komentar);

        return mysqli_query(
            $this->koneksi,
            "INSERT INTO tb_ulasan (id_pengunjung, rating, komentar) 
             VALUES ('$id_pengunjung', '$rating', '$komentar')"
        );
    }

    /**
     * Simpan atau update balasan admin untuk sebuah ulasan
     * Digunakan saat admin membalas ulasan pengunjung
     */
    public function balasUlasan($id_ulasan, $balasan) {
        $id_ulasan = (int) $id_ulasan;
        $balasan   = mysqli_real_escape_string($this->koneksi, $balasan);

        return mysqli_query(
            $this->koneksi,
            "UPDATE tb_ulasan SET balasan_admin = '$balasan' 
             WHERE id_ulasan = $id_ulasan"
        );
    }

    /**
     * Hapus ulasan berdasarkan ID
     * Digunakan saat admin menghapus ulasan
     */
    public function hapusUlasan($id_ulasan) {
        $id_ulasan = (int) $id_ulasan;

        return mysqli_query(
            $this->koneksi,
            "DELETE FROM tb_ulasan WHERE id_ulasan = $id_ulasan"
        );
    }
}