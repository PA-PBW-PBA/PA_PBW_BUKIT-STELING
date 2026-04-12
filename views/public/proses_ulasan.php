<?php
/**
 * proses_ulasan.php
 * Entry point untuk aksi kirim ulasan oleh pengunjung
 */

session_start();

require_once __DIR__ . '/../../controllers/UlasanController.php';

$controller = new UlasanController($koneksi);
$controller->kirim();