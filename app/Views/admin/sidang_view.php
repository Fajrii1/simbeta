<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2 style="color: var(--simbeta-raisin);">Penjadwalan Sidang</h2>
        <p class="text-muted">Kelola jadwal sidang akhir mahasiswa.</p>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-theme-raisin text-white fw-bold">
        <i class="bi bi-calendar-check me-2"></i> Daftar Mahasiswa Siap Sidang
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="p-3">Mahasiswa</th>
                        <th>Jadwal Saat Ini</th>
                        <th>Ruangan</th>
                        <th>Status</th>
                        <th class="text-end p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($list_mhs as $row): ?>
                        <tr>
                            <td class="p-3">
                                <h6 class="mb-0 fw-bold"><?= esc($row['nama_mhs']) ?></h6>
                                <small class="text-muted"><?= esc($row['judul_usulan']) ?></small>
                            </td>
                            <td>
                                <?php if ($row['tanggal_sidang']): ?>
                                    <span class="fw-bold text-dark">
                                        <?= date('d M Y, H:i', strtotime($row['tanggal_sidang'])) ?> WIB
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Belum Dijadwalkan</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= $row['ruangan'] ? esc($row['ruangan']) : '-' ?>
                            </td>
                            <td>
                                <?php if ($row['status_lulus'] == 'lulus'): ?>
                                    <span class="badge bg-success">LULUS</span>
                                <?php elseif ($row['tanggal_sidang']): ?>
                                    <span class="badge bg-info text-dark">Terjadwal</span>
                                <?php else: ?>
                                    <span class="badge bg-light text-dark border">Menunggu</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end p-3">
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalJadwal"
                                        data-id="<?= $row['id'] ?>"
                                        data-nama="<?= $row['nama_mhs'] ?>">
                                        <i class="bi bi-pencil"></i> Jadwal
                                    </button>

                                    <button class="btn btn-sm btn-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalHasil<?= $row['id'] ?>">
                                        <i class="bi bi-trophy"></i> Hasil
                                    </button>
                                </div>

                                <div class="modal fade text-start" id="modalHasil<?= $row['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-success text-white">
                                                <h5 class="modal-title">Input Hasil Sidang</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="<?= base_url('admin/sidang/update_hasil') ?>" method="post">
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_jadwal" value="<?= $row['id'] ?>">
                                                    <p>Mahasiswa: <strong><?= esc($row['nama_mhs']) ?></strong></p>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Keputusan Sidang</label>
                                                        <select name="status_lulus" class="form-select">
                                                            <option value="belum" <?= ($row['status_lulus'] == 'belum') ? 'selected' : '' ?>>Belum / Menunggu</option>
                                                            <option value="lulus" <?= ($row['status_lulus'] == 'lulus') ? 'selected' : '' ?>>✅ LULUS</option>
                                                            <option value="tidak_lulus" <?= ($row['status_lulus'] == 'tidak_lulus') ? 'selected' : '' ?>>❌ TIDAK LULUS</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success">Simpan Keputusan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalJadwal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme-raisin text-white">
                <h5 class="modal-title">Set Jadwal Sidang</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/sidang/simpan') ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="pengajuan_id" id="input_pengajuan_id">

                    <div class="mb-3">
                        <label class="form-label">Mahasiswa</label>
                        <input type="text" class="form-control" id="input_nama_mhs" readonly>
                    </div>

                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label class="form-label fw-bold">Tanggal & Jam</label>
                            <input type="datetime-local" name="tanggal_sidang" class="form-control" required>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label fw-bold">Ruangan</label>
                            <input type="text" name="ruangan" class="form-control" placeholder="Cth: Lab 2" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Penguji 1</label>
                        <select name="penguji_1" class="form-select">
                            <option value="">-- Pilih Dosen --</option>
                            <?php foreach ($list_dosen as $d): ?>
                                <option value="<?= $d['id'] ?>"><?= $d['nama_lengkap'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Penguji 2</label>
                        <select name="penguji_2" class="form-select">
                            <option value="">-- Pilih Dosen --</option>
                            <?php foreach ($list_dosen as $d): ?>
                                <option value="<?= $d['id'] ?>"><?= $d['nama_lengkap'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary bg-theme-blush border-0">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modalJadwal = document.getElementById('modalJadwal');
        modalJadwal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            document.getElementById('input_pengajuan_id').value = button.getAttribute('data-id');
            document.getElementById('input_nama_mhs').value = button.getAttribute('data-nama');
        });
    });
</script>

<?= $this->endSection() ?>