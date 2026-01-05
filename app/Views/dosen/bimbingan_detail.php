<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 style="color: var(--simbeta-raisin);">Logbook Bimbingan</h2>
        <p class="text-muted mb-0">Mahasiswa: <strong><?= esc($header['nama_lengkap']) ?></strong></p>
    </div>
    <a href="<?= base_url('dosen/bimbingan') ?>" class="btn btn-outline-secondary">Kembali</a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-theme-raisin text-white fw-bold">
        <i class="bi bi-clock-history me-2"></i> Riwayat Upload
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="p-3">Tgl</th>
                        <th>Bab</th>
                        <th>File Draft</th>
                        <th>Catatan Mahasiswa</th>
                        <th>Status</th>
                        <th class="text-end p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($riwayat)): ?>
                        <tr>
                            <td colspan="6" class="text-center p-4">Belum ada progress.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($riwayat as $row): ?>
                            <tr class="<?= ($row['status_acc'] == 'acc') ? 'table-success' : '' ?>">
                                <td class="p-3"><?= date('d/m/y', strtotime($row['tanggal_bimbingan'])) ?></td>
                                <td><span class="badge bg-secondary"><?= $row['bab_ke'] ?></span></td>
                                <td>
                                    <a href="<?= base_url('uploads/bimbingan/' . $row['file_draft']) ?>" target="_blank" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-file-pdf"></i> PDF
                                    </a>
                                </td>
                                <td><small><?= esc($row['catatan_mahasiswa']) ?></small></td>
                                <td>
                                    <?php if ($row['status_acc'] == 'acc'): ?>
                                        <span class="badge bg-success">ACC ✅</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Revisi 📝</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end p-3">
                                    <button class="btn btn-sm btn-primary bg-theme-blush border-0"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalRespon<?= $row['id'] ?>">
                                        Beri Feedback
                                    </button>

                                    <div class="modal fade text-start" id="modalRespon<?= $row['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-theme-raisin text-white">
                                                    <h5 class="modal-title">Feedback & File Revisi</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>

                                                <form action="<?= base_url('dosen/bimbingan/respon') ?>" method="post" enctype="multipart/form-data">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="id_bimbingan" value="<?= $row['id'] ?>">
                                                        <input type="hidden" name="id_pengajuan" value="<?= $header['id'] ?>">

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">1. Keputusan Status</label>
                                                            <select name="status_acc" class="form-select">
                                                                <option value="revisi" <?= ($row['status_acc'] == 'revisi') ? 'selected' : '' ?>>Masih Revisi</option>
                                                                <option value="acc" <?= ($row['status_acc'] == 'acc') ? 'selected' : '' ?>>✅ ACC (Lanjut)</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">2. Catatan Teks</label>
                                                            <textarea name="catatan_dosen" class="form-control" rows="3" placeholder="Tulis masukan singkat disini..."><?= $row['catatan_dosen'] ?></textarea>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">3. Upload File Coretan (Opsional)</label>
                                                            <input type="file" name="file_revisi" class="form-control" accept=".pdf">
                                                            <div class="form-text small text-muted">
                                                                Tips: Download file mahasiswa, lingkari salahnya (pakai MS Edge/Reader), simpan, lalu upload lagi kesini.
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary bg-theme-blush border-0">Kirim Revisi</button>
                                                    </div>
                                                </form>
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
<?= $this->endSection() ?>