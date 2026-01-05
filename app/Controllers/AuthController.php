<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function index()
    {
        // 1. Cek jika user sudah login, lempar langsung ke dashboard
        if (session()->get('isLoggedIn')) {
            return $this->redirectBasedOnRole();
        }

        // 2. Jika belum, tampilkan halaman login
        return view('auth/login');
    }

    public function loginProcess()
    {
        $session = session();
        $model = new UserModel();

        // Ambil input dari form
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        // Cari user berdasarkan username
        $data = $model->where('username', $username)->first();

        if ($data) {
            $pass = $data['password'];
            // Verifikasi password (hash vs plain text)
            $verify_pass = password_verify($password, $pass);

            if ($verify_pass) {
                // Simpan data penting ke SESSION
                $ses_data = [
                    'id'       => $data['id'],
                    'username' => $data['username'],
                    'nama'     => $data['nama_lengkap'],
                    'role'     => $data['role'],
                    'foto'     => $data['foto'],
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);

                // Arahkan ke dashboard sesuai role
                return $this->redirectBasedOnRole();
            } else {
                // Password salah
                $session->setFlashdata('msg', 'Password Salah');
                return redirect()->to('/login');
            }
        } else {
            // Username tidak ditemukan
            $session->setFlashdata('msg', 'Username tidak ditemukan');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }

    // Fungsi bantuan untuk redirect
    private function redirectBasedOnRole()
    {
        $role = session()->get('role');
        
        if ($role == 'admin') {
            return redirect()->to('/admin/dashboard');
        } elseif ($role == 'dosen') {
            return redirect()->to('/dosen/dashboard');
        } elseif ($role == 'mahasiswa') {
            return redirect()->to('/mahasiswa/dashboard');
        }
    }
}