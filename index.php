<?php
session_start();
if (isset($_SESSION['login']) && $_SESSION['role'] === 'admin') {
    header("Location: views/admin/dashboard.php");
} else {
    header("Location: views/public/beranda.php");
}
exit;
?>