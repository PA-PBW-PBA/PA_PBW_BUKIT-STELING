<?php
/**
 * UserModel.php
 * Model untuk menangani semua query terkait user (admin & pengunjung)
 * 
 * Digunakan oleh: AuthController.php
 */

require_once __DIR__ . '/../config/koneksi.php';

class UserModel {

    private $koneksi;

    public function __construct($koneksi) {
        $this->koneksi = $koneksi;
    }

    /**
     * Cari admin berdasarkan email, lalu verifikasi password dengan password_verify()
     * Mendukung password lama (plain text) dan password baru (hashed)
     * Digunakan saat proses login sebagai admin
     */
    public function cariAdmin($email, $password) {
        $email = mysqli_real_escape_string($this->koneksi, $email);

        $query = mysqli_query(
            $this->koneksi,
            "SELECT * FROM tb_admin 
             WHERE email = '$email' 
             LIMIT 1"
        );

        $data = mysqli_fetch_assoc($query);

        if (!$data) return null;

        // Dukung password lama (plain text) dan baru (hashed)
        $cocok = password_verify($password, $data['password'])
                 || $data['password'] === $password;

        // Jika cocok tapi masih plain text, upgrade ke hash otomatis
        if ($cocok && !password_verify($password, $data['password'])) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $hash = mysqli_real_escape_string($this->koneksi, $hash);
            mysqli_query($this->koneksi,
                "UPDATE tb_admin SET password = '$hash' WHERE email = '$email'");
        }

        return $cocok ? $data : null;
    }

    /**
     * Cari pengunjung berdasarkan email, lalu verifikasi password dengan password_verify()
     * Mendukung password lama (plain text) dan password baru (hashed)
     * Digunakan saat proses login sebagai pengunjung
     */
    public function cariPengunjung($email, $password) {
        $email = mysqli_real_escape_string($this->koneksi, $email);

        $query = mysqli_query(
            $this->koneksi,
            "SELECT * FROM tb_pengunjung 
             WHERE email = '$email' 
             LIMIT 1"
        );

        $data = mysqli_fetch_assoc($query);

        if (!$data) return null;

        // Dukung password lama (plain text) dan baru (hashed)
        $cocok = password_verify($password, $data['password'])
                 || $data['password'] === $password;

        // Jika cocok tapi masih plain text, upgrade ke hash otomatis
        if ($cocok && !password_verify($password, $data['password'])) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $hash = mysqli_real_escape_string($this->koneksi, $hash);
            mysqli_query($this->koneksi,
                "UPDATE tb_pengunjung SET password = '$hash' WHERE email = '$email'");
        }

        return $cocok ? $data : null;
    }

    /**
     * Cek apakah email sudah terdaftar di tb_pengunjung
     * Digunakan saat proses registrasi untuk mencegah duplikasi
     */
    public function cekEmailSudahAda($email) {
        $email = mysqli_real_escape_string($this->koneksi, $email);

        $query = mysqli_query(
            $this->koneksi,
            "SELECT id_pengunjung FROM tb_pengunjung 
             WHERE email = '$email' 
             LIMIT 1"
        );

        return mysqli_num_rows($query) > 0;
    }

    /**
     * Daftarkan pengunjung baru ke database
     * Password di-hash menggunakan password_hash() sebelum disimpan
     * Digunakan saat proses registrasi berhasil validasi
     */
    public function daftarPengunjung($nama, $email, $password) {
        $nama         = mysqli_real_escape_string($this->koneksi, $nama);
        $email        = mysqli_real_escape_string($this->koneksi, $email);
        $passwordHash = mysqli_real_escape_string(
            $this->koneksi,
            password_hash($password, PASSWORD_DEFAULT)
        );

        $query = mysqli_query(
            $this->koneksi,
            "INSERT INTO tb_pengunjung (nama_lengkap, email, password) 
             VALUES ('$nama', '$email', '$passwordHash')"
        );

        return $query;
    }

    /**
     * Hitung jumlah pengunjung baru bulan ini
     * Digunakan di dashboard admin untuk statistik
     */
    public function hitungUserBaruBulanIni() {
        $bulan = date('m');
        $tahun = date('Y');

        $query = mysqli_query(
            $this->koneksi,
            "SELECT COUNT(id_pengunjung) as total 
             FROM tb_pengunjung 
             WHERE MONTH(created_at) = '$bulan' AND YEAR(created_at) = '$tahun'"
        );

        $data = mysqli_fetch_assoc($query);
        return (int) ($data['total'] ?? 0);
    }
}