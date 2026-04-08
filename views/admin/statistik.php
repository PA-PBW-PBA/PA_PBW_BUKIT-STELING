<?php
session_start();
include '../../config/koneksi.php';

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'bulanan';
$chart_labels = [];
$chart_data = [];

if ($filter == 'harian') {
    for ($i = 6; $i >= 0; $i--) {
        $tgl = date('Y-m-d', strtotime("-$i days"));
        $label = date('d M', strtotime("-$i days"));
        $query = mysqli_query($koneksi, "SELECT COUNT(id_pengunjung) as total FROM tb_pengunjung WHERE DATE(created_at) = '$tgl'");
        $res = mysqli_fetch_assoc($query);
        $chart_labels[] = $label;
        $chart_data[] = (int)$res['total'];
    }
    $title_chart = "Tren Pengunjung (7 Hari Terakhir)";
} elseif ($filter == 'mingguan') {
    for ($i = 3; $i >= 0; $i--) {
        $start_week = date('Y-m-d', strtotime("-$i week sunday +1 day"));
        $end_week = date('Y-m-d', strtotime("-$i week sunday +7 days"));
        $label = "Minggu " . (4-$i);
        $query = mysqli_query($koneksi, "SELECT COUNT(id_pengunjung) as total FROM tb_pengunjung WHERE DATE(created_at) BETWEEN '$start_week' AND '$end_week'");
        $res = mysqli_fetch_assoc($query);
        $chart_labels[] = $label;
        $chart_data[] = (int)$res['total'];
    }
    $title_chart = "Tren Pengunjung (4 Minggu Terakhir)";
} else {
    for ($i = 5; $i >= 0; $i--) {
        $bulan_raw = date('Y-m', strtotime("-$i months"));
        $nama_bulan = date('M Y', strtotime("-$i months"));
        $query = mysqli_query($koneksi, "SELECT COUNT(id_pengunjung) as total FROM tb_pengunjung WHERE DATE_FORMAT(created_at, '%Y-%m') = '$bulan_raw'");
        $res = mysqli_fetch_assoc($query);
        $chart_labels[] = $nama_bulan;
        $chart_data[] = (int)$res['total'];
    }
    $title_chart = "Tren Pengunjung (6 Bulan Terakhir)";
}

$rating_counts = [];
for ($i = 1; $i <= 5; $i++) {
    $query = mysqli_query($koneksi, "SELECT COUNT(id_ulasan) as total FROM tb_ulasan WHERE rating = $i");
    $res = mysqli_fetch_assoc($query);
    $rating_counts[] = (int)$res['total'];
}

include '../templates/header.php';
?>

<div class="container-fluid px-0">
    <div class="row g-0">
        <?php include '../templates/sidebar_admin.php'; ?>

        <div class="col-md-10 p-3 p-md-5 bg-light min-vh-100">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 mb-md-5">
                <div>
                    <h2 class="fw-bold text-dark mb-1 fs-3 fs-md-2">Analisis & Statistik</h2>
                    <p class="text-muted mb-0 small">Data performa kunjungan Puncak Steling.</p>
                </div>
                <div class="d-flex gap-2">
                    <div class="dropdown flex-grow-1">
                        <button class="btn btn-white shadow-sm dropdown-toggle rounded-pill px-3 px-md-4 w-100" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-calendar3 me-1 me-md-2"></i> 
                            <span class="d-none d-sm-inline">Filter:</span> <?php echo ucfirst($filter); ?>
                        </button>
                        <ul class="dropdown-menu border-0 shadow-lg mt-2">
                            <li><a class="dropdown-item py-2" href="statistik.php?filter=harian">Harian (7 Hari)</a></li>
                            <li><a class="dropdown-item py-2" href="statistik.php?filter=mingguan">Mingguan (4 Minggu)</a></li>
                            <li><a class="dropdown-item py-2" href="statistik.php?filter=bulanan">Bulanan (6 Bulan)</a></li>
                        </ul>
                    </div>
                    <button onclick="window.print()" class="btn btn-white shadow-sm rounded-pill px-3 px-md-4">
                        <i class="bi bi-printer"></i>
                    </button>
                </div>
            </div>

            <div class="row g-3 g-md-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4">
                            <h6 class="fw-bold mb-0 text-dark small-mobile-title"><?php echo $title_chart; ?></h6>
                            <span class="badge bg-primary-subtle text-primary-dark border-0 px-2 px-md-3 py-2 rounded-pill" style="font-size: 0.7rem;">Live Data</span>
                        </div>
                        <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
                            <canvas id="userChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm p-3 p-md-4 rounded-4 bg-white h-100">
                        <h6 class="fw-bold mb-3 mb-md-4 text-dark small-mobile-title">Distribusi Kepuasan</h6>
                        <div class="chart-container d-flex align-items-center" style="position: relative; height: 250px; width: 100%;">
                            <canvas id="ratingChart"></canvas>
                        </div>
                        <div class="mt-3 mt-md-4 pt-3 border-top text-center">
                            <span class="small text-muted">Total: <b class="text-dark"><?php echo array_sum($rating_counts); ?></b> Ulasan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const primaryColor = '#79AE6F';
    const primaryDark = '#346739';
    
    // Set Default Font for Mobile
    Chart.defaults.font.size = window.innerWidth < 768 ? 10 : 12;
    Chart.defaults.color = '#64748b';

    // User Trend Chart
    const ctxUser = document.getElementById('userChart').getContext('2d');
    new Chart(ctxUser, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($chart_labels); ?>,
            datasets: [{
                label: 'User Baru',
                data: <?php echo json_encode($chart_data); ?>,
                borderColor: primaryColor,
                backgroundColor: 'rgba(121, 174, 111, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 4,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: primaryColor,
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 10,
                    bodyFont: { size: 12 }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    ticks: { stepSize: 1 },
                    grid: { color: '#f1f5f9' }
                },
                x: { 
                    grid: { display: false }
                }
            }
        }
    });

    // Rating Doughnut Chart
    const ctxRating = document.getElementById('ratingChart').getContext('2d');
    new Chart(ctxRating, {
        type: 'doughnut',
        data: {
            labels: ['1★', '2★', '3★', '4★', '5★'],
            datasets: [{
                data: <?php echo json_encode($rating_counts); ?>,
                backgroundColor: ['#f43f5e', '#fb923c', '#facc15', '#a855f7', primaryColor],
                hoverOffset: 10,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { 
                    position: window.innerWidth < 768 ? 'right' : 'bottom',
                    labels: { 
                        boxWidth: 12,
                        padding: 15,
                        usePointStyle: true
                    }
                }
            },
            cutout: '70%'
        }
    });
</script>

<style>
    .btn-white { background: #fff; border: 1px solid #e2e8f0; color: #64748b; font-size: 0.9rem; }
    .btn-white:hover { background: #f8fafc; color: var(--primary); border-color: var(--primary-light); }
    .dropdown-item:hover { background-color: #F2EDC2; color: var(--primary-dark); }
    .bg-primary-subtle { background-color: #F2EDC2 !important; }
    .text-primary-dark { color: #346739 !important; }

    @media (max-width: 767.98px) {
        .small-mobile-title { font-size: 0.9rem; }
        .chart-container { height: 250px !important; }
    }
</style>

<?php include '../templates/footer.php'; ?>