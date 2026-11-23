<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<style>
/* ═══════════════════════════════════════════
   PAGINATION MODERN — ACCOUNTING INBOX
═══════════════════════════════════════════ */

.acc-table-card {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.04), 0 2px 4px -2px rgba(0, 0, 0, 0.04);
    overflow: hidden;
    background: #ffffff;
}

/* Toolbar atas tabel */
.acc-toolbar {
    border-bottom: 1px solid #f1f5f9;
    background: #ffffff;
    padding: 12px 20px;
}
.acc-toolbar-dot {
    display: inline-block;
    width: 8px; height: 8px;
    border-radius: 50%;
    background: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18);
    flex-shrink: 0;
}

/* Select per-page modern */
.acc-perpage-wrap {
    position: relative;
    display: inline-flex;
    align-items: center;
}
.acc-perpage-select {
    appearance: none;
    -webkit-appearance: none;
    padding: 5px 30px 5px 12px;
    font-size: 0.8125rem;
    font-weight: 600;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    background: #ffffff;
    color: #1e293b;
    cursor: pointer;
    outline: none;
    transition: all .2s ease;
}
.acc-perpage-select:hover {
    border-color: #cbd5e1;
    background: #f8fafc;
}
.acc-perpage-select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}
.acc-perpage-icon {
    position: absolute;
    right: 10px;
    font-size: 10px;
    color: #64748b;
    pointer-events: none;
    transition: color .2s;
}
.acc-perpage-select:focus ~ .acc-perpage-icon {
    color: #2563eb;
}

/* Footer pagination bar */
.acc-pagination-bar {
    border-top: 1px solid #f1f5f9;
    background: #ffffff;
    padding: 14px 20px;
}
.acc-page-summary {
    font-size: 0.825rem;
    color: #64748b;
    font-weight: 500;
}

