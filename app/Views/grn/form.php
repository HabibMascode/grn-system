<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-8">

        <!-- PAGE HEADER -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-1 mb-2">
            <div>
                <h2 class="page-title mb-1">
                    <?= isset($editData) ? 'Edit Data GRN' : 'Form Pengiriman GRN' ?>
                </h2>
                <p class="text-secondary small mb-0">
                    <?= isset($editData)
                        ? 'Perbarui data Goods Receipt Note (GRN).'
                        : 'Masukkan data Goods Receipt Note (GRN) untuk diserahkan.' ?>
                </p>
            </div>
        </div>

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

        <!-- PESAN SUCCESS -->
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

        <!-- CARD FORM -->
        <div class="card">
            <div class="card-header">
                <span>Informasi Dokumen GRN</span>
            </div>

            <div class="card-body px-4 pt-3 pb-4">

                <?php  
                    $editAlasan = $editData['alasan'] ?? '';
                    $editStatus = strtolower(trim($editData['status'] ?? ''));
                ?>
                <?php if (($editStatus === 'ditolak' || $editStatus === 'rejected') && !empty($editAlasan)): ?>
                    <div class="alert alert-danger border-danger-subtle bg-danger-subtle text-dark shadow-sm mb-4">
                        <div class="d-flex align-items-start gap-2">
                            <div class="w-100">
                                <div class="mb-2">
                                <span class="fw-semibold">Alasan Penolakan:</span>
                                <div class="p-2 mt-1 bg-white rounded border border-danger-subtle text-dark"
                                    style="font-size: 0.9rem; font-style: normal;">
                                    <?= esc(trim($editAlasan)) ?>
                                </div>
                            </div>
                                <small class="text-secondary d-block">
                                    Silakan perbaiki data di atas, lalu simpan untuk menyerahkan kembali.
                                </small>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <form
                    action="<?= base_url('grn/submit') ?>"
                    method="post"
                    enctype="multipart/form-data"
                    id="grnForm"
                >
                    <?= csrf_field() ?>

                    <!-- MODE & ID -->
                    <input type="hidden" name="mode" value="<?= isset($editData) ? 'edit' : 'create' ?>">
                    <?php if (isset($editData['id'])): ?>
                        <input type="hidden" name="id" value="<?= esc($editData['id']) ?>">
                    <?php endif; ?>

                    <!-- NAMA SUPPLIER (AUTOCOMPLETE) -->
                    <div class="mb-3 position-relative" id="supplierAutocompleteWrapper">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label mb-0" for="supplierInput">
                                Nama Supplier <span class="text-danger">*</span>
                            </label>
                        </div>

                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search text-secondary"></i>
                            </span>
                            <input
                                type="text"
                                class="form-control border-start-0"
                                id="supplierInput"
                                name="nama_supplier"
                                placeholder="Contoh: Global Ba.."
                                value="<?= esc(old('nama_supplier', $editData['nama_supplier'] ?? '')) ?>"
                                autocomplete="off"
                                required
                            >
                            <button
                                class="btn btn-outline-secondary border-start-0 bg-white"
                                type="button"
                                id="clearSupplierBtn"
                                title="Hapus pilihan"
                                style="display: <?= !empty(old('nama_supplier', $editData['nama_supplier'] ?? '')) ? 'block' : 'none' ?>;"
                            >
                                <i class="bi bi-x-lg text-secondary"></i>
                            </button>
                        </div>

                        <!-- Hidden ID Supplier -->
                        <input
                            type="hidden"
                            name="id_supp"
                            id="idSuppInput"
                            value="<?= esc(old('id_supp', $editData['id_supp'] ?? '')) ?>"
                        >

                        <!-- Feedback Validasi -->
                        <div id="supplierValidationMsg" class="small text-danger mt-1" style="display: none;"></div>

                        <!-- Dropdown Hasil Autocomplete -->
                        <div
                            id="supplierDropdown"
                            class="dropdown-menu w-100 shadow border p-0 mt-1"
                            style="max-height: 260px; overflow-y: auto; display: none; z-index: 1050;"
                        >
                            <!-- Daftar item autocomplete dimuat secara dinamis via JS -->
                        </div>
                    </div>

                    <!-- TANGGAL SERAH -->
                    <div class="mb-3">
                        <label class="form-label" for="tanggal_serah">
                            Tanggal Serah <span class="text-danger">*</span>
                        </label>
                        <input
                            type="date"
                            class="form-control"
                            id="tanggal_serah"
                            name="tanggal_serah"
                            value="<?= esc(old('tanggal_serah', $editData['tanggal_serah'] ?? date('Y-m-d'))) ?>"
                            readonly
                        >
                    </div>

                    <!-- NO SURAT -->
                    <div class="mb-3">
                        <label class="form-label">
                            No. Surat Jalan <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            name="no_surat"
                            placeholder="Contoh: JL-2610-3261"
                            value="<?= esc(old('no_surat', $editData['no_surat'] ?? '')) ?>"
                            required
                        >
                        <div class="form-text mt-1">
                            No. Surat Jalan tidak boleh sama.
                        </div>
                    </div>

                    <!-- NO PO -->
                    <div class="mb-3">
                        <label class="form-label">
                            No. PO <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            name="no_po"
                            placeholder="Masukkan nomor Purchase Order (PO)"
                            value="<?= esc(old('no_po', $editData['no_po'] ?? '')) ?>"
                            required
                        >
                    </div>

                    <!-- NO GRN -->
                    <div class="mb-3">
                        <label class="form-label">
                            No. GRN <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            name="no_grn"
                            placeholder="Masukkan nomor Goods Receipt Note (GRN)"
                            value="<?= esc(old('no_grn', $editData['no_grn'] ?? '')) ?>"
                            required
                        >
                    </div>

                    <!-- FILE SURAT JALAN -->
                    <div class="mb-3">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>
                                File Surat Jalan <span class="text-danger">*</span>
                            </span>
                            <span class="text-secondary small fw-normal">
                                JPG, PNG, PDF (Maks. 5MB)
                            </span>
                        </label>
                        <input
                            type="file"
                            class="form-control"
                            id="file_surat"
                            name="file_surat"
                            accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf"
                            <?= !isset($editData) || empty($editData['file_surat']) ? 'required' : '' ?>
                        >

                        <!-- PREVIEW FILE SURAT -->
                        <div
                            id="fileSuratPreviewContainer"
                            class="mt-3 <?= (isset($editData) && !empty($editData['file_surat'])) ? '' : 'd-none' ?>"
                        >
                            <div class="card bg-light border p-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        id="fileSuratPreviewIcon"
                                        class="rounded border bg-white d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px;"
                                    >
                                        <i class="bi bi-file-earmark text-secondary fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="fw-semibold text-truncate text-dark" id="fileSuratName">
                                            <?= (isset($editData) && !empty($editData['file_surat'])) ? esc($editData['file_surat']) : 'Nama File' ?>
                                        </div>
                                        <small class="text-secondary" id="fileSuratMeta">
                                            <?= (isset($editData) && !empty($editData['file_surat'])) ? 'File tersimpan di sistem' : '' ?>
                                        </small>
                                        <?php if (isset($editData) && !empty($editData['file_surat'])): ?>
                                            <div class="mt-1">
                                                <a
                                                    href="<?= base_url('uploads/surat/' . $editData['file_surat']) ?>"
                                                    target="_blank"
                                                    class="btn btn-sm btn-outline-secondary py-0 px-2"
                                                    style="font-size: 0.75rem;"
                                                >
                                                    Lihat File Saat Ini
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FILE GRN -->
                    <div class="mb-3">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>
                                File GRN <span class="text-danger">*</span>
                            </span>
                            <span class="text-secondary small fw-normal">
                                JPG, PNG, PDF (Maks. 5MB)
                            </span>
                        </label>
                        <input
                            type="file"
                            class="form-control"
                            id="file_grn"
                            name="file_grn"
                            accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf"
                            <?= !isset($editData) || empty($editData['file_grn']) ? 'required' : '' ?>
                        >

                        <!-- PREVIEW FILE GRN -->
                        <div
                            id="fileGrnPreviewContainer"
                            class="mt-3 <?= (isset($editData) && !empty($editData['file_grn'])) ? '' : 'd-none' ?>"
                        >
                            <div class="card bg-light border p-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        id="fileGrnPreviewIcon"
                                        class="rounded border bg-white d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px;"
                                    >
                                        <i class="bi bi-file-earmark text-secondary fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="fw-semibold text-truncate text-dark" id="fileGrnName">
                                            <?= (isset($editData) && !empty($editData['file_grn'])) ? esc($editData['file_grn']) : 'Nama File' ?>
                                        </div>
                                        <small class="text-secondary" id="fileGrnMeta">
                                            <?= (isset($editData) && !empty($editData['file_grn'])) ? 'File tersimpan di sistem' : '' ?>
                                        </small>
                                        <?php if (isset($editData) && !empty($editData['file_grn'])): ?>
                                            <div class="mt-1">
                                                <a
                                                    href="<?= base_url('uploads/grn/' . $editData['file_grn']) ?>"
                                                    target="_blank"
                                                    class="btn btn-sm btn-outline-secondary py-0 px-2"
                                                    style="font-size: 0.75rem;"
                                                >
                                                    Lihat File Saat Ini
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BERITA ACARA -->
                    <div class="mb-3">
                        <label class="form-label d-flex justify-content-between align-items-center">
                            <span>
                                Berita Acara <span class="text-secondary small fw-normal">(Opsional)</span>
                            </span>
                            <span class="text-secondary small fw-normal">
                                JPG, PNG, PDF (Maks. 5MB)
                            </span>
                        </label>
                        <input
                            type="file"
                            class="form-control"
                            id="berita_acara"
                            name="berita_acara"
                            accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf"
                        >

                        <!-- PREVIEW BERITA ACARA -->
                        <div
                            id="beritaAcaraPreviewContainer"
                            class="mt-3 <?= (isset($editData) && !empty($editData['berita_acara'])) ? '' : 'd-none' ?>"
                        >
                            <div class="card bg-light border p-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        id="beritaAcaraPreviewIcon"
                                        class="rounded border bg-white d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px;"
                                    >
                                        <i class="bi bi-file-earmark text-secondary fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="fw-semibold text-truncate text-dark" id="beritaAcaraName">
                                            <?= (isset($editData) && !empty($editData['berita_acara'])) ? esc($editData['berita_acara']) : 'Nama File' ?>
                                        </div>
                                        <small class="text-secondary" id="beritaAcaraMeta">
                                            <?= (isset($editData) && !empty($editData['berita_acara'])) ? 'File tersimpan di sistem' : '' ?>
                                        </small>
                                        <?php if (isset($editData) && !empty($editData['berita_acara'])): ?>
                                            <div class="mt-1">
                                                <a
                                                    href="<?= base_url('uploads/berita_acara/' . $editData['berita_acara']) ?>"
                                                    target="_blank"
                                                    class="btn btn-sm btn-outline-secondary py-0 px-2"
                                                    style="font-size: 0.75rem;"
                                                >
                                                    Lihat File Saat Ini
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BUTTONS -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="<?= base_url('grn') ?>" class="btn btn-light border">
                            Batal
                        </a>
                        <button
                            type="button"
                            id="btnSubmitTrigger"
                            class="btn btn-primary px-4"
                        >
                            <?= isset($editData) ? 'Simpan Perubahan' : 'Simpan &amp; Serahkan GRN' ?>
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

