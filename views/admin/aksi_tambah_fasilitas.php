<?php
/**
 * aksi_tambah_fasilitas.php
 * File aksi untuk menambah fasilitas baru
 * Menerima POST dari: kelola_fasilitas.php
 * Diproses oleh: FasilitasController::tambah()
 */

session_start();
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/FasilitasController.php';

$controller = new FasilitasController($koneksi);
$controller->tambah();
