<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PengajuanModel;
use App\Models\UserModel; // Kita butuh data Dosen untuk Dropdown

class PlottingController extends BaseController
{
    public function index()
    {
        $pengajuanModel = new PengajuanModel();
        $userModel = new UserModel();

        // 1. Ambil Pengajuan yang STATUS='diterima' tapi BELUM PUNYA PEMBIMBING
        $dataMahasiswa = $pengajuanModel->select('tb_pengajuan_judul.*, users.nama_lengkap as nama_mhs, users.username as nim')
            ->join('users', 'users.id = tb_pengajuan_judul.user_id_mahasiswa')
            ->where('status', 'diterima')
            ->where('dosen_pembimbing_id', null) // Hanya yang belum diplot
            ->findAll();

        // 2. Ambil Daftar Dosen untuk Dropdown
        $dataDosen = $userModel->where('role', 'dosen')->findAll();

        return view('admin/plotting_view', [
            'list_mhs' => $dataMahasiswa,
            'list_dosen' => $dataDosen
        ]);
    }

    public function simpan()
    {
        $pengajuanModel = new PengajuanModel();
        
        $idPengajuan = $this->request->getPost('id_pengajuan');
        $idDosen = $this->request->getPost('dosen_id');

        // Update kolom dosen_pembimbing_id
        $pengajuanModel->update($idPengajuan, [
            'dosen_pembimbing_id' => $idDosen
        ]);

        return redirect()->to('admin/plotting')->with('success', 'Dosen pembimbing berhasil ditetapkan!');
    }
}