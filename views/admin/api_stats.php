<?php
error_reporting(0);
header('Content-Type: application/json');
include '../../config/koneksi.php';

if (!$koneksi) {
    echo json_encode(['error' => 'Koneksi gagal']);
    exit;
}

$filter = $_GET['filter'] ?? 'bulanan';
$labels = [];
$values = [];

if ($filter == 'harian') {
    for ($i = 6; $i >= 0; $i--) {
        $tgl = date('Y-m-d', strtotime("-$i days"));
        $labels[] = date('d M', strtotime("-$i days"));
        $q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tb_pengunjung WHERE created_at LIKE '$tgl%'");
        $res = mysqli_fetch_assoc($q);
        $values[] = (int)$res['total'];
    }
} elseif ($filter == 'mingguan') {
    for ($i = 3; $i >= 0; $i--) {
        $start = date('Y-m-d', strtotime("-$i week sunday +1 day"));
        $end = date('Y-m-d', strtotime("-$i week sunday +7 days"));
        $labels[] = "Minggu " . (4-$i);
        $q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tb_pengunjung WHERE created_at >= '$start 00:00:00' AND created_at <= '$end 23:59:59'");
        $res = mysqli_fetch_assoc($q);
        $values[] = (int)$res['total'];
    }
} else {
    for ($i = 5; $i >= 0; $i--) {
        $bulan_raw = date('Y-m', strtotime("-$i months"));
        $labels[] = date('M Y', strtotime("-$i months"));
        $q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tb_pengunjung WHERE created_at LIKE '$bulan_raw%'");
        $res = mysqli_fetch_assoc($q);
        $values[] = (int)$res['total'];
    }
}

echo json_encode(['labels' => $labels, 'values' => $values]);
exit;