<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        // Jika sudah login, arahkan langsung ke halaman beranda role yang sesuai
        if (session()->get('logged_in')) {
            return session()->get('role') === 'admin'
                ? redirect()->to('/home')
                : redirect()->to('/homeacc');
        }

        return view('auth/login');
    }

    // Memproses login
    public function processLogin()
    {
        $session = session();

        // Ambil data dari form login
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Validasi input
        if (empty($username) || empty($password)) {
            return redirect()->back()
                ->with('error', 'Username dan password wajib diisi.');
        }

        // Panggil UserModel
        $userModel = new UserModel();

        // Cari user berdasarkan username
        $user = $userModel->findByUsername($username);

        // Jika username tidak ditemukan
        if (!$user) {
            return redirect()->back()
                ->with('error', 'Username atau password salah.');
        }

        // Verifikasi password
        if (!$userModel->verifyPassword($password, $user['password'])) {
            return redirect()->back()
                ->with('error', 'Username atau password salah.');
        }

        // Simpan data user ke session
        $sessionData = [
            'user_id'   => $user['id'],
            'username'  => $user['username'],
            'name'      => $user['name'],
            'role'      => $user['role'],
            'logged_in' => true
        ];

        $session->set($sessionData);

        // Arahkan berdasarkan role
        if ($user['role'] === 'admin') {
            return redirect()->to('/home');
        }

        if ($user['role'] === 'accounting') {
            return redirect()->to('/homeacc');
        }

                // Jika role tidak dikenali
                $session->destroy();

                return redirect()->back()
                    ->with('error', 'Role pengguna tidak dikenali.');
            }

            // Logout
            public function logout()
            {
                session()->destroy();

                return redirect()->to('/login');
            }
}