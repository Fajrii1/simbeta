<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-theme-blush text-white fw-bold">
                <i class="bi bi-pencil-square me-2"></i> Form Pengajuan Judul TA
            </div>
            <div class="card-body p-4">

                <?php if(session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <ul>
                        <?php foreach(session()->getFlashdata('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('mahasiswa/pengajuan/simpan') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-4">
                        <label for="judul" class="form-label fw-bold" style="color: var(--simbeta-raisin);">Judul Tugas Akhir</label>
                        
                        <input type="text" class="form-control" id="judul_input" name="judul_usulan" placeholder="Ketik judul usulan Anda..." value="<?= old('judul_usulan') ?>" autocomplete="off" required>
                        
                        <div class="form-text">Pastikan judul belum pernah diteliti sebelumnya.</div>

                        <div id="hasil_cek_judul" class="mt-2"></div>
                    </div>

                    <div class="mb-4">
                        <label for="abstrak" class="form-label fw-bold" style="color: var(--simbeta-raisin);">Abstrak / Ringkasan</label>
                        <textarea class="form-control" id="abstrak" name="abstrak" rows="6" placeholder="Jelaskan latar belakang, tujuan, dan metode..." required><?= old('abstrak') ?></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg bg-theme-raisin border-0">
                            <i class="bi bi-send me-2"></i> Ajukan Judul
                        </button>
                        <a href="<?= base_url('mahasiswa/dashboard') ?>" class="btn btn-light text-muted">Batal</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    const inputJudul = document.getElementById('judul_input');
    const hasilDiv = document.getElementById('hasil_cek_judul');

    // Timer untuk mencegah request terlalu banyak saat mengetik cepat (Debounce)
    let timeout = null;

    inputJudul.addEventListener('keyup', function() {
        const keyword = inputJudul.value;

        // Reset hasil jika kosong
        if (keyword.length < 5) {
            hasilDiv.innerHTML = '';
            return;
        }

        // Hapus timer lama
        clearTimeout(timeout);

        // Tunggu 500ms setelah user berhenti mengetik, baru cari
        timeout = setTimeout(function() {
            fetch('<?= base_url('mahasiswa/cek-judul') ?>?judul=' + encodeURIComponent(keyword), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    // Jika ditemukan kemiripan
                    let html = '<div class="alert alert-warning border-warning"><small><strong>⚠️ Peringatan:</strong> Ditemukan judul yang mirip di database:</small><ul class="mb-0 mt-1 small">';
                    
                    data.forEach(item => {
                        html += `<li>"${item.judul_usulan}" <span class="badge bg-secondary">${item.status}</span></li>`;
                    });

                    html += '</ul></div>';
                    hasilDiv.innerHTML = html;
                } else {
                    // Jika aman
                    hasilDiv.innerHTML = '<div class="text-success small"><i class="bi bi-check-circle-fill"></i> Judul ini belum ada di database (Original).</div>';
                }
            })
            .catch(error => console.error('Error:', error));
        }, 500); // Delay 0.5 detik
    });
</script>

<?= $this->endSection() ?>