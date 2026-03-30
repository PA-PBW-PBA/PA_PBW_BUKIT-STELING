<?php
session_start();
session_unset();
session_destroy();

echo "<script>
    alert('Anda telah berhasil keluar.');
    window.location.href = '../public/beranda.php';
</script>";
exit;
?>