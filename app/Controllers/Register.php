<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AccountingSuppModel;

class Register extends BaseController
{
    /**
     * Halaman Kelola Accounting & form pendaftaran akun Accounting (hanya admin)
     */
    public function index()
    {
        // Hanya admin yang boleh akses
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $userModel    = new UserModel();
        $accSuppModel = new AccountingSuppModel();

        // Ambil hanya pengguna dengan role accounting
        $accountingUsers = $userModel
            ->where('role', 'accounting')
            ->orderBy('id', 'ASC')
            ->findAll();

        // Ambil pemetaan supplier yang ditugaskan ke masing-masing accounting
        $userAssignments = [];
        foreach ($accountingUsers as $u) {
            $userAssignments[$u['id']] = $accSuppModel->getSuppliersByAccounting((int)$u['id']);
        }

        $data = [
            'title'           => 'Kelola Accounting',
            'users'           => $accountingUsers,
            'userAssignments' => $userAssignments,
        ];

        return view('regist/regist_user', $data);
    }

    /**
     * Simpan akun Accounting baru (hanya admin, otomatis role accounting)
     */
    public function save()
    {
        // Hanya admin yang boleh akses
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        $userModel = new UserModel();

        $name     = trim((string)$this->request->getPost('name'));
        $username = trim((string)$this->request->getPost('username'));
        $password = trim((string)$this->request->getPost('password'));

        // ── Validasi wajib isi ──
        if ($name === '' || $username === '' || $password === '') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Nama lengkap, username, dan password wajib diisi.');
        }

        // ── Validasi panjang password ──
        if (strlen($password) < 6) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Password minimal 6 karakter.');
        }

        // ── Cek username sudah ada ──
        $existing = $userModel->where('username', $username)->first();
        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username "' . esc($username) . '" sudah digunakan. Silakan gunakan username lain.');
        }

        // ── Simpan ke database: Role otomatis 'accounting' ──
        $userModel->insert([
            'name'     => $name,
            'username' => $username,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role'     => 'accounting',
        ]);

        return redirect()->to('/register')
            ->with('success', 'Akun Accounting "' . esc($username) . '" berhasil ditambahkan.');
    }

    /**
     * Update akun Accounting (hanya admin)
     */
    public function update($id)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return redirect()->to('/register')
                ->with('error', 'Akun pengguna tidak ditemukan.');
        }

        $name     = trim((string)$this->request->getPost('name'));
        $username = trim((string)$this->request->getPost('username'));
        $password = trim((string)$this->request->getPost('password'));

        // Validasi wajib isi nama dan username
        if ($name === '' || $username === '') {
            return redirect()->to('/register')
                ->with('error', 'Nama lengkap dan username wajib diisi.');
        }

        // Validasi panjang password jika diisi
        if ($password !== '' && strlen($password) < 6) {
            return redirect()->to('/register')
                ->with('error', 'Password minimal 6 karakter.');
        }

        // Cek username sudah digunakan oleh user lain
        $existing = $userModel->where('username', $username)->where('id !=', $id)->first();
        if ($existing) {
            return redirect()->to('/register')
                ->with('error', 'Username "' . esc($username) . '" sudah digunakan oleh akun lain.');
        }

        $updateData = [
            'name'     => $name,
            'username' => $username,
        ];

        // Hanya update password jika diisi
        if ($password !== '') {
            $updateData['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $userModel->update($id, $updateData);

        return redirect()->to('/register')
            ->with('success', 'Akun Accounting "' . esc($username) . '" berhasil diperbarui.');
    }

    /**
     * Hapus akun Accounting (hanya admin, tidak bisa hapus diri sendiri)
     */
    public function delete($id)
    {
        // Hanya admin yang boleh akses
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login')
                ->with('error', 'Anda tidak memiliki akses.');
        }

        // Tidak boleh hapus akun diri sendiri
        if ((int)$id === (int)session()->get('user_id')) {
            return redirect()->to('/register')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return redirect()->to('/register')
                ->with('error', 'Akun pengguna tidak ditemukan.');
        }

        // Hapus penugasan supplier terkait jika ada
        $accSuppModel = new AccountingSuppModel();
        $accSuppModel->where('accounting_id', $id)->delete();

        // Hapus akun dari database
        $userModel->delete($id);

        return redirect()->to('/register')
            ->with('success', 'Akun Accounting "' . esc($user['username']) . '" berhasil dihapus.');
    }
}
