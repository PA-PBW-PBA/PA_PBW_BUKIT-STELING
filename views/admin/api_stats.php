<?php
/**
 * views/admin/api_stats.php
 * Endpoint JSON untuk data chart statistik
 * Dipanggil via fetch() oleh Vue JS di halaman statistik.php
 * Semua logika ditangani oleh AdminController::apiStats()
 */

session_start();
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/AdminController.php';

$controller = new AdminController($koneksi);
$controller->apiStats();