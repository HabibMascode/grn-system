<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- PAGE HEADER -->
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="page-title mb-1">Kelola Supplier</h2>
        <p class="text-secondary small mb-0">Kelola data supplier untuk serah terima Goods Receipt Note (GRN).</p>
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

<div class="row g-4">

    <!-- FORM TAMBAH SUPPLIER (KOLOM KIRI) -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <span>Tambah Supplier Baru</span>
            </div>
            <div class="card-body">
                <form action="<?= base_url('supplier/save') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="nama_supp" class="form-label">
                            Nama Supplier <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            id="nama_supp"
                            name="nama_supp"
                            class="form-control"
                            placeholder="Contoh: PT. Mitra Nuansa Abadi"
                            value="<?= esc(old('nama_supp')) ?>"
                            required
                            autofocus
                        >
                        <div class="form-text mt-1">
                            Masukkan nama resmi supplier atau vendor.
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary">
                            Simpan Supplier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- DAFTAR SUPPLIER (KOLOM KANAN) -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span>Daftar Supplier Terdaftar</span>
                    <span class="badge bg-secondary-subtle text-secondary rounded-pill">
                        <?= count($suppliers ?? []) ?> Data
                    </span>
                </div>

                <!-- SEARCH FORM -->
                <form method="get" action="<?= base_url('supplier') ?>" class="d-flex gap-2">
                    <div class="input-group input-group-sm" style="width: 240px;">
                        <input
                            type="text"
                            name="keyword"
                            class="form-control"
                            placeholder="Cari nama supplier..."
                            value="<?= esc($keyword ?? '') ?>"
                        >
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                        <?php if (!empty($keyword)): ?>
                            <a href="<?= base_url('supplier') ?>" class="btn btn-outline-secondary" title="Reset">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 50px;" class="text-center">No</th>
                            <th>Nama Supplier</th>
                            <th style="width: 120px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($suppliers)): ?>
                            <?php $no = 1; foreach ($suppliers as $s): ?>
                                <tr>
                                    <td class="text-center text-secondary fw-medium"><?= $no++ ?></td>
                                    <td>
                                        <span class="fw-semibold text-dark">
                                            <?= esc($s['nama_supp']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-inline-flex gap-1">
                                            <!-- TOMBOL EDIT (MODAL) -->
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEdit<?= $s['id_supp'] ?>"
                                                title="Edit"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            <!-- TOMBOL HAPUS -->
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalDelete<?= $s['id_supp'] ?>"
                                                title="Hapus"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>

                                        <!-- MODAL EDIT -->
                                        <div class="modal fade text-start" id="modalEdit<?= $s['id_supp'] ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="<?= base_url('supplier/update/' . $s['id_supp']) ?>" method="post">
                                                        <?= csrf_field() ?>
                                                        <div class="modal-header">
                                                            <h5 class="modal-title mb-0">
                                                                Edit Nama Supplier
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">
                                                                    Nama Supplier <span class="text-danger">*</span>
                                                                </label>
                                                                <input
                                                                    type="text"
                                                                    name="nama_supp"
                                                                    class="form-control"
                                                                    value="<?= esc($s['nama_supp']) ?>"
                                                                    required
                                                                >
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">
                                                                Simpan Perubahan
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- MODAL HAPUS -->
                                        <div class="modal fade text-start" id="modalDelete<?= $s['id_supp'] ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <form action="<?= base_url('supplier/delete/' . $s['id_supp']) ?>" method="post">
                                                        <?= csrf_field() ?>
                                                        <div class="modal-header">
                                                            <h5 class="modal-title text-danger mb-0">
                                                                Konfirmasi Hapus Supplier
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="mb-2">
                                                                Apakah Anda yakin ingin menghapus supplier <strong><?= esc($s['nama_supp']) ?></strong>?
                                                            </p>
                                                            <div class="small text-secondary">
                                                                Supplier yang sudah memiliki data transaksi GRN tidak dapat dihapus.
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger">
                                                                Ya, Hapus
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center py-5 text-secondary">
                                    <?= !empty($keyword) ? 'Tidak ada supplier yang cocok dengan kata kunci pencarian.' : 'Belum ada data supplier yang ditambahkan.' ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
