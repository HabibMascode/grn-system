<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SupplierModel;
use App\Models\AccountingSuppModel;

class AccountingSupp extends BaseController
{
    /**
     * Tampilkan halaman pengaturan supplier untuk user Accounting (Admin only)
     */
    public function index()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $userModel     = new UserModel();
        $supplierModel = new SupplierModel();
        $accSuppModel  = new AccountingSuppModel();

        // Ambil semua pengguna dengan role accounting
        $accountingUsers = $userModel
            ->where('role', 'accounting')
            ->orderBy('name', 'ASC')
            ->findAll();

        // Ambil semua supplier terdaftar
        $allSuppliers = $supplierModel->orderBy('nama_supp', 'ASC')->findAll();

        // Petakan daftar supplier yang ditugaskan ke masing-masing accounting
        $userAssignments = [];
        foreach ($accountingUsers as $user) {
            $userAssignments[$user['id']] = $accSuppModel->getSuppliersByAccounting((int)$user['id']);
        }

        $data = [
            'title'           => 'Pengaturan Supplier Accounting',
            'accountingUsers' => $accountingUsers,
            'allSuppliers'    => $allSuppliers,
            'userAssignments' => $userAssignments,
        ];

        return view('accounting/supplier_assign', $data);
    }

    /**
     * Simpan penugasan supplier untuk user Accounting (Admin only)
     */
    public function save()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        $accountingId = (int)$this->request->getPost('accounting_id');
        $supplierIds  = (array)($this->request->getPost('supplier_ids') ?? []);

        if (!$accountingId) {
            return redirect()->back()
                ->with('error', 'ID user Accounting tidak valid.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($accountingId);

        if (!$user || $user['role'] !== 'accounting') {
            return redirect()->back()
                ->with('error', 'User Accounting tidak ditemukan.');
        }

        $accSuppModel = new AccountingSuppModel();
        $result = $accSuppModel->syncSuppliers($accountingId, $supplierIds);

        if ($result) {
            $totalAssigned = count(array_unique(array_filter(array_map('intval', $supplierIds))));
            return redirect()->to('/accounting-supp')
                ->with('success', 'Berhasil memperbarui supplier ' . esc($user['name']) . ' (' . $totalAssigned . ' supplier dipilih).');
        }

        return redirect()->back()
            ->with('error', 'Gagal menyimpan pengaturan supplier.');
    }

    /**
     * Ambil supplier terpilih untuk user tertentu via JSON (untuk modal ajax)
     */
    public function getSuppliers($accountingId)
    {
        if (session()->get('role') !== 'admin') {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Akses ditolak']);
        }

        $accSuppModel = new AccountingSuppModel();
        $supplierIds = $accSuppModel->getSupplierIdsByAccounting((int)$accountingId);

        return $this->response->setJSON([
            'accounting_id' => (int)$accountingId,
            'supplier_ids'  => $supplierIds,
        ]);
    }
}

