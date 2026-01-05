<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2 style="color: var(--simbeta-raisin);">Mahasiswa Bimbingan</h2>
        <p class="text-muted">Daftar mahasiswa yang sedang Anda bimbing.</p>
    </div>
</div>

<div class="row">
    <?php if (empty($mahasiswa)): ?>
        <div class="col-12">
            <div class="alert alert-info">Belum ada mahasiswa yang di-plotting ke Anda.</div>
        </div>
    <?php else: ?>
        <?php foreach ($mahasiswa as $mhs): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <img src="https://via.placeholder.com/100" class="rounded-circle mb-3" alt="Foto">
                        <h5 class="fw-bold mb-1" style="color: var(--simbeta-raisin);"><?= esc($mhs['nama_mhs']) ?></h5>
                        <p class="text-muted small"><?= esc($mhs['nim']) ?></p>

                        <div class="text-start bg-light p-3 rounded mb-3">
                            <small class="text-muted fw-bold">Judul:</small>
                            <p class="mb-0 small line-clamp-2">
                                <?= esc($mhs['judul_usulan']) ?>
                            </p>
                        </div>

                        <a href="<?= base_url('dosen/bimbingan/detail/' . $mhs['id']) ?>" class="btn btn-primary bg-theme-blush border-0 w-100">
                            <i class="bi bi-chat-text-fill me-2"></i> Buka Logbook
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>