<?php 
session_start();
include '../../config/koneksi.php'; 
include '../templates/header.php'; 
include '../templates/navbar_public.php'; 

$query_info = mysqli_query($koneksi, "SELECT * FROM tb_informasi LIMIT 1");
$info = mysqli_fetch_assoc($query_info);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

<div id="app-tentang" class="min-vh-100 bg-white overflow-hidden">
    <section class="py-5 border-b border-light bg-light-green animate__animated animate__fadeIn">
        <div class="container py-3">
            <nav class="breadcrumb-custom mb-4">
                <a href="beranda.php" class="breadcrumb-link">Home</a>
                <i class="bi bi-chevron-right breadcrumb-separator"></i>
                <span class="breadcrumb-current">Tentang Kami</span>
            </nav>
            <h1 class="display-5 fw-bold text-dark mb-0 animate__animated animate__fadeInDown">Tentang Puncak Steling</h1>
            <p class="text-muted mt-2 mb-0 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">Mengenal lebih dekat destinasi wisata alam Kota Samarinda</p>
        </div>
    </section>

    <section class="py-5 lg:py-20">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 animate__animated animate__fadeInLeft" style="animation-delay: 0.3s;">
                    <div class="position-relative">
                        <img src="../../assets/img/fasilitas/Puncak Steling.JPG" class="img-fluid rounded-4 shadow-lg w-100 hover-up" alt="Puncak Steling View" onerror="this.src='https://via.placeholder.com/800x600?text=Indahnya+Puncak+Steling'">
                        <div class="position-absolute bottom-0 start-0 bg-primary-custom text-white p-4 rounded-end-4 d-none d-md-block shadow-lg" style="margin-bottom: -20px;">
                            <h4 class="fw-bold mb-0">{{ displayHeight }} mdpl</h4>
                            <p class="small mb-0">Ketinggian dari permukaan laut</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 px-lg-5 animate__animated animate__fadeInRight" style="animation-delay: 0.4s;">
                    <h2 class="fw-bold text-dark mb-4">Ikon Wisata Alam <br>Kota Samarinda</h2>
                    <p class="text-muted mb-4 text-relaxed">
                        Puncak Steling merupakan destinasi wisata alam yang terletak di Kelurahan Selili, Samarinda Ilir. Dikenal dengan panorama "City Light" yang memukau di malam hari dan kabut pagi yang menyegarkan, tempat ini menjadi pelarian favorit warga kota dari kebisingan rutinitas.
                    </p>
                    <p class="text-muted mb-5 text-relaxed">
                        Dikelola dengan semangat pemberdayaan masyarakat lokal, kami berkomitmen untuk menjaga kelestarian alam sambil terus meningkatkan fasilitas kenyamanan bagi para pendaki dan wisatawan.
                    </p>
                    
                    <div class="row g-4">
                        <div class="col-6" v-for="item in highlights" :key="item.title">
                            <div class="d-flex align-items-start gap-3 hover-up p-2 rounded-3">
                                <div class="text-primary-custom fs-3"><i :class="'bi ' + item.icon"></i></div>
                                <div>
                                    <h6 class="fw-bold mb-1">{{ item.title }}</h6>
                                    <p class="small text-muted mb-0">{{ item.desc }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5 animate__animated animate__fadeIn">
                <h6 class="text-primary-custom fw-bold text-uppercase small mb-2 text-letter-2px">POKDARWIS</h6>
                <h3 class="fw-bold">Struktur Organisasi Steling</h3>
                <div class="bg-primary-custom mx-auto" style="width: 60px; height: 3px;"></div>
            </div>
            
            <div class="row justify-content-center g-4">
                <div class="col-md-5" v-for="(lead, index) in leaders" :key="lead.name">
                    <div class="card card-custom border-0 shadow-sm p-4 h-100 bg-white text-center animate__animated animate__zoomIn" :style="'animation-delay: ' + (0.5 + (index * 0.1)) + 's'">
                        <div class="mb-3 text-primary-custom opacity-75">
                            <i :class="'bi ' + lead.icon + ' fs-1'"></i>
                        </div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-2">{{ lead.role }}</h6>
                        <h5 class="fw-bold text-dark mb-0">{{ lead.name }}</h5>
                    </div>
                </div>

                <div class="col-md-8 mt-4 animate__animated animate__fadeInUp" style="animation-delay: 0.7s;">
                    <div class="card card-custom border-0 shadow-sm p-5 bg-primary-custom text-white text-center hover-up position-relative overflow-hidden">
                        <div class="position-relative" style="z-index: 2;">
                            <h6 class="text-uppercase small fw-bold mb-3 opacity-75 text-letter-2px">Ketua POKDARWIS</h6>
                            <h3 class="fw-bold mb-1">La Riamu</h3>
                            <p class="mb-0 opacity-75">Memimpin dan mengarahkan pengelolaan Puncak Steling secara keseluruhan.</p>
                        </div>
                        <i class="bi bi-person-workspace position-absolute opacity-10" style="font-size: 8rem; right: -20px; bottom: -20px;"></i>
                    </div>
                </div>

                <div class="col-md-5 mt-4" v-for="(staff, index) in staffMembers" :key="staff.name">
                    <div class="card card-custom border-0 shadow-sm p-4 h-100 bg-white text-center animate__animated animate__zoomIn" :style="'animation-delay: ' + (0.8 + (index * 0.1)) + 's'">
                        <div class="mb-3 text-primary-custom opacity-75">
                            <i :class="'bi ' + staff.icon + ' fs-1'"></i>
                        </div>
                        <h6 class="text-muted small fw-bold text-uppercase mb-2">{{ staff.role }}</h6>
                        <h5 class="fw-bold text-dark mb-1">{{ staff.name }}</h5>
                        <p class="small text-muted mb-0">{{ staff.desc }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-5 p-4 rounded-4 bg-primary-custom text-white text-center shadow animate__animated animate__fadeInUp" style="animation-delay: 1s;">
                <div class="row align-items-center">
                    <div class="col-md-4 border-end border-white border-opacity-25 py-2">
                        <h6 class="text-uppercase-xs fw-bold opacity-75 mb-1">Tiket Masuk</h6>
                        <h4 class="fw-bold mb-0">Rp <?php echo number_format($info['harga_tiket'], 0, ',', '.'); ?></h4>
                    </div>
                    <div class="col-md-4 border-end border-white border-opacity-25 py-2">
                        <h6 class="text-uppercase-xs fw-bold opacity-75 mb-1">Jam Operasional</h6>
                        <h4 class="fw-bold mb-0"><?php echo date('H:i', strtotime($info['jam_buka'])); ?> - <?php echo date('H:i', strtotime($info['jam_tutup'])); ?> WITA</h4>
                    </div>
                    <div class="col-md-4 py-2">
                        <h6 class="text-uppercase-xs fw-bold opacity-75 mb-1">Status</h6>
                        <h4 class="fw-bold mb-0">Buka Setiap Hari</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    const { createApp } = Vue;
    createApp({
        data() {
            return {
                targetHeight: 150,
                displayHeight: 0,
                highlights: [
                    { title: 'Lokasi Strategis', desc: 'Akses mudah dari pusat kota Samarinda.', icon: 'bi-geo-alt' },
                    { title: 'Spot Estetik', desc: 'Beragam titik foto dengan latar panorama.', icon: 'bi-camera' }
                ],
                leaders: [
                    { role: 'Pembina', name: 'Camat Samarinda Ilir', icon: 'bi-person-check-fill' },
                    { role: 'Penasehat', name: 'Lurah Sungai Dama', icon: 'bi-shield-check' }
                ],
                staffMembers: [
                    { role: 'Sekretaris', name: 'Hesni Kilo', desc: 'Administrasi & Kesekretariatan', icon: 'bi-journal-text' },
                    { role: 'Bendahara', name: 'Wa Ice', desc: 'Pengelolaan Keuangan & Anggaran', icon: 'bi-wallet2' }
                ]
            }
        },
        mounted() {
            this.animateHeight();
        },
        methods: {
            animateHeight() {
                let current = 0;
                const duration = 1500; // 1.5 detik
                const stepTime = 20;
                const increment = this.targetHeight / (duration / stepTime);
                
                const interval = setInterval(() => {
                    current += increment;
                    if (current >= this.targetHeight) {
                        this.displayHeight = this.targetHeight;
                        clearInterval(interval);
                    } else {
                        this.displayHeight = Math.floor(current);
                    }
                }, stepTime);
            }
        }
    }).mount('#app-tentang');
</script>

<?php include '../templates/footer.php'; ?>