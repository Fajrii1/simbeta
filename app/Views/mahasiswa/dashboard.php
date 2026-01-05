<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Berhasil!</strong> <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('msg')): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Info:</strong> <?= session()->getFlashdata('msg') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2 style="color: var(--simbeta-raisin);">Dashboard Mahasiswa</h2>
        <p class="text-muted">Selamat datang di Sistem Informasi Manajemen Tugas Akhir.</p>
    </div>
</div>

<div class="row">
    
    <div class="col-md-6 col-lg-4 mb-4">
        
        <?php if($pengajuan): ?>
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-theme-blush fw-bold text-white">
                    Status Pengajuan Judul
                </div>
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Judul Anda:</h6>
                    <p class="card-text fw-bold" style="color: var(--simbeta-raisin);">
                        <?= esc($pengajuan['judul_usulan']) ?>
                    </p>
                    
                    <hr>
                    
                    <?php 
                        $statusRaw = strtolower($pengajuan['status']);
                        $displayStatus = strtoupper($statusRaw);
                        $badgeColor = 'bg-secondary';
                        $isLulus = false;

                        // Cek Hasil Sidang untuk Override Status Judul
                        if (!empty($jadwal_sidang) && isset($jadwal_sidang['status_lulus'])) {
                            if($jadwal_sidang['status_lulus'] == 'lulus') {
                                $displayStatus = 'LULUS / SELESAI';
                                $badgeColor = 'bg-success'; 
                                $isLulus = true;
                            } elseif($jadwal_sidang['status_lulus'] == 'tidak_lulus') {
                                $displayStatus = 'TIDAK LULUS SIDANG';
                                $badgeColor = 'bg-danger'; 
                            }
                        } 
                        
                        // Jika belum ada hasil sidang, pakai status judul biasa
                        if (!$isLulus && $displayStatus != 'TIDAK LULUS SIDANG') {
                            if($statusRaw == 'pending') {
                                $badgeColor = 'bg-warning text-dark';
                                $displayStatus = 'MENUNGGU VALIDASI';
                            }
                            elseif($statusRaw == 'diterima') {
                                $badgeColor = 'bg-success';
                                $displayStatus = 'DITERIMA (LANJUT BIMBINGAN)';
                            }
                            elseif($statusRaw == 'revisi') {
                                $badgeColor = 'bg-danger'; 
                            }
                        }
                    ?>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Status:</span>
                        <span class="badge <?= $badgeColor ?> fs-6">
                            <?= $displayStatus ?>
                        </span>
                    </div>

                    <?php if($statusRaw == 'revisi' && !$isLulus): ?>
                        <button type="button" class="btn btn-warning btn-sm w-100 fw-bold mb-3" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalRevisi">
                            <i class="bi bi-pencil-square me-1"></i> Perbaiki Judul & Deskripsi
                        </button>
                    <?php endif; ?>

                    <?php if(!empty($pengajuan['catatan_verifikasi'])): ?>
                        <div class="alert alert-secondary bg-light border-0">
                            <strong style="color: var(--simbeta-raisin);">
                                <i class="bi bi-chat-quote-fill me-2"></i>Catatan Dosen:
                            </strong>
                            <p class="mb-0 mt-2 fst-italic text-muted">
                                "<?= esc($pengajuan['catatan_verifikasi']) ?>"
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        <?php else: ?>
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-theme-blush fw-bold text-white">Status Pengajuan Judul</div>
                <div class="card-body">
                    <h5 class="card-title">Belum Mengajukan</h5>
                    <p class="card-text text-muted">Anda belum mengajukan usulan judul tugas akhir.</p>
                    <a href="<?= base_url('mahasiswa/pengajuan') ?>" class="btn btn-sm btn-primary mt-2">Ajukan Sekarang</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-theme-raisin fw-bold text-white">Progres Bimbingan</div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <h2 class="fw-bold mb-0 me-2" style="color: var(--simbeta-blush);"><?= $persentase ?>%</h2>
                    <span class="badge bg-secondary"><?= $labelProgres ?></span>
                </div>
                <div class="progress" style="height: 8px;">
                  <div class="progress-bar bg-theme-blush" role="progressbar" style="width: <?= $persentase ?>%"></div>
                </div>
                <p class="card-text text-muted mt-3 small">
                    <?php if($persentase == 100): ?>
                        Selamat! Skripsi Anda sudah lengkap (ACC).
                    <?php else: ?>
                        Tetap semangat! Perjalanan masih berlanjut.
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-lg-4 mb-4">
         <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-theme-raisin fw-bold text-white">
                <i class="bi bi-calendar-event me-2"></i> Jadwal Sidang
            </div>
            
            <div class="card-body">
                <?php if($jadwal_sidang): ?>
                    <div class="text-center mb-3">
                        <h1 class="display-4 fw-bold" style="color: var(--simbeta-blush);">
                            <?= date('d', strtotime($jadwal_sidang['tanggal_sidang'])) ?>
                        </h1>
                        <h5 class="text-uppercase text-muted">
                            <?= date('M Y', strtotime($jadwal_sidang['tanggal_sidang'])) ?>
                        </h5>
                        <span class="badge bg-info text-dark fs-6 mt-2">
                            <i class="bi bi-clock"></i> <?= date('H:i', strtotime($jadwal_sidang['tanggal_sidang'])) ?> WIB
                        </span>
                    </div>

                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Ruangan</span>
                            <span class="fw-bold"><?= esc($jadwal_sidang['ruangan']) ?></span>
                        </li>
                        <li class="list-group-item px-0">
                            <span class="d-block text-muted mb-1">Penguji:</span>
                            1. <?= esc($jadwal_sidang['penguji1']) ?><br>
                            2. <?= esc($jadwal_sidang['penguji2']) ?>
                        </li>
                    </ul>

                    <?php if(isset($jadwal_sidang['status_lulus'])): ?>
                        
                        <?php if($jadwal_sidang['status_lulus'] == 'lulus'): ?>
                            <div class="alert alert-success mt-3 text-center mb-0 fw-bold shadow-sm">
                                <i class="bi bi-trophy-fill me-2"></i> SELAMAT! ANDA LULUS.
                            </div>

                        <?php elseif($jadwal_sidang['status_lulus'] == 'tidak_lulus'): ?>
                            <div class="alert alert-danger mt-3 text-center mb-0 fw-bold shadow-sm">
                                <i class="bi bi-x-circle-fill me-2"></i> MAAF, ANDA TIDAK LULUS.
                                <div class="small fw-normal mt-1">Silakan hubungi dosen pembimbing.</div>
                            </div>

                        <?php else: ?>
                            <div class="alert alert-light border mt-3 text-center mb-0 text-muted">
                                <i class="bi bi-hourglass-split me-2"></i> Menunggu Hasil Sidang...
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>

                <?php else: ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-calendar-x fs-1 d-block mb-2" style="opacity: 0.3;"></i>
                        <p>Jadwal sidang belum keluar.</p>
                        <small>Pastikan Anda sudah menyelesaikan bimbingan.</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<?php if($pengajuan && $statusRaw == 'revisi' && !$isLulus): ?>
<div class="modal fade" id="modalRevisi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-dark">Perbaiki Pengajuan Judul</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('mahasiswa/pengajuan/update_revisi') ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id_pengajuan" value="<?= $pengajuan['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Baru</label>
                        <textarea name="judul_usulan" class="form-control" rows="3" required><?= esc($pengajuan['judul_usulan']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="4"><?= esc($pengajuan['deskripsi'] ?? '') ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold">Kirim Revisi</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>