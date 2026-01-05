<?php

namespace App\Controllers\Dosen;

use App\Controllers\BaseController;
use App\Models\PengajuanModel;
use App\Models\JadwalSidangModel;

class DashboardController extends BaseController
{
    public function index()
    {
        if (session()->get('role') != 'dosen') {
            return redirect()->to('/login');
        }

        $dosenId = session()->get('id');
        $pengajuanModel = new PengajuanModel();
        $jadwalModel = new JadwalSidangModel();

        // 1. Hitung Validasi Masuk (Global Pending)
        // Semua judul pending perlu divalidasi oleh dosen
        $countPending = $pengajuanModel->where('status', 'pending')->countAllResults();

        // 2. Hitung Mahasiswa Bimbingan Saya
        // Hanya yang statusnya 'diterima' DAN pembimbingnya adalah saya
        $countBimbingan = $pengajuanModel->where('dosen_pembimbing_id', $dosenId)
                                         ->where('status', 'diterima')
                                         ->countAllResults();

        // 3. Cek Jadwal Menguji Terdekat
        // Cari jadwal dimana saya jadi Penguji 1 ATAU Penguji 2
        // Dan tanggalnya belum lewat (>= hari ini)
        $nextSidang = $jadwalModel->select('tb_jadwal_sidang.*, users.nama_lengkap as nama_mhs')
                                  ->join('tb_pengajuan_judul', 'tb_pengajuan_judul.id = tb_jadwal_sidang.pengajuan_id')
                                  ->join('users', 'users.id = tb_pengajuan_judul.user_id_mahasiswa')
                                  ->groupStart()
                                      ->where('penguji_1_id', $dosenId)
                                      ->orWhere('penguji_2_id', $dosenId)
                                  ->groupEnd()
                                  ->where('tanggal_sidang >=', date('Y-m-d'))
                                  ->orderBy('tanggal_sidang', 'ASC')
                                  ->first();

        return view('dosen/dashboard', [
            'total_pending'   => $countPending,
            'total_bimbingan' => $countBimbingan,
            'next_sidang'     => $nextSidang
        ]);
    }
}