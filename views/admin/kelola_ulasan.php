<?php
/**
 * views/admin/kelola_ulasan.php
 * Halaman kelola ulasan pengunjung — hanya tampilan HTML
 * Aksi balas/hapus diteruskan ke file aksi masing-masing
 */

session_start();
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

        <div class="col-md-10 p-5 bg-light min-vh-100">
            <div class="mb-5 animate__animated animate__fadeInDown">
                <h3 class="fw-bold mb-1">Manajemen Ulasan</h3>
                <p class="text-muted">Pantau dan kelola feedback dari pengunjung Puncak Steling.</p>
            </div>

            <!-- Kartu Ringkasan -->
            <div class="row g-3 mb-4">
                <div class="col-md-6 animate__animated animate__fadeInLeft">
                    <div class="card border-0 shadow-sm p-4 bg-white h-100 rounded-4 hover-up">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-danger bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted small fw-bold mb-1">ISU INFRASTRUKTUR</h6>
                                <h3 class="fw-bold mb-0">{{ alertKeywordsCount }}</h3>
                                <small class="text-muted">Keluhan Fasilitas/Akses terdeteksi</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 animate__animated animate__fadeInRight">
                    <div class="card border-0 shadow-sm p-4 bg-white h-100 rounded-4 hover-up">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-chat-left-check-fill text-success fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-muted small fw-bold mb-1">RESPON RATE</h6>
                                <h3 class="fw-bold mb-0">{{ responseRate }}%</h3>
                                <small class="text-muted">Ulasan yang telah ditanggapi</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter -->
            <div class="d-flex flex-wrap gap-3 mb-4 align-items-center animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="d-flex gap-2 bg-white p-1 rounded-pill shadow-sm border">
                    <button v-for="st in statusOptions" :key="st.value"
                            @click="filterStatus = st.value; currentPage = 1"
                            class="btn rounded-pill px-4 py-2 small fw-bold transition-all"
                            :class="filterStatus === st.value ? 'btn-primary-custom text-white' : 'btn-white text-muted'">
                        {{ st.label }}
                    </button>
                </div>
                <div class="d-flex gap-2 bg-white p-1 rounded-pill shadow-sm border">
                    <button v-for="rt in ['all', 5, 4, 3, 2, 1]" :key="rt"
                            @click="filterRating = rt; currentPage = 1"
                            class="btn rounded-pill px-3 py-2 small fw-bold transition-all"
                            :class="filterRating === rt ? 'btn-warning text-white' : 'btn-white text-muted'">
                        <span v-if="rt === 'all'">Semua Bintang</span>
                        <span v-else>{{ rt }} <i class="bi bi-star-fill small"></i></span>
                    </button>
                </div>
            </div>

            <!-- Tabel Ulasan -->
            <div class="card border-0 shadow-sm overflow-hidden bg-white rounded-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 border-0 small text-uppercase fw-bold text-muted">Pengunjung</th>
                                <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Rating</th>
                                <th class="py-3 border-0 small text-uppercase fw-bold text-muted">Komentar</th>
                                <th class="py-3 border-0 small text-uppercase fw-bold text-muted text-center">Status</th>
                                <th class="py-3 border-0 small text-uppercase fw-bold text-muted text-center">Aksi</th>
                            </tr>
                        </thead>
                        <transition-group name="fade-list" tag="tbody">
                            <tr v-for="u in paginatedData" :key="u.id_ulasan">
                                <td class="px-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;">
                                            <i class="bi bi-person text-secondary"></i>
                                        </div>
                                        <span class="fw-bold small">{{ u.nama_lengkap }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-warning small text-nowrap">
                                        <i v-for="i in 5" class="bi" :class="i <= u.rating ? 'bi-star-fill' : 'bi-star text-muted'"></i>
                                    </div>
                                </td>
                                <td class="text-muted small" style="max-width:250px;">
                                    <div @click="showFullComment(u.nama_lengkap, u.komentar)" class="cursor-pointer">
                                        {{ u.komentar.length > 50 ? u.komentar.substring(0, 50) + '...' : u.komentar }}
                                        <span v-if="u.komentar.length > 50" class="text-primary-custom x-small d-block fw-bold mt-1">Baca Selengkapnya</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span v-if="u.balasan_admin" class="badge bg-success-subtle text-success px-3">Dibalas</span>
                                    <span v-else class="badge bg-light text-muted px-3">Pending</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold small hover-scale"
                                                data-bs-toggle="modal" :data-bs-target="'#modalBalas' + u.id_ulasan">
                                            <i class="bi bi-reply-fill"></i> Balas
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm rounded-pill px-3 hover-scale"
                                                @click="confirmDelete(u.id_ulasan)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="filteredData.length === 0">
                                <td colspan="5" class="text-center py-5 text-muted small">Tidak ada ulasan yang sesuai kriteria.</td>
                            </tr>
                        </transition-group>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4 animate__animated animate__fadeInUp" v-if="totalPages > 1">
                <nav>
                    <ul class="pagination pagination-sm gap-2">
                        <li class="page-item" :class="{ disabled: currentPage === 1 }">
                            <button class="page-link rounded-3 border-0 bg-white shadow-sm text-dark px-3 py-2" @click="currentPage--">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                        </li>
                        <li class="page-item" v-for="p in totalPages" :key="p">
                            <button class="page-link rounded-3 border-0 shadow-sm px-3 py-2 fw-bold"
                                    :class="currentPage === p ? 'btn-primary-custom text-white' : 'bg-white text-dark'"
                                    @click="currentPage = p">{{ p }}</button>
                        </li>
                        <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                            <button class="page-link rounded-3 border-0 bg-white shadow-sm text-dark px-3 py-2" @click="currentPage++">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Modal Balas (form action ke file aksi) -->
    <div v-for="u in allUlasan" :key="'modal' + u.id_ulasan"
         class="modal fade" :id="'modalBalas' + u.id_ulasan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <form action="aksi_balas_ulasan.php" method="POST">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="fw-bold mb-0">Tanggapi Ulasan</h5>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-4">
                        <input type="hidden" name="id_ulasan" :value="u.id_ulasan">
                        <div class="mb-4">
                            <label class="small fw-bold text-muted text-uppercase mb-2 d-block">Ulasan Pengunjung</label>
                            <div class="p-3 rounded-3 bg-light border">
                                <p class="mb-0 text-dark small">"{{ u.komentar }}"</p>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="small fw-bold text-muted text-uppercase mb-2 d-block">Balasan Admin</label>
                            <textarea name="balasan_admin" class="form-control border-0 bg-light p-3 shadow-none rounded-3"
                                      rows="4" placeholder="Tulis jawaban resmi..." required
                                      v-model="u.balasan_admin"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-4">
                        <button type="submit" name="kirim_balasan" class="btn btn-primary-custom rounded-pill px-4 fw-bold w-100">
                            Simpan Balasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .fade-list-move, .fade-list-enter-active, .fade-list-leave-active { transition: all 0.4s ease; }
    .fade-list-enter-from, .fade-list-leave-to { opacity: 0; transform: translateX(30px); }
    .fade-list-leave-active { position: absolute; }
    .hover-up:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; transition: all 0.3s ease; }
    .hover-scale { transition: transform 0.2s ease; }
    .hover-scale:hover { transform: scale(1.05); }
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
                statusOptions: [
                    { label: 'Semua Status', value: 'all' },
                    { label: 'Pending',      value: 'pending' },
                    { label: 'Dibalas',      value: 'dibalas' }
                ]
            }
        },
        computed: {
            alertKeywordsCount() {
                const keywords = ['parkir', 'jalan', 'licin', 'gelap', 'kotor', 'sampah', 'fasilitas', 'tangga', 'toilet', 'akses'];
                return this.allUlasan.filter(u => keywords.some(k => u.komentar.toLowerCase().includes(k))).length;
            },
            responseRate() {
                if (!this.allUlasan.length) return 0;
                return Math.round((this.allUlasan.filter(u => u.balasan_admin).length / this.allUlasan.length) * 100);
            },
            filteredData() {
                return this.allUlasan.filter(u => {
                    const matchRating = this.filterRating === 'all' || parseInt(u.rating) === parseInt(this.filterRating);
                    const matchStatus = this.filterStatus === 'all' ||
                                       (this.filterStatus === 'dibalas' ? u.balasan_admin : !u.balasan_admin);
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
                    title: 'Ulasan dari ' + nama, text: komentar,
                    confirmButtonText: 'Tutup',
                    customClass: { popup: 'rounded-4', confirmButton: 'rounded-pill px-4' }
                });
            },
            confirmDelete(id) {
                Swal.fire({
                    title: 'Hapus Ulasan?', text: 'Data tidak bisa dikembalikan!',
                    icon: 'warning', showCancelButton: true,
                    confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) window.location.href = 'aksi_hapus_ulasan.php?id=' + id;
                });
            }
        }
    }).mount('#app-kelola-ulasan');
</script>

<?php include '../templates/footer.php'; ?>