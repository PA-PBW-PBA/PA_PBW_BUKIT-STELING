<?php
/**
 * aksi_hapus_galeri.php
 * File aksi untuk menghapus foto galeri
 * Menerima GET ?id=... dari: kelola_galeri.php
 * Diproses oleh: GaleriController::hapus()
 */

session_start();
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/GaleriController.php';

$controller = new GaleriController($koneksi);
$controller->hapus();
