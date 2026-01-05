<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->get('/', 'Home::index');

// ... kode default routes lain ...

// 1. Route untuk Login & Logout
$routes->get('/', 'AuthController::index');       // Halaman awal langsung login
$routes->get('/login', 'AuthController::index');  // URL /login
$routes->post('/login/auth', 'AuthController::loginProcess'); // Proses Form Login
$routes->get('/logout', 'AuthController::logout'); // Proses Logout

// 2. Route Sementara untuk Dashboard (Agar tidak error saat redirect)
// Nanti kita buat Controller dashboard benerannya
$routes->group('mahasiswa', function($routes) {
    $routes->get('dashboard', 'Mahasiswa\DashboardController::index');
    $routes->get('pengajuan', 'Mahasiswa\PengajuanController::index'); // Halaman Form
    $routes->post('pengajuan/simpan', 'Mahasiswa\PengajuanController::simpan'); // Proses Simpan
    $routes->get('bimbingan', 'Mahasiswa\BimbinganController::index'); // Halaman Bimbingan
    $routes->post('bimbingan/upload', 'Mahasiswa\BimbinganController::upload'); // Proses Upload

    $routes->get('cek-judul', 'Mahasiswa\PengajuanController::cek_kemiripan'); // <--- Route AJAX
});

$routes->group('dosen', function($routes) {
    $routes->get('dashboard', 'Dosen\DashboardController::index');
    
    // TAMBAHKAN INI:
    $routes->get('validasi', 'Dosen\ValidasiController::index'); // List Pengajuan
    $routes->post('validasi/proses', 'Dosen\ValidasiController::proses'); // Action Terima/Tolak

    $routes->get('bimbingan', 'Dosen\BimbinganController::index'); // List Mahasiswa
    $routes->get('bimbingan/detail/(:num)', 'Dosen\BimbinganController::detail/$1'); // Detail Chat/Logbook
    $routes->post('bimbingan/respon', 'Dosen\BimbinganController::respon'); // Kirim Feedback
});

$routes->group('admin', function($routes) {
    $routes->get('dashboard', 'Admin\DashboardController::index');
    
    // TAMBAHKAN INI: Modul Plotting
    $routes->get('plotting', 'Admin\PlottingController::index'); // List Mahasiswa siap plotting
    $routes->post('plotting/simpan', 'Admin\PlottingController::simpan'); // Proses simpan dosen
    
    $routes->get('sidang', 'Admin\SidangController::index'); // List Mahasiswa Siap Sidang
    $routes->post('sidang/simpan', 'Admin\SidangController::simpan'); // Simpan Jadwal

    $routes->get('users', 'Admin\UserController::index'); // List User
    $routes->post('users/simpan', 'Admin\UserController::simpan'); // Simpan User Baru
    $routes->get('users/hapus/(:num)', 'Admin\UserController::hapus/$1'); // Hapus User

    // [!!!] TAMBAHKAN BARIS INI UNTUK UPDATE:
    $routes->post('users/update', 'Admin\UserController::update');

    $routes->post('sidang/update_hasil', 'Admin\SidangController::update_hasil'); // <--- Route Baru
});

// Route Profile (Bisa diakses semua user yang login)
$routes->get('profile', 'ProfileController::index', ['filter' => 'auth']);
$routes->post('profile/update', 'ProfileController::update', ['filter' => 'auth']);


