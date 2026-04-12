<?php
/**
 * InformasiModel.php
 * Model untuk menangani query terkait informasi wisata
 * (harga tiket, jam buka, jam tutup)
 * 
 * Digunakan oleh: AdminController.php
 */

require_once __DIR__ . '/../config/koneksi.php';

class InformasiModel {

    private $koneksi;

    public function __construct($koneksi) {
        $this->koneksi = $koneksi;
    }

    /**
     * Ambil data informasi wisata (harga tiket, jam operasional)
     * Digunakan di halaman publik (beranda, profil) & admin (kelola_informasi)
     */
    public function getInformasi() {
        $query = mysqli_query(
            $this->koneksi,
            "SELECT * FROM tb_informasi LIMIT 1"
        );

        return mysqli_fetch_assoc($query);
    }

    /**
     * Update harga tiket dan jam operasional
     * Digunakan saat admin menyimpan perubahan di halaman kelola informasi
     */
    public function updateInformasi($id_info, $harga_tiket, $jam_buka, $jam_tutup) {
        $id_info     = (int) $id_info;
        $harga_tiket = (int) $harga_tiket;
        $jam_buka    = mysqli_real_escape_string($this->koneksi, $jam_buka);
        $jam_tutup   = mysqli_real_escape_string($this->koneksi, $jam_tutup);

        return mysqli_query(
            $this->koneksi,
            "UPDATE tb_informasi 
             SET harga_tiket = '$harga_tiket', jam_buka = '$jam_buka', jam_tutup = '$jam_tutup' 
             WHERE id_info = $id_info"
        );
    }
}