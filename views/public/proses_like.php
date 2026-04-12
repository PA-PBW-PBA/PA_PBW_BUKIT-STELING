<?php
/**
 * proses_like.php
 * Entry point untuk aksi like/unlike foto galeri oleh pengunjung
 * Response: JSON (dikonsumsi Vue JS)
 */

session_start();

require_once __DIR__ . '/../../controllers/GaleriController.php';

$controller = new GaleriController($koneksi);
$controller->like();