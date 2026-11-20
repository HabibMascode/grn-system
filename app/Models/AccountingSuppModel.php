<?php

namespace App\Models;

use CodeIgniter\Model;

class AccountingSuppModel extends Model
{
    protected $table = 'accounting_supplier';

    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType = 'array';

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = [
        'accounting_id',
        'supplier_id',
    ];

    /**
     * Mendapatkan daftar supplier_id yang ditugaskan ke accounting user
     *
     * @param int $accountingId
     * @return array List of supplier_id integers
     */
    public function getSupplierIdsByAccounting(int $accountingId): array
    {
        $rows = $this->where('accounting_id', $accountingId)->findAll();
        return array_map(static function ($row) {
            return (int)$row['supplier_id'];
        }, $rows);
    }

    /**
     * Mendapatkan daftar lengkap supplier untuk accounting user tertentu
     *
     * @param int $accountingId
     * @return array
     */
    public function getSuppliersByAccounting(int $accountingId): array
    {
        return $this->db->table('accounting_supplier')
            ->select('supplier.id_supp, supplier.nama_supp, accounting_supplier.id as relation_id')
            ->join('supplier', 'supplier.id_supp = accounting_supplier.supplier_id')
            ->where('accounting_supplier.accounting_id', $accountingId)
            ->orderBy('supplier.nama_supp', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Update/Sinkronisasi supplier untuk accounting user tertentu
     *
     * @param int $accountingId
     * @param array $supplierIds
     * @return bool
     */
    public function syncSuppliers(int $accountingId, array $supplierIds): bool
    {
        $this->db->transStart();

        // Hapus penugasan lama untuk accounting ini
        $this->where('accounting_id', $accountingId)->delete();

        // Masukkan penugasan baru jika ada
        $uniqueIds = array_unique(array_filter(array_map('intval', $supplierIds)));
        if (!empty($uniqueIds)) {
            $dataInsert = [];
            $now = date('Y-m-d H:i:s');
            foreach ($uniqueIds as $suppId) {
                $dataInsert[] = [
                    'accounting_id' => $accountingId,
                    'supplier_id'   => $suppId,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ];
            }
            $this->builder()->insertBatch($dataInsert);
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }
}

if (!class_exists('App\Models\AccountingSupplierModel', false)) {
    class AccountingSupplierModel extends AccountingSuppModel {}
}
