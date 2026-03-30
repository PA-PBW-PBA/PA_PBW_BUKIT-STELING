<?php 
include '../../config/koneksi.php'; 
include '../templates/header.php'; 
include '../templates/navbar_public.php'; 

$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_informasi LIMIT 1"));
?>

<div class="min-h-screen bg-white">
    <section class="py-10 lg:py-14 border-b border-light" style="background-color: #F0FAF5;">
        <div class="container py-4">
            <nav class="d-flex align-items-center gap-2 mb-4 text-muted small">
                <a href="beranda.php" class="text-decoration-none text-muted hover-primary">Home</a>
                <i class="bi bi-chevron-right" style="font-size: 0.7rem;"></i>
                <span class="text-dark fw-bold">Informasi</span>
            </nav>
            <h1 class="display-5 fw-bold text-dark">Informasi Kunjungan</h1>
            <p class="text-muted mt-2">Persiapkan perjalananmu ke Puncak Steling</p>
        </div>
    </section>

    <div class="container py-5">
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card card-custom p-5 bg-white shadow-sm border-0">
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

        <h4 class="fw-bold mb-4">Fasilitas yang Tersedia</h4>
        <div class="row g-4">
            <?php
            $fasilitas = mysqli_query($koneksi, "SELECT * FROM tb_fasilitas");
            while($f = mysqli_fetch_assoc($fasilitas)) :
            ?>
            <div class="col-md-3 col-6">
                <div class="card card-custom overflow-hidden shadow-sm border-0 bg-white">
                    <div class="ratio ratio-4x3">
                        <img src="../../assets/img/fasilitas/<?php echo $f['file_gambar']; ?>" class="object-fit-cover" alt="img">
                    </div>
                    <div class="card-body p-3 text-center">
                        <p class="mb-0 fw-bold small text-uppercase" style="letter-spacing: 0.5px;"><?php echo $f['nama_fasilitas']; ?></p>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>