<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PengajuanModel;
use App\Models\JadwalSidangModel; // Kita butuh model baru ini
use App\Models\UserModel;

class SidangController extends BaseController
{
    public function index()
    {
        $pengajuanModel = new PengajuanModel();
        $userModel = new UserModel();

        // 1. Ambil Mahasiswa yang judulnya DITERIMA & SUDAH ADA PEMBIMBING
        // Kita join juga ke tabel jadwal_sidang untuk cek apakah sudah dijadwalkan atau belum
        $dataMahasiswa = $pengajuanModel->select('tb_pengajuan_judul.*, users.nama_lengkap as nama_mhs, tb_jadwal_sidang.tanggal_sidang, tb_jadwal_sidang.ruangan, tb_jadwal_sidang.status_lulus')
            ->join('users', 'users.id = tb_pengajuan_judul.user_id_mahasiswa')
            ->join('tb_jadwal_sidang', 'tb_jadwal_sidang.pengajuan_id = tb_pengajuan_judul.id', 'left') // Left join agar yang belum sidang tetap muncul
            ->where('status', 'diterima')
            ->where('dosen_pembimbing_id !=', null)
            ->findAll();

        // 2. Ambil List Dosen untuk jadi Penguji
        $dataDosen = $userModel->where('role', 'dosen')->findAll();

        return view('admin/sidang_view', [
            'list_mhs' => $dataMahasiswa,
            'list_dosen' => $dataDosen
        ]);
    }

    public function simpan()
    {
        $jadwalModel = new JadwalSidangModel();
        
        $pengajuanId = $this->request->getPost('pengajuan_id');
        
        // Cek apakah data sudah ada (untuk update) atau baru (insert)
        $cekData = $jadwalModel->where('pengajuan_id', $pengajuanId)->first();
        
        $dataSimpan = [
            'pengajuan_id' => $pengajuanId,
            'tanggal_sidang' => $this->request->getPost('tanggal_sidang'),
            'ruangan' => $this->request->getPost('ruangan'),
            'penguji_1_id' => $this->request->getPost('penguji_1'),
            'penguji_2_id' => $this->request->getPost('penguji_2'),
            'status_lulus' => 'belum' // Default
        ];

        if($cekData) {
            // Kalau sudah ada, pakai ID-nya untuk update
            $dataSimpan['id'] = $cekData['id'];
        }

        $jadwalModel->save($dataSimpan);

        return redirect()->to('admin/sidang')->with('success', 'Jadwal sidang berhasil disimpan!');
    }

    public function update_hasil()
    {
        $jadwalModel = new JadwalSidangModel();
        
        $idJadwal = $this->request->getPost('id_jadwal');
        $status = $this->request->getPost('status_lulus');
        
        // Simpan Status Lulus/Tidak
        $jadwalModel->update($idJadwal, [
            'status_lulus' => $status
        ]);

        return redirect()->to('admin/sidang')->with('success', 'Status kelulusan berhasil diupdate!');
    }
}