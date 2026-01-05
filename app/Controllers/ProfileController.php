<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class ProfileController extends BaseController
{
    public function index()
    {
        return view('profile_view');
    }

    public function update()
    {
        $userModel = new UserModel();
        $idUser = session()->get('id');
        
        // 1. Validasi Input
        if (!$this->validate([
            'foto_profil' => [
                'rules' => 'uploaded[foto_profil]|is_image[foto_profil]|mime_in[foto_profil,image/jpg,image/jpeg,image/png]|max_size[foto_profil,2048]',
                'errors' => [
                    'uploaded' => 'Pilih foto terlebih dahulu.',
                    'is_image' => 'File harus berupa gambar.',
                    'mime_in' => 'Format harus JPG, JPEG, atau PNG.',
                    'max_size' => 'Ukuran maksimal 2MB.'
                ]
            ]
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 2. Proses Upload File
        $fileFoto = $this->request->getFile('foto_profil');
        
        // Generate nama unik agar tidak bentrok
        $namaFotoBaru = $fileFoto->getRandomName();
        
        // Pindahkan file ke folder public/assets/img/profile
        $fileFoto->move('assets/img/profile', $namaFotoBaru);

        // 3. Update Database
        $userModel->update($idUser, [
            'foto' => $namaFotoBaru
        ]);

        // 4. PENTING: Update Session Foto (Agar sidebar langsung berubah)
        session()->set('foto', $namaFotoBaru);

        return redirect()->to('profile')->with('success', 'Foto profil berhasil diperbarui!');
    }
}