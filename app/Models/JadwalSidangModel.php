<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalSidangModel extends Model
{
    protected $table            = 'tb_jadwal_sidang';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'pengajuan_id', 
        'tanggal_sidang', 
        'ruangan', 
        'penguji_1_id', 
        'penguji_2_id', 
        'status_lulus', 
        'nilai_akhir'
    ];
}