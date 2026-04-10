<?php
include '../../config/koneksi.php';

$filter = $_GET['filter'] ?? 'bulanan';
$labels = [];
$values = [];

if ($filter == 'harian') {
    for ($i = 6; $i >= 0; $i--) {
        $tgl = date('Y-m-d', strtotime("-$i days"));
        $labels[] = date('d M', strtotime("-$i days"));
        $q = mysqli_query($koneksi, "SELECT COUNT(id_pengunjung) as total FROM tb_pengunjung WHERE DATE(created_at) = '$tgl'");
        $values[] = (int)mysqli_fetch_assoc($q)['total'];
    }
} elseif ($filter == 'mingguan') {
    for ($i = 3; $i >= 0; $i--) {
        $start = date('Y-m-d', strtotime("-$i week sunday +1 day"));
        $end = date('Y-m-d', strtotime("-$i week sunday +7 days"));
        $labels[] = "Minggu " . (4-$i);
        $q = mysqli_query($koneksi, "SELECT COUNT(id_pengunjung) as total FROM tb_pengunjung WHERE DATE(created_at) BETWEEN '$start' AND '$end'");
        $values[] = (int)mysqli_fetch_assoc($q)['total'];
    }
} else {
    for ($i = 5; $i >= 0; $i--) {
        $bulan = date('Y-m', strtotime("-$i months"));
        $labels[] = date('M Y', strtotime("-$i months"));
        $q = mysqli_query($koneksi, "SELECT COUNT(id_pengunjung) as total FROM tb_pengunjung WHERE DATE_FORMAT(created_at, '%Y-%m') = '$bulan'");
        $values[] = (int)mysqli_fetch_assoc($q)['total'];
    }
}

echo json_encode(['labels' => $labels, 'values' => $values]);