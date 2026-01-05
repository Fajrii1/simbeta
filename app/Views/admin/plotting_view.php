<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2 style="color: var(--simbeta-raisin);">Plotting Dosen Pembimbing</h2>
        <p class="text-muted">Tetapkan pembimbing untuk mahasiswa yang judulnya sudah diterima.</p>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-theme-raisin text-white fw-bold">
        <i class="bi bi-people-fill me-2"></i> Antrian Plotting
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="p-3">Mahasiswa</th>
                        <th>Judul Disetujui</th>
                        <th class="text-end p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($list_mhs)): ?>
                        <tr>
                            <td colspan="3" class="text-center p-4 text-muted">Semua mahasiswa sudah mendapatkan pembimbing.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($list_mhs as $row): ?>
                        <tr>
                            <td class="p-3">
                                <h6 class="mb-0 fw-bold"><?= esc($row['nama_mhs']) ?></h6>
                                <small class="text-muted"><?= esc($row['nim']) ?></small>
                            </td>
                            <td><?= esc($row['judul_usulan']) ?></td>
                            <td class="text-end p-3">
                                <button class="btn btn-sm btn-primary bg-theme-blush border-0" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalPlotting"
                                        data-id="<?= $row['id'] ?>"
                                        data-nama="<?= $row['nama_mhs'] ?>">
                                    <i class="bi bi-person-plus"></i> Pilih Dosen
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

<div class="modal fade" id="modalPlotting" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme-raisin text-white">
                <h5 class="modal-title">Pilih Pembimbing</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/plotting/simpan') ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id_pengajuan" id="input_id_pengajuan">
                    
                    <div class="mb-3">
                        <label class="form-label">Mahasiswa</label>
                        <input type="text" class="form-control" id="input_nama_mhs" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Dosen Pembimbing</label>
                        <select name="dosen_id" class="form-select" required>
                            <option value="">-- Pilih Dosen --</option>
                            <?php foreach($list_dosen as $dosen): ?>
                                <option value="<?= $dosen['id'] ?>">
                                    <?= $dosen['nama_lengkap'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary bg-theme-blush border-0">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modalPlotting = document.getElementById('modalPlotting');
        modalPlotting.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            
            // Ambil data dari tombol
            var id = button.getAttribute('data-id');
            var nama = button.getAttribute('data-nama');
            
            // Masukkan ke dalam input modal
            document.getElementById('input_id_pengajuan').value = id;
            document.getElementById('input_nama_mhs').value = nama;
        });
    });
</script>

<?= $this->endSection() ?>