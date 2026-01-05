<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2 style="color: var(--simbeta-raisin);">Dashboard Dosen</h2>
        <p class="text-muted">Selamat datang, Bapak/Ibu Dosen. Berikut ringkasan aktivitas akademik Anda.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-theme-blush fw-bold text-white">
                <i class="bi bi-check-circle me-2"></i> Validasi Judul Masuk
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="fw-bold mb-0" style="color: var(--simbeta-raisin);">
                            <?= $total_pending ?>
                        </h2>
                        <span class="text-muted small">Mahasiswa Menunggu</span>
                    </div>
                    <div class="fs-1" style="color: var(--simbeta-orchid);">
                        📝
                    </div>
                </div>
                <hr style="opacity: 0.1;">
                <a href="<?= base_url('dosen/validasi') ?>" class="btn btn-sm btn-primary bg-theme-raisin border-0 w-100">
                    Cek Pengajuan
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-theme-raisin fw-bold text-white">
                <i class="bi bi-people me-2"></i> Mahasiswa Bimbingan
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="fw-bold mb-0" style="color: var(--simbeta-blush);">
                            <?= $total_bimbingan ?>
                        </h2>
                        <span class="text-muted small">Mahasiswa Aktif</span>
                    </div>
                    <div class="fs-1" style="color: var(--simbeta-orchid);">
                        🎓
                    </div>
                </div>
                <hr style="opacity: 0.1;">
                <a href="<?= base_url('dosen/bimbingan') ?>" class="btn btn-outline-dark btn-sm w-100">
                    Lihat Progres
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-theme-raisin fw-bold text-white">
                <i class="bi bi-calendar-event me-2"></i> Jadwal Menguji
            </div>
            <div class="card-body">
                <?php if($next_sidang): ?>
                    <p class="text-muted small mb-2">Jadwal Terdekat:</p>
                    <div class="alert alert-light border text-center p-2 mb-0" style="color: var(--simbeta-raisin);">
                        <strong class="d-block text-truncate"><?= esc($next_sidang['nama_mhs']) ?></strong>
                        <small class="text-muted">
                            <?= date('d M, H:i', strtotime($next_sidang['tanggal_sidang'])) ?> WIB
                            <br>
                            Ruang: <?= esc($next_sidang['ruangan']) ?>
                        </small>
                    </div>
                <?php else: ?>
                    <p class="text-muted small mb-3">Tidak ada jadwal sidang dalam waktu dekat.</p>
                    <div class="alert alert-light border text-center p-2 mb-0 text-muted">
                        <small>Sidang Berikutnya: <strong>-</strong></small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>