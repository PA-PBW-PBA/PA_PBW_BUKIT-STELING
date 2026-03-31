<?php $current_page = basename($_SERVER['PHP_SELF']); ?>

<!-- Mobile Navbar buat Admin -->
<nav class="navbar navbar-expand-lg d-md-none p-3 shadow-sm sticky-top" style="background-color: #0f172a;">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-2 text-white">
            <div class="bg-primary-custom rounded-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 32px; height: 32px;">
                <i class="bi bi-mountain text-white"></i>
            </div>
            <h6 class="mb-0 fw-bold" style="letter-spacing: 0.5px;">STELING ADMIN</h6>
        </div>
        <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebarMobile">
            <i class="bi bi-list fs-1"></i>
        </button>
    </div>
</nav>

<!-- Mobile Sidebar buat Admin -->
<div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="adminSidebarMobile" style="background-color: #0f172a; width: 280px;">
    <div class="offcanvas-header border-bottom border-secondary-subtle">
        <h5 class="offcanvas-title text-white fw-bold">Admin Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-4">
        <ul class="nav flex-column gap-1">
            <?php renderNavLinks($current_page); ?>
        </ul>
    </div>
</div>

<!-- Desktop Sidebar -->
<div class="col-md-2 vh-100 sticky-top d-none d-md-block p-0 shadow-lg" style="background-color: #0f172a; border-right: 1px solid rgba(255,255,255,0.05);">
    <div class="p-4">
        <div class="mb-5 d-flex align-items-center gap-3">
            <div class="bg-primary-custom rounded-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                <i class="bi bi-mountain text-white fs-5"></i>
            </div>
            <div>
                <h6 class="text-white fw-bold mb-0" style="letter-spacing: 0.5px;">STELING</h6>
                <span class="text-muted fw-medium" style="font-size: 0.65rem; text-transform: uppercase;">Admin Panel</span>
            </div>
        </div>

        <div class="mb-5">
            <a href="../public/beranda.php" target="_blank" class="btn btn-glass-sidebar w-100 rounded-3 py-2 d-flex align-items-center justify-content-center gap-2 text-decoration-none">
                <i class="bi bi-arrow-up-right-square"></i> <span style="font-size: 0.8rem; font-weight: 600;">Lihat Website</span>
            </a>
        </div>
        
        <ul class="nav flex-column gap-1">
            <?php renderNavLinks($current_page); ?>
        </ul>
    </div>
</div>

<?php
function renderNavLinks($current_page) {
?>
    <li class="nav-item text-muted small fw-bold mb-2 ps-2" style="font-size: 0.65rem; letter-spacing: 1.5px; opacity: 0.5;">MAIN NAVIGATION</li>
    
    <li class="nav-item">
        <a class="nav-link admin-nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link admin-nav-link <?php echo ($current_page == 'kelola_informasi.php') ? 'active' : ''; ?>" href="kelola_informasi.php">
            <i class="bi bi-info-circle"></i> Info & Tiket
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link admin-nav-link <?php echo ($current_page == 'kelola_fasilitas.php') ? 'active' : ''; ?>" href="kelola_fasilitas.php">
            <i class="bi bi-house-door"></i> Kelola Fasilitas
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link admin-nav-link <?php echo ($current_page == 'kelola_galeri.php') ? 'active' : ''; ?>" href="kelola_galeri.php">
            <i class="bi bi-images"></i> Moderasi Galeri
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link admin-nav-link <?php echo ($current_page == 'kelola_ulasan.php') ? 'active' : ''; ?>" href="kelola_ulasan.php">
            <i class="bi bi-chat-square-text"></i> Kelola Ulasan
        </a>
    </li>

    <li class="nav-item text-muted small fw-bold mt-4 mb-2 ps-2" style="font-size: 0.65rem; letter-spacing: 1.5px; opacity: 0.5;">SISTEM</li>
    
    <li class="nav-item">
        <a class="nav-link text-danger-custom px-3 py-2 rounded-3 d-flex align-items-center gap-3 transition-all cursor-pointer" onclick="handleLogout()">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </li>
<?php } ?>

<script>
function handleLogout() {
    Swal.fire({
        title: 'Konfirmasi Keluar',
        text: "Apakah Anda yakin ingin mengakhiri sesi ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#f43f5e',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../auth/logout.php';
        }
    })
}
</script>

<style>
    .btn-glass-sidebar {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.8);
        transition: all 0.3s ease;
    }
    .btn-glass-sidebar:hover {
        background: rgba(255, 255, 255, 0.08);
        color: white;
        border-color: rgba(255, 255, 255, 0.2);
    }
    .admin-nav-link {
        font-size: 0.875rem;
        font-weight: 500;
        color: #94a3b8 !important;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px !important;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .admin-nav-link:hover {
        color: white !important;
        background: rgba(255, 255, 255, 0.05);
        transform: translateX(4px);
    }
    .admin-nav-link.active {
        color: white !important;
        background: var(--primary) !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
    }
    .admin-nav-link i {
        font-size: 1.1rem;
    }
    .text-danger-custom {
        color: #f43f5e;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
    }
    .text-danger-custom:hover {
        background: rgba(244, 63, 94, 0.1);
        color: #fb7185;
    }
    .transition-all {
        transition: all 0.3s ease;
    }
</style>