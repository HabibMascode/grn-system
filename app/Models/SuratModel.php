<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratModel extends Model
{
    protected $table = 'surat';

    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType = 'array';

    protected $allowedFields = [
        'nama_supplier',
        'id_supp',
        'no_surat',
        'no_po',
        'no_grn',
        'tanggal_serah',
        'file_surat',
        'file_grn',
        'berita_acara',
        'status',
        'alasan'
    ];
}