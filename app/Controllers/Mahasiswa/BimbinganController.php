<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;
use App\Models\PengajuanModel;
use App\Models\BimbinganModel;

class BimbinganController extends BaseController
{
    public function index()
    {
        $userId = session()->get('id');
        $pengajuanModel = new PengajuanModel();
        $bimbinganModel = new BimbinganModel();

        // 1. Cek Data Pengajuan Mahasiswa (Join ke Dosen untuk dapat nama pembimbing)
        $dataPengajuan = $pengajuanModel->select('tb_pengajuan_judul.*, users.nama_lengkap as nama_dosen')
                                        ->join('users', 'users.id = tb_pengajuan_judul.dosen_pembimbing_id', 'left')
                                        ->where('user_id_mahasiswa', $userId)
                                        ->first();

        // VALIDASI: Belum mengajukan atau belum diterima atau belum dapat pembimbing
        if (!$dataPengajuan || $dataPengajuan['status'] != 'diterima' || empty($dataPengajuan['dosen_pembimbing_id'])) {
            return redirect()->to('mahasiswa/dashboard')->with('msg', 'Anda belum bisa melakukan bimbingan. Pastikan judul diterima dan sudah mendapat pembimbing.');
        }

        // 2. Ambil Riwayat Bimbingan
        $riwayat = $bimbinganModel->where('pengajuan_id', $dataPengajuan['id'])
                                  ->orderBy('tanggal_bimbingan', 'DESC')
                                  ->findAll();

        return view('mahasiswa/bimbingan_view', [
            'pengajuan' => $dataPengajuan,
            'riwayat'   => $riwayat
        ]);
    }

    public function upload()
    {
        $bimbinganModel = new BimbinganModel();
        
        // Validasi File
        if (!$this->validate([
            'file_draft' => [
                'rules' => 'uploaded[file_draft]|mime_in[file_draft,application/pdf]|max_size[file_draft,5120]',
                'errors' => [
                    'mime_in' => 'File harus berupa PDF.',
                    'max_size' => 'Ukuran file maksimal 5MB.'
                ]
            ]
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Proses Upload File
        $fileDraft = $this->request->getFile('file_draft');
        $namaFileBaru = $fileDraft->getRandomName(); // Generate nama acak agar aman
        $fileDraft->move('uploads/bimbingan', $namaFileBaru);

        // Simpan ke Database
        $bimbinganModel->save([
            'pengajuan_id'      => $this->request->getPost('pengajuan_id'),
            'bab_ke'            => $this->request->getPost('bab_ke'),
            'catatan_mahasiswa' => $this->request->getPost('catatan'),
            'file_draft'        => $namaFileBaru,
            'status_acc'        => 'revisi', // Default status saat baru upload
            'tanggal_bimbingan' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('mahasiswa/bimbingan')->with('success', 'File bab berhasil dikirim ke dosen pembimbing.');
    }
}