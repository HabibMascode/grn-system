<?php

namespace App\Controllers;

class HomeAcc extends BaseController
{
    /**
     * Beranda Accounting - Arahkan ke inbox GRN dengan proteksi hak akses
     */
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        if (session()->get('role') !== 'accounting') {
            return redirect()->to('/home')
                ->with('error', 'Anda tidak memiliki hak akses Accounting.');
        }

        return redirect()->to('/accounting');
    }
}
