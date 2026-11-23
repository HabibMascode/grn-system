<?php

namespace App\Controllers;

use App\Models\SuratModel;
use App\Models\SupplierModel;
use App\Models\AccountingSuppModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class Api extends BaseController
{
    /**
     * Format response helper
     */
    private function jsonResponse(bool $success, string $message, $data = null, int $statusCode = 200): ResponseInterface
    {
        $payload = [
            'success' => $success,
            'message' => $message,
        ];

        if ($data !== null) {
            $payload['data'] = $data;
        }

        return $this->response
            ->setStatusCode($statusCode)
            ->setContentType('application/json')
            ->setJSON($payload);
    }

    /**
     * Format data surat dengan URL lampiran publik
     */
    private function formatSuratRow(array $row): array
    {
        $row['file_surat_url'] = !empty($row['file_surat'])
            ? base_url('uploads/surat/' . $row['file_surat'])
            : null;

        $row['file_grn_url'] = !empty($row['file_grn'])
            ? base_url('uploads/grn/' . $row['file_grn'])
            : null;

        $row['berita_acara_url'] = !empty($row['berita_acara'])
            ? base_url('uploads/berita_acara/' . $row['berita_acara'])
            : null;

        return $row;
    }

    /**
     * GET /api/config
     * Memberikan info konfigurasi backend & URL lampiran untuk frontend terpisah
     */
    public function config(): ResponseInterface
    {
        return $this->jsonResponse(true, 'Konfigurasi backend berhasil dimuat', [
            'base_url'    => base_url(),
            'api_version' => '1.0.0',
            'uploads'     => [
                'surat'        => base_url('uploads/surat/'),
                'grn'          => base_url('uploads/grn/'),
                'berita_acara' => base_url('uploads/berita_acara/'),
            ],
        ]);
    }

    /**
     * POST /api/auth/login
     */
    public function login(): ResponseInterface
    {
        // Ambil data dari JSON body atau x-www-form-urlencoded
        $json = $this->request->getJSON(true);
        $username = trim((string)($json['username'] ?? $this->request->getPost('username')));
        $password = trim((string)($json['password'] ?? $this->request->getPost('password')));

        if (empty($username) || empty($password)) {
            return $this->jsonResponse(false, 'Username dan password wajib diisi.', null, 400);
        }

        $userModel = new UserModel();
        $user = $userModel->findByUsername($username);

        if (!$user || !$userModel->verifyPassword($password, $user['password'])) {
            return $this->jsonResponse(false, 'Username atau password salah.', null, 401);
        }

        // Set session
        $sessionData = [
            'user_id'   => $user['id'],
            'username'  => $user['username'],
            'name'      => $user['name'],
            'role'      => $user['role'],
            'logged_in' => true
        ];
        session()->set($sessionData);

        return $this->jsonResponse(true, 'Login berhasil.', [
            'user' => [
                'id'       => (int)$user['id'],
                'username' => $user['username'],
                'name'     => $user['name'],
                'role'     => $user['role'],
            ]
        ]);
    }

    /**
     * GET /api/auth/me
     */
    public function me(): ResponseInterface
    {
        if (!session()->get('logged_in')) {
            return $this->jsonResponse(false, 'Belum login / sesi telah berakhir.', null, 401);
        }

        return $this->jsonResponse(true, 'Sesi aktif.', [
            'user' => [
                'id'       => (int)session()->get('user_id'),
                'username' => session()->get('username'),
                'name'     => session()->get('name'),
                'role'     => session()->get('role'),
            ]
        ]);
    }

    /**
     * POST /api/auth/logout
     */
    public function logout(): ResponseInterface
    {
        session()->destroy();
        return $this->jsonResponse(true, 'Logout berhasil.');
    }

    /**
     * GET /api/grn
     * Daftar GRN (dengan filter & URL lampiran)
     */
    public function getGrnList(): ResponseInterface
    {
        $keyword = trim((string)$this->request->getGet('keyword'));
        $field   = trim((string)$this->request->getGet('field'));
        $status  = trim((string)$this->request->getGet('status'));

        $model = new SuratModel();
        $builder = $model->builder();

        if ($keyword !== '') {
            $allowedFields = ['nama_supplier', 'no_surat', 'no_po', 'no_grn'];
            if (in_array($field, $allowedFields, true)) {
                $builder->like($field, $keyword);
            } else {
                $builder->groupStart()
                    ->like('nama_supplier', $keyword)
                    ->orLike('no_surat', $keyword)
                    ->orLike('no_po', $keyword)
                    ->orLike('no_grn', $keyword)
                    ->groupEnd();
            }
        }

        if ($status !== '') {
            $builder->where('status', $status);
        }

        $builder->orderBy('id', 'DESC');
        $rows = $builder->get()->getResultArray();

        $formatted = array_map([$this, 'formatSuratRow'], $rows);

        return $this->jsonResponse(true, 'Data GRN berhasil dimuat', $formatted);
    }

    /**
     * GET /api/grn/(:num)
     * Detail satu GRN
     */
    public function getGrnDetail($id): ResponseInterface
    {
        $model = new SuratModel();
        $row = $model->find($id);

        if (!$row) {
            return $this->jsonResponse(false, 'Data GRN tidak ditemukan', null, 404);
        }

        return $this->jsonResponse(true, 'Detail GRN berhasil dimuat', $this->formatSuratRow($row));
    }

    /**
     * POST /api/grn/submit
     * Upload & Buat Data GRN Baru (Multipart Form Data)
     */
    public function submitGrn(): ResponseInterface
    {
        $model         = new SuratModel();
        $supplierModel = new SupplierModel();

        $namaSupplier = trim((string)$this->request->getPost('nama_supplier'));
        $idSupp       = (int)$this->request->getPost('id_supp');
        $noSurat      = trim((string)$this->request->getPost('no_surat'));
        $noPo         = trim((string)$this->request->getPost('no_po'));
        $noGrn        = trim((string)$this->request->getPost('no_grn'));
        $tanggalSerah = trim((string)$this->request->getPost('tanggal_serah'));

        if ($namaSupplier === '' || $noSurat === '' || $noPo === '' || $noGrn === '' || $tanggalSerah === '') {
            return $this->jsonResponse(false, 'Semua kolom bertanda wajib harus diisi.', null, 400);
        }

        // Cek duplikasi no_surat
        $existing = $model->where('no_surat', $noSurat)->first();
        if ($existing) {
            return $this->jsonResponse(false, 'No. Surat Jalan sudah terdaftar. Harap gunakan nomor yang berbeda.', null, 422);
        }

        // Validasi Supplier
        $supplier = null;
        if ($idSupp > 0) {
            $supplier = $supplierModel->find($idSupp);
        }
        if (!$supplier && !empty($namaSupplier)) {
            $supplier = $supplierModel->where('LOWER(nama_supp)', strtolower($namaSupplier))->first();
        }
        if ($supplier) {
            $idSupp       = (int)$supplier['id_supp'];
            $namaSupplier = $supplier['nama_supp'];
        }

        // Upload Files
        $allowedExts  = ['jpg', 'jpeg', 'png', 'pdf'];
        $uploadedSurat = null;
        $uploadedGrn   = null;
        $uploadedBerita = null;

        // 1. File Surat (Wajib)
        $fileSurat = $this->request->getFile('file_surat');
        if (!$fileSurat || !$fileSurat->isValid() || $fileSurat->hasMoved()) {
            return $this->jsonResponse(false, 'File Surat wajib diunggah.', null, 400);
        }
        $ext = strtolower($fileSurat->getClientExtension());
        if (!in_array($ext, $allowedExts, true) || $fileSurat->getSizeByUnit('mb') > 5) {
            return $this->jsonResponse(false, 'File Surat harus berupa JPG/PNG/PDF dan maksimal 5MB.', null, 400);
        }
        $dirSurat = FCPATH . 'uploads/surat';
        if (!is_dir($dirSurat)) {
            mkdir($dirSurat, 0777, true);
        }
        $uploadedSurat = $fileSurat->getRandomName();
        $fileSurat->move($dirSurat, $uploadedSurat);

        // 2. File GRN (Wajib)
        $fileGrn = $this->request->getFile('file_grn');
        if (!$fileGrn || !$fileGrn->isValid() || $fileGrn->hasMoved()) {
            return $this->jsonResponse(false, 'File GRN wajib diunggah.', null, 400);
        }
        $ext = strtolower($fileGrn->getClientExtension());
        if (!in_array($ext, $allowedExts, true) || $fileGrn->getSizeByUnit('mb') > 5) {
            return $this->jsonResponse(false, 'File GRN harus berupa JPG/PNG/PDF dan maksimal 5MB.', null, 400);
        }
        $dirGrn = FCPATH . 'uploads/grn';
        if (!is_dir($dirGrn)) {
            mkdir($dirGrn, 0777, true);
        }
        $uploadedGrn = $fileGrn->getRandomName();
        $fileGrn->move($dirGrn, $uploadedGrn);

        // 3. Berita Acara (Opsional)
        $fileBerita = $this->request->getFile('berita_acara');
        if ($fileBerita && $fileBerita->isValid() && !$fileBerita->hasMoved()) {
            $ext = strtolower($fileBerita->getClientExtension());
            if (in_array($ext, $allowedExts, true) && $fileBerita->getSizeByUnit('mb') <= 5) {
                $dirBerita = FCPATH . 'uploads/berita_acara';
                if (!is_dir($dirBerita)) {
                    mkdir($dirBerita, 0777, true);
                }
                $uploadedBerita = $fileBerita->getRandomName();
                $fileBerita->move($dirBerita, $uploadedBerita);
            }
        }

        $data = [
            'nama_supplier' => $namaSupplier,
            'id_supp'       => $idSupp ?: null,
            'no_surat'      => $noSurat,
            'no_po'         => $noPo,
            'no_grn'        => $noGrn,
            'tanggal_serah' => $tanggalSerah,
            'file_surat'    => $uploadedSurat,
            'file_grn'      => $uploadedGrn,
            'berita_acara'  => $uploadedBerita,
            'status'        => 'diserahkan',
            'alasan'        => null,
        ];

        $insertedId = $model->insert($data);
        $data['id'] = $insertedId;

        return $this->jsonResponse(true, 'Data GRN berhasil dibuat.', $this->formatSuratRow($data), 201);
    }

    /**
     * GET /api/accounting
     * Daftar dokumen untuk Accounting (difilter berdasarkan penugasan supplier akun)
     */
    public function getAccountingList(): ResponseInterface
    {
        $model = new SuratModel();
        $isAccounting = (session()->get('role') === 'accounting');

        if ($isAccounting) {
            $accountingId = (int)session()->get('user_id');
            $accSuppModel = new AccountingSuppModel();
            $assignedSupps = $accSuppModel->getSuppliersByAccounting($accountingId);

            if (empty($assignedSupps)) {
                return $this->jsonResponse(true, 'Belum ada supplier yang ditugaskan ke akun ini.', []);
            }

            $assignedIds   = array_column($assignedSupps, 'id_supp');
            $assignedNames = array_column($assignedSupps, 'nama_supp');

            $builder = $model->builder();
            $builder->groupStart();
            if (!empty($assignedIds)) {
                $builder->whereIn('id_supp', $assignedIds);
            }
            if (!empty($assignedNames)) {
                $builder->orWhereIn('nama_supplier', $assignedNames);
            }
            $builder->groupEnd();
            $builder->orderBy('id', 'DESC');
            $rows = $builder->get()->getResultArray();
        } else {
            // Admin melihat semua
            $rows = $model->orderBy('id', 'DESC')->findAll();
        }

        $formatted = array_map([$this, 'formatSuratRow'], $rows);
        return $this->jsonResponse(true, 'Data accounting berhasil dimuat.', $formatted);
    }

    /**
     * POST /api/accounting/terima
     */
    public function terimaAccounting(): ResponseInterface
    {
        $json = $this->request->getJSON(true);
        $id = (int)($json['id'] ?? $this->request->getPost('id'));

        if (!$id) {
            return $this->jsonResponse(false, 'Parameter ID GRN tidak valid.', null, 400);
        }

        $model = new SuratModel();
        $surat = $model->find($id);
        if (!$surat) {
            return $this->jsonResponse(false, 'Data GRN tidak ditemukan.', null, 404);
        }

        $model->update($id, [
            'status' => 'diterima',
            'alasan' => null,
        ]);

        return $this->jsonResponse(true, 'Dokumen GRN berhasil diterima.');
    }

    /**
     * POST /api/accounting/tolak
     */
    public function tolakAccounting(): ResponseInterface
    {
        $json = $this->request->getJSON(true);
        $id     = (int)($json['id'] ?? $this->request->getPost('id'));
        $alasan = trim((string)($json['alasan'] ?? $this->request->getPost('alasan')));

        if (!$id) {
            return $this->jsonResponse(false, 'Parameter ID GRN tidak valid.', null, 400);
        }
        if ($alasan === '') {
            return $this->jsonResponse(false, 'Alasan penolakan wajib diisi.', null, 400);
        }

        $model = new SuratModel();
        $surat = $model->find($id);
        if (!$surat) {
            return $this->jsonResponse(false, 'Data GRN tidak ditemukan.', null, 404);
        }

        $model->update($id, [
            'status' => 'ditolak',
            'alasan' => $alasan,
        ]);

        return $this->jsonResponse(true, 'Dokumen GRN berhasil ditolak.');
    }

    /**
     * GET /api/suppliers
     */
    public function getSuppliers(): ResponseInterface
    {
        $keyword = trim((string)$this->request->getGet('q'));
        $supplierModel = new SupplierModel();

        if ($keyword !== '') {
            $suppliers = $supplierModel->like('nama_supp', $keyword)
                ->orderBy('nama_supp', 'ASC')
                ->findAll(50);
        } else {
            $suppliers = $supplierModel->orderBy('nama_supp', 'ASC')->findAll();
        }

        return $this->jsonResponse(true, 'Daftar supplier berhasil dimuat.', $suppliers);
    }
}

