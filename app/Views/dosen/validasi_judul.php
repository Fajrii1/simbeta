<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2 style="color: var(--simbeta-raisin);">Validasi Judul Masuk</h2>
        <p class="text-muted">Daftar judul yang menunggu persetujuan Anda.</p>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-theme-raisin text-white fw-bold">
        <i class="bi bi-list-task me-2"></i> Daftar Pengajuan Pending
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="p-3">Mahasiswa</th>
                        <th>Judul Usulan</th>
                        <th>Tanggal</th>
                        <th class="text-end p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($list_pengajuan)): ?>
                        <tr>
                            <td colspan="4" class="text-center p-4 text-muted">Belum ada pengajuan judul baru.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($list_pengajuan as $row): ?>
                        <tr>
                            <td class="p-3">
                                <div class="d-flex align-items-center">
                                    <div class="ms-2">
                                        <h6 class="mb-0 fw-bold"><?= esc($row['nama_lengkap']) ?></h6>
                                        <small class="text-muted">Mahasiswa</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="mb-1 fw-bold text-dark"><?= esc($row['judul_usulan']) ?></p>
                                <button type="button" class="btn btn-sm btn-link p-0 text-muted" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalAbstrak<?= $row['id'] ?>">
                                    <i class="bi bi-eye"></i> Lihat Abstrak
                                </button>

                                <div class="modal fade" id="modalAbstrak<?= $row['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Abstrak</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p><?= esc($row['abstrak']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                            <td class="text-end p-3">
                                <button class="btn btn-sm btn-success btn-verifikasi" 
                                        data-id="<?= $row['id'] ?>" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalVerifikasi">
                                    Proses
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalVerifikasi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme-blush text-white">
                <h5 class="modal-title">Berikan Keputusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('dosen/validasi/proses') ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id_pengajuan" id="input_id_pengajuan">
                    
                    <div class="mb-3">
                        <label class="form-label">Status Keputusan</label>
                        <select name="aksi" class="form-select" required>
                            <option value="diterima">✅ Terima Judul</option>
                            <option value="revisi">📝 Minta Revisi</option>
                            <option value="ditolak">❌ Tolak Judul</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Catatan / Alasan</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Berikan alasan atau catatan revisi..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary bg-theme-raisin border-0">Simpan Keputusan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modalVerifikasi = document.getElementById('modalVerifikasi');
        modalVerifikasi.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var inputId = document.getElementById('input_id_pengajuan');
            inputId.value = id;
        });
    });
</script>

<?= $this->endSection() ?>