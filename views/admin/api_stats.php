<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/AdminController.php';

$controller = new AdminController($koneksi);
$controller->apiStats();