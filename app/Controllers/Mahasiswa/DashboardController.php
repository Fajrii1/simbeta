<?php

namespace App\Controllers\Mahasiswa;

use App\Controllers\BaseController;
use App\Models\PengajuanModel;
use App\Models\JadwalSidangModel;
use App\Models\BimbinganModel;

class DashboardController extends BaseController
{
    public function index()
    {
        if (session()->get('role') != 'mahasiswa') {
            return redirect()->to('/login');
        }

        $userId = session()->get('id');
        $pengajuanModel = new PengajuanModel();
        $jadwalModel = new JadwalSidangModel();
        $bimbinganModel = new BimbinganModel();

        // Ambil data pengajuan
        $dataPengajuan = $pengajuanModel->where('user_id_mahasiswa', $userId)->first();
        
        $dataSidang = null;
        $persentase = 0; // Default 0%
        $labelProgres = 'Tahap Awal'; // Default Label

        if ($dataPengajuan) {
            // --- LOGIKA HITUNG PROGRES ---
            // 1. Cek apakah FULL DRAFT sudah ACC?
            $cekFull = $bimbinganModel->where('pengajuan_id', $dataPengajuan['id'])
                                      ->where('bab_ke', 'FULL DRAFT')
                                      ->where('status_acc', 'acc')
                                      ->first();
            
            if ($cekFull) {
                $persentase = 100;
                $labelProgres = 'Siap Sidang';
            } else {
                // 2. Hitung jumlah Bab (1-5) yang sudah ACC
                $babAcc = $bimbinganModel->select('bab_ke')
                                         ->where('pengajuan_id', $dataPengajuan['id'])
                                         ->where('status_acc', 'acc')
                                         ->like('bab_ke', 'BAB')
                                         ->groupBy('bab_ke')
                                         ->countAllResults();

                $persentase = ($babAcc / 5) * 100;

                // Tentukan Label
                if($persentase == 0) $labelProgres = 'Persiapan';
                elseif($persentase <= 40) $labelProgres = 'Penyusunan Awal';
                elseif($persentase <= 80) $labelProgres = 'Penyusunan Lanjut';
                else $labelProgres = 'Finishing';
            }

            // Ambil data sidang
            $dataSidang = $jadwalModel->select('tb_jadwal_sidang.*, p1.nama_lengkap as penguji1, p2.nama_lengkap as penguji2')
                ->join('users as p1', 'p1.id = tb_jadwal_sidang.penguji_1_id', 'left')
                ->join('users as p2', 'p2.id = tb_jadwal_sidang.penguji_2_id', 'left')
                ->where('pengajuan_id', $dataPengajuan['id'])
                ->first();
        }

        return view('mahasiswa/dashboard', [
            'pengajuan'     => $dataPengajuan,
            'jadwal_sidang' => $dataSidang,
            'persentase'    => $persentase,
            // Perbaikan kunci array di sini:
            'labelProgres'  => $labelProgres 
        ]);
    }
}