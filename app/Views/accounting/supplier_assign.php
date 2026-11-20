<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<style>
    .supp-checklist-container {
        max-height: 320px;
        overflow-y: auto;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem;
        background: #f8fafc;
    }
    .supp-check-item {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        margin-bottom: 0.5rem;
        transition: background 0.15s ease, border-color 0.15s ease;
    }
    .supp-check-item:hover {
        background: #f0f7ff;
        border-color: #bfdbfe;
    }
    /* Pengaturan tabel supplier */
#tableAccountingAssign {
    width: 100%;
    table-layout: fixed;
    border-collapse: collapse;
}

#tableAccountingAssign th,
#tableAccountingAssign td {
    vertical-align: middle;
    padding: 14px 12px;
}

/* Lebar masing-masing kolom */
#tableAccountingAssign th:nth-child(1),
#tableAccountingAssign td:nth-child(1) {
    width: 5%;
    text-align: center;
}

#tableAccountingAssign th:nth-child(2),
#tableAccountingAssign td:nth-child(2) {
    width: 19%;
}

#tableAccountingAssign th:nth-child(3),
#tableAccountingAssign td:nth-child(3) {
    width: 14%;
}

#tableAccountingAssign th:nth-child(4),
#tableAccountingAssign td:nth-child(4) {
    width: 17%;
    text-align: center;
}

#tableAccountingAssign th:nth-child(5),
#tableAccountingAssign td:nth-child(5) {
    width: 29%;
    padding-left: 28px;
}

#tableAccountingAssign th:nth-child(6),
#tableAccountingAssign td:nth-child(6) {
    width: 16%;
    text-align: center;
}

/* Daftar supplier */
#tableAccountingAssign td:nth-child(5) {
    line-height: 1.6;
}

/* Tombol aksi */
#tableAccountingAssign .btn {
    white-space: nowrap;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- PAGE HEADER -->
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="page-title mb-1">Pengaturan Supplier Accounting</h2>
        <p class="text-secondary small mb-0">Kelola Supplier ke Accounting.</p>
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

<!-- CARD TABEL PENUGASAN SUPPLIER -->
<div class="card">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
            <span>Daftar Penugasan Supplier Accounting</span>
            <span class="badge bg-secondary-subtle text-secondary rounded-pill">
                <?= count($accountingUsers ?? []) ?> Akun
            </span>
        </div>

        <!-- SEARCH INPUT -->
        <div class="input-group input-group-sm" style="max-width: 260px;">
            <span class="input-group-text bg-white border-end-0 text-secondary">
                <i class="bi bi-search"></i>
            </span>
            <input
                type="text"
                id="searchAccInput"
                class="form-control border-start-0"
                placeholder="Cari accounting atau supplier..."
            >
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0" id="tableAccountingAssign">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Accounting</th>
                    <th>Username</th>
                    <th>Jumlah Supplier</th>
                    <th>Daftar Supplier</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($accountingUsers)): ?>
                    <?php $no = 1; foreach ($accountingUsers as $acc): ?>
                        <?php
                            $assigned = $userAssignments[$acc['id']] ?? [];
                            $assignedIds = array_column($assigned, 'id_supp');
                            $assignedCount = count($assigned);
                            $supplierNames = array_column($assigned, 'nama_supp');
                            $supplierListText = implode(', ', $supplierNames);
                            $searchMeta = strtolower(esc($acc['name'] . ' ' . $acc['username'] . ' ' . $supplierListText));
                        ?>
                        <tr class="acc-row" data-search="<?= $searchMeta ?>">
                            <!-- No -->
                            <td class="text-center text-secondary fw-medium"><?= $no++ ?></td>

                            <!-- Nama Accounting -->
                            <td>
                                <span class="fw-semibold text-dark d-block">
                                    <?= esc($acc['name']) ?>
                                </span>
                            </td>

                            <!-- Username -->
                            <td class="text-secondary">
                                <?= esc($acc['username']) ?>
                            </td>

                            <!-- Jumlah Supplier -->
                            <td class="text-center">
                                <span class="fw-medium text-dark">
                                    <?= $assignedCount ?> Supplier
                                </span>
                            </td>

                            <!-- Daftar Supplier -->
                            <td>
                                <?php if ($assignedCount > 0): ?>
                                    <?php foreach ($supplierNames as $supplier): ?>
                                        <div class="text-dark">
                                            <?= esc($supplier) ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">
                                        Belum ada supplier yang ditugaskan
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Aksi -->
                            <td class="text-center">
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalAssign<?= $acc['id'] ?>"
                                >
                                    <i class="bi bi-pencil-square me-1"></i>
                                    <span>Atur Supplier</span>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-secondary">
                            Belum ada akun Accounting yang terdaftar di sistem.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
$allAssignedSupplierIds = [];

