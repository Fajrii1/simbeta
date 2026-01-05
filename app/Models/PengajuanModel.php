<?php

namespace App\Models;

use CodeIgniter\Model;

class PengajuanModel extends Model
{
    protected $table            = 'tb_pengajuan_judul'; // Sesuai nama tabel di DB
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // Semua kolom yang akan di-input user/sistem
    protected $allowedFields    = [
        'user_id_mahasiswa', 
        'judul_usulan', 
        'abstrak', 
        'similarity_score', 
        'status', 
        'dosen_pembimbing_id', 
        'catatan_verifikasi'
    ];

    protected $useTimestamps = true;
    
    // Fungsi Custom untuk mengambil data lengkap dengan nama mahasiswa
    public function getPengajuanLengkap()
    {
        return $this->select('tb_pengajuan_judul.*, users.nama_lengkap as nama_mhs, users.username as nim')
                    ->join('users', 'users.id = tb_pengajuan_judul.user_id_mahasiswa')
                    ->findAll();
    }


public function update_revisi()
{
    // 1. Ambil Data dari Form
    $id = $this->request->getPost('id_pengajuan');
    $judulBaru = $this->request->getPost('judul_ta');
    $deskripsiBaru = $this->request->getPost('deskripsi_ta');

    // 2. Siapkan Data Update
    // Opsional: Kamu bisa mengubah status kembali ke 'menunggu' agar dosen tau mahasiswa sudah merevisi
    // Atau tetap 'revisi' sampai dosen klik Validasi lagi.
    // Disini saya ubah jadi 'menunggu' agar masuk lagi ke list validasi dosen.
    $data = [
        'judul_ta'      => $judulBaru,
        'deskripsi_ta'  => $deskripsiBaru,
        'status_judul'  => 'menunggu', // Mengembalikan status agar dicek dosen lagi
        'updated_at'    => date('Y-m-d H:i:s')
    ];

    // 3. Eksekusi Update ke Database
    // Pastikan model kamu mengarah ke tabel pengajuan
    $this->PengajuanModel->update($id, $data);

    // 4. Redirect kembali ke Dashboard
    return redirect()->to(base_url('mahasiswa/dashboard'))->with('success', 'Judul revisi berhasil dikirim! Menunggu validasi dosen.');
}
}