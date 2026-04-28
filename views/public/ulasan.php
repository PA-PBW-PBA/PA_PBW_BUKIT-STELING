<?php 
session_start();

if (isset($_SESSION['login'])) {
    $timeout = 3600;
    
    if (isset($_SESSION['last_activity'])) {
        $duration = time() - $_SESSION['last_activity'];
        if ($duration > $timeout) {
            session_unset();
            session_destroy();
            header("Location: ../auth/login.php?msg=session_expired");
            exit;
        }
    }
    $_SESSION['last_activity'] = time();
}

include '../../config/koneksi.php'; 

$query_all = mysqli_query($koneksi, "SELECT tb_ulasan.*, tb_pengunjung.nama_lengkap 
                                     FROM tb_ulasan 
                                     JOIN tb_pengunjung ON tb_ulasan.id_pengunjung = tb_pengunjung.id_pengunjung 
                                     ORDER BY id_ulasan DESC");
$data_ulasan = [];
while($row = mysqli_fetch_assoc($query_all)) {
    $data_ulasan[] = $row;
}

$query_avg = mysqli_query($koneksi, "SELECT AVG(rating) as rata_rata, COUNT(*) as total FROM tb_ulasan");
$data_avg = mysqli_fetch_assoc($query_avg);
$rata_rata = round($data_avg['rata_rata'], 1);
$total_ulasan = $data_avg['total'];

include '../templates/header.php'; 
include '../templates/navbar_public.php'; 
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

<div id="app-ulasan" class="min-vh-100 bg-white overflow-hidden">
    <section class="py-5 border-b border-light bg-light-green animate__animated animate__fadeIn">
        <div class="container py-3">
            <nav class="breadcrumb-custom mb-4">
                <a href="beranda.php" class="breadcrumb-link">Home</a>
                <i class="bi bi-chevron-right breadcrumb-separator"></i>
                <span class="breadcrumb-current">Ulasan</span>
            </nav>
            <h1 class="display-5 fw-bold text-dark mb-0 animate__animated animate__fadeInDown">Ulasan Pengunjung</h1>
            <p class="text-muted mt-2 mb-0 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">Cerita dan pengalaman mereka di Puncak Steling</p>
        </div>
    </section>

    <div class="container py-4 py-md-5">
        <div class="row g-4">
            <div class="col-lg-4 animate__animated animate__fadeInLeft" style="animation-delay: 0.3s;">
                <div class="sticky-top z-99" style="top: 100px;">
                    <div class="card card-custom border-0 shadow-sm p-4 mb-4 bg-white hover-up">
                        <h6 class="fw-bold mb-3">Statistik Penilaian</h6>
                        <div class="d-flex align-items-center gap-3">
                            <h1 class="display-4 fw-bold text-primary-custom mb-0"><?php echo $rata_rata ?: '0'; ?></h1>
                            <div>
                                <div class="text-warning mb-1">
                                    <?php 
                                    for($i=1; $i<=5; $i++) {
                                        if ($i <= $rata_rata) echo '<i class="bi bi-star-fill"></i>';
                                        else if ($i - 0.5 <= $rata_rata) echo '<i class="bi bi-star-half"></i>';
                                        else echo '<i class="bi bi-star text-muted"></i>';
                                    }
                                    ?>
                                </div>
                                <p class="text-muted small mb-0"><?php echo $total_ulasan; ?> ulasan masuk</p>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($_SESSION['login']) && $_SESSION['role'] === 'pengunjung') : ?>
                        <div class="card card-custom border-0 shadow-sm p-4 bg-white hover-up">
                            <h6 class="fw-bold mb-4">Tulis Ulasan Kamu</h6>
                            <form action="proses_ulasan.php" method="POST">
                                <div class="mb-4 text-center">
                                    <input type="hidden" name="rating" :value="rating">
                                    <div class="d-flex justify-content-center gap-2 fs-2 text-warning">
                                        <i v-for="n in 5" :key="n" 
                                           class="bi cursor-pointer star-icon transition-all" 
                                           :class="(n <= (hoverRating || rating)) ? 'bi-star-fill' : 'bi-star'"
                                           @click="setRating(n)" 
                                           @mouseover="setHover(n)" 
                                           @mouseleave="setHover(0)"></i>
                                    </div>
                                    <p class="small fw-bold mt-2" :class="rating === 0 ? 'text-danger' : 'text-success'">
                                        {{ rating === 0 ? 'Pilih rating dahulu' : 'Rating: ' + rating + ' Bintang' }}
                                    </p>
                                </div>
                                <div class="mb-4">
                                    <textarea name="komentar" v-model="formKomentar" class="form-control rounded-3" rows="4" placeholder="Bagikan pengalamanmu..." maxlength="100" required></textarea>
                                    <div class="d-flex justify-content-between mt-2">
                                        <small :class="formKomentar.length >= 100 ? 'text-danger fw-bold' : 'text-muted'" class="fs-xs">
                                            {{ formKomentar.length }} / 100
                                        </small>
                                    </div>
                                </div>
                                <button type="submit" name="kirim" class="btn btn-primary-custom w-100 rounded-pill py-2 fw-bold shadow-sm hover-scale" :disabled="rating === 0 || formKomentar.length < 5">
                                    Kirim Ulasan
                                </button>
                            </form>
                        </div>
                    <?php elseif (isset($_SESSION['login']) && $_SESSION['role'] === 'admin') : ?>
                        <div class="card card-custom border-0 shadow-sm p-5 text-center bg-white hover-up">
                            <div class="mb-3 text-primary-custom"><i class="bi bi-shield-lock-fill icon-xl"></i></div>
                            <h6 class="fw-bold">Panel Moderator</h6>
                            <p class="small text-muted mb-4">Akses kelola ulasan di panel admin.</p>
                            <a href="../admin/kelola_ulasan.php" class="btn btn-primary-custom w-100 rounded-pill fw-bold shadow-sm py-2 hover-scale">Kelola Ulasan</a>
                        </div>
                    <?php else : ?>
                        <div class="card card-custom border-0 shadow-sm p-5 text-center bg-white hover-up">
                            <div class="mb-3 text-muted opacity-50"><i class="bi bi-lock-fill icon-xl"></i></div>
                            <h6 class="fw-bold">Ingin Menulis Ulasan?</h6>
                            <p class="small text-muted mb-4">Silakan login sebagai pengunjung.</p>
                            <a href="../auth/login.php" class="btn btn-primary-custom w-100 rounded-pill fw-bold shadow-sm py-2 hover-scale">Login Sekarang</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-8 animate__animated animate__fadeInRight" style="animation-delay: 0.4s;">
                <div class="d-flex overflow-auto pb-3 gap-2 hide-scrollbar mb-4">
                    <button v-for="f in filterOptions" :key="f.value" @click="activeFilter = f.value; currentPage = 1"
                        class="btn rounded-pill px-3 py-2 small fw-bold shadow-sm transition-all text-nowrap"
                        :class="activeFilter === f.value ? 'btn-primary-custom text-white' : 'btn-light text-muted border'">
                        {{ f.label }}
                    </button>
                </div>

                <transition-group name="fade-list" tag="div" class="row g-3">
                    <div class="col-12" v-for="u in paginatedUlasan" :key="u.id_ulasan">
                        <div class="card card-custom border-0 shadow-sm p-3 p-md-4 bg-white hover-up">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center gap-2 gap-md-3">
                                    <div class="bg-primary-custom bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center avatar-sm">
                                        <i class="bi bi-person-fill text-primary-custom"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0 small-mobile">{{ u.nama_lengkap }}</h6>
                                        <small class="text-muted fs-xs">{{ formatTanggal(u.tanggal_ulasan) }}</small>
                                    </div>
                                </div>
                                <div class="text-warning small">
                                    <i v-for="n in 5" :key="n" class="bi" :class="n <= u.rating ? 'bi-star-fill' : 'bi-star text-muted'"></i>
                                </div>
                            </div>
                            <p class="mb-0 text-dark small-mobile text-normal-height">"{{ u.komentar }}"</p>
                            <div v-if="u.balasan_admin" class="mt-3 p-3 rounded-3 border-start border-4 border-primary-custom bg-light">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <i class="bi bi-patch-check-fill text-primary-custom"></i>
                                    <small class="fw-bold text-dark">Tanggapan Pengelola</small>
                                </div>
                                <p class="mb-0 small text-dark opacity-75">{{ u.balasan_admin }}</p>
                            </div>
                        </div>
                    </div>
                </transition-group>

                <div v-if="filteredUlasan.length === 0" class="text-center py-5 shadow-sm rounded-4 bg-light">
                    <i class="bi bi-chat-left-dots text-muted display-4"></i>
                    <p class="text-muted mt-3">Tidak ada ulasan dengan rating ini.</p>
                </div>

                <div class="d-flex justify-content-center mt-5" v-if="totalPages > 1">
                    <nav>
                        <ul class="pagination pagination-sm gap-2 border-0">
                            <li class="page-item" :class="{ disabled: currentPage === 1 }">
                                <button class="page-link rounded-3 border-0 bg-light text-dark shadow-none hover-scale" @click="currentPage--">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                            </li>
                            <li class="page-item" v-for="page in totalPages" :key="page">
                                <button class="page-link rounded-3 border-0 fw-bold shadow-none hover-scale" 
                                        :class="currentPage === page ? 'bg-primary-custom text-white shadow' : 'bg-light text-dark'"
                                        @click="currentPage = page">
                                    {{ page }}
                                </button>
                            </li>
                            <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                                <button class="page-link rounded-3 border-0 bg-light text-dark shadow-none hover-scale" @click="currentPage++">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const { createApp } = Vue;
    createApp({
        data() {
            return {
                allUlasan: <?php echo json_encode($data_ulasan); ?>,
                rating: 0,
                hoverRating: 0,
                formKomentar: '',
                activeFilter: 'all',
                currentPage: 1,
                perPage: 5,
                filterOptions: [
                    { label: 'Semua Ulasan', value: 'all' },
                    { label: '5 Bintang', value: 5 },
                    { label: '4 Bintang', value: 4 },
                    { label: '3 Bintang', value: 3 },
                    { label: '2 Bintang', value: 2 },
                    { label: '1 Bintang', value: 1 }
                ]
            }
        },
        computed: {
            filteredUlasan() {
                if (this.activeFilter === 'all') return this.allUlasan;
                return this.allUlasan.filter(u => parseInt(u.rating) === this.activeFilter);
            },
            totalPages() {
                return Math.ceil(this.filteredUlasan.length / this.perPage);
            },
            paginatedUlasan() {
                const start = (this.currentPage - 1) * this.perPage;
                const end = start + this.perPage;
                return this.filteredUlasan.slice(start, end);
            }
        },
        methods: {
            setRating(n) { this.rating = n; },
            setHover(n) { this.hoverRating = n; },
            formatTanggal(tgl) {
                const options = { day: 'numeric', month: 'long', year: 'numeric' };
                return new Date(tgl).toLocaleDateString('id-ID', options);
            }
        }
    }).mount('#app-ulasan');
</script>

<?php include '../templates/footer.php'; ?>