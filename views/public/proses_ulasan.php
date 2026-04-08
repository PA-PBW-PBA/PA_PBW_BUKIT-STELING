<?php
session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SESSION['role'] !== 'pengunjung') {
    echo "<script>alert('Admin tidak diizinkan mengirim ulasan!'); window.location='ulasan.php';</script>";
    exit;
}

if (isset($_POST['kirim'])) {
    $id_user = $_SESSION['id'];
    $rating = $_POST['rating'];
    $komentar = mysqli_real_escape_string($koneksi, $_POST['komentar']);

    $query = "INSERT INTO tb_ulasan (id_pengunjung, rating, komentar) VALUES ('$id_user', '$rating', '$komentar')";
    
    if (mysqli_query($koneksi, $query)) {
        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <link href='https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap' rel='stylesheet'>
            <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
        </head>
        <body>
            <script>
                Swal.fire({
                    title: 'Terima Kasih!',
                    text: 'Ulasan Anda telah kami terima.',
                    icon: 'success',
                    timer: 2500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'ulasan.php';
                });
            </script>
        </body>
        </html>";
    }
}
?>