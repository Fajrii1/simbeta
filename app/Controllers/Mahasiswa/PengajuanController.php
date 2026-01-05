<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;
use App\Models\PengajuanModel;

class PengajuanController extends BaseController
{
    public function index()
    {
        // 1. Cek apakah mahasiswa ini sudah pernah mengajukan judul?
        // (Agar tidak mengajukan double)
        $model = new PengajuanModel();
        $userId = session()->get('id');
        
        $sudahAda = $model->where('user_id_mahasiswa', $userId)->first();

        if ($sudahAda) {
            // Jika sudah ada, kembalikan ke dashboard dengan pesan
            return redirect()->to('mahasiswa/dashboard')->with('msg', 'Anda sudah mengajukan judul. Silakan tunggu validasi.');
        }

        return view('mahasiswa/form_pengajuan');
    }

    public function simpan()
    {
        // 1. Validasi Input
        if (!$this->validate([
            'judul_usulan' => [
                'rules'  => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Judul Skripsi harus diisi.',
                    'min_length' => 'Judul terlalu pendek.'
                ]
            ],
            'abstrak' => [
                'rules'  => 'required|min_length[50]',
                'errors' => [
                    'required' => 'Abstrak harus diisi.',
                    'min_length' => 'Abstrak minimal 50 karakter.'
                ]
            ]
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 2. Siapkan Data
        $model = new PengajuanModel();
        
        $data = [
            'user_id_mahasiswa' => session()->get('id'), // Ambil ID dari session login
            'judul_usulan'      => $this->request->getVar('judul_usulan'),
            'abstrak'           => $this->request->getVar('abstrak'),
            'status'            => 'pending', // Default status
            'similarity_score'  => 0 // Nanti kita update dengan logika cek plagiasi
        ];

        // 3. Simpan ke Database
        $model->save($data);

        // 4. Sukses
        return redirect()->to('mahasiswa/dashboard')->with('success', 'Judul berhasil diajukan! Menunggu validasi Dosen.');
    }

    // Fungsi Khusus untuk AJAX Cek Judul
    public function cek_kemiripan()
    {
        if (!$this->request->isAJAX()) {
            exit('Maaf, akses ditolak.');
        }

        $judulInput = $this->request->getGet('judul');
        $model = new PengajuanModel();

        // Logika Pencarian: 
        // Mencari judul yang mirip menggunakan LIKE %keyword%
        // Kita cari di semua status (baik yang diterima, ditolak, atau lulus) agar unik
        $dataMirip = $model->like('judul_usulan', $judulInput)
                           ->select('judul_usulan, status, created_at')
                           ->orderBy('created_at', 'DESC')
                           ->findAll(5); // Ambil maksimal 5 kemiripan teratas

        return $this->response->setJSON($dataMirip);
    }
}