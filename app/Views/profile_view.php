<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2 style="color: var(--simbeta-raisin);">Profil Saya</h2>
        <p class="text-muted">Kelola informasi akun dan foto profil Anda.</p>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <?php foreach(session()->getFlashdata('errors') as $error): ?>
            <li><?= $error ?></li>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-theme-raisin text-white fw-bold">
                <i class="bi bi-person-circle me-2"></i> Ganti Foto Profil
            </div>
            <div class="card-body text-center p-4">
                
                <?php 
                    $foto = session()->get('foto');
                    if (!empty($foto) && file_exists('assets/img/profile/' . $foto)) {
                        $url = base_url('assets/img/profile/' . $foto);
                    } else {
                        $url = "https://ui-avatars.com/api/?name=" . urlencode(session()->get('nama')) . "&size=128";
                    }
                ?>
                <img src="<?= $url ?>" class="rounded-circle mb-3 border border-3 border-light shadow" width="150" height="150" style="object-fit: cover;">
                
                <h5 class="fw-bold"><?= session()->get('nama') ?></h5>
                <p class="text-muted"><?= strtoupper(session()->get('role')) ?></p>

                <hr>

                <form action="<?= base_url('profile/update') ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3 text-start">
                        <label class="form-label small fw-bold">Pilih Foto Baru (Max 2MB)</label>
                        <input type="file" name="foto_profil" class="form-control" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary bg-theme-blush border-0 w-100">
                        <i class="bi bi-cloud-upload"></i> Upload Foto Baru
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title fw-bold mb-4">Detail Akun</h5>
                <div class="mb-3">
                    <label class="form-label text-muted">Nama Lengkap</label>
                    <input type="text" class="form-control" value="<?= session()->get('nama') ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Username / NIM / NIP</label>
                    <input type="text" class="form-control" value="<?= session()->get('username') ?>" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Role</label>
                    <input type="text" class="form-control" value="<?= strtoupper(session()->get('role')) ?>" readonly>
                </div>
                <div class="alert alert-info small">
                    <i class="bi bi-info-circle"></i> Untuk mengubah nama atau password, silakan hubungi Administrator.
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>