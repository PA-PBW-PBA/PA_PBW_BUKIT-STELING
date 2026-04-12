<?php
/**
 * views/admin/statistik.php
 * Halaman statistik & analisis pengunjung
 * Semua logika ditangani oleh AdminController
 */

session_start();
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/AdminController.php';

$controller    = new AdminController($koneksi);
$data          = $controller->statistik();
$rating_counts = $data['rating_counts'];

include '../templates/header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div id="app-statistik" class="container-fluid px-0">
    <div class="row g-0">
        <?php include '../templates/sidebar_admin.php'; ?>

        <div class="col-md-10 p-3 p-md-5 bg-light min-vh-100">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-5">
                <div>
                    <h2 class="fw-bold text-dark mb-1 fs-3">Analisis & Statistik</h2>
                    <p class="text-muted mb-0 small">Data performa kunjungan Puncak Steling.</p>
                </div>
                <div class="d-flex gap-2">
                    <div class="dropdown flex-grow-1">
                        <button class="btn btn-white shadow-sm dropdown-toggle rounded-pill px-4 w-100" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-calendar3 me-2"></i>
                            <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                            {{ filterDisplay }}
                        </button>
                        <ul class="dropdown-menu border-0 shadow-lg mt-2">
                            <li><a class="dropdown-item py-2" href="#" @click.prevent="changeFilter('harian')">Harian</a></li>
                            <li><a class="dropdown-item py-2" href="#" @click.prevent="changeFilter('mingguan')">Mingguan</a></li>
                            <li><a class="dropdown-item py-2" href="#" @click.prevent="changeFilter('bulanan')">Bulanan</a></li>
                        </ul>
                    </div>
                    <button @click="printPage" class="btn btn-white shadow-sm rounded-pill px-4">
                        <i class="bi bi-printer"></i>
                    </button>
                </div>
            </div>

            <div class="row g-4">
                <!-- Chart Tren Pengunjung -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm p-4 rounded-4 bg-white h-100 animate__animated animate__fadeInUp">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="fw-bold mb-0 text-dark">{{ titleChart }}</h6>
                            <span :class="loading ? 'bg-warning' : 'bg-success'" class="badge text-white border-0 px-3 py-2 rounded-pill">
                                {{ loading ? 'Updating...' : 'Live' }}
                            </span>
                        </div>
                        <div style="height: 250px;">
                            <canvas id="userChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Chart Distribusi Rating -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm p-4 rounded-4 bg-white h-100 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                        <h6 class="fw-bold mb-4 text-dark">Distribusi Kepuasan</h6>
                        <div style="height: 200px;">
                            <canvas id="ratingChart"></canvas>
                        </div>
                        <div class="mt-4 pt-3 border-top text-center">
                            <span class="small text-muted">Total: <b class="text-dark"><?php echo array_sum($rating_counts); ?></b> Ulasan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-white { background: #fff; border: 1px solid #e2e8f0; color: #64748b; transition: 0.3s; }
    .btn-white:hover { border-color: #79AE6F; color: #79AE6F; }
</style>

<script>
const { createApp } = Vue;

createApp({
    data() {
        return {
            filter: 'bulanan',
            loading: false,
            userChart: null,
            primaryColor: '#79AE6F'
        }
    },
    computed: {
        filterDisplay() { return this.filter.charAt(0).toUpperCase() + this.filter.slice(1); },
        titleChart() {
            const t = { harian: '7 Hari', mingguan: '4 Minggu', bulanan: '6 Bulan' };
            return `Tren Pengunjung (${t[this.filter]})`;
        }
    },
    methods: {
        async renderChart() {
            this.loading = true;
            try {
                const res  = await fetch(`api_stats.php?filter=${this.filter}&t=${Date.now()}`);
                const json = await res.json();

                if (this.userChart) { this.userChart.destroy(); }

                const ctx    = document.getElementById('userChart').getContext('2d');
                const maxVal = Math.max(...json.values, 0);

                this.userChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: json.labels,
                        datasets: [{
                            data: json.values,
                            borderColor: this.primaryColor,
                            backgroundColor: 'rgba(121, 174, 111, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 5,
                            pointBackgroundColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, max: maxVal + (maxVal < 5 ? 5 : Math.ceil(maxVal * 0.2)), ticks: { stepSize: 1 } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            } catch (e) { console.error(e); }
            finally { this.loading = false; }
        },
        changeFilter(f) { this.filter = f; this.renderChart(); },
        printPage() { window.print(); }
    },
    mounted() {
        this.renderChart();

        const ctxR = document.getElementById('ratingChart').getContext('2d');
        new Chart(ctxR, {
            type: 'doughnut',
            data: {
                labels: ['1★', '2★', '3★', '4★', '5★'],
                datasets: [{
                    data: <?php echo json_encode($rating_counts); ?>,
                    backgroundColor: ['#f43f5e', '#fb923c', '#facc15', '#a855f7', '#79AE6F'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }
}).mount('#app-statistik');
</script>

<?php include '../templates/footer.php'; ?>