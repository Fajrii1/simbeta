<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2 style="color: var(--simbeta-raisin);">Dashboard Administrator</h2>
        <p class="text-muted">Panel kontrol utama sistem SIMBETA.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body border-start border-4" style="border-color: var(--simbeta-blush) !important;">
                <h6 class="text-muted text-uppercase small">Total Mahasiswa</h6>
                <h3 class="fw-bold mb-0" style="color: var(--simbeta-raisin);"><?= $total_mhs ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body border-start border-4" style="border-color: var(--simbeta-raisin) !important;">
                <h6 class="text-muted text-uppercase small">Total Dosen</h6>
                <h3 class="fw-bold mb-0" style="color: var(--simbeta-raisin);"><?= $total_dosen ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body border-start border-4" style="border-color: var(--simbeta-orchid) !important;">
                <h6 class="text-muted text-uppercase small">Judul Diterima</h6>
                <h3 class="fw-bold mb-0" style="color: var(--simbeta-raisin);"><?= $total_judul ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body border-start border-4 bg-theme-raisin text-white">
                <h6 class="text-uppercase small" style="color: var(--simbeta-orchid);">Lulus Sidang</h6>
                <h3 class="fw-bold mb-0"><?= $total_lulus ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-theme-blush text-white fw-bold">
                ⚡ Plotting Pembimbing Pending
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead style="background-color: var(--simbeta-white);">
                        <tr>
                            <th class="ps-3">Mahasiswa</th>
                            <th>Judul Usulan</th>
                            <th class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($plotting_pending)): ?>
                            <tr>
                                <td colspan="3" class="text-center p-4 text-muted">
                                    <i class="bi bi-check-circle-fill text-success"></i> Tidak ada antrian plotting. Semua aman!
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($plotting_pending as $row): ?>
                            <tr>
                                <td class="ps-3 fw-bold"><?= esc($row['nama_mhs']) ?></td>
                                <td class="text-truncate" style="max-width: 250px;">
                                    <?= esc($row['judul_usulan']) ?>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="<?= base_url('admin/plotting') ?>" class="btn btn-sm btn-outline-dark">
                                        Plotting
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white text-center">
                <a href="<?= base_url('admin/plotting') ?>" class="text-decoration-none small" style="color: var(--simbeta-blush);">Lihat Semua Data</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
         <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-theme-raisin text-white fw-bold">
                System Info
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Status Server
                    <span class="badge bg-success">Online</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Framework
                    <span>CodeIgniter 4</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Database
                    <span>MySQL</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Server Date
                    <small><?= date('d M Y') ?></small>
                </li>
            </ul>
         </div>
    </div>
</div>

<?= $this->endSection() ?>