<?php
require_once __DIR__ . '/../config/koneksi.php';

class UserModel {
    private $koneksi;

    public function __construct($koneksi) {
        $this->koneksi = $koneksi;
    }

    public function cariAdmin($email, $password) {
        $email = mysqli_real_escape_string($this->koneksi, $email);
        $query = mysqli_query($this->koneksi, "SELECT * FROM tb_admin WHERE email = '$email' LIMIT 1");
        $data = mysqli_fetch_assoc($query);
        if (!$data) return null;
        $cocok = password_verify($password, $data['password']) || $data['password'] === $password;
        if ($cocok && !password_verify($password, $data['password'])) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $hash = mysqli_real_escape_string($this->koneksi, $hash);
            mysqli_query($this->koneksi, "UPDATE tb_admin SET password = '$hash' WHERE email = '$email'");
        }
        return $cocok ? $data : null;
    }

    public function cariPengunjung($email, $password) {
        $email = mysqli_real_escape_string($this->koneksi, $email);
        $query = mysqli_query($this->koneksi, "SELECT * FROM tb_pengunjung WHERE email = '$email' LIMIT 1");
        $data = mysqli_fetch_assoc($query);
        if (!$data) return null;
        $cocok = password_verify($password, $data['password']) || $data['password'] === $password;
        if ($cocok && !password_verify($password, $data['password'])) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $hash = mysqli_real_escape_string($this->koneksi, $hash);
            mysqli_query($this->koneksi, "UPDATE tb_pengunjung SET password = '$hash' WHERE email = '$email'");
        }
        return $cocok ? $data : null;
    }

    public function cariPengunjungById($id) {
        $id = mysqli_real_escape_string($this->koneksi, $id);
        $query = mysqli_query($this->koneksi, "SELECT * FROM tb_pengunjung WHERE id_pengunjung = '$id'");
        return mysqli_fetch_assoc($query);
    }

    public function updatePassword($id, $passwordBaru) {
        $id = mysqli_real_escape_string($this->koneksi, $id);
        $hash = mysqli_real_escape_string($this->koneksi, password_hash($passwordBaru, PASSWORD_DEFAULT));
        return mysqli_query($this->koneksi, "UPDATE tb_pengunjung SET password = '$hash' WHERE id_pengunjung = '$id'");
    }

    public function cekEmailSudahAda($email) {
        $email = mysqli_real_escape_string($this->koneksi, $email);
        $query = mysqli_query($this->koneksi, "SELECT id_pengunjung FROM tb_pengunjung WHERE email = '$email' LIMIT 1");
        return mysqli_num_rows($query) > 0;
    }

    public function daftarPengunjung($nama, $email, $password) {
        $nama = mysqli_real_escape_string($this->koneksi, $nama);
        $email = mysqli_real_escape_string($this->koneksi, $email);
        $passwordHash = mysqli_real_escape_string($this->koneksi, password_hash($password, PASSWORD_DEFAULT));
        return mysqli_query($this->koneksi, "INSERT INTO tb_pengunjung (nama_lengkap, email, password) VALUES ('$nama', '$email', '$passwordHash')");
    }

    public function hitungUserBaruBulanIni() {
        $bulan = date('m');
        $tahun = date('Y');
        $query = mysqli_query($this->koneksi, "SELECT COUNT(id_pengunjung) as total FROM tb_pengunjung WHERE MONTH(created_at) = '$bulan' AND YEAR(created_at) = '$tahun'");
        $data = mysqli_fetch_assoc($query);
        return (int) ($data['total'] ?? 0);
    }
}