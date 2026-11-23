<?php

namespace App\Controllers;

use App\Models\SupplierModel;
use App\Models\SuratModel;
use App\Models\AccountingSuppModel;

class Supplier extends BaseController
{
    /**
     * Tampilkan daftar supplier & form tambah (Admin only)
     */
    public function index()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $supplierModel = new SupplierModel();
        $keyword = trim((string)$this->request->getGet('keyword'));

        if ($keyword !== '') {
            $supplierModel->like('nama_supp', $keyword);
        }

        $suppliers = $supplierModel->orderBy('nama_supp', 'ASC')->findAll();

        $data = [
            'title'     => 'Manajemen Supplier',
            'suppliers' => $suppliers,
            'keyword'   => $keyword,
        ];

        return view('supplier/list_supp', $data);
    }

    /**
     * Simpan supplier baru (Admin only)
     */
    public function save()
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        $namaSupp = trim((string)$this->request->getPost('nama_supp'));

        if ($namaSupp === '') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Nama supplier wajib diisi.');
        }

        if (mb_strlen($namaSupp) < 2) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Nama supplier minimal 2 karakter.');
        }

        $supplierModel = new SupplierModel();

        // Cek duplikasi nama supplier (case-insensitive)
        $existing = $supplierModel->where('LOWER(nama_supp)', strtolower($namaSupp))->first();
        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Supplier "' . esc($namaSupp) . '" sudah terdaftar.');
        }

        $supplierModel->insert([
            'nama_supp' => $namaSupp
        ]);

        return redirect()->to('/supplier')
            ->with('success', 'Supplier "' . esc($namaSupp) . '" berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit supplier (Admin only)
     */
    public function edit($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        $supplierModel = new SupplierModel();
        $supplier = $supplierModel->find($id);

        if (!$supplier) {
            return redirect()->to('/supplier')
                ->with('error', 'Data supplier tidak ditemukan.');
        }

        $data = [
            'title'    => 'Edit Supplier',
            'supplier' => $supplier,
        ];

        return view('supplier/form_supp', $data);
    }

    /**
     * Simpan perubahan nama supplier (Admin only)
     */
    public function update($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        $supplierModel = new SupplierModel();
        $supplier = $supplierModel->find($id);

        if (!$supplier) {
            return redirect()->to('/supplier')
                ->with('error', 'Data supplier tidak ditemukan.');
        }

        $namaSupp = trim((string)$this->request->getPost('nama_supp'));

        if ($namaSupp === '') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Nama supplier wajib diisi.');
        }

        if (mb_strlen($namaSupp) < 2) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Nama supplier minimal 2 karakter.');
        }

        // Cek duplikasi dengan ID lain
        $duplicate = $supplierModel
            ->where('LOWER(nama_supp)', strtolower($namaSupp))
            ->where('id_supp !=', $id)
            ->first();

        if ($duplicate) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Supplier dengan nama "' . esc($namaSupp) . '" sudah digunakan.');
        }

        $oldName = $supplier['nama_supp'];

        // Update nama supplier
        $supplierModel->update($id, [
            'nama_supp' => $namaSupp
        ]);

        // Sinkronisasi nama supplier pada tabel surat jika sebelumnya tersimpan
        $suratModel = new SuratModel();
        $suratModel->builder()
            ->where('id_supp', $id)
            ->orWhere('nama_supplier', $oldName)
            ->update([
                'nama_supplier' => $namaSupp,
                'id_supp'       => $id
            ]);

        return redirect()->to('/supplier')
            ->with('success', 'Nama supplier berhasil diperbarui.');
    }

    /**
     * Hapus data supplier (Admin only)
     */
    public function delete($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        $supplierModel = new SupplierModel();
        $supplier = $supplierModel->find($id);

        if (!$supplier) {
            return redirect()->to('/supplier')
                ->with('error', 'Supplier tidak ditemukan.');
        }

        // Cek apakah supplier digunakan dalam data surat/GRN
        $suratModel = new SuratModel();
        $hasSurat = $suratModel
            ->where('id_supp', $id)
            ->orWhere('nama_supplier', $supplier['nama_supp'])
            ->first();

        if ($hasSurat) {
            return redirect()->to('/supplier')
                ->with('error', 'Supplier "' . esc($supplier['nama_supp']) . '" tidak dapat dihapus karena masih digunakan pada data GRN.');
        }

        // Hapus relasi di accounting_supplier jika ada
        $accSuppModel = new AccountingSuppModel();
        $accSuppModel->where('supplier_id', $id)->delete();

        // Hapus supplier
        $supplierModel->delete($id);

        return redirect()->to('/supplier')
            ->with('success', 'Supplier "' . esc($supplier['nama_supp']) . '" berhasil dihapus.');
    }

    /**
     * Endpoint API Autocomplete / Pencarian Supplier (JSON)
     */
    public function search()
    {
        $keyword = trim((string)$this->request->getGet('q'));

        $supplierModel = new SupplierModel();
        if ($keyword !== '') {
            $suppliers = $supplierModel->like('nama_supp', $keyword)
                ->orderBy('nama_supp', 'ASC')
                ->findAll(30);
        } else {
            $suppliers = $supplierModel->orderBy('nama_supp', 'ASC')->findAll(30);
        }

        return $this->response->setJSON($suppliers);
    }
}

