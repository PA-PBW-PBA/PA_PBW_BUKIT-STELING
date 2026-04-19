<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary-custom d-flex align-items-center gap-2" href="beranda.php">
            <i class="bi bi-mountain"></i> Puncak Steling
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto text-uppercase small fw-bold ls-normal">
                <li class="nav-item"><a class="nav-link px-3" href="beranda.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="informasi.php">Informasi</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="galeri.php">Galeri</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="ulasan.php">Ulasan</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="tentang.php">Tentang</a></li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <?php if (isset($_SESSION['login'])) : ?>
                    <div class="dropdown">
                        <a class="btn btn-primary-custom rounded-pill px-4 dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> 
                            <span>
                                <?php 
                                    $nama = explode(' ', $_SESSION['user'])[0]; 
                                    echo ($_SESSION['role'] === 'admin') ? $nama . " (Admin)" : $nama;
                                ?>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 mt-3 py-2">
                            <?php if ($_SESSION['role'] === 'admin') : ?>
                                <li>
                                    <a class="dropdown-item py-2 fw-bold text-primary-custom" href="../admin/dashboard.php">
                                        <i class="bi bi-speedometer2 me-2"></i> Dashboard Admin
                                    </a>
                                </li>
                            <?php else : ?>
                                <li>
                                    <a class="dropdown-item py-2 fw-bold text-primary-custom" href="profil.php">
                                        <i class="bi bi-person-badge me-2"></i> Profil Saya
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <li><hr class="dropdown-divider mx-2"></li>
                            
                            <li>
                                <a class="dropdown-item py-2 text-danger fw-bold" href="../auth/logout.php">
                                    <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php else : ?>
                    <a href="../auth/login.php" class="text-decoration-none text-dark fw-bold small px-3">Masuk</a>
                    <a href="../auth/register.php" class="btn btn-primary-custom rounded-pill px-4 shadow-sm fw-bold">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>