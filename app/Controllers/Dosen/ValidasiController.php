<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;
use App\Models\PengajuanModel;

class ValidasiController extends BaseController
{
    public function index()
    {
        $model = new PengajuanModel();

        // Ambil semua pengajuan yang statusnya 'pending'
        // Kita perlu JOIN ke tabel users untuk dapat nama mahasiswa
        $dataPengajuan = $model->select('tb_pengajuan_judul.*, users.nama_lengkap, users.foto')
                               ->join('users', 'users.id = tb_pengajuan_judul.user_id_mahasiswa')
                               ->where('status', 'pending')
                               ->findAll();

        return view('dosen/validasi_judul', [
            'list_pengajuan' => $dataPengajuan
        ]);
    }

    public function proses()
    {
        $model = new PengajuanModel();
        
        $id = $this->request->getPost('id_pengajuan');
        $aksi = $this->request->getPost('aksi'); // terima, tolak, revisi
        $catatan = $this->request->getPost('catatan');

        $dataUpdate = [
            'id' => $id,
            'status' => $aksi,
            'catatan_verifikasi' => $catatan
        ];

        $model->save($dataUpdate);

        return redirect()->to('dosen/validasi')->with('success', 'Status pengajuan berhasil diperbarui.');
    }
}