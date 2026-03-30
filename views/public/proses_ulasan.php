<?php
session_start();
include '../../config/koneksi.php';

if (isset($_POST['kirim'])) {
    if (!isset($_SESSION['login'])) {
        header("Location: ../auth/login.php");
        exit;
    }

    $id_pengunjung = $_SESSION['id'];
    $rating = mysqli_real_escape_string($koneksi, $_POST['rating']);
    $komentar = mysqli_real_escape_string($koneksi, $_POST['komentar']);
    $tanggal = date('Y-m-d');

    $query = "INSERT INTO tb_ulasan (id_pengunjung, rating, komentar, tanggal_ulasan) 
            VALUES ('$id_pengunjung', '$rating', '$komentar', '$tanggal')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Terima kasih! Ulasan Anda sangat berharga bagi kami.'); window.location='ulasan.php';</script>";
    } else {
        echo "Gagal mengirim ulasan: " . mysqli_error($koneksi);
    }
} else {
    header("Location: ulasan.php");
    exit;
}
?>