<?php
/**
 * aksi_setujui_galeri.php
 * File aksi untuk menyetujui (approve) foto galeri
 * Menerima GET ?id=... dari: kelola_galeri.php
 * Diproses oleh: GaleriController::setujui()
 */

session_start();
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/GaleriController.php';

$controller = new GaleriController($koneksi);
$controller->setujui();
