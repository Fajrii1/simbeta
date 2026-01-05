<?php

namespace App\Models;

use CodeIgniter\Model;

class BimbinganModel extends Model
{
    // 1. Nama Tabel yang Benar
    protected $table            = 'tb_bimbingan'; 
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // 2. BAGIAN PENTING: Daftar kolom yang boleh diisi (Mencegah Error DataException)
    protected $allowedFields    = [
        'pengajuan_id', 
        'bab_ke', 
        'file_draft', 
        'catatan_mahasiswa', 
        'catatan_dosen', 
        'file_lks', 
        'status_acc', 
        'tanggal_bimbingan'
    ];

    // Dates
    protected $useTimestamps = false; 
}