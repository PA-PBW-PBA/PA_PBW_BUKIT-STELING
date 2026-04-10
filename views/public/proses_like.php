<?php
session_start();
error_reporting(0);
include '../../config/koneksi.php';

header('Content-Type: application/json');

if (!isset($_SESSION['login'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$id_galeri = isset($input['id_galeri']) ? (int)$input['id_galeri'] : 0;
$id_user = (int)$_SESSION['id'];

if ($id_galeri === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
    exit;
}

$cek = mysqli_query($koneksi, "SELECT id_like FROM tb_like WHERE id_galeri = $id_galeri AND id_pengunjung = $id_user");

if (mysqli_num_rows($cek) > 0) {
    $query = mysqli_query($koneksi, "DELETE FROM tb_like WHERE id_galeri = $id_galeri AND id_pengunjung = $id_user");
    $action = 'unliked';
} else {
    $query = mysqli_query($koneksi, "INSERT INTO tb_like (id_galeri, id_pengunjung) VALUES ($id_galeri, $id_user)");
    $action = 'liked';
}

if ($query) {
    $res_count = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tb_like WHERE id_galeri = $id_galeri");
    $data_count = mysqli_fetch_assoc($res_count);
    echo json_encode([
        'status' => 'success',
        'action' => $action,
        'new_count' => (int)$data_count['total']
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database Error']);
}
exit;