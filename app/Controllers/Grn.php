<?php

namespace App\Controllers;

use App\Models\SuratModel;
use App\Models\SupplierModel;
use App\Models\AccountingSuppModel;

class Grn extends BaseController
{
    /**
     * Menampilkan form tambah GRN
     */
    public function form()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/accounting')->with('error', 'Akses ditolak. Fitur ini hanya untuk Administrator.');
        }

        $supplierModel = new SupplierModel();
        $suppliers = $supplierModel->orderBy('nama_supp', 'ASC')->findAll();

        return view('grn/form', [
            'suppliers' => $suppliers
        ]);
    }


    /**
     * Menampilkan form edit GRN
     */
    public function edit()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/accounting')->with('error', 'Akses ditolak. Fitur ini hanya untuk Administrator.');
        }

        $id      = $this->request->getGet('id');
        $noSurat = $this->request->getGet('no_surat');

        if (!$id && !$noSurat) {
            return redirect()->to('/grn')
                ->with('error', 'Parameter ID atau Nomor Surat tidak ditemukan.');
        }

        $model = new SuratModel();

        $dataGrn = $id
            ? $model->find($id)
            : $model->where('no_surat', $noSurat)->first();

        if (!$dataGrn) {
            return redirect()->to('/grn')
                ->with('error', 'Data GRN tidak ditemukan.');
        }

        // Data yang sudah diterima tidak boleh diedit
        if (strtolower(trim($dataGrn['status'])) === 'diterima') {
            return redirect()->to('/grn')
                ->with('error', 'Data GRN tidak dapat diedit karena status sudah Diterima.');
        }

        $supplierModel = new SupplierModel();
        $suppliers = $supplierModel->orderBy('nama_supp', 'ASC')->findAll();

        return view('grn/form', [
            'editData'  => $dataGrn,
            'suppliers' => $suppliers
        ]);
    }


    /**
     * Menyimpan data GRN baru atau perubahan
     */
    public function submit()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/accounting')->with('error', 'Akses ditolak. Fitur ini hanya untuk Administrator.');
        }

        $model         = new SuratModel();
        $supplierModel = new SupplierModel();

        $mode = $this->request->getPost('mode');
        $id   = $this->request->getPost('id');

        $namaSupplier = trim((string)$this->request->getPost('nama_supplier'));
        $idSupp       = $this->request->getPost('id_supp');
        $tanggalSerah = trim((string)$this->request->getPost('tanggal_serah')) ?: date('Y-m-d');
        $noSurat      = trim((string)$this->request->getPost('no_surat'));
        $noPo         = trim((string)$this->request->getPost('no_po'));
        $noGrn        = trim((string)$this->request->getPost('no_grn'));

        // ==========================================
        // VALIDASI DATA TEKS
        // ==========================================

        if (
            $namaSupplier === '' ||
            $noSurat === '' ||
            $noPo === '' ||
            $noGrn === ''
        ) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Semua data wajib diisi.');
        }

        // ==========================================
        // VALIDASI SUPPLIER TERDAFTAR
        // ==========================================
        $supplier = null;
        if (!empty($idSupp)) {
            $supplier = $supplierModel->find($idSupp);
        }
        if (!$supplier && !empty($namaSupplier)) {
            $supplier = $supplierModel->where('LOWER(nama_supp)', strtolower($namaSupplier))->first();
        }

        if (!$supplier) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Supplier "' . esc($namaSupplier) . '" tidak terdaftar. Harap pilih supplier yang terdaftar pada sistem.');
        }

        $idSupp       = (int)$supplier['id_supp'];
        $namaSupplier = $supplier['nama_supp'];

        // ==========================================
        // MODE CREATE - CEK NO SURAT TERLEBIH DAHULU
        // ==========================================

        if ($mode === 'create') {

            $existing = $model
                ->where('no_surat', $noSurat)
                ->first();

            if ($existing) {
                return redirect()->back()
                    ->withInput()
                    ->with(
                        'error',
                        'No. Surat Jalan sudah terdaftar. Harap gunakan nomor yang berbeda.'
                    );
            }
        }


        // ==========================================
        // AMBIL FILE
        // ==========================================

        $fileSurat       = $this->request->getFile('file_surat');
        $fileGrn         = $this->request->getFile('file_grn');
        $fileBeritaAcara = $this->request->getFile('berita_acara');


        // ==========================================
        // FUNGSI VALIDASI FILE
        // ==========================================

        $allowedExtensions = [
            'jpg',
            'jpeg',
            'png',
            'pdf'
        ];

        $allowedMimes = [
            'image/jpeg',
            'image/jpg',
            'image/pjpeg',
            'image/png',
            'application/pdf'
        ];


        // ==========================================
        // PROSES FILE SURAT
        // ==========================================

        $uploadedSurat = null;

        if ($fileSurat && $fileSurat->isValid() && !$fileSurat->hasMoved()) {

            $ext  = strtolower($fileSurat->getClientExtension());
            $mime = $fileSurat->getMimeType();

            if (
                !in_array($ext, $allowedExtensions) ||
                !in_array($mime, $allowedMimes)
            ) {
                return redirect()->back()
                    ->withInput()
                    ->with(
                        'error',
                        'File Surat hanya boleh JPG, JPEG, PNG, atau PDF.'
                    );
            }

            if ($fileSurat->getSizeByUnit('mb') > 5) {
                return redirect()->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Ukuran File Surat maksimal 5MB.'
                    );
            }

            $targetDir = FCPATH . 'uploads/surat';

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $uploadedSurat = $fileSurat->getRandomName();

            $fileSurat->move(
                $targetDir,
                $uploadedSurat
            );
        }


        // ==========================================
        // PROSES FILE GRN
        // ==========================================

        $uploadedGrn = null;

        if ($fileGrn && $fileGrn->isValid() && !$fileGrn->hasMoved()) {

            $ext  = strtolower($fileGrn->getClientExtension());
            $mime = $fileGrn->getMimeType();

            if (
                !in_array($ext, $allowedExtensions) ||
                !in_array($mime, $allowedMimes)
            ) {
                return redirect()->back()
                    ->withInput()
                    ->with(
                        'error',
                        'File GRN hanya boleh JPG, JPEG, PNG, atau PDF.'
                    );
            }

            if ($fileGrn->getSizeByUnit('mb') > 5) {
                return redirect()->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Ukuran File GRN maksimal 5MB.'
                    );
            }

            $targetDir = FCPATH . 'uploads/grn';

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $uploadedGrn = $fileGrn->getRandomName();

            $fileGrn->move(
                $targetDir,
                $uploadedGrn
            );
        }


        // ==========================================
        // PROSES BERITA ACARA
        // ==========================================

        $uploadedBeritaAcara = null;

        if (
            $fileBeritaAcara &&
            $fileBeritaAcara->isValid() &&
            !$fileBeritaAcara->hasMoved()
        ) {

            $ext  = strtolower($fileBeritaAcara->getClientExtension());
            $mime = $fileBeritaAcara->getMimeType();

            if (
                !in_array($ext, $allowedExtensions) ||
                !in_array($mime, $allowedMimes)
            ) {
                return redirect()->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Berita Acara hanya boleh JPG, JPEG, PNG, atau PDF.'
                    );
            }

            if ($fileBeritaAcara->getSizeByUnit('mb') > 5) {
                return redirect()->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Ukuran Berita Acara maksimal 5MB.'
                    );
            }

            $targetDir = FCPATH . 'uploads/berita_acara';

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $uploadedBeritaAcara = $fileBeritaAcara->getRandomName();

            $fileBeritaAcara->move(
                $targetDir,
                $uploadedBeritaAcara
            );
        }


        // ==========================================
        // CREATE DATA BARU
        // ==========================================

        if ($mode === 'create') {

            // File Surat wajib
            if (!$uploadedSurat) {
                return redirect()->back()
                    ->withInput()
                    ->with(
                        'error',
                        'File Surat wajib diunggah.'
                    );
            }

            // File GRN wajib
            if (!$uploadedGrn) {
                return redirect()->back()
                    ->withInput()
                    ->with(
                        'error',
                        'File GRN wajib diunggah.'
                    );
            }

            $data = [
                'nama_supplier'    => $namaSupplier,
                'id_supp'          => $idSupp,
                'no_surat'         => $noSurat,
                'no_po'            => $noPo,
                'no_grn'           => $noGrn,
                'tanggal_serah'    => $tanggalSerah,
                'file_surat'       => $uploadedSurat,
                'file_grn'         => $uploadedGrn,
                'berita_acara'     => $uploadedBeritaAcara,
                'status'           => 'diserahkan',
                'alasan'           => null
            ];

            $model->insert($data);

            return redirect()->to('/grn')
                ->with(
                    'success',
                    'Data GRN berhasil diserahkan kepada Accounting.'
                );
        }


        // ==========================================
        // EDIT DATA
        // ==========================================

        if ($mode === 'edit') {

            $existing = $id
                ? $model->find($id)
                : $model->where('no_surat', $noSurat)->first();

            if (!$existing) {
                return redirect()->to('/grn')
                    ->with(
                        'error',
                        'Data GRN tidak ditemukan.'
                    );
            }


            // Tidak boleh edit jika sudah diterima
            if (strtolower(trim($existing['status'])) === 'diterima') {
                return redirect()->to('/grn')
                    ->with(
                        'error',
                        'Data GRN tidak dapat diedit karena status sudah Diterima.'
                    );
            }


            // Cek no surat agar tidak duplikat
            $duplicate = $model
                ->where('no_surat', $noSurat)
                ->where('id !=', $existing['id'])
                ->first();

            if ($duplicate) {
                return redirect()->back()
                    ->withInput()
                    ->with(
                        'error',
                        'No. Surat Jalan sudah terdaftar pada data lain.'
                    );
            }


            // ==========================================
            // FILE SURAT
            // ==========================================

            $finalSurat = $existing['file_surat'];

            if ($uploadedSurat) {

                if (
                    !empty($existing['file_surat']) &&
                    file_exists(
                        FCPATH . 'uploads/surat/' .
                        $existing['file_surat']
                    )
                ) {
                    @unlink(
                        FCPATH . 'uploads/surat/' .
                        $existing['file_surat']
                    );
                }

                $finalSurat = $uploadedSurat;
            }


            // ==========================================
            // FILE GRN
            // ==========================================

            $finalGrn = $existing['file_grn'];

            if ($uploadedGrn) {

                if (
                    !empty($existing['file_grn']) &&
                    file_exists(
                        FCPATH . 'uploads/grn/' .
                        $existing['file_grn']
                    )
                ) {
                    @unlink(
                        FCPATH . 'uploads/grn/' .
                        $existing['file_grn']
                    );
                }

                $finalGrn = $uploadedGrn;
            }


            // File Surat wajib tersedia
            if (empty($finalSurat)) {
                return redirect()->back()
                    ->withInput()
                    ->with(
                        'error',
                        'File Surat tidak boleh kosong.'
                    );
            }


            // File GRN wajib tersedia
            if (empty($finalGrn)) {
                return redirect()->back()
                    ->withInput()
                    ->with(
                        'error',
                        'File GRN tidak boleh kosong.'
                    );
            }


            // ==========================================
            // BERITA ACARA
            // ==========================================

            $finalBeritaAcara = $existing['berita_acara'];

            if ($uploadedBeritaAcara) {

                if (
                    !empty($existing['berita_acara']) &&
                    file_exists(
                        FCPATH . 'uploads/berita_acara/' .
                        $existing['berita_acara']
                    )
                ) {
                    @unlink(
                        FCPATH . 'uploads/berita_acara/' .
                        $existing['berita_acara']
                    );
                }

                $finalBeritaAcara = $uploadedBeritaAcara;
            }


            // ==========================================
            // UPDATE DATABASE
            // ==========================================

            $updateData = [
                'nama_supplier'    => $namaSupplier,
                'id_supp'          => $idSupp,
                'no_surat'         => $noSurat,
                'no_po'            => $noPo,
                'no_grn'           => $noGrn,
                'tanggal_serah'    => $tanggalSerah,
                'file_surat'       => $finalSurat,
                'file_grn'         => $finalGrn,
                'berita_acara'     => $finalBeritaAcara,
                'status'           => 'diserahkan',
                'alasan'           => null
            ];

            $model->update(
                $existing['id'],
                $updateData
            );

            return redirect()->to('/grn')
                ->with(
                    'success',
                    'Data GRN berhasil diperbarui dan diserahkan kembali.'
                );
        }


        // ==========================================
        // MODE TIDAK DIKENALI
        // ==========================================

        return redirect()->to('/grn')
            ->with(
                'error',
                'Permintaan tidak valid.'
            );
    }


    // ==========================================
    // SEARCH DATA (ADMIN)
    // ==========================================

    public function index()
    {
        $model = new SuratModel();

        $field   = $this->request->getGet('field');
        $keyword = $this->request->getGet('keyword');

        $allowedFields = [
            'nama_supplier',
            'no_surat',
            'no_po',
            'no_grn'
        ];

        if (
            $field &&
            $keyword &&
            in_array($field, $allowedFields)
        ) {
            $model->like($field, $keyword);
        }

        $data = [
            'title'   => 'Inbox GRN',
            'surat'   => $model->orderBy('id', 'DESC')->findAll(),
            'field'   => $field,
            'keyword' => $keyword
        ];

        return view('grn/list', $data);
    }

    // ==========================================
    // HALAMAN ACCOUNTING (DENGAN FILTER SUPPLIER)
    // ==========================================

    public function accounting()
    {
        $model = new SuratModel();

        $field   = $this->request->getGet('field');
        $keyword = $this->request->getGet('keyword');

        $allowedFields = [
            'nama_supplier',
            'no_surat',
            'no_po',
            'no_grn',
            'status'
        ];

        $isAccounting = (session()->get('role') === 'accounting');
        $assignedSuppliers = [];
        $hasNoSuppliers = false;

        if ($isAccounting) {
            $accountingId = (int)session()->get('user_id');
            $accSuppModel = new AccountingSuppModel();
            $assignedSuppliers = $accSuppModel->getSuppliersByAccounting($accountingId);
            $assignedIds   = array_column($assignedSuppliers, 'id_supp');
            $assignedNames = array_column($assignedSuppliers, 'nama_supp');

            if (empty($assignedIds) && empty($assignedNames)) {
                // Accounting belum ditugaskan supplier manapun
                $surat = [];
                $hasNoSuppliers = true;
            } else {
                $builder = $model->builder();
                $builder->groupStart();
                if (!empty($assignedIds)) {
                    $builder->whereIn('id_supp', $assignedIds);
                }
                if (!empty($assignedNames)) {
                    $builder->orWhereIn('nama_supplier', $assignedNames);
                }
                $builder->groupEnd();

                if (
                    $field &&
                    $keyword &&
                    in_array($field, $allowedFields)
                ) {
                    $builder->like($field, $keyword);
                }

                $surat = $builder->orderBy('id', 'DESC')->get()->getResultArray();
            }
        } else {
            // Admin: Melihat semua data
            if (
                $field &&
                $keyword &&
                in_array($field, $allowedFields)
            ) {
                $model->like($field, $keyword);
            }

            $surat = $model->orderBy('id', 'DESC')->findAll();
        }

        $data = [
            'title'             => 'Inbox GRN',
            'surat'             => $surat,
            'field'             => $field,
            'keyword'           => $keyword,
            'assignedSuppliers' => $assignedSuppliers,
            'hasNoSuppliers'    => $hasNoSuppliers,
        ];

        return view('accounting/list', $data);
    }


    // ==========================================
    // TERIMA GRN
    // ==========================================

    public function terima()
    {
        $id = $this->request->getPost('id');

        if (!$id) {
            return redirect()->to('/accounting')
                ->with('error', 'ID data GRN tidak ditemukan.');
        }

        $model = new SuratModel();
        $surat = $model->find($id);

        if (!$surat) {
            return redirect()->to('/accounting')
                ->with('error', 'Data GRN tidak ditemukan.');
        }

        // Verifikasi wewenang accounting terhadap supplier
        if (session()->get('role') === 'accounting') {
            $accountingId  = (int)session()->get('user_id');
            $accSuppModel  = new AccountingSuppModel();
            $assignedSupps = $accSuppModel->getSuppliersByAccounting($accountingId);
            $assignedIds   = array_column($assignedSupps, 'id_supp');
            $assignedNames = array_column($assignedSupps, 'nama_supp');

            $isAllowed = (
                (!empty($surat['id_supp']) && in_array((int)$surat['id_supp'], $assignedIds)) ||
                in_array($surat['nama_supplier'], $assignedNames)
            );

            if (!$isAllowed) {
                return redirect()->to('/accounting')
                    ->with('error', 'Anda tidak memiliki wewenang untuk memproses dokumen dari supplier ini.');
            }
        }

        $model->update($id, [
            'status' => 'diterima',
            'alasan' => null
        ]);

        return redirect()->to('/accounting')
            ->with('success', 'GRN berhasil diterima dan disimpan.');
    }


    // ==========================================
    // TOLAK GRN
    // ==========================================

    public function tolak()
    {
        $id     = $this->request->getPost('id');
        $alasan = trim((string)($this->request->getPost('alasan') ?? $this->request->getPost('alasan_penolakan') ?? ''));

        if (!$id) {
            return redirect()->to('/accounting')
                ->with('error', 'ID data GRN tidak ditemukan.');
        }

        if ($alasan === '') {
            return redirect()->to('/accounting')
                ->with('error', 'Alasan penolakan wajib diisi.');
        }

        $model = new SuratModel();

        $existing = $model->find($id);
        if (!$existing) {
            return redirect()->to('/accounting')
                ->with('error', 'Data GRN tidak ditemukan.');
        }

        // Verifikasi wewenang accounting terhadap supplier
        if (session()->get('role') === 'accounting') {
            $accountingId  = (int)session()->get('user_id');
            $accSuppModel  = new AccountingSuppModel();
            $assignedSupps = $accSuppModel->getSuppliersByAccounting($accountingId);
            $assignedIds   = array_column($assignedSupps, 'id_supp');
            $assignedNames = array_column($assignedSupps, 'nama_supp');

            $isAllowed = (
                (!empty($existing['id_supp']) && in_array((int)$existing['id_supp'], $assignedIds)) ||
                in_array($existing['nama_supplier'], $assignedNames)
            );

            if (!$isAllowed) {
                return redirect()->to('/accounting')
                    ->with('error', 'Anda tidak memiliki wewenang untuk memproses dokumen dari supplier ini.');
            }
        }

        $model->update($id, [
            'status' => 'ditolak',
            'alasan' => $alasan
        ]);

        return redirect()->to('/accounting')
            ->with('success', 'GRN berhasil ditolak.');
    }
}
