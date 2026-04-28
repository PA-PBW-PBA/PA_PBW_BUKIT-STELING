<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/FasilitasController.php';

$controller = new FasilitasController($koneksi);
$controller->edit();
