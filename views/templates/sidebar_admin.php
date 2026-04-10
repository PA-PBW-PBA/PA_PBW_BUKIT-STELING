<?php $current_page = basename($_SERVER['PHP_SELF']); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<nav class="navbar navbar-expand-lg d-md-none p-3 shadow-sm sticky-top sidebar-admin-dark z-1040">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-2 text-white">
            <div class="bg-primary-custom rounded-2 d-flex align-items-center justify-content-center shadow-sm sidebar-icon-sm">
                <i class="bi bi-mountain text-white"></i>
            </div>
            <h6 class="mb-0 fw-bold sidebar-brand-size">STELING ADMIN</h6>
        </div>
        <button class="navbar-toggler border-0 text-white p-0" type="button" id="sidebarToggleBtn">
            <i class="bi bi-list fs-1"></i>
        </button>
    </div>
</nav>

<div class="offcanvas offcanvas-start d-md-none border-0 sidebar-offcanvas-width sidebar-admin-dark z-1050" tabindex="-1" id="adminSidebarMobile">
    <div class="offcanvas-header border-bottom border-secondary border-opacity-25 p-4">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary-custom rounded-3 d-flex align-items-center justify-content-center shadow-sm sidebar-icon-md">
                <i class="bi bi-mountain text-white"></i>
            </div>
            <h5 class="offcanvas-title text-white fw-bold mb-0">Menu Admin</h5>
        </div>
        <button type="button" class="btn-close btn-close-white shadow-none" id="sidebarCloseBtn"></button>
    </div>
    <div class="offcanvas-body p-4 custom-scrollbar">
        <div class="mb-4">
            <a href="../public/beranda.php" class="btn w-100 rounded-3 py-2 d-flex align-items-center justify-content-center gap-2 text-decoration-none text-white border border-secondary border-opacity-25 sidebar-btn-glass">
                <i class="bi bi-arrow-up-right-square"></i> <span class="font-link-sm">Lihat Website</span>
            </a>
        </div>
        <ul class="nav flex-column gap-1">
            <li class="nav-item small fw-bold mb-2 ps-2 sidebar-nav-label">MAIN NAVIGATION</li>
            <li class="nav-item">
                <a class="nav-link admin-nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php">
                    <i class="bi bi-grid-1x2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link admin-nav-link <?php echo ($current_page == 'statistik.php') ? 'active' : ''; ?>" href="statistik.php">
                    <i class="bi bi-bar-chart-line"></i> Analisis Wisata
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
            <li class="nav-item small fw-bold mt-4 mb-2 ps-2 sidebar-nav-label">SISTEM</li>
            <li class="nav-item">
                <a class="nav-link logout-btn px-3 py-2 rounded-3 d-flex align-items-center gap-3 cursor-pointer" onclick="handleLogout()">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="col-md-2 d-none d-md-block p-0 shadow-lg sidebar-admin-dark sidebar-border-right">
    <div class="sticky-top overflow-y-auto custom-scrollbar sidebar-admin-full">
        <div class="p-4 d-flex flex-column sidebar-nav-min">
            <div class="mb-4 d-flex align-items-center gap-3 mt-2">
                <div class="bg-primary-custom rounded-3 d-flex align-items-center justify-content-center shadow-sm sidebar-icon-lg">
                    <i class="bi bi-mountain text-white fs-5"></i>
                </div>
                <div>
                    <h6 class="text-white fw-bold mb-0 ls-tight">STELING</h6>
                    <span class="fw-medium sidebar-xxs-label">Admin Panel</span>
                </div>
            </div>

            <div class="mb-4">
                <a href="../public/beranda.php" class="btn w-100 rounded-3 py-2 d-flex align-items-center justify-content-center gap-2 text-decoration-none text-white border border-secondary border-opacity-25 sidebar-btn-glass">
                    <i class="bi bi-arrow-up-right-square"></i> <span class="font-link-sm">Lihat Website</span>
                </a>
            </div>

            <ul class="nav flex-column gap-1 pb-2">
                <li class="nav-item small fw-bold mb-2 ps-2 sidebar-nav-label">MAIN NAVIGATION</li>
                <li class="nav-item">
                    <a class="nav-link admin-nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php">
                        <i class="bi bi-grid-1x2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link admin-nav-link <?php echo ($current_page == 'statistik.php') ? 'active' : ''; ?>" href="statistik.php">
                        <i class="bi bi-bar-chart-line"></i> Analisis Wisata
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
                <li class="nav-item small fw-bold mt-4 mb-2 ps-2 sidebar-nav-label">SISTEM</li>
                <li class="nav-item">
                    <a class="nav-link logout-btn px-3 py-2 rounded-3 d-flex align-items-center gap-3 cursor-pointer" onclick="handleLogout()">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
function handleLogout() {
    Swal.fire({
        title: 'Konfirmasi Keluar',
        text: "Apakah Anda yakin ingin mengakhiri sesi ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#79AE6F',
        cancelButtonColor: '#f43f5e',
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../auth/logout.php';
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('sidebarToggleBtn');
    const closeBtn = document.getElementById('sidebarCloseBtn');
    const sidebarEl = document.getElementById('adminSidebarMobile');
    
    if (sidebarEl && toggleBtn) {
        function openSidebar() {
            sidebarEl.classList.add('show');
            sidebarEl.style.visibility = 'visible';
            document.body.style.overflow = 'hidden';

            if (!document.getElementById('sidebarBackdrop')) {
                const backdrop = document.createElement('div');
                backdrop.id = 'sidebarBackdrop';
                backdrop.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1040;backdrop-filter:blur(4px);';
                backdrop.addEventListener('click', closeSidebar);
                document.body.appendChild(backdrop);
            }
        }

        function closeSidebar() {
            sidebarEl.classList.remove('show');
            sidebarEl.style.visibility = '';
            document.body.style.overflow = '';
            const backdrop = document.getElementById('sidebarBackdrop');
            if (backdrop) backdrop.remove();
        }

        toggleBtn.addEventListener('click', openSidebar);
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    }
});
</script>