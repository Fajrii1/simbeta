<?php
$uri = service('uri');
// Ambil segmen ke-2 dari URL (misal: dashboard, pengajuan, validasi) untuk cek menu aktif
$currentMenu = $uri->getSegment(2);
$role = session()->get('role');
?>

<div class="d-flex flex-column flex-shrink-0 p-3 sidebar-custom" style="width: 280px; min-height: 100vh;">

    <a href="<?= base_url($role . '/dashboard') ?>" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none">
        <span class="fs-3 fw-bold" style="color: #fff; letter-spacing: 1px;">SIMBETA</span>
    </a>
    
    <hr class="text-white-50">

    <ul class="nav nav-pills flex-column mb-auto">

        <li class="nav-item">
            <a href="<?= base_url($role . '/dashboard') ?>" class="nav-link <?= ($currentMenu == 'dashboard') ? 'active' : '' ?>">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        <?php if ($role == 'mahasiswa'): ?>
            <li class="nav-item">
                <a href="<?= base_url('mahasiswa/pengajuan') ?>" class="nav-link <?= ($currentMenu == 'pengajuan') ? 'active' : '' ?>">
                    📄 Ajukan Judul
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('mahasiswa/bimbingan') ?>" class="nav-link <?= ($currentMenu == 'bimbingan') ? 'active' : '' ?>">
                    📝 Bimbingan Saya
                </a>
            </li>
        <?php endif; ?>

        <?php if ($role == 'dosen'): ?>
            <li class="nav-item">
                <a href="<?= base_url('dosen/validasi') ?>" class="nav-link <?= ($currentMenu == 'validasi') ? 'active' : '' ?>">
                    ✅ Validasi Judul
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('dosen/bimbingan') ?>" class="nav-link <?= ($currentMenu == 'bimbingan') ? 'active' : '' ?>">
                    👥 Mahasiswa Bimbingan
                </a>
            </li>
        <?php endif; ?>

        <?php if ($role == 'admin'): ?>
            <li class="nav-item">
                <a href="<?= base_url('admin/users') ?>" class="nav-link <?= ($currentMenu == 'users') ? 'active' : '' ?>">
                    🗄️ Master Data User
                </a>
            </li>
            
            <li class="nav-item">
                <a href="<?= base_url('admin/plotting') ?>" class="nav-link <?= ($currentMenu == 'plotting') ? 'active' : '' ?>">
                    🎓 Plotting Dosen
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('admin/sidang') ?>" class="nav-link <?= ($currentMenu == 'sidang') ? 'active' : '' ?>">
                    ⚖️ Jadwal Sidang
                </a>
            </li>
        <?php endif; ?>

    </ul>
    
    <hr class="text-white-50">
    
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle p-2 rounded hover-effect" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: rgba(255,255,255,0.1);">
            
            <div class="flex-shrink-0">
                <?php 
                    $fotoProfile = session()->get('foto');
                    
                    // Cek apakah file ada di folder public/assets/img/profile/
                    if (!empty($fotoProfile) && file_exists('assets/img/profile/' . $fotoProfile)) {
                        $urlFoto = base_url('assets/img/profile/' . $fotoProfile);
                    } else {
                        // Jika tidak ada, pakai Avatar Generator (Inisial Nama)
                        $urlFoto = "https://ui-avatars.com/api/?name=" . urlencode(session()->get('nama')) . "&background=random&color=fff";
                    }
                ?>
                <img src="<?= $urlFoto ?>" alt="mdo" width="40" height="40" class="rounded-circle border border-2 border-white me-2" style="object-fit: cover;">
            </div>

            <div class="text-white overflow-hidden">
                <strong class="d-block text-truncate" style="max-width: 140px;"><?= session()->get('nama'); ?></strong>
                <small class="text-white-50" style="font-size: 11px; letter-spacing: 1px;">
                    <?= strtoupper($role) ?>
                </small>
            </div>
        </a>

        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1" style="background-color: var(--simbeta-raisin);">
            <li><a class="dropdown-item" href="<?= base_url('profile') ?>">Profile Saya</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item text-danger fw-bold" href="<?= base_url('logout') ?>">
                    <i class="bi bi-box-arrow-right me-2"></i> Sign out
                </a>
            </li>
        </ul>
    </div>
</div>