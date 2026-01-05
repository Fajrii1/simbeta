<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cek apakah ada session 'isLoggedIn'
        if (!session()->get('isLoggedIn')) {
            // Kalau tidak ada, arahkan ke halaman login
            return redirect()->to('/login')->with('msg', 'Silakan login terlebih dahulu.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Kosongkan saja (tidak perlu ada aksi setelah request selesai)
    }
}