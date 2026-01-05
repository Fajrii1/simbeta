<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2 style="color: var(--simbeta-raisin);">Bimbingan Skripsi</h2>
        <p class="text-muted">Upload progres bab dan pantau revisi dari dosen.</p>
    </div>
</div>

<div class="alert alert-light border-start border-4 shadow-sm" style="border-color: var(--simbeta-blush) !important;">
    <div class="d-flex align-items-center">
        <div class="fs-1 me-3" style="color: var(--simbeta-raisin);">👨‍🏫</div>
        <div>
            <h6 class="text-muted mb-0">Dosen Pembimbing Anda:</h6>
            <h4 class="fw-bold mb-0" style="color: var(--simbeta-raisin);">
                <?= esc($pengajuan['nama_dosen']) ?>
            </h4>
            <small>Judul: <?= esc($pengajuan['judul_usulan']) ?></small>
        </div>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<?php if(session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <?php foreach(session()->getFlashdata('errors') as $e): ?>
            <li><?= $e ?></li>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-theme-raisin text-white fw-bold">
                <i class="bi bi-cloud-upload me-2"></i> Upload Progres
            </div>
            <div class="card-body">
                <form action="<?= base_url('mahasiswa/bimbingan/upload') ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="pengajuan_id" value="<?= $pengajuan['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label">Tahapan Bab</label>
                        <select name="bab_ke" class="form-select" required>
                            <option value="BAB 1">BAB 1 - Pendahuluan</option>
                            <option value="BAB 2">BAB 2 - Tinjauan Pustaka</option>
                            <option value="BAB 3">BAB 3 - Metodologi</option>
                            <option value="BAB 4">BAB 4 - Pembahasan</option>
                            <option value="BAB 5">BAB 5 - Penutup</option>
                            <option value="FULL DRAFT">Full Draft (Lengkap)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">File Dokumen (PDF)</label>
                        <input type="file" name="file_draft" class="form-control" accept=".pdf" required>
                        <div class="form-text">Maksimal 5MB.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan untuk Dosen</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Contoh: Ini revisi bab 1 bagian latar belakang..."></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary bg-theme-blush border-0">Kirim ke Pembimbing</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold" style="color: var(--simbeta-raisin);">
                ⏳ Riwayat Logbook
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Bab</th>
                                <th>Catatan Anda</th>
                                <th>Status</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($riwayat)): ?>
                                <tr>
                                    <td colspan="5" class="text-center p-4 text-muted">Belum ada file yang diunggah.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($riwayat as $row): ?>
                                <tr>
                                    <td><?= date('d/m/Y', strtotime($row['tanggal_bimbingan'])) ?></td>
                                    <td><span class="badge bg-secondary"><?= $row['bab_ke'] ?></span></td>
                                    <td><small class="text-muted"><?= esc($row['catatan_mahasiswa']) ?></small></td>
                                    <td>
                                        <?php if($row['status_acc'] == 'acc'): ?>
                                            <span class="badge bg-success">✅ ACC</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Revisi / Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-grid gap-2">
                                            <?php if(!empty($row['catatan_dosen'])): ?>
                                                <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalFeedback<?= $row['id'] ?>">
                                                    <i class="bi bi-chat-text"></i> Pesan Dosen
                                                </button>
                                            <?php endif; ?>

                                            <?php if(!empty($row['file_lks'])): ?>
                                                <a href="<?= base_url('uploads/bimbingan/' . $row['file_lks']) ?>" target="_blank" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-pencil-square"></i> Lihat Coretan
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if(empty($row['catatan_dosen']) && empty($row['file_lks'])): ?>
                                                <small class="text-muted d-block text-center">-</small>
                                            <?php endif; ?>
                                        </div>

                                        <div class="modal fade" id="modalFeedback<?= $row['id'] ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-info text-white">
                                                        <h5 class="modal-title">Catatan Dosen</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><?= esc($row['catatan_dosen']) ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>