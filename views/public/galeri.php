<?php 
session_start();
include '../../config/koneksi.php'; 

$id_curr_user = isset($_SESSION['id']) ? $_SESSION['id'] : 0;

$query = mysqli_query($koneksi, "SELECT g.*, p.nama_lengkap,
    (SELECT COUNT(*) FROM tb_like WHERE id_galeri = g.id_galeri) as total_like,
    (SELECT COUNT(*) FROM tb_like WHERE id_galeri = g.id_galeri AND id_pengunjung = '$id_curr_user') as is_liked
    FROM tb_galeri g 
    JOIN tb_pengunjung p ON g.id_pengunjung = p.id_pengunjung 
    WHERE g.status = 'approved' ORDER BY g.id_galeri DESC");

$data_galeri = [];
while($row = mysqli_fetch_assoc($query)) { 
    $data_galeri[] = $row; 
}

include '../templates/header.php'; 
include '../templates/navbar_public.php'; 
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<div id="app-galeri" class="min-vh-100 bg-white overflow-hidden">
    <section class="py-5 border-b border-light bg-light-green animate__animated animate__fadeIn">
        <div class="container py-3">
            <nav class="breadcrumb-custom mb-4">
                <a href="beranda.php" class="breadcrumb-link">Home</a>
                <i class="bi bi-chevron-right breadcrumb-separator"></i>
                <span class="breadcrumb-current">Galeri</span>
            </nav>
            <h1 class="display-5 fw-bold text-dark mb-0 animate__animated animate__fadeInDown">Galeri Puncak Steling</h1>
            <p class="text-muted mt-2 mb-0 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">Keindahan Samarinda dalam setiap jepretan.</p>
        </div>
    </section>

    <div class="container py-4 py-md-5">
        <div class="d-flex flex-column flex-xl-row justify-content-between gap-3 mb-4 mb-md-5 animate__animated animate__fadeIn" style="animation-delay: 0.2s;">
            
            <div class="d-flex flex-column flex-md-row gap-3 overflow-hidden w-100">
                <div class="d-flex bg-white shadow-sm p-1 rounded-pill overflow-auto hide-scrollbar border">
                    <button v-for="cat in categories" :key="cat" @click="activeCategory = cat; currentPage = 1"
                        class="btn rounded-pill px-4 py-2 small transition-all fw-bold text-nowrap border-0"
                        :class="activeCategory === cat ? 'btn-primary-custom text-white shadow' : 'text-muted hover-bg'">
                        {{ cat }}
                    </button>
                </div>
                
                <div class="d-flex bg-white shadow-sm p-1 rounded-pill overflow-auto hide-scrollbar border">
                    <button type="button" class="btn rounded-pill px-4 py-2 small fw-bold transition-all text-nowrap border-0 hover-bg"
                        :class="sortBy === 'newest' ? 'btn-primary-custom text-white shadow' : 'text-muted'"
                        @click="sortBy = 'newest'; currentPage = 1">
                        <i class="bi bi-clock-history me-1"></i> Terbaru
                    </button>
                    <button type="button" class="btn rounded-pill px-4 py-2 small fw-bold transition-all text-nowrap border-0 hover-bg"
                        :class="sortBy === 'oldest' ? 'btn-primary-custom text-white shadow' : 'text-muted'"
                        @click="sortBy = 'oldest'; currentPage = 1">
                        <i class="bi bi-calendar2-week me-1"></i> Terlama
                    </button>
                    <button type="button" class="btn rounded-pill px-4 py-2 small fw-bold transition-all text-nowrap border-0 hover-bg"
                        :class="sortBy === 'liked' ? 'btn-primary-custom text-white shadow' : 'text-muted'"
                        @click="sortBy = 'liked'; currentPage = 1">
                        <i class="bi bi-heart-fill me-1"></i> Paling Disukai
                    </button>
                </div>
            </div>
            
            <div class="flex-shrink-0 d-grid d-xl-block mt-2 mt-xl-0">
                <?php if (isset($_SESSION['login']) && $_SESSION['role'] === 'pengunjung') : ?>
                    <a href="unggah_foto.php" class="btn btn-outline-primary-custom rounded-pill px-4 py-2 shadow-sm d-inline-flex align-items-center justify-content-center gap-2 hover-scale w-100">
                        <i class="bi bi-upload"></i> <span class="fw-bold small text-uppercase text-nowrap">Upload Foto</span>
                    </a>
                <?php elseif (isset($_SESSION['login']) && $_SESSION['role'] === 'admin') : ?>
                    <a href="../admin/kelola_galeri.php" class="btn btn-primary-custom rounded-pill px-4 py-2 shadow-sm d-inline-flex align-items-center justify-content-center gap-2 text-white hover-scale w-100">
                        <i class="bi bi-gear-fill"></i> <span class="fw-bold small text-uppercase text-nowrap">Kelola Galeri</span>
                    </a>
                <?php else : ?>
                    <a href="javascript:void(0)" onclick="alertLogin()" class="btn btn-outline-primary-custom rounded-pill px-4 py-2 shadow-sm d-inline-flex align-items-center justify-content-center gap-2 hover-scale w-100">
                        <i class="bi bi-lock-fill"></i> <span class="fw-bold small text-uppercase text-nowrap">Upload Foto</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <transition-group name="fade-list" tag="div" class="row g-3 g-md-4 position-relative min-h-300px">
            <div class="col-6 col-md-4" v-for="g in paginatedGaleri" :key="g.id_galeri">
                <div class="card card-custom h-100 bg-white border-0 shadow-sm overflow-hidden group position-relative hover-up animate__animated animate__fadeInUp">
                    <div class="ratio ratio-4x3 overflow-hidden cursor-pointer" data-bs-toggle="modal" :data-bs-target="'#imageModal' + g.id_galeri">
                        <img :src="'../../assets/img/uploads/' + g.file_foto" class="card-img-top object-fit-cover transition-all img-hover-zoom">
                    </div>
                    
                    <div class="card-body p-3 p-md-4 position-relative">
                        <div class="mb-2 d-flex justify-content-between align-items-center">
                            <span class="badge rounded-pill text-primary-custom px-2 py-1 fs-xs fw-medium bg-light-green">{{ g.kategori }}</span>
                            <button @click.stop="toggleLike(g)" class="btn-like-trigger transition-all">
                                <i class="bi fs-5" :class="parseInt(g.is_liked) ? 'bi-heart-fill text-danger animate__animated animate__heartBeat' : 'bi-heart text-muted'"></i>
                                <span class="small fw-bold ms-1" :class="parseInt(g.is_liked) ? 'text-danger' : 'text-muted'">{{ g.total_like }}</span>
                            </button>
                        </div>
                        <p class="card-text text-dark fw-medium mb-3 small text-truncate-2">"{{ g.caption }}"</p>
                        <div class="d-flex align-items-center gap-2 border-top pt-3">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center avatar-xs"><i class="bi bi-person text-secondary icon-xs-08"></i></div>
                            <span class="text-muted x-small text-truncate">{{ g.nama_lengkap }}</span>
                        </div>
                    </div>
                </div>

                <div class="modal fade" :id="'imageModal' + g.id_galeri" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content bg-transparent border-0 position-relative">
                            <button type="button" class="btn-close btn-close-white position-absolute rounded-circle modal-close-btn z-3" data-bs-dismiss="modal"></button>
                            <div class="modal-body p-0 text-center rounded-4 bg-black d-flex align-items-center justify-content-center shadow-lg overflow-hidden" style="height: 80vh;">
                                <img :src="'../../assets/img/uploads/' + g.file_foto" class="img-fluid w-100 h-100 object-fit-contain">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div v-if="filteredGaleri.length === 0" class="col-12 text-center py-5">
                <p class="text-muted italic">Tidak ada foto ditemukan.</p>
            </div>
        </transition-group>

        <div class="d-flex justify-content-center mt-5 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;" v-if="totalPages > 1">
            <nav>
                <ul class="pagination pagination-sm gap-2 border-0">
                    <li class="page-item" :class="{ disabled: currentPage === 1 }">
                        <button class="page-link border-0 bg-light text-dark px-3 py-2 fw-bold shadow-none hover-scale" @click="currentPage--">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                    </li>
                    <li class="page-item" v-for="page in totalPages" :key="page">
                        <button class="page-link border-0 px-3 py-2 fw-bold shadow-none hover-scale" 
                                :class="currentPage === page ? 'bg-primary-custom text-white' : 'bg-light text-dark'" 
                                @click="currentPage = page">{{ page }}</button>
                    </li>
                    <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                        <button class="page-link border-0 bg-light text-dark px-3 py-2 fw-bold shadow-none hover-scale" @click="currentPage++">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script src="https://unpkg.com/vue@3.4.21/dist/vue.global.prod.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const { createApp } = Vue;
    createApp({
        data() {
            return {
                allGaleri: <?php echo json_encode($data_galeri); ?>,
                activeCategory: 'Semua Momen',
                sortBy: 'newest',
                currentPage: 1,
                perPage: 9,
                categories: ['Semua Momen', 'Pagi Hari', 'Sore & Sunset', 'Malam Hari'],
                isLoggedIn: <?php echo isset($_SESSION['login']) ? 'true' : 'false'; ?>
            }
        },
        computed: {
            filteredGaleri() {
                let result = this.activeCategory === 'Semua Momen' 
                    ? [...this.allGaleri] 
                    : this.allGaleri.filter(g => g.kategori === this.activeCategory);
                
                if (this.sortBy === 'liked') {
                    result.sort((a, b) => parseInt(b.total_like) - parseInt(a.total_like));
                } else if (this.sortBy === 'oldest') {
                    result.sort((a, b) => parseInt(a.id_galeri) - parseInt(b.id_galeri));
                } else {
                    result.sort((a, b) => parseInt(b.id_galeri) - parseInt(a.id_galeri));
                }
                
                return result;
            },
            totalPages() {
                return Math.ceil(this.filteredGaleri.length / this.perPage);
            },
            paginatedGaleri() {
                const start = (this.currentPage - 1) * this.perPage;
                const end = start + this.perPage;
                return this.filteredGaleri.slice(start, end);
            }
        },
        methods: {
            async toggleLike(foto) {
                if (!this.isLoggedIn) {
                    alertLogin();
                    return;
                }
                try {
                    const response = await fetch('proses_like.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id_galeri: foto.id_galeri })
                    });
                    const rawData = await response.text();
                    try {
                        const res = JSON.parse(rawData);
                        if (res.status === 'success') {
                            foto.total_like = res.new_count;
                            foto.is_liked = (res.action === 'liked') ? 1 : 0;
                        }
                    } catch (jsonErr) {
                        console.error("Respon bukan JSON:", rawData);
                    }
                } catch (e) {
                    console.error("Network Error:", e);
                }
            }
        }
    }).mount('#app-galeri');

    function alertLogin() {
        Swal.fire({
            title: 'Akses Terbatas',
            text: "Silakan login sebagai pengunjung untuk menyukai foto.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#79AE6F',
            confirmButtonText: 'Masuk Sekarang',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) window.location.href = '../auth/login.php';
        });
    }
</script>

<style>
    .fade-list-move,
    .fade-list-enter-active,
    .fade-list-leave-active {
        transition: all 0.5s cubic-bezier(0.55, 0, 0.1, 1);
    }
    .fade-list-enter-from,
    .fade-list-leave-to {
        opacity: 0;
        transform: scale(0.8) translateY(30px);
    }
    .fade-list-leave-active {
        position: absolute;
    }
    .min-h-300px { min-height: 300px; }

    .hover-up { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .hover-up:hover { transform: translateY(-8px); box-shadow: 0 1rem 3rem rgba(0,0,0,.1) !important; }
    
    .hover-scale { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-scale:hover { transform: scale(1.05); box-shadow: 0 10px 20px rgba(0,0,0,0.15) !important; }
    
    .img-hover-zoom { transition: transform 0.4s ease; }
    .img-hover-zoom:hover { transform: scale(1.08); }
    
    .hover-bg { transition: background-color 0.2s ease; }
    .hover-bg:hover:not(.btn-primary-custom) { background-color: #f1f3f5 !important; }

    .cursor-pointer { cursor: pointer; }
    .btn-like-trigger { background: none; border: none; padding: 0; outline: none; }
    
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<?php include '../templates/footer.php'; ?>