<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<style>
    .pass-input-group .toggle-eye {
        border-left: none;
        background: #ffffff;
        cursor: pointer;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- PAGE HEADER -->
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="page-title mb-1">Kelola Accounting</h2>
        <p class="text-secondary small mb-0">Kelola data akun Accounting yang terdaftar di sistem.</p>
    </div>

</div>

<!-- FLASH ALERTS -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-check-circle-fill text-success fs-5"></i>
        <div><?= esc(session()->getFlashdata('success')) ?></div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
        <div><?= esc(session()->getFlashdata('error')) ?></div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- CARD 1: FORM TAMBAH ACCOUNTING -->
<div class="card mb-4">
    <div class="card-header">
        <span>Tambah Akun Accounting Baru</span>
    </div>
    <div class="card-body p-4">
        <form id="formRegister" action="<?= base_url('register/save') ?>" method="post" novalidate>
            <?= csrf_field() ?>

            <div class="row g-3">
                <!-- Nama Lengkap -->
                <div class="col-md-4">
                    <label for="reg_name" class="form-label">
                        Nama Lengkap <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        id="reg_name"
                        name="name"
                        class="form-control"
                        placeholder="Contoh: Budi Santoso"
                        value="<?= esc(old('name')) ?>"
                        required
                        autocomplete="off"
                    >
                </div>

                <!-- Username -->
                <div class="col-md-4">
                    <label for="reg_username" class="form-label">
                        Username <span class="text-danger">*</span>
                    </label>
                    <input
                        type="text"
                        id="reg_username"
                        name="username"
                        class="form-control"
                        placeholder="Contoh: budi_acc"
                        value="<?= esc(old('username')) ?>"
                        required
                        autocomplete="off"
                    >
                </div>

                <!-- Password -->
                <div class="col-md-4">
                    <label for="reg_password" class="form-label">
                        Password <span class="text-danger">*</span>
                    </label>
                    <div class="input-group pass-input-group">
                        <input
                            type="password"
                            id="reg_password"
                            name="password"
                            class="form-control"
                            placeholder="Min. 6 karakter"
                            required
                            autocomplete="new-password"
                        >
                        <button
                            type="button"
                            class="btn btn-outline-secondary toggle-eye"
                            data-target="reg_password"
                            title="Tampilkan password"
                        >
                            <i class="bi bi-eye" id="eye_password"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tombol Simpan & Keterangan Role Otomatis -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 pt-3 border-top gap-2">
                <button type="submit" id="btnSimpan" class="btn btn-primary px-4">
                    Simpan Akun
                </button>
            </div>
        </form>
    </div>
</div>

<!-- CARD 2: TABEL DAFTAR ACCOUNTING -->
<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
            <span>Daftar Akun Accounting</span>
            <span class="badge bg-secondary-subtle text-secondary rounded-pill">
                <?= count($users ?? []) ?> Akun
            </span>
        </div>

        <!-- SEARCH INPUT -->
        <div class="input-group input-group-sm" style="max-width: 250px;">
            <span class="input-group-text bg-white border-end-0 text-secondary">
                <i class="bi bi-search"></i>
            </span>
            <input
                type="text"
                id="tableSearchInput"
                class="form-control border-start-0"
                placeholder="Cari nama atau username..."
            >
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0" id="accountingTable">
            <thead>
                <tr>
                    <th style="width: 50px;" class="text-center">No</th>
                    <th style="width: 200px;">Nama Lengkap</th>
                    <th style="width: 140px;">Username</th>
                    <th style="width: 140px;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $i => $u): ?>
                        <?php
                            $assigned = $userAssignments[$u['id']] ?? [];
                            $assignedCount = count($assigned);
                            $supplierNames = array_column($assigned, 'nama_supp');
                            $supplierListText = implode(', ', $supplierNames);
                            $searchMeta = strtolower(esc($u['name'] . ' ' . $u['username'] . ' ' . $supplierListText));
                        ?>
                        <tr class="accounting-row" data-search="<?= $searchMeta ?>">
                            <!-- No -->
                            <td class="text-center text-secondary fw-medium">
                                <?= $i + 1 ?>
                            </td>

                            <!-- Nama Lengkap -->
                            <td>
                                <span class="fw-semibold text-dark">
                                    <?= esc($u['name']) ?>
                                </span>
                            </td>

                            <!-- Username -->
                            <td class="text-secondary">
                                <?= esc($u['username']) ?>
                            </td>

                            <!-- Aksi (Ikon hanya di kolom aksi) -->
                            <td class="text-center">
                                <div class="d-inline-flex gap-1">
                                    <!-- Edit Akun -->
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-primary btn-edit"
                                        data-id="<?= $u['id'] ?>"
                                        data-nama="<?= esc($u['name']) ?>"
                                        data-username="<?= esc($u['username']) ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEdit"
                                        title="Edit Akun"
                                    >
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <!-- Hapus Akun -->
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger btn-hapus"
                                        data-id="<?= $u['id'] ?>"
                                        data-nama="<?= esc($u['name']) ?>"
                                        data-username="<?= esc($u['username']) ?>"
                                        title="Hapus"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-secondary">
                            Belum ada akun Accounting terdaftar.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL EDIT AKUN ACCOUNTING -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formEdit" method="post" action="" novalidate>
                <?= csrf_field() ?>

                <div class="modal-header">
                    <h5 class="modal-title fw-semibold" id="modalEditLabel">
                        <i class="bi bi-person-gear me-2 text-primary"></i>Edit Akun Accounting
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <!-- Nama Lengkap -->
                    <div class="mb-3">
                        <label for="edit_name" class="form-label fw-medium">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            id="edit_name"
                            name="name"
                            class="form-control"
                            placeholder="Contoh: Budi Santoso"
                            required
                            autocomplete="off"
                        >
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <label for="edit_username" class="form-label fw-medium">
                            Username <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            id="edit_username"
                            name="username"
                            class="form-control"
                            placeholder="Contoh: budi_acc"
                            required
                            autocomplete="off"
                        >
                    </div>

                    <!-- Password -->
                    <div class="mb-1">
                        <label for="edit_password" class="form-label fw-medium">
                            Password Baru
                        </label>
                        <div class="input-group pass-input-group">
                            <input
                                type="password"
                                id="edit_password"
                                name="password"
                                class="form-control"
                                placeholder="Kosongkan jika tidak ingin diubah"
                                autocomplete="new-password"
                            >
                            <button
                                type="button"
                                class="btn btn-outline-secondary toggle-eye"
                                data-target="edit_password"
                                title="Tampilkan password"
                            >
                                <i class="bi bi-eye" id="eye_edit_password"></i>
                            </button>
                        </div>
                        <div class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i>Kosongkan jika tidak ingin mengubah password. Min. 6 karakter jika diisi.
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" id="btnSimpanEdit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI HAPUS -->
<div class="modal fade" id="modalHapus" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger mb-0" id="modalHapusLabel">
                    Konfirmasi Hapus Akun Accounting
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-2 text-dark">
                    Apakah Anda yakin ingin menghapus akun Accounting <strong id="hapusNama">—</strong> (<span id="hapusUsername">—</span>)?
                </p>
                <div class="small text-secondary">
                    Penugasan supplier terkait akun ini juga akan otomatis dihapus.
                </div>
            </div>
            <div class="modal-footer">
                <form id="formHapus" method="post" action="" class="w-100 d-flex justify-content-end gap-2 mb-0">
                    <?= csrf_field() ?>
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle Tampilkan / Sembunyikan Password
    document.querySelectorAll('.toggle-eye').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var targetId = this.getAttribute('data-target');
            var input    = document.getElementById(targetId);
            var icon     = document.getElementById('eye_' + targetId.replace('reg_', ''));

            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        });
    });

    // Pencarian Real-Time pada Tabel
    var searchInput = document.getElementById('tableSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            var keyword = this.value.toLowerCase().trim();
            var rows = document.querySelectorAll('.accounting-row');

            rows.forEach(function (row) {
                var searchData = row.getAttribute('data-search') || '';
                if (keyword === '' || searchData.indexOf(keyword) > -1) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Validasi form & loading state submit
    var formRegister = document.getElementById('formRegister');
    if (formRegister) {
        formRegister.addEventListener('submit', function (e) {
            var pass = document.getElementById('reg_password').value.trim();
            if (pass.length < 6) {
                e.preventDefault();
                alert('Password minimal 6 karakter.');
                document.getElementById('reg_password').focus();
                return;
            }

            var btn = document.getElementById('btnSimpan');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Menyimpan...';
        });
    }

    // Modal Hapus Accounting
    var modalHapusEl = document.getElementById('modalHapus');
    if (modalHapusEl) {
        var modalHapus      = new bootstrap.Modal(modalHapusEl);
        var formHapus       = document.getElementById('formHapus');
        var elHapusNama     = document.getElementById('hapusNama');
        var elHapusUsername = document.getElementById('hapusUsername');

        document.querySelectorAll('.btn-hapus').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var id       = this.getAttribute('data-id');
                var nama     = this.getAttribute('data-nama');
                var username = this.getAttribute('data-username');

                elHapusNama.textContent     = nama;
                elHapusUsername.textContent = '@' + username;
                formHapus.action = '<?= base_url('register/delete') ?>/' + id;

                modalHapus.show();
            });
        });
    }

    // Modal Edit Akun Accounting
    var formEdit       = document.getElementById('formEdit');
    var editName       = document.getElementById('edit_name');
    var editUsername   = document.getElementById('edit_username');
    var editPassword   = document.getElementById('edit_password');
    var btnSimpanEdit  = document.getElementById('btnSimpanEdit');

    document.querySelectorAll('.btn-edit').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id       = this.getAttribute('data-id');
            var nama     = this.getAttribute('data-nama');
            var username = this.getAttribute('data-username');

            // Isi form dengan data akun yang dipilih
            editName.value     = nama;
            editUsername.value = username;
            editPassword.value = '';

            // Set action form ke endpoint update
            formEdit.action = '<?= base_url('register/update') ?>/' + id;

            // Reset tombol simpan
            if (btnSimpanEdit) {
                btnSimpanEdit.disabled = false;
                btnSimpanEdit.innerHTML = '<i class="bi bi-check-lg me-1"></i>Simpan Perubahan';
            }
        });
    });

    // Validasi & loading state form edit
    if (formEdit) {
        formEdit.addEventListener('submit', function (e) {
            var name     = editName.value.trim();
            var username = editUsername.value.trim();
            var pass     = editPassword.value.trim();

            if (name === '' || username === '') {
                e.preventDefault();
                alert('Nama lengkap dan username wajib diisi.');
                return;
            }

            if (pass !== '' && pass.length < 6) {
                e.preventDefault();
                alert('Password minimal 6 karakter.');
                editPassword.focus();
                return;
            }

            if (btnSimpanEdit) {
                btnSimpanEdit.disabled = true;
                btnSimpanEdit.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Menyimpan...';
            }
        });
    }

    // Toggle show/hide password untuk field edit
    var editEyeBtn = document.querySelector('[data-target="edit_password"]');
    if (editEyeBtn) {
        editEyeBtn.addEventListener('click', function () {
            var input = document.getElementById('edit_password');
            var icon  = document.getElementById('eye_edit_password');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        });
    }
});
</script>
<?= $this->endSection() ?>
