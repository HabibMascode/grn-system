<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table = 'supplier';

    protected $primaryKey = 'id_supp';

    protected $useAutoIncrement = true;

    protected $returnType = 'array';

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'nama_supp'
    ];

    /**
     * Cari supplier berdasarkan nama (untuk autocomplete / pencarian)
     *
     * @param string $keyword
     * @param int $limit
     * @return array
     */
    public function searchByName(string $keyword, int $limit = 20): array
    {
        return $this->like('nama_supp', $keyword)
            ->orderBy('nama_supp', 'ASC')
            ->findAll($limit);
    }
}
