<?php
/**
 * aksi_hapus_ulasan.php
 * File aksi untuk menghapus ulasan pengunjung (admin)
 * Menerima GET ?id=... dari: kelola_ulasan.php
 * Diproses oleh: UlasanController::hapus()
 */

session_start();
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/UlasanController.php';

$controller = new UlasanController($koneksi);
$data       = $controller->hapus();

if (!empty($data['pesan_swal'])) {
    require_once __DIR__ . '/../templates/header.php';
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>{$data['pesan_swal']}</script>";
    exit;
}

header("Location: kelola_ulasan.php");
exit;
