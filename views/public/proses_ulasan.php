<?php
session_start();

require_once __DIR__ . '/../../controllers/UlasanController.php';

$controller = new UlasanController($koneksi);
$controller->kirim();