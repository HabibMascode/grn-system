<?php

namespace App\Controllers;

class HomeAdmin extends BaseController
{
    /**
     * Beranda Admin - Arahkan ke daftar transaksi GRN dengan proteksi hak akses
     */
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        if (session()->get('role') !== 'admin') {
            return redirect()->to('/homeacc')
                ->with('error', 'Anda tidak memiliki hak akses Administrator.');
        }

        return redirect()->to('/grn');
    }
}
