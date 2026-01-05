<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
    /* Style Dasar Tombol Kotak Rounded */
    .btn-custom {
        width: 42px;          /* Lebar tombol */
        height: 38px;         /* Tinggi tombol */
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;  /* Sudut melengkung */
        border: 2px solid;    /* Garis pinggir tebal */
        background-color: transparent;
        transition: all 0.2s ease-in-out;
        font-size: 1.1rem;    /* Ukuran icon */
        text-decoration: none;
    }

    /* 1. Tombol Edit (Warna Ungu/Magenta) */
    .btn-edit-outline {
        border-color: #F4B8CA; /* Warna Ungu Cerah */
        color: #F4B8CA;
    }
    .btn-edit-outline:hover {
        background-color: #F4B8CA;
        color: white;
        box-shadow: 0 4px 10px rgba(191, 37, 211, 0.3);
        transform: translateY(-2px);
    }

    /* 2. Tombol Hapus (Warna Merah Tua/Marun) */
    .btn-delete-outline {
        border-color: #8a1c1c; /* Warna Merah Tua */
        color: #8a1c1c;
    }
    .btn-delete-outline:hover {
        background-color: #8a1c1c;
        color: white;
        box-shadow: 0 4px 10px rgba(138, 28, 28, 0.3);
        transform: translateY(-2px);
    }
</style>

<div class="row mb-4">
    <div class="col-md-12">
        <h2 style="color: var(--simbeta-raisin);">Master Data User</h2>
        <p class="text-muted">Kelola akun Mahasiswa, Dosen, dan Admin.</p>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('msg')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('msg') ?></div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-theme-raisin text-white d-flex justify-content-between align-items-center">
        <span class="fw-bold"><i class="bi bi-people me-2"></i> Daftar Pengguna</span>
        <button class="btn btn-sm btn-light text-dark fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-lg"></i> Tambah User Baru
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="p-3">Nama Lengkap</th>
                        <th>Username (NIM/NIP)</th>
                        <th>Role</th>
                        <th class="text-end p-3" style="min-width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="p-3">
                                <div class="d-flex align-items-center">
                                    <?php
                                    $fotoDB = $user['foto']; 
                                    if (!empty($fotoDB) && file_exists('assets/img/profile/' . $fotoDB)) {
                                        $imgSrc = base_url('assets/img/profile/' . $fotoDB);
                                    } else {
                                        $imgSrc = "https://ui-avatars.com/api/?name=" . urlencode($user['nama_lengkap']) . "&background=random&size=40";
                                    }
                                    ?>
                                    <img src="<?= $imgSrc ?>" class="rounded-circle me-2 border" width="40" height="40" style="object-fit: cover;">
                                    <div>
                                        <span class="fw-bold d-block"><?= esc($user['nama_lengkap']) ?></span>
                                        <small class="text-muted" style="font-size: 11px;">ID: <?= $user['id'] ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?= esc($user['username']) ?></td>
                            <td>
                                <?php
                                $badge = 'bg-secondary';
                                if ($user['role'] == 'admin') $badge = 'bg-danger';
                                if ($user['role'] == 'dosen') $badge = 'bg-info text-dark';
                                if ($user['role'] == 'mahasiswa') $badge = 'bg-success';
                                ?>
                                <span class="badge <?= $badge ?>"><?= strtoupper($user['role']) ?></span>
                            </td>
                            <td class="text-end p-3">
                                <button type="button" class="btn-custom btn-edit-outline me-2"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEdit"
                                        data-id="<?= $user['id'] ?>"
                                        data-nama="<?= esc($user['nama_lengkap']) ?>"
                                        data-username="<?= esc($user['username']) ?>"
                                        data-email="<?= esc($user['email']) ?>"
                                        data-role="<?= $user['role'] ?>"
                                        title="Edit User">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>

                                <?php if ($user['id'] != session()->get('id')): ?>
                                    <a href="<?= base_url('admin/users/hapus/' . $user['id']) ?>"
                                       class="btn-custom btn-delete-outline"
                                       onclick="return confirm('Yakin ingin menghapus user ini? Data pengajuan mereka juga akan hilang/error.')"
                                       title="Hapus User">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme-blush text-white">
                <h5 class="modal-title">Tambah User Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/users/simpan') ?>" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" placeholder="Contoh: Budi Santoso" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username (NIM / NIP / ID)</label>
                        <input type="text" name="username" class="form-control" placeholder="Contoh: 0902128..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Contoh: budi@polsri.ac.id">
                        <div class="form-text text-muted" style="font-size: 11px;">*Opsional</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="text" name="password" class="form-control" value="123456" required>
                            <div class="form-text">Default: 123456</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select">
                                <option value="mahasiswa">Mahasiswa</option>
                                <option value="dosen">Dosen</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary bg-theme-raisin border-0">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #bf25d3;"> 
                <h5 class="modal-title">Edit Data User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/users/update') ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" id="edit_nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username (NIM / NIP)</label>
                        <input type="text" name="username" id="edit_username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="text" name="password" class="form-control" placeholder="Kosongkan jika tetap">
                            <div class="form-text text-danger" style="font-size: 11px;">*Isi hanya jika ingin ganti password</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" id="edit_role" class="form-select">
                                <option value="mahasiswa">Mahasiswa</option>
                                <option value="dosen">Dosen</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn text-white" style="background-color: #bf25d3;">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modalEdit = document.getElementById('modalEdit');
        modalEdit.addEventListener('show.bs.modal', function (event) {
            // Tombol yang diklik
            var button = event.relatedTarget;
            
            // Ambil data dari atribut data-*
            var id = button.getAttribute('data-id');
            var nama = button.getAttribute('data-nama');
            var username = button.getAttribute('data-username');
            var email = button.getAttribute('data-email');
            var role = button.getAttribute('data-role');

            // Isi input di dalam modal
            modalEdit.querySelector('#edit_id').value = id;
            modalEdit.querySelector('#edit_nama').value = nama;
            modalEdit.querySelector('#edit_username').value = username;
            modalEdit.querySelector('#edit_email').value = email;
            modalEdit.querySelector('#edit_role').value = role;
        });
    });
</script>

<?= $this->endSection() ?>