foreach ($userAssignments as $assignments) {
    foreach ($assignments as $assignment) {
        $allAssignedSupplierIds[] = $assignment['id_supp'];
    }
}
?>

<!-- MODAL ASSIGNMENT FOR EACH ACCOUNTING USER -->
<?php if (!empty($accountingUsers)): ?>
    <?php foreach ($accountingUsers as $acc): ?>
        <?php
            $assigned = $userAssignments[$acc['id']] ?? [];
            $assignedIds = array_column($assigned, 'id_supp');
        ?>
        <div class="modal fade" id="modalAssign<?= $acc['id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <form action="<?= base_url('accounting-supp/save') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="accounting_id" value="<?= $acc['id'] ?>">

                        <div class="modal-header">
                            <div>
                                <h5 class="modal-title mb-0">
                                    Pengaturan Supplier <?= esc($acc['name']) ?>
                                </h5>
                                <div class="text-secondary small mt-1">Centang supplier yang menjadi tanggung jawab akun ini.</div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <?php if (!empty($allSuppliers)): ?>
                                <!-- PENCARIAN & TOMBOL PILIH CEPAT -->
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                                    <div class="input-group input-group-sm" style="max-width: 280px;">
                                        <span class="input-group-text bg-white text-secondary"><i class="bi bi-search"></i></span>
                                        <input
                                            type="text"
                                            class="form-control filter-supp-input"
                                            data-target="#suppList<?= $acc['id'] ?>"
                                            placeholder="Cari nama supplier..."
                                        >
                                    </div>
                                </div>

                                <!-- DAFTAR CHECKBOX SUPPLIER -->
                                <div class="supp-checklist-container" id="suppList<?= $acc['id'] ?>">
                                    <div class="row g-2">
                                        <?php foreach ($allSuppliers as $sp): ?>
                                            <?php
                                                $supplierId = $sp['id_supp'];

                                                // Supplier sudah ditugaskan ke Accounting yang sedang dibuka
                                                $isAssignedToThisAccounting = in_array($supplierId, $assignedIds);

                                                // Supplier sudah ditugaskan ke Accounting mana pun
                                                $isAlreadyAssigned = in_array($supplierId, $allAssignedSupplierIds);

                                                /*
                                                * Tampilkan supplier jika:
                                                * 1. Belum ditugaskan ke siapa pun, ATAU
                                                * 2. Sudah ditugaskan ke Accounting ini sendiri
                                                */
                                                if ($isAlreadyAssigned && !$isAssignedToThisAccounting) {
                                                    continue;
                                                }

                                                $isChecked = $isAssignedToThisAccounting;
                                            ?>
                                            <div class="col-md-6 supp-item-wrapper" data-name="<?= strtolower(esc($sp['nama_supp'])) ?>">
                                                <div class="supp-check-item d-flex align-items-center">
                                                    <div class="form-check w-100 mb-0">
                                                        <input
                                                            class="form-check-input supp-checkbox"
                                                            type="checkbox"
                                                            name="supplier_ids[]"
                                                            value="<?= $sp['id_supp'] ?>"
                                                            id="supp_<?= $acc['id'] ?>_<?= $sp['id_supp'] ?>"
                                                            <?= $isChecked ? 'checked' : '' ?>
                                                        >
                                                        <label
                                                            class="form-check-label fw-medium ms-1 text-dark w-100"
                                                            for="supp_<?= $acc['id'] ?>_<?= $sp['id_supp'] ?>"
                                                            style="cursor: pointer;"
                                                        >
                                                            <?= esc($sp['nama_supp']) ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4 text-secondary">
                                    Belum ada data supplier terdaftar pada sistem.<br>
                                    <a href="<?= base_url('supplier') ?>" class="btn btn-sm btn-primary mt-2">
                                        Tambah Supplier Terlebih Dahulu
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" <?= empty($allSuppliers) ? 'disabled' : '' ?>>
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Pencarian real-time pada tabel penugasan
    var searchAccInput = document.getElementById('searchAccInput');
    if (searchAccInput) {
        searchAccInput.addEventListener('input', function () {
            var keyword = this.value.toLowerCase().trim();
            var rows = document.querySelectorAll('.acc-row');
            rows.forEach(function (row) {
                var text = row.getAttribute('data-search') || '';
                if (keyword === '' || text.indexOf(keyword) > -1) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Filter pencarian di dalam modal supplier
    document.querySelectorAll('.filter-supp-input').forEach(function (input) {
        input.addEventListener('input', function () {
            var targetSelector = this.getAttribute('data-target');
            var container = document.querySelector(targetSelector);
            if (!container) return;
            var keyword = this.value.toLowerCase().trim();
            var items = container.querySelectorAll('.supp-item-wrapper');
            items.forEach(function (item) {
                var name = item.getAttribute('data-name') || '';
                if (keyword === '' || name.includes(keyword)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});
</script>
<?= $this->endSection() ?>
