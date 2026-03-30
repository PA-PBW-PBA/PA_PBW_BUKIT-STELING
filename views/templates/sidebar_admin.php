<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
<div class="col-md-2 bg-dark vh-100 sticky-top d-none d-md-block p-4 shadow">
    <div class="mb-4 d-flex align-items-center gap-2">
        <div class="bg-primary-custom rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
            <i class="bi bi-mountain text-white"></i>
        </div>
        <h5 class="text-white fw-bold mb-0 small">Admin Panel</h5>
    </div>

    <div class="mb-4">
        <a href="../public/beranda.php" target="_blank" class="btn btn-outline-light btn-sm w-100 rounded-pill py-2 d-flex align-items-center justify-content-center gap-2 opacity-75 hover-opacity-100" style="font-size: 0.75rem; letter-spacing: 0.5px;">
            <i class="bi bi-globe"></i> LIHAT WEBSITE
        </a>
    </div>
    
    <ul class="nav flex-column gap-2">
        <li class="nav-item text-muted small fw-bold mb-1 ps-2" style="font-size: 0.65rem; letter-spacing: 1px;">MENU UTAMA</li>
        
        <li class="nav-item">
            <a class="nav-link rounded-3 px-3 py-2 <?php echo ($current_page == 'dashboard.php') ? 'text-white bg-primary-custom' : 'text-secondary'; ?>" href="dashboard.php">
                <i class="bi bi-grid-1x2-fill me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-3 px-3 py-2 <?php echo ($current_page == 'kelola_informasi.php') ? 'text-white bg-primary-custom' : 'text-secondary'; ?>" href="kelola_informasi.php">
                <i class="bi bi-info-circle-fill me-2"></i> Info & Tiket
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-3 px-3 py-2 <?php echo ($current_page == 'kelola_galeri.php') ? 'text-white bg-primary-custom' : 'text-secondary'; ?>" href="kelola_galeri.php">
                <i class="bi bi-images me-2"></i> Moderasi Galeri
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-3 px-3 py-2 <?php echo ($current_page == 'kelola_ulasan.php') ? 'text-white bg-primary-custom' : 'text-secondary'; ?>" href="kelola_ulasan.php">
                <i class="bi bi-chat-square-text-fill me-2"></i> Kelola Ulasan
            </a>
        </li>

        <hr class="border-secondary opacity-25 my-4">
        
        <li class="nav-item">
            <a class="nav-link text-danger px-3 py-2 fw-bold small" href="../auth/logout.php" onclick="return confirm('Yakin ingin keluar?')">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
        </li>
    </ul>
</div>

<style>
    .hover-opacity-100:hover { opacity: 1 !important; background-color: rgba(255,255,255,0.1); transition: 0.3s; }
    .nav-link { font-size: 0.9rem; transition: 0.3s; }
    .nav-link:hover:not(.bg-primary-custom) { color: white !important; background-color: rgba(255,255,255,0.05); }
</style>