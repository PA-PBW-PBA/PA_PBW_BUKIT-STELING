<?php
/**
 * views/auth/logout.php
 * Proses logout — hancurkan session via AuthController
 */

session_start();
require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../config/koneksi.php';

$controller = new AuthController($koneksi);
$controller->logout();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - Puncak Steling</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body>
    <script>
        Swal.fire({
            title: 'Berhasil Keluar',
            text: 'Anda telah berhasil mengakhiri sesi.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false,
            willClose: () => {
                window.location.href = '../public/beranda.php';
            }
        });
    </script>
</body>
</html>