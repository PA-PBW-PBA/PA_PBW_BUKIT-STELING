<?php 
session_start();
include '../../config/koneksi.php'; 

$query = mysqli_query($koneksi, "SELECT tb_galeri.*, tb_pengunjung.nama_lengkap FROM tb_galeri JOIN tb_pengunjung ON tb_galeri.id_pengunjung = tb_pengunjung.id_pengunjung WHERE status = 'approved' ORDER BY id_galeri DESC");
$data_galeri = [];
while($row = mysqli_fetch_assoc($query)) { 
    $data_galeri[] = $row; 
}

include '../templates/header.php'; 
include '../templates/navbar_public.php'; 
?>

<div id="app-galeri" class="min-vh-100 bg-white">
    <section class="py-5 border-b border-light" style="background-color: #F0FAF5;">
        <div class="container py-3">
            <nav class="d-flex align-items-center gap-2 mb-4 text-muted small">
                <a href="beranda.php" class="text-decoration-none text-muted">Home</a>
                <i class="bi bi-chevron-right" style="font-size: 0.7rem;"></i>
                <span class="text-dark fw-bold">Galeri</span>
            </nav>
            <h1 class="display-5 fw-bold text-dark mb-0">Galeri Puncak Steling</h1>
            <p class="text-muted mt-2 mb-0">Keindahan Samarinda dalam setiap jepretan.</p>
        </div>
    </section>

    <div class="container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4 mb-5">
            <div class="d-flex overflow-auto pb-2 gap-1">
                <button v-for="cat in categories" :key="cat" @click="activeCategory = cat"
                    class="btn border-0 px-4 py-2 small transition-all border-bottom border-2 text-nowrap shadow-none"
                    :class="activeCategory === cat ? 'border-primary-custom text-primary-custom fw-bold' : 'border-transparent text-muted'">
                    {{ cat }}
                </button>
            </div>
            
            <?php if (isset($_SESSION['login']) && $_SESSION['role'] === 'pengunjung') : ?>
                <a href="unggah_foto.php" class="btn btn-outline-primary-custom rounded-pill px-4 py-2 shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="bi bi-upload"></i> <span class="fw-bold small text-uppercase">Upload Foto</span>
                </a>
            <?php elseif (isset($_SESSION['login']) && $_SESSION['role'] === 'admin') : ?>
                <a href="../admin/kelola_galeri.php" class="btn btn-primary-custom rounded-pill px-4 py-2 shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="bi bi-gear-fill"></i> <span class="fw-bold small text-uppercase">Kelola Galeri</span>
                </a>
            <?php else : ?>
                <a href="javascript:void(0)" onclick="alertLogin()" class="btn btn-outline-primary-custom rounded-pill px-4 py-2 shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="bi bi-lock-fill"></i> <span class="fw-bold small text-uppercase">Upload Foto</span>
                </a>
            <?php endif; ?>
        </div>

        <transition-group name="fade-list" tag="div" class="row g-4">
            <div class="col-md-4 col-sm-6" v-for="g in paginatedGaleri" :key="g.id_galeri">
                <div class="card card-custom h-100 bg-white border-0 shadow-sm overflow-hidden group">
                    <div class="ratio ratio-4x3 overflow-hidden cursor-pointer" 
                         data-bs-toggle="modal" 
                         :data-bs-target="'#imageModal' + g.id_galeri">
                        <img :src="'../../assets/img/uploads/' + g.file_foto" class="card-img-top object-fit-cover transition-all">
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <span class="badge rounded-pill text-primary-custom px-3 py-2 small fw-medium" style="background-color: #F0FAF5;">{{ g.kategori }}</span>
                            <small class="text-muted small">{{ g.tanggal_upload }}</small>
                        </div>
                        <p class="card-text text-dark fw-medium mb-3">"{{ g.caption }}"</p>
                        <hr class="border-light my-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="bi bi-person text-secondary"></i></div>
                            <div><p class="mb-0 fw-bold text-dark small">Pengunggah</p><span class="text-muted small">{{ g.nama_lengkap }}</span></div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" :id="'imageModal' + g.id_galeri" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-fullscreen-mobile">
                        <div class="modal-content bg-transparent border-0 shadow-none position-relative modal-content-fullscreen">
                            <button type="button" 
                                    class="btn-close btn-close-white position-absolute rounded-circle btn-close-custom" 
                                    data-bs-dismiss="modal" 
                                    aria-label="Close"
                                    style="top: 20px; right: 20px; z-index: 1070;">
                            </button>
                            <div class="modal-body p-0 text-center overflow-hidden rounded-4 bg-black d-flex align-items-center justify-content-center shadow-lg">
                                <img :src="'../../assets/img/uploads/' + g.file_foto" class="img-fluid w-100 h-100 object-fit-contain" alt="Fullscreen">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition-group>

        <div class="d-flex justify-content-center mt-5" v-if="totalPages > 1">
            <nav>
                <ul class="pagination gap-2 border-0">
                    <li class="page-item" :class="{ disabled: currentPage === 1 }">
                        <button class="page-link rounded-3 border-0 bg-light text-dark px-3 py-2 fw-bold shadow-none" @click="currentPage--">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                    </li>
                    <li class="page-item" v-for="page in totalPages" :key="page">
                        <button class="page-link rounded-3 border-0 px-3 py-2 fw-bold shadow-none" 
                                :class="currentPage === page ? 'bg-primary-custom text-white shadow' : 'bg-light text-dark'"
                                @click="currentPage = page">
                            {{ page }}
                        </button>
                    </li>
                    <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                        <button class="page-link rounded-3 border-0 bg-light text-dark px-3 py-2 fw-bold shadow-none" @click="currentPage++">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.dataGaleri = <?php echo json_encode($data_galeri); ?>;

    function alertLogin() {
        Swal.fire({
            title: 'Akses Terbatas',
            text: "Silakan login sebagai pengunjung terlebih dahulu untuk mengunggah foto momen kamu.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: 'var(--primary) !important',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Masuk Sekarang',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../auth/login.php';
            }
        })
    }
</script>

<style>
.fade-list-enter-active, .fade-list-leave-active { transition: all 0.4s ease; }
.fade-list-enter-from, .fade-list-leave-to { opacity: 0; transform: translateY(20px); }
.fade-list-move { transition: transform 0.4s ease; }
.btn-outline-primary-custom { color: var(--primary) !important; border-color: var(--primary) !important; }
.btn-outline-primary-custom:hover { background-color: var(--primary) !important; color: white !important; }
.text-primary-custom { color: var(--primary) !important; }
.border-primary-custom { border-color: var(--primary) !important; }
.bg-primary-custom { background-color: var(--primary) !important; }
</style>

<?php include '../templates/footer.php'; ?>