<?php
/**
 * aksi_hapus_fasilitas.php
 * File aksi untuk menghapus fasilitas
 * Menerima GET ?id=... dari: kelola_fasilitas.php
 * Diproses oleh: FasilitasController::hapus()
 */

session_start();
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/FasilitasController.php';

$controller = new FasilitasController($koneksi);
$controller->hapus();
