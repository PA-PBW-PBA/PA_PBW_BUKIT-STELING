<?php 
include '../../config/koneksi.php'; 
include '../templates/header.php'; 
include '../templates/navbar_public.php'; 

$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_informasi LIMIT 1"));
?>

<div class="min-vh-100 bg-white">
    <section class="py-5 border-b border-light" style="background-color: #F0FAF5;">
        <div class="container py-3">
            <nav class="d-flex align-items-center gap-2 mb-4 text-muted small">
                <a href="beranda.php" class="text-decoration-none text-muted">Home</a>
                <i class="bi bi-chevron-right" style="font-size: 0.7rem;"></i>
                <span class="text-dark fw-bold">Informasi</span>
            </nav>
            <h1 class="display-5 fw-bold text-dark mb-0">Informasi Kunjungan</h1>
            <p class="text-muted mt-2 mb-0">Persiapkan perjalananmu ke Puncak Steling</p>
        </div>
    </section>

    <div class="container py-5">
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card card-custom p-5 bg-white shadow-sm border-0 h-100">
                    <h6 class="text-muted text-uppercase fw-bold mb-4" style="font-size: 0.75rem; letter-spacing: 1px;">Tiket & Jam Operasional</h6>
                    <div class="mb-4">
                        <h1 class="fw-800 text-primary-custom mb-1">Rp <?php echo number_format($data['harga_tiket'], 0, ',', '.'); ?></h1>
                        <p class="text-muted">Biaya retribusi per orang</p>
                    </div>
                    <div class="d-flex align-items-center gap-3 bg-light p-3 rounded-4">
                        <i class="bi bi-calendar-check fs-4 text-primary-custom"></i>
                        <div>
                            <p class="mb-0 fw-bold"><?php echo substr($data['jam_buka'], 0, 5); ?> - <?php echo substr($data['jam_tutup'], 0, 5); ?> WITA</p>
                            <p class="mb-0 small text-muted">Operasional Setiap Hari</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-custom p-5 bg-primary-custom text-white shadow-sm border-0 h-100">
                    <h6 class="text-white-50 text-uppercase fw-bold mb-4" style="font-size: 0.75rem; letter-spacing: 1px;">Deskripsi Kawasan</h6>
                    <p class="fs-5" style="line-height: 1.8; opacity: 0.9;">
                        <?php echo $data['deskripsi']; ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="row gy-5 align-items-center mb-5">
            <div class="col-lg-5">
                <h6 class="text-primary-custom fw-bold text-uppercase small mb-3" style="letter-spacing: 2px;">Lokasi Objek Wisata</h6>
                <h2 class="fw-bold text-dark mb-4">Titik Kumpul & Jalur Pendakian</h2>
                <div class="d-flex gap-3 mb-3">
                    <i class="bi bi-geo-alt-fill text-primary-custom fs-4"></i>
                    <p class="text-muted mb-0">Selili, Kec. Samarinda Ilir, Kota Samarinda, Kalimantan Timur 75251</p>
                </div>
                <div class="d-flex gap-3 mb-4">
                    <i class="bi bi-info-circle-fill text-primary-custom fs-4"></i>
                    <p class="text-muted mb-0">Gunakan peta di samping untuk melihat ulasan, foto terbaru, dan detail lokasi langsung dari Google Maps.</p>
                </div>
                <a href="https://www.google.com/maps/search/?api=1&query=Puncak+Steling+Samarinda&query_place_id=ChIJd8TuR1l_9i0RWUL2OPYPubI" target="_blank" class="btn btn-primary-custom rounded-pill px-5 py-3 fw-bold shadow">
                    <i class="bi bi-cursor-fill me-2"></i> Buka di Aplikasi Maps
                </a>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 shadow-lg overflow-hidden rounded-4">
                    <iframe 
                        src="https://www.google.com/maps?q=-0.5093,117.1618&output=embed"" 
                        width="100%" 
                        height="500" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>

        <h4 class="fw-bold mb-4 pt-4">Fasilitas yang Tersedia</h4>
        <div class="row g-4">
            <?php
            $fasilitas = mysqli_query($koneksi, "SELECT * FROM tb_fasilitas");
            while($f = mysqli_fetch_assoc($fasilitas)) :
            ?>
            <div class="col-md-3 col-6">
                <div class="card card-custom overflow-hidden shadow-sm border-0 bg-white cursor-pointer" 
                     data-bs-toggle="modal" 
                     data-bs-target="#modalFasilitas<?php echo $f['id_fasilitas']; ?>">
                    <div class="ratio ratio-4x3 overflow-hidden">
                        <img src="../../assets/img/fasilitas/<?php echo $f['file_gambar']; ?>" class="object-fit-cover hover-zoom" alt="img">
                    </div>
                    <div class="card-body p-3 text-center">
                        <p class="mb-0 fw-bold small text-uppercase" style="letter-spacing: 0.5px;"><?php echo $f['nama_fasilitas']; ?></p>
                    </div>
                </div>

                <div class="modal fade" id="modalFasilitas<?php echo $f['id_fasilitas']; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content rounded-4 border-0 overflow-hidden">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="fw-bold mb-0"><?php echo $f['nama_fasilitas']; ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4 text-center">
                                <img src="../../assets/img/fasilitas/<?php echo $f['file_gambar']; ?>" class="img-fluid rounded-4 shadow-sm" alt="Fasilitas">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>