/* Tombol-tombol pagination modern */
.acc-pagination {
    display: flex;
    align-items: center;
    gap: 6px;
    list-style: none;
    padding: 0;
    margin: 0;
}
.acc-pagination .acc-pg-item a,
.acc-pagination .acc-pg-item span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 10px;
    border-radius: 8px;
    font-size: 0.8125rem;
    font-weight: 600;
    border: 1px solid #e2e8f0;
    color: #475569;
    background: #ffffff;
    text-decoration: none;
    cursor: pointer;
    transition: all .18s ease;
    user-select: none;
    white-space: nowrap;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
}
.acc-pagination .acc-pg-item a:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #1e293b;
    transform: translateY(-1px);
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.06);
}
.acc-pagination .acc-pg-item.active a {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    border-color: transparent;
    color: #ffffff;
    box-shadow: 0 4px 10px rgba(37, 99, 235, 0.35);
    transform: translateY(-1px);
}
.acc-pagination .acc-pg-item.active a:hover {
    box-shadow: 0 6px 14px rgba(37, 99, 235, 0.45);
}
.acc-pagination .acc-pg-item.disabled span,
.acc-pagination .acc-pg-item.disabled a {
    color: #cbd5e1;
    border-color: #f1f5f9;
    background: #f8fafc;
    cursor: not-allowed;
    pointer-events: none;
    transform: none;
    box-shadow: none;
}
/* Tombol prev/next */
.acc-pagination .acc-pg-nav a {
    gap: 6px;
    padding: 0 14px;
    font-size: 0.8125rem;
    font-weight: 500;
    color: #334155;
    background: #ffffff;
    border-color: #e2e8f0;
}
.acc-pagination .acc-pg-nav a:hover {
    background: #f1f5f9;
    color: #0f172a;
}
.acc-pagination .acc-pg-ellipsis span {
    border-color: transparent;
    background: transparent;
    color: #94a3b8;
    cursor: default;
    pointer-events: none;
    box-shadow: none;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div>

    <!-- HEADER HALAMAN -->
    <div class="mb-4">
        <h2 class="page-title">Inbox GRN</h2>
        <p class="page-subtitle">Daftar Goods Receipt Note (GRN) yang perlu diverifikasi.</p>
    </div>

    <!-- TAMPILAN INFORMASI SUPPLIER TANGGUNG JAWAB ACCOUNTING -->
<?php if (session()->get('role') === 'accounting'): ?>
    <?php if (!empty($assignedSuppliers)): ?>
        <div class="card mb-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="small fw-bold text-dark flex-shrink-0">
                        Daftar Supplier Anda (<?= count($assignedSuppliers) ?>):
                    </span>

                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                        <span class="small text-muted text-truncate">
                            <?= esc(implode(', ', array_column($assignedSuppliers, 'nama_supp'))) ?>
                        </span>

                        <button type="button"
                            class="btn btn-link btn-sm p-0 text-decoration-none flex-shrink-0"
                            data-bs-toggle="modal"
                            data-bs-target="#modalDaftarSupplier">
                            Selengkapnya
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- POP-UP DAFTAR SUPPLIER -->
        <div class="modal fade" id="modalDaftarSupplier" tabindex="-1"
            aria-labelledby="modalDaftarSupplierLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header">
                        <h5 class="modal-title fw-semibold" id="modalDaftarSupplierLabel">
                            Daftar Supplier Anda
                        </h5>
                        <button type="button" class="btn-close"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p class="small text-muted mb-3">
                            Total <?= count($assignedSuppliers) ?> supplier yang menjadi tanggung jawab Anda.
                        </p>

                        <div class="list-group">
                            <?php foreach ($assignedSuppliers as $index => $supplier): ?>
                                <div class="list-group-item">
                                    <span class="text-muted me-2"><?= $index + 1 ?>.</span>
                                    <?= esc($supplier['nama_supp']) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif (!empty($hasNoSuppliers)): ?>
        <div class="alert alert-warning d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0"></i>
            <div>
                <strong>Perhatisan:</strong> Anda belum memiliki supplier.
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

    <!-- PESAN SUKSES -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4 py-2 px-3" role="alert">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 15px;"></i>
            <div class="small">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
                style="position: static; padding: 8px; margin-left: auto; flex-shrink: 0;"
            ></button>
        </div>
    <?php endif; ?>

    <!-- PESAN ERROR -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4 py-2 px-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 15px;"></i>
            <div class="small">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
                style="position: static; padding: 8px; margin-left: auto; flex-shrink: 0;"
            ></button>
        </div>
    <?php endif; ?>

    <!-- FORM PENCARIAN -->
    <div class="card mb-4">
        <div class="card-body p-3">
            <form method="get" action="<?= base_url('accounting') ?>">
                <div class="row g-2 align-items-center">
                    <div class="col-md-3">
                        <select name="field" class="form-select">
                            <option value="nama_supplier" <?= ($field ?? '') === 'nama_supplier' ? 'selected' : '' ?>>
                                Nama Supplier
                            </option>
                            <option value="no_surat" <?= ($field ?? '') === 'no_surat' ? 'selected' : '' ?>>
                                No. Surat Jalan
                            </option>
                            <option value="no_po" <?= ($field ?? '') === 'no_po' ? 'selected' : '' ?>>
                                No. PO
                            </option>
                            <option value="no_grn" <?= ($field ?? '') === 'no_grn' ? 'selected' : '' ?>>
                                No. GRN
                            </option>
                            <option value="status" <?= ($field ?? '') === 'status' ? 'selected' : '' ?>>
                                Status
                            </option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <input
                            type="text"
                            name="keyword"
                            class="form-control"
                            placeholder="Ketik kata kunci pencarian..."
                            value="<?= esc($keyword ?? '') ?>"
                        >
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            Cari
                        </button>
                    </div>

                    <div class="col-md-1">
                        <a href="<?= base_url('accounting') ?>" class="btn btn-light border w-100" title="Reset">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL DATA GRN ACCOUNTING -->
    <div class="card acc-table-card">
        <!-- Toolbar: info + per-page -->
        <div class="acc-toolbar d-flex flex-wrap align-items-center justify-content-between gap-3 px-4 py-3">
            <div class="d-flex align-items-center gap-2">
                <span class="acc-toolbar-dot"></span>
                <span class="small fw-medium text-dark" id="acc-info-rows">Memuat data...</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="small text-secondary">Tampilkan</span>
                <div class="acc-perpage-wrap">
                    <select id="acc-per-page" class="acc-perpage-select">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <i class="bi bi-chevron-down acc-perpage-icon"></i>
                </div>
                <span class="small text-secondary">baris per halaman</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0" id="acc-table">
                <thead>
                    <tr>
                        <th class="ps-3">Nama Supplier</th>
                        <th>No. Surat Jalan</th>
                        <th>No. PO</th>
                        <th>No. GRN</th>
                        <th>Tanggal Serah</th>
                        <th>File Surat Jalan</th>
                        <th>File GRN</th>
                        <th>Berita Acara</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Alasan Penolakan</th>
                        <th class="text-center" style="width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="acc-tbody">
                    <?php if (!empty($surat)): ?>
                        <?php foreach ($surat as $row): ?>
                            <?php
                                $status = strtolower(trim($row['status'] ?? ''));
                                $isDiterima = ($status === 'diterima');
                                $isDitolak = ($status === 'ditolak');
                                $isMenunggu = ($status === 'diserahkan');
                                $alasan = $row['alasan_penolakan'] ?? $row['alasan'] ?? '';

                                // File Surat
                                $fileSurat = $row['file_surat'] ?? '';
                                $hasFileSurat = !empty($fileSurat);
                                $extSurat = $hasFileSurat ? strtolower(pathinfo($fileSurat, PATHINFO_EXTENSION)) : '';
                                $isImageSurat = in_array($extSurat, ['jpg', 'jpeg', 'png']);
                                $fileSuratUrl = $hasFileSurat ? base_url('uploads/surat/' . $fileSurat) : '#';

                                // File GRN
                                $fileGrn = $row['file_grn'] ?? '';
                                $hasFileGrn = !empty($fileGrn);
                                $extGrn = $hasFileGrn ? strtolower(pathinfo($fileGrn, PATHINFO_EXTENSION)) : '';
                                $isImageGrn = in_array($extGrn, ['jpg', 'jpeg', 'png']);
                                $fileGrnUrl = $hasFileGrn ? base_url('uploads/grn/' . $fileGrn) : '#';

                                // Berita Acara
                                $beritaAcara = $row['berita_acara'] ?? '';
                                $hasBeritaAcara = !empty($beritaAcara);
                                $extBerita = $hasBeritaAcara ? strtolower(pathinfo($beritaAcara, PATHINFO_EXTENSION)) : '';
                                $isImageBerita = in_array($extBerita, ['jpg', 'jpeg', 'png']);
                                $beritaAcaraUrl = $hasBeritaAcara ? base_url('uploads/berita_acara/' . $beritaAcara) : '#';
                            ?>

                            <tr>
                                <!-- Nama Supplier -->
                                <td class="ps-3 fw-medium">
                                    <?= esc($row['nama_supplier'] ?? '-') ?>
                                </td>

                                <!-- No Surat -->
                                <td class="fw-medium">
                                    <?= esc($row['no_surat'] ?? '-') ?>
                                </td>

                                <!-- No PO -->
                                <td>
                                    <?= esc($row['no_po'] ?? '-') ?>
                                </td>

                                <!-- No GRN -->
                                <td>
                                    <?= esc($row['no_grn'] ?? '-') ?>
                                </td>

                                <!-- Tanggal Serah (Teks saja, tanpa ikon) -->
                                <td class="text-nowrap">
                                    <?= !empty($row['tanggal_serah']) ? date('d/m/Y', strtotime($row['tanggal_serah'])) : '-' ?>
                                </td>

                                <!-- File Surat -->
                                <td>
                                    <?php if ($hasFileSurat): ?>
                                        <?php if ($isImageSurat): ?>
                                            <a
                                                href="javascript:void(0)"
                                                class="preview-trigger text-decoration-none"
                                                data-bs-toggle="modal"
                                                data-bs-target="#imagePreviewModal"
                                                data-img-src="<?= $fileSuratUrl ?>"
                                                data-img-title="Surat Jalan: <?= esc($row['no_surat']) ?>"
                                            >
                                                <img
                                                    src="<?= $fileSuratUrl ?>"
                                                    alt="Surat Jalan"
                                                    class="rounded border"
                                                    style="width: 40px; height: 40px; object-fit: cover;"
                                                >
                                            </a>
                                        <?php elseif ($extSurat === 'pdf'): ?>
                                            <a href="<?= $fileSuratUrl ?>" target="_blank" class="btn btn-sm btn-outline-danger py-1 px-2">
                                                PDF
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>

                                <!-- File GRN -->
                                <td>
                                    <?php if ($hasFileGrn): ?>
                                        <?php if ($isImageGrn): ?>
                                            <a
                                                href="javascript:void(0)"
                                                class="preview-trigger text-decoration-none"
                                                data-bs-toggle="modal"
                                                data-bs-target="#imagePreviewModal"
                                                data-img-src="<?= $fileGrnUrl ?>"
                                                data-img-title="GRN: <?= esc($row['no_grn']) ?>"
                                            >
                                                <img
                                                    src="<?= $fileGrnUrl ?>"
                                                    alt="File GRN"
                                                    class="rounded border"
                                                    style="width: 40px; height: 40px; object-fit: cover;"
                                                >
                                            </a>
                                        <?php elseif ($extGrn === 'pdf'): ?>
                                            <a href="<?= $fileGrnUrl ?>" target="_blank" class="btn btn-sm btn-outline-danger py-1 px-2">
                                                PDF
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Berita Acara -->
                                <td>
                                    <?php if ($hasBeritaAcara): ?>
                                        <?php if ($isImageBerita): ?>
                                            <a
                                                href="javascript:void(0)"
                                                class="preview-trigger text-decoration-none"
                                                data-bs-toggle="modal"
                                                data-bs-target="#imagePreviewModal"
                                                data-img-src="<?= $beritaAcaraUrl ?>"
                                                data-img-title="Berita Acara: <?= esc($row['no_grn']) ?>"
                                            >
                                                <img
                                                    src="<?= $beritaAcaraUrl ?>"
                                                    alt="Berita Acara"
                                                    class="rounded border"
                                                    style="width: 40px; height: 40px; object-fit: cover;"
                                                >
                                            </a>
                                        <?php elseif ($extBerita === 'pdf'): ?>
                                            <a href="<?= $beritaAcaraUrl ?>" target="_blank" class="btn btn-sm btn-outline-danger py-1 px-2">
                                                PDF
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Status (Badge teks saja, tanpa ikon) -->
                                <td class="text-center">
                                    <?php if ($isMenunggu): ?>
                                        <span class="badge-status badge-status-pending">
                                            Menunggu Verifikasi
                                        </span>
                                    <?php elseif ($isDiterima): ?>
                                        <span class="badge-status badge-status-received">
                                            Diterima
                                        </span>
                                    <?php elseif ($isDitolak): ?>
                                        <span class="badge-status badge-status-rejected">
                                            Ditolak
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-status">
                                            <?= esc(ucfirst($status ?: '-')) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Alasan Penolakan (Teks saja) -->
<!-- Aksi -->
<td class="text-center">
    <?php if ($isMenunggu): ?>
        <div class="d-flex justify-content-center gap-1">

            <!-- Tombol Terima -->
            <button
                type="button"
                class="btn btn-sm btn-success py-1 px-2 btn-trigger-terima"
                data-bs-toggle="modal"
                data-bs-target="#modalTerimaGrn"
                data-id="<?= esc($row['id']) ?>"
                data-no-grn="<?= esc($row['no_grn']) ?>"
                data-supplier="<?= esc($row['nama_supplier']) ?>"
                data-no-surat="<?= esc($row['no_surat']) ?>"
            >
                Terima
            </button>

            <!-- Tombol Tolak -->
            <button
                type="button"
                class="btn btn-sm btn-outline-danger py-1 px-2 btn-trigger-tolak"
                data-bs-toggle="modal"
                data-bs-target="#modalTolakGrn"
                data-id="<?= esc($row['id']) ?>"
                data-no-grn="<?= esc($row['no_grn']) ?>"
                data-supplier="<?= esc($row['nama_supplier']) ?>"
                data-no-surat="<?= esc($row['no_surat']) ?>"
            >
                Tolak
            </button>

        </div>
    <?php elseif ($isDiterima): ?>
        <span class="badge-status badge-status-received">
            Sudah Diterima
        </span>
    <?php elseif ($isDitolak): ?>
        <div class="d-flex align-items-center justify-content-center gap-1">
            <span class="badge-status badge-status-rejected">
                Sudah Ditolak
            </span>
            <button
                type="button"
                class="btn btn-sm btn-outline-secondary py-1 px-2 btn-trigger-tolak"
                data-bs-toggle="modal"
                data-bs-target="#modalTolakGrn"
                data-id="<?= esc($row['id']) ?>"
                data-no-grn="<?= esc($row['no_grn']) ?>"
                data-supplier="<?= esc($row['nama_supplier']) ?>"
                data-no-surat="<?= esc($row['no_surat']) ?>"
                data-alasan="<?= esc($alasan) ?>"
                title="Ubah Alasan Penolakan"
            >
                <i class="bi bi-pencil-square"></i>
            </button>
        </div>
    <?php else: ?>
        <span class="text-muted small">-</span>
    <?php endif; ?>
</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr id="acc-empty-row">
                            <td colspan="11" class="text-center text-muted py-5">
                                <?php if (!empty($hasNoSuppliers)): ?>
                                    Belum ada supplier yang ditugaskan ke akun Anda.
                                <?php else: ?>
                                    Belum ada data GRN yang masuk.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="acc-pagination-bar d-flex flex-wrap align-items-center justify-content-between gap-3 px-4 py-3" id="acc-pagination-wrapper">
            <div class="acc-page-summary" id="acc-page-info">-</div>
            <nav aria-label="Navigasi halaman GRN">
                <ul class="acc-pagination mb-0" id="acc-pagination"></ul>
            </nav>
        </div>
    </div>

</div>

<!-- MODAL KONFIRMASI TERIMA GRN -->
<div class="modal fade" id="modalTerimaGrn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form action="<?= base_url('accounting/terima') ?>" method="post" id="formTerimaGrn">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="terimaId">

                <div class="modal-header">
                    <h5 class="modal-title text-success">
                        Konfirmasi Penerimaan GRN
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body p-4">

                    <p class="text-muted mb-4">
                        Apakah Anda yakin ingin menerima dokumen GRN berikut?
                    </p>

                    <div class="mb-3">
                        <div class="small text-muted">Nomor Surat Jalan:</div>
                        <div class="fw-bold" id="terimaNoSurat">-</div>
                    </div>

                    <div class="mb-3">
                        <div class="small text-muted">Nomor GRN:</div>
                        <div class="fw-bold" id="terimaNoGrn">-</div>
                    </div>

                    <div class="mb-3">
                        <div class="small text-muted">Supplier:</div>
                        <div class="fw-bold" id="terimaSupplier">-</div>
                    </div>

                    <div class="alert alert-success small mb-0">
                        Setelah diterima, status dokumen tidak dapat diubah kembali.
                        Pastikan seluruh informasi dan dokumen telah diperiksa dengan benar.
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-success">
                        Ya, Terima
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════
     MODAL TOLAK GRN (INPUT ALASAN)
════════════════════════════════════════════ -->
<div class="modal fade" id="modalTolakGrn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= base_url('accounting/tolak') ?>" method="post" id="formTolakGrn">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="tolakId">

                <div class="modal-header">
                    <h5 class="modal-title text-danger">Konfirmasi Penolakan Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <div class="small text-muted">Nomor Surat Jalan:</div>
                        <div class="fw-bold" id="tolakNoSurat">-</div>
                    </div>
                    <div class="mb-3">
                        <div class="small text-muted">Nomor GRN:</div>
                        <div class="fw-bold" id="tolakNoGrn">-</div>
                    </div>
                    <div class="mb-3">
                        <div class="small text-muted">Supplier:</div>
                        <div class="fw-bold" id="tolakSupplier">-</div>
                    </div>
                    <div class="mb-2">
                        <label for="alasan_penolakan" class="form-label text-danger">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea
                            class="form-control"
                            id="alasan_penolakan"
                            name="alasan"
                            rows="4"
                            placeholder="Tuliskan alasan penolakan secara jelas agar dapat diperbaiki oleh Admin..."
                            required
                        ></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Konfirmasi Tolak GRN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════
     MODAL LIHAT ALASAN PENOLAKAN
════════════════════════════════════════════ -->
<div class="modal fade" id="modalLihatAlasan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Catatan Penolakan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <div class="small text-muted">Nomor Surat Jalan:</div>
                    <div class="fw-bold" id="modalLihatNoSurat">-</div>
                </div>
                <div class="mb-3">
                    <div class="small text-muted">Nomor GRN:</div>
                    <div class="fw-bold" id="modalLihatNoGrn">-</div>
                </div>
                <div class="mb-3">
                    <div class="small text-muted">Supplier:</div>
                    <div class="fw-bold" id="modalLihatSupplier">-</div>
                </div>
                <div class="mb-2">
                    <label class="form-label text-danger">Alasan Penolakan:</label>
                    <div class="p-3 bg-light rounded border text-dark" id="modalLihatAlasanText" style="white-space: pre-wrap; font-size: 0.9rem;">
                        -
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════
     MODAL PREVIEW DOKUMEN
════════════════════════════════════════════ -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalTitle">Preview Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img src="" id="previewModalImage" class="img-fluid rounded border" alt="Preview Dokumen" style="max-height: 75vh; object-fit: contain;">
            </div>
            <div class="modal-footer">
                <a href="#" id="previewDownloadBtn" class="btn btn-primary" target="_blank">
                    Buka Ukuran Penuh
                </a>
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Preview Gambar
    document.querySelectorAll('.preview-trigger').forEach(function (trigger) {
        trigger.addEventListener('click', function () {
            var imgSrc = this.getAttribute('data-img-src');
            var imgTitle = this.getAttribute('data-img-title') || 'Preview Dokumen';
            var previewImg = document.getElementById('previewModalImage');
            var previewTitle = document.getElementById('previewModalTitle');
            var downloadBtn = document.getElementById('previewDownloadBtn');

            if (previewImg && previewTitle && downloadBtn) {
                previewImg.src = imgSrc;
                previewTitle.textContent = imgTitle;
                downloadBtn.href = imgSrc;
            }
        });
    });

    // Tolak GRN
    document.querySelectorAll('.btn-trigger-tolak').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = this.getAttribute('data-id');
            var noGrn = this.getAttribute('data-no-grn');
            var supplier = this.getAttribute('data-supplier');
            var noSurat = this.getAttribute('data-no-surat');
            var alasan = this.getAttribute('data-alasan') || '';

            document.getElementById('tolakId').value = id;
            document.getElementById('tolakNoGrn').textContent = noGrn || '-';
            document.getElementById('tolakSupplier').textContent = supplier || '-';
            document.getElementById('tolakNoSurat').textContent = noSurat || '-';
            document.getElementById('alasan_penolakan').value = alasan;
        });
    });

    // Lihat Alasan
    document.querySelectorAll('.btn-lihat-alasan').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var noGrn = this.getAttribute('data-no-grn');
            var supplier = this.getAttribute('data-supplier');
            var noSurat = this.getAttribute('data-no-surat');
            var alasan = this.getAttribute('data-alasan');

            document.getElementById('modalLihatNoGrn').textContent = noGrn || '-';
            document.getElementById('modalLihatSupplier').textContent = supplier || '-';
            document.getElementById('modalLihatNoSurat').textContent = noSurat || '-';
            document.getElementById('modalLihatAlasanText').textContent = alasan || '-';
        });
    });

        // Konfirmasi Terima GRN
        document.querySelectorAll('.btn-trigger-terima').forEach(function (btn) {
        btn.addEventListener('click', function () {
        var id = this.getAttribute('data-id');
        var noGrn = this.getAttribute('data-no-grn');
        var supplier = this.getAttribute('data-supplier');
        var noSurat = this.getAttribute('data-no-surat');

        document.getElementById('terimaId').value = id;
        document.getElementById('terimaNoGrn').textContent = noGrn || '-';
        document.getElementById('terimaSupplier').textContent = supplier || '-';
        document.getElementById('terimaNoSurat').textContent = noSurat || '-';
    });
});

    // ── PAGINATION TABEL ACCOUNTING GRN ──────────────────────────
    (function () {
        var tbody      = document.getElementById('acc-tbody');
        var perPageSel = document.getElementById('acc-per-page');
        var infoRows   = document.getElementById('acc-info-rows');
        var pageInfo   = document.getElementById('acc-page-info');
        var pagination = document.getElementById('acc-pagination');
        var wrapper    = document.getElementById('acc-pagination-wrapper');

        if (!tbody) return;

        var allRows     = Array.from(tbody.querySelectorAll('tr')).filter(function (r) {
            return !r.id || r.id !== 'acc-empty-row';
        });
        var perPage     = parseInt(perPageSel ? perPageSel.value : 10, 10);
        var currentPage = 1;

        function totalPages() {
            return Math.max(1, Math.ceil(allRows.length / perPage));
        }

        function mkItem(cls, inner, onClick, disabled) {
            var li = document.createElement('li');
            li.className = 'acc-pg-item ' + (cls || '');
            if (disabled) {
                li.className += ' disabled';
                li.innerHTML = '<span>' + inner + '</span>';
            } else {
                li.innerHTML = '<a href="javascript:void(0)">' + inner + '</a>';
                if (onClick) li.querySelector('a').addEventListener('click', onClick);
            }
            return li;
        }

        function render() {
            var total = allRows.length;
            var pages = totalPages();
            var start = (currentPage - 1) * perPage;
            var end   = Math.min(start + perPage, total);

            allRows.forEach(function (row, idx) {
                row.style.display = (idx >= start && idx < end) ? '' : 'none';
            });

            // Info atas
            if (infoRows) {
                if (total > 0) {
                    infoRows.innerHTML =
                        '<span class="fw-semibold text-dark">' + (start + 1) + '–' + end + '</span>' +
                        ' dari <span class="fw-semibold text-dark">' + total + '</span> data';
                } else {
                    infoRows.textContent = 'Tidak ada data';
                }
            }

            // Info bawah
            if (pageInfo) {
                pageInfo.innerHTML = total > 0
                    ? 'Halaman <span class="fw-semibold text-dark">' + currentPage + '</span> dari <span class="fw-semibold text-dark">' + pages + '</span>'
                    : '';
            }

            if (wrapper) wrapper.style.display = (pages <= 1 && total > 0) ? 'none' : '';
            if (!pagination) return;
            pagination.innerHTML = '';

            // ← Sebelumnya
            pagination.appendChild(mkItem('acc-pg-nav', '<i class="bi bi-arrow-left me-1"></i>Sebelumnya',
                function () { if (currentPage > 1) { currentPage--; render(); } },
                currentPage === 1
            ));

            // Ellipsis kiri
            var maxBtn = 5, half = Math.floor(maxBtn / 2);
            var pStart = Math.max(1, Math.min(currentPage - half, pages - maxBtn + 1));
            var pEnd   = Math.min(pages, pStart + maxBtn - 1);

            if (pStart > 1) {
                pagination.appendChild(mkItem('', '1', function () { currentPage = 1; render(); }));
                if (pStart > 2) pagination.appendChild(mkItem('acc-pg-ellipsis', '···', null, true));
            }

            // Nomor halaman
            for (var p = pStart; p <= pEnd; p++) {
                (function (pg) {
                    var isCurrent = pg === currentPage;
                    pagination.appendChild(mkItem(isCurrent ? 'active' : '', pg,
                        function () { currentPage = pg; render(); },
                        false
                    ));
                })(p);
            }

            // Ellipsis kanan
            if (pEnd < pages) {
                if (pEnd < pages - 1) pagination.appendChild(mkItem('acc-pg-ellipsis', '···', null, true));
                pagination.appendChild(mkItem('', pages, function () { currentPage = pages; render(); }));
            }

            // Selanjutnya →
            pagination.appendChild(mkItem('acc-pg-nav', 'Selanjutnya<i class="bi bi-arrow-right ms-1"></i>',
                function () { if (currentPage < totalPages()) { currentPage++; render(); } },
                currentPage === pages
            ));
        }

        if (perPageSel) {
            perPageSel.addEventListener('change', function () {
                perPage = parseInt(this.value, 10);
                currentPage = 1;
                render();
            });
        }

        render();
    })();
    // ── END PAGINATION ────────────────────────────────────────────
});
</script>
<?= $this->endSection() ?>
