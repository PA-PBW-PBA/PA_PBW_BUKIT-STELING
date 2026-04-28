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

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/UlasanController.php';

$controller  = new UlasanController($koneksi);
$data        = $controller->index();
$data_ulasan = $data['data_ulasan'];

include '../templates/header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div id="app-kelola-ulasan" class="container-fluid px-0">
    <div class="row g-0">
        <?php include '../templates/sidebar_admin.php'; ?>

        <div class="col-md-10 p-3 p-md-5 bg-light min-vh-100">
            <div class="d-flex justify-content-between align-items-center mb-4 mb-md-5 animate__animated animate__fadeInDown">
                <div>
                    <h3 class="fw-bold mb-1 fs-4 fs-md-3">Manajemen Ulasan</h3>
                    <p class="text-muted small d-none d-sm-block">Pantau dan kelola feedback dari pengunjung Puncak Steling.</p>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-6 animate__animated animate__fadeInLeft">
                    <div class="card border-0 shadow-sm p-3 p-md-4 bg-white h-100 rounded-4 hover-up">
                        <div class="d-flex align-items-center gap-2 gap-md-3">
                            <div class="bg-danger bg-opacity-10 p-2 p-md-3 rounded-circle d-none d-sm-block">
                                <i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted fw-bold mb-1 x-small text-uppercase">Isu Fasilitas</h6>
                                <h3 class="fw-bold mb-0 fs-4">{{ alertKeywordsCount }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 animate__animated animate__fadeInRight">
                    <div class="card border-0 shadow-sm p-3 p-md-4 bg-white h-100 rounded-4 hover-up">
                        <div class="d-flex align-items-center gap-2 gap-md-3">
                            <div class="bg-success bg-opacity-10 p-2 p-md-3 rounded-circle d-none d-sm-block">
                                <i class="bi bi-chat-left-check-fill text-success fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted fw-bold mb-1 x-small text-uppercase">Respon Rate</h6>
                                <h3 class="fw-bold mb-0 fs-4">{{ responseRate }}%</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4 animate__animated animate__fadeInUp">
                <div class="col-12 d-none d-md-flex gap-3">
                    <div class="bg-white p-1 rounded-pill shadow-sm border d-flex">
                        <button v-for="st in statusOptions" :key="st.value"
                                @click="filterStatus = st.value; currentPage = 1"
                                class="btn rounded-pill px-4 py-2 small fw-bold transition-all"
                                :class="filterStatus === st.value ? 'btn-primary-custom text-white' : 'btn-white text-muted'">
                            {{ st.label }}
                        </button>
                    </div>
                    <div class="bg-white p-1 rounded-pill shadow-sm border d-flex">
                        <button v-for="rt in ['all', 5, 4, 3, 2, 1]" :key="rt"
                                @click="filterRating = rt; currentPage = 1"
                                class="btn rounded-pill px-3 py-2 small fw-bold transition-all"
                                :class="filterRating === rt ? 'btn-warning text-white' : 'btn-white text-muted'">
                            <span v-if="rt === 'all'">Semua Bintang</span>
                            <span v-else>{{ rt }} <i class="bi bi-star-fill small"></i></span>
                        </button>
                    </div>
                </div>

                <div class="col-12 d-md-none d-flex gap-2">
                    <select v-model="filterStatus" @change="currentPage = 1" class="form-select form-select-sm rounded-pill border-0 shadow-sm px-3 py-2 fw-bold text-muted">
                        <option v-for="st in statusOptions" :value="st.value">{{ st.label }}</option>
                    </select>
                    <select v-model="filterRating" @change="currentPage = 1" class="form-select form-select-sm rounded-pill border-0 shadow-sm px-3 py-2 fw-bold text-muted">
                        <option value="all">Semua Rating</option>
                        <option v-for="rt in [5,4,3,2,1]" :value="rt">{{ rt }} Bintang</option>
                    </select>
                </div>
            </div>

            <div class="card border-0 shadow-sm overflow-hidden bg-white rounded-4 animate__animated animate__fadeInUp">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 border-0 small text-uppercase fw-bold text-muted">Pengunjung</th>
                                <th class="py-3 border-0 small text-uppercase fw-bold text-muted d-none d-md-table-cell">Rating</th>
                                <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Komentar</th>
                                <th class="py-3 border-0 small text-uppercase fw-bold text-muted text-center">Aksi</th>
                            </tr>
                        </thead>
                        <transition-group name="fade-list" tag="tbody">
                            <tr v-for="u in paginatedData" :key="u.id_ulasan" class="transition-all">
                                <td class="px-4">
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold small">{{ u.nama_lengkap }}</span>
                                        <div class="d-md-none text-warning x-small">
                                            <i v-for="i in 5" class="bi" :class="i <= u.rating ? 'bi-star-fill' : 'bi-star text-muted'"></i>
                                        </div>
                                    </div>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <div class="text-warning small text-nowrap">
                                        <i v-for="i in 5" class="bi" :class="i <= u.rating ? 'bi-star-fill' : 'bi-star text-muted'"></i>
                                    </div>
                                </td>
                                <td class="text-muted small">
                                    <div @click="showFullComment(u.nama_lengkap, u.komentar)" class="cursor-pointer">
                                        <span class="d-md-none">
                                            {{ u.komentar.length > 10 ? u.komentar.substring(0, 10) + '...' : u.komentar }}
                                        </span>
                                        <span class="d-none d-md-inline">
                                            {{ u.komentar.length > 30 ? u.komentar.substring(0, 30) + '...' : u.komentar }}
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1 gap-md-2">
                                        <button class="btn btn-primary-custom btn-sm rounded-circle p-2 shadow-sm"
                                                data-bs-toggle="modal" :data-bs-target="'#modalBalas' + u.id_ulasan">
                                            <i class="bi bi-reply-fill"></i>
                                        </button>
                                        <button class="btn btn-light btn-sm rounded-circle p-2 shadow-sm text-danger"
                                                @click="confirmDelete(u.id_ulasan)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredData.length === 0">
                                <td colspan="4" class="text-center py-5 text-muted small">Tidak ada ulasan ditemukan.</td>
                            </tr>
                        </transition-group>
                    </table>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-4 mb-4" v-if="totalPages > 1">
                <nav>
                    <ul class="pagination pagination-sm gap-2">
                        <li class="page-item" :class="{ disabled: currentPage === 1 }">
                            <button class="page-link rounded-3 border-0 shadow-sm" @click="currentPage--">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                        </li>
                        <li class="page-item" v-for="p in totalPages" :key="p">
                            <button class="page-link rounded-3 border-0 shadow-sm" 
                                    :class="currentPage === p ? 'btn-primary-custom text-white' : 'bg-white text-dark'" 
                                    @click="currentPage = p">{{ p }}</button>
                        </li>
                        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                            <button class="page-link rounded-3 border-0 shadow-sm" @click="currentPage++">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <div v-for="u in allUlasan" :key="'modal' + u.id_ulasan" class="modal fade" :id="'modalBalas' + u.id_ulasan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <form action="aksi_balas_ulasan.php" method="POST">
                    <div class="modal-header border-0 pb-0 p-4">
                        <h5 class="fw-bold mb-0">Tanggapi Ulasan</h5>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 pt-2">
                        <input type="hidden" name="id_ulasan" :value="u.id_ulasan">
                        <div class="mb-4 p-3 rounded-3 bg-light border">
                            <label class="x-small fw-bold text-muted text-uppercase mb-1 d-block">Ulasan Pengunjung</label>
                            <p class="mb-0 text-dark small">"{{ u.komentar }}"</p>
                        </div>
                        <div class="mb-0">
                            <label class="x-small fw-bold text-muted text-uppercase mb-2 d-block">Balasan Admin</label>
                            <textarea name="balasan_admin" class="form-control border-0 bg-light p-3 shadow-none rounded-3" 
                                      rows="4" placeholder="Tulis jawaban resmi..." required v-model="u.balasan_admin"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="submit" name="kirim_balasan" class="btn btn-primary-custom rounded-pill px-4 fw-bold w-100 py-2 shadow-sm">
                            Simpan Balasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .x-small { font-size: 0.65rem; }
    .fade-list-move, .fade-list-enter-active, .fade-list-leave-active { transition: all 0.4s ease; }
    .fade-list-enter-from, .fade-list-leave-to { opacity: 0; transform: translateX(30px); }
    .fade-list-leave-active { position: absolute; }
    .cursor-pointer { cursor: pointer; }
    .hover-up { transition: all 0.3s ease; }
    .hover-up:hover { transform: translateY(-5px); }
</style>

<script>
    const { createApp } = Vue;
    createApp({
        data() {
            return {
                allUlasan: <?php echo json_encode($data_ulasan); ?>,
                filterStatus: 'all',
                filterRating: 'all',
                currentPage: 1,
                itemsPerPage: 10,
                keywords: [
                    'jelek', 'rusak', 'hancur', 'bobrok', 'kotor', 'bau', 'kumuh', 'licin', 
                    'gelap', 'pengap', 'panas', 'berdebu', 'usang', 'sempit', 'berisik',
                    'lambat', 'lelet', 'lama', 'antri', 'cuek', 'kasar', 'galak', 'buruk', 
                    'payah', 'mengecewakan', 'kecewa', 'parah', 'ngaco', 'mahal', 'rugi', 
                    'boros', 'pungli', 'getok', 'bahaya', 'rawan', 'seram', 'susah', 
                    'sulit', 'macet', 'jauh', 'nyesel', 'kapok', 'sedih', 'kesal', 
                    'kesel', 'marah', 'ogah'
                ],
                statusOptions: [
                    { label: 'Semua Status', value: 'all' },
                    { label: 'Pending', value: 'pending' },
                    { label: 'Dibalas', value: 'dibalas' },
                    { label: 'Isu Negatif', value: 'isu' }
                ]
            }
        },
        computed: {
            alertKeywordsCount() {
                return this.allUlasan.filter(u => this.keywords.some(k => u.komentar.toLowerCase().includes(k))).length;
            },
            responseRate() {
                if (!this.allUlasan.length) return 0;
                return Math.round((this.allUlasan.filter(u => u.balasan_admin).length / this.allUlasan.length) * 100);
            },
            filteredData() {
                return this.allUlasan.filter(u => {
                    const matchRating = this.filterRating === 'all' || parseInt(u.rating) === parseInt(this.filterRating);
                    
                    let matchStatus = true;
                    if (this.filterStatus === 'dibalas') {
                        matchStatus = u.balasan_admin;
                    } else if (this.filterStatus === 'pending') {
                        matchStatus = !u.balasan_admin;
                    } else if (this.filterStatus === 'isu') {
                        matchStatus = this.keywords.some(k => u.komentar.toLowerCase().includes(k));
                    }
                    
                    return matchRating && matchStatus;
                });
            },
            totalPages() { return Math.ceil(this.filteredData.length / this.itemsPerPage); },
            paginatedData() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                return this.filteredData.slice(start, start + this.itemsPerPage);
            }
        },
        methods: {
            showFullComment(nama, komentar) {
                Swal.fire({
                    title: 'Ulasan ' + nama,
                    text: komentar,
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#79AE6F',
                    customClass: { popup: 'rounded-4', confirmButton: 'rounded-pill px-4' }
                });
            },
            confirmDelete(id) {
                Swal.fire({
                    title: 'Hapus Ulasan?',
                    text: 'Data yang dihapus tidak bisa dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((r) => { if (r.isConfirmed) window.location.href = 'aksi_hapus_ulasan.php?id=' + id; });
            }
        }
    }).mount('#app-kelola-ulasan');
</script>

<?php include '../templates/footer.php'; ?>