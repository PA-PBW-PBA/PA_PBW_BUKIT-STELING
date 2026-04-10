<?php
$is_admin = strpos($_SERVER['REQUEST_URI'], '/admin/') !== false;
?>

<?php if (!$is_admin) : ?>
<footer class="mt-auto footer-dark overflow-x-hidden w-100\">
    <div class="pt-5 pb-4">
        <div class="container">
            <div class="row gy-4">
                
                <div class="col-lg-5 col-md-12 pe-lg-5">
                    <h5 class="fw-bold text-white mb-3 d-flex align-items-center gap-2">Bukit Steling</h5>
                    <p class="small text-secondary text-relaxed">
                        Destinasi wisata alam yang menawarkan pemandangan menakjubkan Kota Samarinda dan hamparan Sungai Mahakam dari ketinggian. Dikelola dengan penuh dedikasi oleh Kelompok Sadar Wisata (POKDARWIS) setempat.
                    </p>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold text-white mb-3">Jelajahi</h6>
                    <ul class="list-unstyled small mb-4">
                        <li class="mb-2"><a href="beranda.php" class="text-secondary text-decoration-none custom-hover">Beranda</a></li>
                        <li class="mb-2"><a href="informasi.php" class="text-secondary text-decoration-none custom-hover">Fasilitas & Tiket</a></li>
                        <li class="mb-2"><a href="galeri.php" class="text-secondary text-decoration-none custom-hover">Galeri Foto</a></li>
                        <li class="mb-2"><a href="ulasan.php" class="text-secondary text-decoration-none custom-hover">Ulasan Pengunjung</a></li>
                    </ul>
                    <h6 class="fw-bold text-white mb-3">Ikuti Kami</h6>
                    <a href="https://www.instagram.com/puncakstelingsamarinda" target="_blank" class="text-secondary text-decoration-none custom-hover d-flex align-items-center gap-2">
                        <i class="bi bi-instagram fs-5 flex-shrink-0"></i>
                        <span>@puncakstelingsamarinda</span>
                    </a>
                </div>

                <div class="col-lg-4 col-md-6">
                    <h6 class="fw-bold text-white mb-3">Hubungi Pengelola</h6>
                    <ul class="list-unstyled small text-secondary">
                        <li class="mb-3 d-flex align-items-start gap-3">
                            <i class="bi bi-geo-alt mt-1 text-primary-custom flex-shrink-0 fs-6"></i> 
                            <span>RT 32, Kelurahan Sungai Dama, Kecamatan Samarinda Ilir, Kota Samarinda</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center gap-3">
                            <i class="bi bi-clock text-primary-custom flex-shrink-0 fs-6"></i> 
                            <span>Buka Setiap Hari (06.00 - 23.00 WITA)</span>
                        </li>
                        <li class="d-flex align-items-center gap-3">
                            <i class="bi bi-whatsapp text-primary-custom flex-shrink-0 fs-6"></i> 
                            <a href="https://wa.me/6281234567890" target="_blank" class="text-secondary text-decoration-none custom-hover">
                                <span>+62 812-3456-7890 (Bapak La Riamu)</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="py-3 footer-darker border-top-subtle">
        <div class="container text-center">
            <p class="text-secondary small mb-0">&copy; 2026 Puncak Steling Samarinda. All rights reserved.</p>
        </div>
    </div>
</footer>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.3.7/photoswipe.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/5.3.7/photoswipe-lightbox.umd.min.js"></script>
<script src="../../assets/js/script.js"></script>

<script>
    if (document.querySelector('#pswp-gallery')) {
        const lightbox = new PhotoSwipeLightbox({
            gallery: '#pswp-gallery',
            children: 'a[target="_blank"]',
            pswpModule: PhotoSwipe 
        });
        lightbox.init();
    }

    function konfirmasiHapus(event, url, pesan = "Apakah Anda yakin ingin menghapus data ini?") {
        event.preventDefault();
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: pesan,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f43f5e',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
</script>

</body>
</html>