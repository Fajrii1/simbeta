<?php

namespace App\Models;

use CodeIgniter\Model;

class RepositoriModel extends Model
{
    protected $table            = 'tb_repositori_final';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['judul', 'abstrak', 'penulis', 'tahun_lulus', 'file_skripsi'];
    
    // Fitur Pencarian Fulltext
    public function cariKemiripan($keyword)
    {
        // Menggunakan query raw karena Fulltext search lebih spesifik
        // Pastikan $keyword sudah di-escape di controller nanti
        $sql = "SELECT *, MATCH(judul, abstrak) AGAINST (? IN NATURAL LANGUAGE MODE) as skor 
                FROM tb_repositori_final 
                WHERE MATCH(judul, abstrak) AGAINST (? IN NATURAL LANGUAGE MODE) 
                ORDER BY skor DESC LIMIT 5";
        
        return $this->db->query($sql, [$keyword, $keyword])->getResultArray();
    }
}