<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;
use App\Models\PengajuanModel;
use App\Models\BimbinganModel;

class BimbinganController extends BaseController
{
    public function index()
    {
        $dosenId = session()->get('id');
        $pengajuanModel = new PengajuanModel();

        // Ambil daftar mahasiswa yang dibimbing oleh dosen ini
        $mahasiswaBimbingan = $pengajuanModel->select('tb_pengajuan_judul.*, users.nama_lengkap as nama_mhs, users.username as nim, users.foto')
            ->join('users', 'users.id = tb_pengajuan_judul.user_id_mahasiswa')
            ->where('dosen_pembimbing_id', $dosenId)
            ->where('status', 'diterima') // Hanya yang judulnya sudah acc
            ->findAll();

        return view('dosen/bimbingan_list', [
            'mahasiswa' => $mahasiswaBimbingan
        ]);
    }

    public function detail($idPengajuan)
    {
        $bimbinganModel = new BimbinganModel();
        $pengajuanModel = new PengajuanModel();

        // Ambil Data Header (Nama Mahasiswa, Judul)
        $header = $pengajuanModel->select('tb_pengajuan_judul.*, users.nama_lengkap, users.username')
            ->join('users', 'users.id = tb_pengajuan_judul.user_id_mahasiswa')
            ->find($idPengajuan);

        // Ambil Riwayat Chat/Bimbingan
        $riwayat = $bimbinganModel->where('pengajuan_id', $idPengajuan)
            ->orderBy('tanggal_bimbingan', 'DESC') // Yang terbaru diatas
            ->findAll();

        return view('dosen/bimbingan_detail', [
            'header' => $header,
            'riwayat' => $riwayat
        ]);
    }

    public function respon()
{
    $bimbinganModel = new BimbinganModel();
    
    $idBimbingan = $this->request->getPost('id_bimbingan');
    $idPengajuan = $this->request->getPost('id_pengajuan');
    
    // Siapkan data dasar yang akan diupdate
    $dataUpdate = [
        'catatan_dosen' => $this->request->getPost('catatan_dosen'),
        'status_acc'    => $this->request->getPost('status_acc')
    ];

    // Cek apakah Dosen mengupload file revisi (coretan)?
    $fileRevisi = $this->request->getFile('file_revisi');

    if ($fileRevisi && $fileRevisi->isValid() && !$fileRevisi->hasMoved()) {
        // Generate nama unik: revisi_HARI_JAM_ACAK.pdf
        $namaFileBaru = 'revisi_' . date('Ymd_His') . '_' . $fileRevisi->getRandomName();
        
        // Simpan ke folder yang sama (uploads/bimbingan)
        $fileRevisi->move('uploads/bimbingan', $namaFileBaru);

        // Tambahkan nama file ke data yang akan diupdate ke DB
        // Kita pakai kolom 'file_lks' (Lembar Kerja/Revisi)
        $dataUpdate['file_lks'] = $namaFileBaru;
    }

    // Eksekusi Update ke Database
    $bimbinganModel->update($idBimbingan, $dataUpdate);

    return redirect()->to('dosen/bimbingan/detail/' . $idPengajuan)->with('success', 'Feedback dan file revisi berhasil dikirim.');
}
}