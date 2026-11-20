<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<style>
/* ═══════════════════════════════════════════
   PAGINATION MODERN — GRN LIST
═══════════════════════════════════════════ */

.grn-table-card {
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.04), 0 2px 4px -2px rgba(0, 0, 0, 0.04);
    overflow: hidden;
    background: #ffffff;
}

/* Toolbar atas tabel */
.grn-toolbar {
    border-bottom: 1px solid #f1f5f9;
    background: #ffffff;
    padding: 12px 20px;
}
.grn-toolbar-dot {
    display: inline-block;
    width: 8px; height: 8px;
    border-radius: 50%;
    background: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18);
    flex-shrink: 0;
}

/* Select per-page modern */
.grn-perpage-wrap {
    position: relative;
    display: inline-flex;
    align-items: center;
}
.grn-perpage-select {
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
.grn-perpage-select:hover {
    border-color: #cbd5e1;
    background: #f8fafc;
}
.grn-perpage-select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}
.grn-perpage-icon {
    position: absolute;
    right: 10px;
    font-size: 10px;
    color: #64748b;
    pointer-events: none;
    transition: color .2s;
}
.grn-perpage-select:focus ~ .grn-perpage-icon {
    color: #2563eb;
}

/* Footer pagination bar */
.grn-pagination-bar {
    border-top: 1px solid #f1f5f9;
    background: #ffffff;
    padding: 14px 20px;
}
.grn-page-summary {
    font-size: 0.825rem;
    color: #64748b;
    font-weight: 500;
}

