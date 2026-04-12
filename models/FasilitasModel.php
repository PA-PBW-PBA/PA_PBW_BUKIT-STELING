<?php
/**
 * FasilitasModel.php
 * Model untuk menangani semua query terkait fasilitas wisata
 * 
 * Digunakan oleh: FasilitasController.php
 */

require_once __DIR__ . '/../config/koneksi.php';

class FasilitasModel {

    private $koneksi;

    public function __construct($koneksi) {
        $this->koneksi = $koneksi;
    }

    /**
     * Ambil semua data fasilitas, urut terbaru
     * Digunakan di halaman kelola fasilitas admin & halaman publik
     */
    public function semuaFasilitas() {
        $query = mysqli_query(
            $this->koneksi,
            "SELECT * FROM tb_fasilitas ORDER BY id_fasilitas DESC"
        );

        $hasil = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $hasil[] = $row;
        }
        return $hasil;
    }

    /**
     * Ambil satu data fasilitas berdasarkan ID
     * Digunakan saat akan edit atau hapus fasilitas
     */
    public function getFasilitasById($id_fasilitas) {
        $id_fasilitas = (int) $id_fasilitas;

        $query = mysqli_query(
            $this->koneksi,
            "SELECT * FROM tb_fasilitas WHERE id_fasilitas = $id_fasilitas LIMIT 1"
        );

        return mysqli_fetch_assoc($query);
    }

    /**
     * Tambah data fasilitas baru ke database
     * Digunakan saat admin menambahkan fasilitas baru
     */
    public function tambahFasilitas($nama_fasilitas, $nama_file_gambar) {
        $nama         = mysqli_real_escape_string($this->koneksi, $nama_fasilitas);
        $nama_file    = mysqli_real_escape_string($this->koneksi, $nama_file_gambar);

        return mysqli_query(
            $this->koneksi,
            "INSERT INTO tb_fasilitas (nama_fasilitas, file_gambar) 
             VALUES ('$nama', '$nama_file')"
        );
    }

    /**
     * Update data fasilitas yang sudah ada
     * Digunakan saat admin mengedit fasilitas (nama dan/atau gambar)
     */
    public function editFasilitas($id_fasilitas, $nama_fasilitas, $nama_file_gambar) {
        $id           = (int) $id_fasilitas;
        $nama         = mysqli_real_escape_string($this->koneksi, $nama_fasilitas);
        $nama_file    = mysqli_real_escape_string($this->koneksi, $nama_file_gambar);

        return mysqli_query(
            $this->koneksi,
            "UPDATE tb_fasilitas 
             SET nama_fasilitas = '$nama', file_gambar = '$nama_file' 
             WHERE id_fasilitas = $id"
        );
    }

    /**
     * Hapus data fasilitas dari database
     * Digunakan saat admin menghapus fasilitas
     */
    public function hapusFasilitas($id_fasilitas) {
        $id = (int) $id_fasilitas;

        return mysqli_query(
            $this->koneksi,
            "DELETE FROM tb_fasilitas WHERE id_fasilitas = $id"
        );
    }
}