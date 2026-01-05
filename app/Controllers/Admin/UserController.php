<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        
        // Ambil semua user urutkan dari yang terbaru
        $dataUser = $userModel->orderBy('id', 'DESC')->findAll();

        return view('admin/user_view', [
            'users' => $dataUser
        ]);
    }

    public function simpan()
    {
        $userModel = new UserModel();

        // Validasi sederhana
        if (!$this->validate([
            'username' => 'required|is_unique[users.username]', // Username (NIM/NIP) gak boleh kembar
            'password' => 'required|min_length[6]',
            'nama'     => 'required'
        ])) {
            return redirect()->to('admin/users')->with('msg', 'Gagal! Username sudah ada atau password kurang kuat.');
        }

        $userModel->save([
            'username'      => $this->request->getPost('username'),
            'password'      => $this->request->getPost('password'), // Akan di-hash otomatis oleh Model
            'nama_lengkap'  => $this->request->getPost('nama'),
            'role'          => $this->request->getPost('role'),
            'foto'          => 'default.jpg' // Default foto
        ]);

        return redirect()->to('admin/users')->with('success', 'User berhasil ditambahkan!');
    }

    public function hapus($id)
    {
        $userModel = new UserModel();
        $userModel->delete($id);
        return redirect()->to('admin/users')->with('success', 'User berhasil dihapus.');
    }
}