/* Tombol-tombol pagination modern */
.grn-pagination {
    display: flex;
    align-items: center;
    gap: 6px;
    list-style: none;
    padding: 0;
    margin: 0;
}
.grn-pagination .grn-pg-item a,
.grn-pagination .grn-pg-item span {
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
.grn-pagination .grn-pg-item a:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #1e293b;
    transform: translateY(-1px);
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.06);
}
.grn-pagination .grn-pg-item.active a {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    border-color: transparent;
    color: #ffffff;
    box-shadow: 0 4px 10px rgba(37, 99, 235, 0.35);
    transform: translateY(-1px);
}
.grn-pagination .grn-pg-item.active a:hover {
    box-shadow: 0 6px 14px rgba(37, 99, 235, 0.45);
}
.grn-pagination .grn-pg-item.disabled span,
.grn-pagination .grn-pg-item.disabled a {
    color: #cbd5e1;
    border-color: #f1f5f9;
    background: #f8fafc;
    cursor: not-allowed;
    pointer-events: none;
    transform: none;
    box-shadow: none;
}
/* Tombol prev/next */
.grn-pagination .grn-pg-nav a {
    gap: 6px;
    padding: 0 14px;
    font-size: 0.8125rem;
    font-weight: 500;
    color: #334155;
    background: #ffffff;
    border-color: #e2e8f0;
}
.grn-pagination .grn-pg-nav a:hover {
    background: #f1f5f9;
    color: #0f172a;
}
.grn-pagination .grn-pg-ellipsis span {
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
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="page-title">Inbox &amp; Riwayat GRN</h2>
            <p class="page-subtitle">Daftar Goods Receipt Note yang diserahkan.</p>
        </div>

        <div>
            <a href="<?= base_url('grn/form') ?>" class="btn btn-primary d-inline-flex align-items-center gap-1 shadow-sm">
                <i class="bi bi-plus-lg"></i>
                <span>Input GRN</span>
            </a>
        </div>
    </div>

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
            <form method="get" action="<?= base_url('grn') ?>">
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
                        <a href="<?= base_url('grn') ?>" class="btn btn-light border w-100" title="Reset">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL DATA GRN -->
    <div class="card grn-table-card">
        <!-- Toolbar: info + per-page -->
        <div class="grn-toolbar d-flex flex-wrap align-items-center justify-content-between gap-3 px-4 py-3">
            <div class="d-flex align-items-center gap-2">
                <span class="grn-toolbar-dot"></span>
                <span class="small fw-medium text-dark" id="grn-info-rows">Memuat data...</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="small text-secondary">Tampilkan</span>
                <div class="grn-perpage-wrap">
                    <select id="grn-per-page" class="grn-perpage-select">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <i class="bi bi-chevron-down grn-perpage-icon"></i>
                </div>
                <span class="small text-secondary">baris per halaman</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0" id="grn-table">
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
                        <th>Status</th>
                        <th class="text-center">Alasan Penolakan</th>
                        <th class="text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="grn-tbody">
                    <?php if (!empty($surat)): ?>
                        <?php foreach ($surat as $row): ?>
                            <?php
                                $status = strtolower(trim($row['status'] ?? ''));
                                $isDiterima = in_array($status, ['diterima', 'received', 'confirmed']);
                                $isDitolak = in_array($status, ['ditolak', 'rejected']);
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
                                                    alt="Foto Surat"
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
                                <td>
                                    <?php if ($status === 'diserahkan'): ?>
                                        <span class="badge-status badge-status-pending">
                                            Diserahkan
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
                                            <?= esc(ucfirst($row['status'] ?? '-')) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Alasan Penolakan (Teks saja) -->
                                <td class="text-center">
                                    <?php if ($isDitolak): ?>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger py-1 px-2 btn-lihat-alasan"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalLihatAlasan"
                                            data-no-grn="<?= esc($row['no_grn']) ?>"
                                            data-supplier="<?= esc($row['nama_supplier']) ?>"
                                            data-no-surat="<?= esc($row['no_surat']) ?>"
                                            data-alasan="<?= esc($alasan ?: 'Tidak ada catatan alasan penolakan dari Accounting.') ?>"
                                            data-edit-url="<?= base_url('grn/edit?id=' . $row['id']) ?>"
                                            title="Lihat Alasan Penolakan"
                                        >
                                            Lihat Alasan
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Aksi (Hanya Edit yang boleh berikon) -->
                                <td class="text-center">
                                    <?php if ($isDiterima): ?>
                                        <span class="text-muted small">Terkunci</span>
                                    <?php elseif ($isDitolak): ?>
                                        <a href="<?= base_url('grn/edit?id=' . $row['id']) ?>" class="btn btn-sm btn-danger py-1 px-2">
                                            <i class="bi bi-pencil-square me-1"></i> Edit &amp; Kirim
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= base_url('grn/edit?id=' . $row['id']) ?>" class="btn btn-sm btn-outline-primary py-1 px-2">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr id="grn-empty-row">
                            <td colspan="11" class="text-center text-muted py-5">
                                Belum ada data GRN yang terdaftar.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="grn-pagination-bar d-flex flex-wrap align-items-center justify-content-between gap-3 px-4 py-3" id="grn-pagination-wrapper">
            <div class="grn-page-summary" id="grn-page-info">-</div>
            <nav aria-label="Navigasi halaman GRN">
                <ul class="grn-pagination mb-0" id="grn-pagination"></ul>
            </nav>
        </div>
    </div>

</div>

<!-- ═══════════════════════════════════════════
     MODAL PREVIEW FILE DOKUMEN
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

<!-- ═══════════════════════════════════════════
     MODAL LIHAT ALASAN PENOLAKAN
════════════════════════════════════════════ -->
<div class="modal fade" id="modalLihatAlasan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Catatan Penolakan Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <div class="small text-muted">Nomor Surat Jalan:</div>
                    <div class="fw-bold" id="modalAlasanNoSurat">-</div>
                </div>
                <div class="mb-3">
                    <div class="small text-muted">Nomor GRN:</div>
                    <div class="fw-bold" id="modalAlasanNoGrn">-</div>
                </div>
                <div class="mb-3">
                    <div class="small text-muted">Supplier:</div>
                    <div class="fw-bold" id="modalAlasanSupplier">-</div>
                </div>
                <div class="mb-2">
                    <label class="form-label text-danger">Alasan Penolakan dari Accounting:</label>
                    <div class="p-3 bg-light rounded border text-dark" id="modalAlasanText" style="white-space: pre-wrap; font-size: 0.9rem;">
                        -
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="btnModalEditDirect" class="btn btn-primary">
                    <i class="bi bi-pencil-square me-1"></i> Perbaiki Dokumen
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

    // Lihat Alasan Penolakan
    document.querySelectorAll('.btn-lihat-alasan').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var noGrn = this.getAttribute('data-no-grn');
            var supplier = this.getAttribute('data-supplier');
            var noSurat = this.getAttribute('data-no-surat');
            var alasan = this.getAttribute('data-alasan');
            var editUrl = this.getAttribute('data-edit-url');

            document.getElementById('modalAlasanNoGrn').textContent = noGrn || '-';
            document.getElementById('modalAlasanSupplier').textContent = supplier || '-';
            document.getElementById('modalAlasanNoSurat').textContent = noSurat || '-';
            document.getElementById('modalAlasanText').textContent = alasan || '-';
            document.getElementById('btnModalEditDirect').href = editUrl || '#';
        });
    });

    // ── PAGINATION TABEL GRN ──────────────────────────────────────
    (function () {
        var tbody      = document.getElementById('grn-tbody');
        var perPageSel = document.getElementById('grn-per-page');
        var infoRows   = document.getElementById('grn-info-rows');
        var pageInfo   = document.getElementById('grn-page-info');
        var pagination = document.getElementById('grn-pagination');
        var wrapper    = document.getElementById('grn-pagination-wrapper');

        if (!tbody) return;

        var allRows     = Array.from(tbody.querySelectorAll('tr')).filter(function (r) {
            return !r.id || r.id !== 'grn-empty-row';
        });
        var perPage     = parseInt(perPageSel ? perPageSel.value : 10, 10);
        var currentPage = 1;

        function totalPages() {
            return Math.max(1, Math.ceil(allRows.length / perPage));
        }

        function mkItem(cls, inner, onClick, disabled) {
            var li = document.createElement('li');
            li.className = 'grn-pg-item ' + (cls || '');
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
            pagination.appendChild(mkItem('grn-pg-nav', '<i class="bi bi-arrow-left me-1"></i>Sebelumnya',
                function () { if (currentPage > 1) { currentPage--; render(); } },
                currentPage === 1
            ));

            // Ellipsis kiri
            var maxBtn = 5, half = Math.floor(maxBtn / 2);
            var pStart = Math.max(1, Math.min(currentPage - half, pages - maxBtn + 1));
            var pEnd   = Math.min(pages, pStart + maxBtn - 1);

            if (pStart > 1) {
                pagination.appendChild(mkItem('', '1', function () { currentPage = 1; render(); }));
                if (pStart > 2) pagination.appendChild(mkItem('grn-pg-ellipsis', '···', null, true));
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
                if (pEnd < pages - 1) pagination.appendChild(mkItem('grn-pg-ellipsis', '···', null, true));
                pagination.appendChild(mkItem('', pages, function () { currentPage = pages; render(); }));
            }

            // Selanjutnya →
            pagination.appendChild(mkItem('grn-pg-nav', 'Selanjutnya<i class="bi bi-arrow-right ms-1"></i>',
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
