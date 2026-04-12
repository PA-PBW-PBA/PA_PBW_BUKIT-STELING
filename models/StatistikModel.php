<?php
/**
 * StatistikModel.php
 * Model untuk menangani query data statistik pengunjung
 * (harian, mingguan, bulanan) untuk kebutuhan chart
 * 
 * Digunakan oleh: AdminController.php & api_stats endpoint
 */

require_once __DIR__ . '/../config/koneksi.php';

class StatistikModel {

    private $koneksi;

    public function __construct($koneksi) {
        $this->koneksi = $koneksi;
    }

    /**
     * Ambil data pengunjung baru per hari (7 hari terakhir)
     * Digunakan untuk chart line harian di halaman statistik admin
     */
    public function getDataHarian() {
        $labels = [];
        $values = [];

        for ($i = 6; $i >= 0; $i--) {
            $tgl      = date('Y-m-d', strtotime("-$i days"));
            $labels[] = date('d M', strtotime("-$i days"));

            $query    = mysqli_query(
                $this->koneksi,
                "SELECT COUNT(id_pengunjung) as total FROM tb_pengunjung
                 WHERE DATE(created_at) = '$tgl'"
            );
            $values[] = (int) mysqli_fetch_assoc($query)['total'];
        }

        return ['labels' => $labels, 'values' => $values];
    }

    /**
     * Ambil data pengunjung baru per minggu (4 minggu terakhir)
     * Digunakan untuk chart line mingguan di halaman statistik admin
     */
    public function getDataMingguan() {
        $labels = [];
        $values = [];

        for ($i = 3; $i >= 0; $i--) {
            $start    = date('Y-m-d', strtotime("-$i week sunday +1 day"));
            $end      = date('Y-m-d', strtotime("-$i week sunday +7 days"));
            $labels[] = "Minggu " . (4 - $i);

            $query    = mysqli_query(
                $this->koneksi,
                "SELECT COUNT(id_pengunjung) as total FROM tb_pengunjung
                 WHERE DATE(created_at) BETWEEN '$start' AND '$end'"
            );
            $values[] = (int) mysqli_fetch_assoc($query)['total'];
        }

        return ['labels' => $labels, 'values' => $values];
    }

    /**
     * Ambil data pengunjung baru per bulan (6 bulan terakhir)
     * Digunakan untuk chart line bulanan di halaman statistik admin
     */
    public function getDataBulanan() {
        $labels = [];
        $values = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan    = date('Y-m', strtotime("-$i months"));
            $labels[] = date('M Y', strtotime("-$i months"));

            $query    = mysqli_query(
                $this->koneksi,
                "SELECT COUNT(id_pengunjung) as total FROM tb_pengunjung
                 WHERE DATE_FORMAT(created_at, '%Y-%m') = '$bulan'"
            );
            $values[] = (int) mysqli_fetch_assoc($query)['total'];
        }

        return ['labels' => $labels, 'values' => $values];
    }
}