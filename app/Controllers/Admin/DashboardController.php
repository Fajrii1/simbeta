<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PengajuanModel;
use App\Models\JadwalSidangModel;

class DashboardController extends BaseController
{
    public function index()
    {
        if (session()->get('role') != 'admin') {
            return redirect()->to('/login');
        }

        $userModel = new UserModel();
        $pengajuanModel = new PengajuanModel();
        $jadwalModel = new JadwalSidangModel();

        // 1. Hitung Statistik Kartu Atas
        $totalMhs   = $userModel->where('role', 'mahasiswa')->countAllResults();
        $totalDosen = $userModel->where('role', 'dosen')->countAllResults();
        $totalJudul = $pengajuanModel->where('status', 'diterima')->countAllResults();
        
        // "Arsip Skripsi" kita hitung dari mahasiswa yang sudah status LULUS sidang
        $totalLulus = $jadwalModel->where('status_lulus', 'lulus')->countAllResults();

        // 2. Ambil Data untuk Tabel "Plotting Pending"
        // Logika: Judul sudah 'diterima' TAPI 'dosen_pembimbing_id' masih KOSONG (null)
        $plottingPending = $pengajuanModel->select('tb_pengajuan_judul.*, users.nama_lengkap as nama_mhs')
                                          ->join('users', 'users.id = tb_pengajuan_judul.user_id_mahasiswa')
                                          ->where('status', 'diterima')
                                          ->where('dosen_pembimbing_id', null)
                                          ->orderBy('updated_at', 'DESC')
                                          ->findAll(5); // Batasi 5 baris saja agar tidak kepanjangan

        return view('admin/dashboard', [
            'total_mhs'        => $totalMhs,
            'total_dosen'      => $totalDosen,
            'total_judul'      => $totalJudul,
            'total_lulus'      => $totalLulus,
            'plotting_pending' => $plottingPending
        ]);
    }
}