<!-- MODAL KONFIRMASI SUBMIT -->
<div class="modal fade" id="confirmSubmitModal" tabindex="-1" aria-labelledby="confirmSubmitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mb-0" id="confirmSubmitModalLabel">
                    Konfirmasi Penyerahan GRN
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body py-4">
                <p class="mb-2 text-dark text-center">
                    Apakah Anda yakin data GRN sudah benar dan lengkap?
                </p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" class="btn btn-primary px-4" id="btnConfirmSubmit">
                    Ya, <?= isset($editData) ? 'Simpan Perubahan' : 'Serahkan GRN' ?>
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const grnForm          = document.getElementById('grnForm');
    const btnSubmitTrigger = document.getElementById('btnSubmitTrigger');
    const btnConfirmSubmit = document.getElementById('btnConfirmSubmit');
    const confirmModalEl   = document.getElementById('confirmSubmitModal');
    const confirmModal     = new bootstrap.Modal(confirmModalEl);

    // =========================================================
    // VALIDASI FILE
    // =========================================================
    const allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
    const maxFileSize       = 5 * 1024 * 1024; // 5MB

    function setupFileInput(inputId, previewContainerId, iconId, nameId, metaId) {
        const input            = document.getElementById(inputId);
        const previewContainer = document.getElementById(previewContainerId);
        const previewIcon      = document.getElementById(iconId);
        const fileName         = document.getElementById(nameId);
        const fileMeta         = document.getElementById(metaId);

        if (!input) return;

        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            const ext = file.name.split('.').pop().toLowerCase();

            if (!allowedExtensions.includes(ext)) {
                alert('Format file harus JPG, JPEG, PNG, atau PDF.');
                this.value = '';
                previewContainer.classList.add('d-none');
                return;
            }

            if (file.size > maxFileSize) {
                alert('Ukuran file maksimal adalah 5MB.');
                this.value = '';
                previewContainer.classList.add('d-none');
                return;
            }

            previewContainer.classList.remove('d-none');
            fileName.textContent = file.name;
            const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
            fileMeta.textContent = `Ukuran: ${sizeInMB} MB`;

            if (ext === 'pdf') {
                previewIcon.innerHTML = '<i class="bi bi-file-earmark-pdf text-danger fs-3"></i>';
            } else {
                previewIcon.innerHTML = '<i class="bi bi-file-earmark-image text-primary fs-3"></i>';
            }
        });
    }

    setupFileInput('file_surat', 'fileSuratPreviewContainer', 'fileSuratPreviewIcon', 'fileSuratName', 'fileSuratMeta');
    setupFileInput('file_grn', 'fileGrnPreviewContainer', 'fileGrnPreviewIcon', 'fileGrnName', 'fileGrnMeta');
    setupFileInput('berita_acara', 'beritaAcaraPreviewContainer', 'beritaAcaraPreviewIcon', 'beritaAcaraName', 'beritaAcaraMeta');

    // =========================================================
    // AUTOCOMPLETE SUPPLIER
    // =========================================================
    const suppliersData         = <?= json_encode($suppliers ?? []) ?>;
    const supplierInput         = document.getElementById('supplierInput');
    const idSuppInput           = document.getElementById('idSuppInput');
    const supplierDropdown      = document.getElementById('supplierDropdown');
    const clearSupplierBtn      = document.getElementById('clearSupplierBtn');
    const supplierValidationMsg = document.getElementById('supplierValidationMsg');
    let activeDropdownIndex     = -1;

    function escapeHtml(str) {
        return (str || '').replace(/[&<>'"]/g, function (tag) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                "'": '&#39;',
                '"': '&quot;'
            }[tag] || tag;
        });
    }

    function highlightMatch(text, query) {
        if (!query) return escapeHtml(text);
        var regex = new RegExp('(' + query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
        return escapeHtml(text).replace(regex, '<mark class="bg-warning-subtle p-0 fw-bold">$1</mark>');
    }

    function renderSupplierDropdown(query) {
        query = (query || '').trim().toLowerCase();
        activeDropdownIndex = -1;

        var matches = [];
        if (query === '') {
            matches = suppliersData.slice(0, 15);
        } else {
            matches = suppliersData.filter(function (s) {
                return (s.nama_supp || '').toLowerCase().includes(query);
            });
        }

        if (matches.length === 0) {
            supplierDropdown.innerHTML = '<div class="p-3 text-center text-muted small">Tidak ada supplier yang cocok dengan "<strong>' + escapeHtml(query) + '</strong>".<br><a href="<?= base_url('supplier') ?>" target="_blank" class="btn btn-sm btn-link mt-1 text-decoration-none">+ Tambah Supplier Baru</a></div>';
            supplierDropdown.style.display = 'block';
            return;
        }

        var html = '<div class="list-group list-group-flush">';
        matches.forEach(function (s, idx) {
            var name = s.nama_supp;
            var highlighted = highlightMatch(name, query);
            html += '<button type="button" class="list-group-item list-group-item-action supplier-item py-2 px-3 d-flex align-items-center justify-content-between" data-id="' + s.id_supp + '" data-name="' + escapeHtml(name) + '" data-index="' + idx + '">';
            html += '<span>' + highlighted + '</span>';
            html += '</button>';
        });
        html += '</div>';

        supplierDropdown.innerHTML = html;
        supplierDropdown.style.display = 'block';

        supplierDropdown.querySelectorAll('.supplier-item').forEach(function (item) {
            item.addEventListener('click', function (e) {
                e.preventDefault();
                selectSupplier(this.getAttribute('data-id'), this.getAttribute('data-name'));
            });
        });
    }

    function selectSupplier(id, name) {
        idSuppInput.value = id;
        supplierInput.value = name;
        supplierDropdown.style.display = 'none';
        clearSupplierBtn.style.display = 'block';
        supplierValidationMsg.style.display = 'none';
        supplierInput.classList.remove('is-invalid');
        supplierInput.classList.add('is-valid');
    }

    supplierInput.addEventListener('input', function () {
        idSuppInput.value = '';
        supplierInput.classList.remove('is-valid');
        if (this.value.trim() !== '') {
            clearSupplierBtn.style.display = 'block';
            renderSupplierDropdown(this.value);
        } else {
            clearSupplierBtn.style.display = 'none';
            renderSupplierDropdown('');
        }
    });

    supplierInput.addEventListener('focus', function () {
        renderSupplierDropdown(this.value);
    });

    clearSupplierBtn.addEventListener('click', function () {
        supplierInput.value = '';
        idSuppInput.value = '';
        clearSupplierBtn.style.display = 'none';
        supplierDropdown.style.display = 'none';
        supplierInput.classList.remove('is-valid', 'is-invalid');
        supplierValidationMsg.style.display = 'none';
        supplierInput.focus();
    });

    document.addEventListener('click', function (e) {
        var wrapper = document.getElementById('supplierAutocompleteWrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            supplierDropdown.style.display = 'none';
        }
    });

    supplierInput.addEventListener('keydown', function (e) {
        var items = supplierDropdown.querySelectorAll('.supplier-item');
        if (!items.length || supplierDropdown.style.display === 'none') return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            activeDropdownIndex = (activeDropdownIndex + 1) % items.length;
            updateActiveItem(items);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            activeDropdownIndex = (activeDropdownIndex - 1 + items.length) % items.length;
            updateActiveItem(items);
        } else if (e.key === 'Enter') {
            if (activeDropdownIndex >= 0 && activeDropdownIndex < items.length) {
                e.preventDefault();
                items[activeDropdownIndex].click();
            }
        } else if (e.key === 'Escape') {
            supplierDropdown.style.display = 'none';
        }
    });

    function updateActiveItem(items) {
        items.forEach(function (el, i) {
            if (i === activeDropdownIndex) {
                el.classList.add('active', 'bg-light');
                el.scrollIntoView({ block: 'nearest' });
            } else {
                el.classList.remove('active', 'bg-light');
            }
        });
    }

    if (supplierInput.value.trim() !== '') {
        var existingName = supplierInput.value.trim().toLowerCase();
        var match = suppliersData.find(function (s) {
            return (s.nama_supp || '').toLowerCase() === existingName;
        });
        if (match) {
            idSuppInput.value = match.id_supp;
            supplierInput.classList.add('is-valid');
        }
    }

    // =========================================================
    // KONFIRMASI SUBMIT
    // =========================================================
    btnSubmitTrigger.addEventListener('click', function () {
        if (!grnForm.checkValidity()) {
            grnForm.reportValidity();
            return;
        }

        var inputVal = supplierInput.value.trim().toLowerCase();
        var matched = suppliersData.find(function (s) {
            return (s.nama_supp || '').toLowerCase() === inputVal;
        });

        if (!matched) {
            supplierInput.classList.add('is-invalid');
            supplierInput.classList.remove('is-valid');
            supplierValidationMsg.innerText = 'Harap pilih nama supplier yang terdaftar pada sistem dari daftar pilihan.';
            supplierValidationMsg.style.display = 'block';
            supplierInput.focus();
            return;
        } else {
            idSuppInput.value = matched.id_supp;
            supplierInput.value = matched.nama_supp;
            supplierValidationMsg.style.display = 'none';
            supplierInput.classList.remove('is-invalid');
            supplierInput.classList.add('is-valid');
        }

        confirmModal.show();
    });

    btnConfirmSubmit.addEventListener('click', function () {
        btnConfirmSubmit.disabled = true;
        btnConfirmSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...';
        grnForm.submit();
    });
});
</script>
<?= $this->endSection() ?>
