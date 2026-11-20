<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-6">

        <!-- PAGE HEADER -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h2 class="page-title mb-1">Edit Data Supplier</h2>
                <p class="text-secondary small mb-0">Perbarui nama supplier yang terdaftar di sistem.</p>
            </div>
            <a href="<?= base_url('supplier') ?>" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Supplier</span>
            </a>
        </div>

        <!-- FLASH ERROR -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                <div><?= esc(session()->getFlashdata('error')) ?></div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- FORM CARD -->
        <div class="card">
            <div class="card-header">
                <span>Form Edit Supplier</span>
            </div>
            <div class="card-body p-4">
                <form action="<?= base_url('supplier/update/' . $supplier['id_supp']) ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-4">
                        <label for="nama_supp" class="form-label">
                            Nama Supplier <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            id="nama_supp"
                            name="nama_supp"
                            class="form-control"
                            value="<?= esc(old('nama_supp', $supplier['nama_supp'])) ?>"
                            required
                            autofocus
                        >
                        <div class="form-text mt-1">
                            Perubahan nama supplier akan otomatis disesuaikan pada data transaksi terkait.
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="<?= base_url('supplier') ?>" class="btn btn-light border">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>
