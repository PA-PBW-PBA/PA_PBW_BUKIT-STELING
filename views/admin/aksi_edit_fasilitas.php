<?php
/**
 * aksi_edit_fasilitas.php
 * File aksi untuk mengedit data fasilitas
 * Menerima POST dari: kelola_fasilitas.php
 * Diproses oleh: FasilitasController::edit()
 */

session_start();
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/FasilitasController.php';

$controller = new FasilitasController($koneksi);
$controller->edit();
