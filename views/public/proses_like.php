<?php
session_start();

require_once __DIR__ . '/../../controllers/GaleriController.php';

$controller = new GaleriController($koneksi);
$controller->like();