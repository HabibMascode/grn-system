<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $title ?? 'Sistem Serah Terima GRN' ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('logoglobal.png?v=999') ?>">

    <!-- Bootstrap 5.3.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --theme-navy: #1e3a8a;          /* Biru tua / Navy blue utama */
            --theme-navy-dark: #172554;     /* Biru tua pekat */
            --theme-blue-primary: #2563eb;  /* Biru aksen */
            --theme-blue-hover: #1d4ed8;    /* Biru hover */
            --theme-sidebar-bg: #eef6fc;    /* Biru muda / pastel untuk sidebar */
            --theme-sidebar-border: #dbe8f6;
            --theme-body-bg: #f4f8fd;       /* Abu-abu kebiruan sangat muda / bersih */
            --theme-card-bg: #ffffff;
            --theme-card-border: #e2e8f0;
            --bs-body-font-family: 'Inter', sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--theme-body-bg);
            color: #1e293b;
            min-height: 100vh;
            margin: 0;
            -webkit-font-smoothing: antialiased;
        }

        /* ═══════════════════════════════════════════
           LAYOUT WRAPPER & SIDEBAR (BIRU MUDA/PASTEL)
        ════════════════════════════════════════════ */
        .layout-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* SIDEBAR KIRI */
        .sidebar {
            width: 260px;
            min-width: 260px;
            background-color: var(--theme-sidebar-bg);
            border-right: 1px solid var(--theme-sidebar-border);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1040;
            transition: transform 0.25s ease-in-out;
        }

        /* SIDEBAR BRAND HEADER */
        .sidebar-brand {
            padding: 1.25rem 1.25rem;
            background: #ffffff;
            border-bottom: 1px solid var(--theme-sidebar-border);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .sidebar-logo {
            height: 38px;
            width: auto;
            object-fit: contain;
        }

        .sidebar-brand-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--theme-navy);
            line-height: 1.2;
            margin: 0;
        }

        .sidebar-brand-sub {
            font-size: 0.72rem;
            color: #64748b;
            margin: 0;
        }

        /* SIDEBAR NAVIGATION */
        .sidebar-nav {
            padding: 1.25rem 0.75rem;
            flex-grow: 1;
            overflow-y: auto;
        }

        .sidebar-heading {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            padding: 0.5rem 0.75rem 0.25rem;
            margin-top: 0.5rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.9rem;
            color: #334155 !important;
            font-weight: 600;
            font-size: 0.86rem;
            border-radius: 8px;
            text-decoration: none;
            margin-bottom: 0.3rem;
            transition: all 0.15s ease;
        }

        .sidebar-link i {
            font-size: 1.05rem;
            color: #475569;
            transition: color 0.15s ease;
        }

        .sidebar-link:hover {
            background-color: #dbeafe;
            color: var(--theme-navy) !important;
        }

        .sidebar-link:hover i {
            color: var(--theme-navy);
        }

        /* MENU AKTIF - BIRU LEBIH GELAP */
        .sidebar-link.active {
            background-color: var(--theme-navy) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(30, 58, 138, 0.25);
        }

        .sidebar-link.active i {
            color: #ffffff !important;
        }

        /* SIDEBAR FOOTER USER */
        .sidebar-footer {
            padding: 1rem 1.1rem;
            background: #ffffff;
            border-top: 1px solid var(--theme-sidebar-border);
        }

        .sidebar-user-name {
            font-size: 0.84rem;
            font-weight: 700;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-role {
            font-size: 0.7rem;
            color: #64748b;
        }

        /* ═══════════════════════════════════════════
           MAIN CONTENT AREA
        ════════════════════════════════════════════ */
        .main-wrapper {
            margin-left: 260px;
            flex-grow: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            background-color: var(--theme-body-bg);
        }

        /* TOPBAR MOBILE HEADER */
        .topbar-mobile {
            display: none;
            background: #ffffff;
            border-bottom: 1px solid var(--theme-card-border);
            padding: 0.75rem 1.25rem;
            align-items: center;
            justify-content: space-between;
        }

        .content-container {
            padding: 1.75rem 2rem 3rem;
            flex-grow: 1;
        }

        /* ═══════════════════════════════════════════
           TYPOGRAPHY & HEADINGS (NAVY BLUE)
        ════════════════════════════════════════════ */
        .page-title {
            color: var(--theme-navy);
            font-weight: 700;
            font-size: 1.45rem;
            letter-spacing: -0.02em;
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 0.875rem;
            margin-bottom: 0;
        }

        /* ═══════════════════════════════════════════
           CARDS & CONTAINERS (PUTIH, SUDUT MEMBULAT, SOFT SHADOW)
        ════════════════════════════════════════════ */
        .card {
            background-color: var(--theme-card-bg);
            border: 1px solid var(--theme-card-border);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #edf2f7;
            padding: 1rem 1.25rem;
            font-weight: 600;
            color: var(--theme-navy);
        }

        .card-body {
            padding: 1.25rem;
        }

        /* ═══════════════════════════════════════════
           TABEL SISTEM (HEADER BIRU TUA, TEKS PUTIH)
        ════════════════════════════════════════════ */
        .table {
            font-size: 0.865rem;
            margin-bottom: 0;
            vertical-align: middle;
        }

        .table thead th {
            background-color: var(--theme-navy) !important;
            color: #ffffff !important;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.85rem 1rem;
            border: none;
            vertical-align: middle;
            white-space: nowrap;
        }

        .table tbody td {
            background-color: #ffffff;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #edf2f7;
            vertical-align: middle;
            color: #1e293b;
        }

        .table-hover tbody tr:hover td {
            background-color: #f8fbff;
        }

        /* ═══════════════════════════════════════════
           STATUS BADGE (TEKS SAJA, TANPA IKON)
        ════════════════════════════════════════════ */
        .badge-status {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35rem 0.65rem;
            border-radius: 6px;
            text-align: center;
            white-space: nowrap;
        }

        /* Diterima / Received / Confirmed (Hijau Lembut) */
        .badge-status-received,
        .badge-status-confirmed,
        .badge-status-diterima {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        /* Diserahkan / Pending / Menunggu (Kuning/Amber Lembut) */
        .badge-status-pending,
        .badge-status-diserahkan,
        .badge-status-menunggu {
            background-color: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        /* Ditolak / Rejected (Merah Lembut) */
        .badge-status-rejected,
        .badge-status-ditolak {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        /* BUTTONS */
        .btn {
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.5rem 0.9rem;
            transition: all 0.15s ease;
        }

        .btn-primary {
            background-color: var(--theme-navy);
            border-color: var(--theme-navy);
            color: #ffffff;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: var(--theme-blue-hover);
            border-color: var(--theme-blue-hover);
            color: #ffffff;
        }

        .btn-outline-primary {
            border-color: var(--theme-navy);
            color: var(--theme-navy);
        }

        .btn-outline-primary:hover {
            background-color: var(--theme-navy);
            border-color: var(--theme-navy);
            color: #ffffff;
        }

        /* FORMS */
        .form-control,
        .form-select {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.875rem;
            padding: 0.55rem 0.85rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--theme-blue-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #334155;
            margin-bottom: 0.4rem;
        }

        /* MODALS */
        .modal-content {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 15px 30px rgba(15, 23, 42, 0.12);
            overflow: hidden;
        }

        .modal-header {
            background-color: #ffffff;
            border-bottom: 1px solid #edf2f7;
            padding: 1.1rem 1.5rem;
        }

        .modal-title {
            color: var(--theme-navy);
            font-weight: 700;
            font-size: 1.1rem;
        }

        /* RESPONSIVE BREAKPOINT (< 992px) */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
                box-shadow: 6px 0 20px rgba(0, 0, 0, 0.12);
            }

            .main-wrapper {
                margin-left: 0;
            }

            .topbar-mobile {
                display: flex;
            }

            .content-container {
                padding: 1.25rem 1rem 2.5rem;
            }

            .sidebar-backdrop {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, 0.4);
                z-index: 1030;
            }

            .sidebar-backdrop.show {
                display: block;
            }
        }
    </style>

    <?= $this->renderSection('styles') ?>
</head>

<body>

    <!-- BACKDROP UNTUK MOBILE SIDEBAR -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <div class="layout-wrapper">

        <!-- ═══════════════════════════════════════════
             SIDEBAR NAVIGASI KIRI (BIRU MUDA/PASTEL)
        ════════════════════════════════════════════ -->
        <aside class="sidebar" id="mainSidebar">

            <!-- BRAND LOGO -->
            <a href="<?= session()->get('role') === 'accounting' ? base_url('accounting') : base_url('grn') ?>" class="sidebar-brand">
                <img src="<?= base_url('logoglobal.png') ?>" alt="Logo Global Bangunan" class="sidebar-logo">
                <div>
                    <h1 class="sidebar-brand-title text-center">Sistem</h1>
                    <h1 class="sidebar-brand-title">Serah Terima</h1>
                    <p class="sidebar-brand-sub text-center">Dokumen GRN</p>
                </div>
            </a>

            <!-- NAVIGASI MENU -->
            <div class="sidebar-nav">

                <?php if (session()->get('role') === 'accounting'): ?>

                    <div class="sidebar-heading">Menu Utama</div>

                    <!-- INBOX GRN ACCOUNTING -->
                    <a href="<?= base_url('accounting') ?>" class="sidebar-link <?= (url_is('accounting*') || url_is('homeacc*')) ? 'active' : '' ?>">
                        <i class="bi bi-inbox"></i>
                        <span>Inbox GRN</span>
                    </a>

                <?php else: ?>

                    <!-- ADMIN NAVIGATION -->
                    <div class="sidebar-heading">Serah Terima GRN</div>

                    <!-- INBOX & RIWAYAT -->
                    <a href="<?= base_url('grn') ?>" class="sidebar-link <?= (url_is('grn') || url_is('home')) ? 'active' : '' ?>">
                        <i class="bi bi-inbox"></i>
                        <span>Inbox &amp; Riwayat</span>
                    </a>

                    <!-- FORM GRN -->
                    <a href="<?= base_url('grn/form') ?>" class="sidebar-link <?= url_is('grn/form*') ? 'active' : '' ?>">
                        <i class="bi bi-file-earmark-plus"></i>
                        <span>Form Input GRN</span>
                    </a>

                    <div class="sidebar-heading">Pengaturan &amp; Data</div>

                    <!-- KELOLA SUPPLIER -->
                    <a href="<?= base_url('supplier') ?>" class="sidebar-link <?= url_is('supplier*') ? 'active' : '' ?>">
                        <i class="bi bi-truck"></i>
                        <span>Kelola Supplier</span>
                    </a>

                    <!-- SUPPLIER ACCOUNTING -->
                    <a href="<?= base_url('accounting-supp') ?>" class="sidebar-link <?= url_is('accounting-supp*') ? 'active' : '' ?>">
                        <i class="bi bi-person-gear"></i>
                        <span>Supplier Accounting</span>
                    </a>

                    <!-- KELOLA ACCOUNTING -->
                    <a href="<?= base_url('register') ?>" class="sidebar-link <?= (url_is('register*') || url_is('kelola-accounting*')) ? 'active' : '' ?>">
                        <i class="bi bi-people"></i>
                        <span>Kelola Accounting</span>
                    </a>

                <?php endif; ?>

            </div>

            <!-- FOOTER USER INFO & LOGOUT -->
            <div class="sidebar-footer">
                <div class="mb-2">
                    <div class="sidebar-user-name text-center">
                        <?= esc(session()->get('name') ?? 'Pengguna') ?>
                    </div>
                    <div class="sidebar-user-role text-center">
                        <strong><?= ucfirst(esc(session()->get('role') ?? '-')) ?></strong>
                    </div>
                </div>
                <a href="<?= base_url('logout') ?>" class="btn btn-sm btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-1 py-1">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Keluar</span>
                </a>
            </div>

        </aside>

        <!-- ═══════════════════════════════════════════
             MAIN CONTENT WRAPPER
        ════════════════════════════════════════════ -->
        <div class="main-wrapper">

            <!-- MOBILE TOPBAR -->
            <div class="topbar-mobile">
                <button type="button" class="btn btn-sm btn-light border py-1 px-2" id="btnToggleSidebar">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div class="fw-bold text-dark" style="font-size: 0.9rem;">
                    Sistem GRN
                </div>
                <span class="badge bg-light text-secondary border">
                    <?= ucfirst(esc(session()->get('role') ?? '')) ?>
                </span>
            </div>

            <!-- CONTENT BODY -->
            <div class="content-container">
                <?= $this->renderSection('content') ?>
            </div>

        </div>

    </div>

    <!-- Bootstrap 5.3.2 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle Sidebar pada layar mobile
        document.addEventListener('DOMContentLoaded', function () {
            var btnToggle = document.getElementById('btnToggleSidebar');
            var sidebar = document.getElementById('mainSidebar');
            var backdrop = document.getElementById('sidebarBackdrop');

            if (btnToggle && sidebar && backdrop) {
                btnToggle.addEventListener('click', function () {
                    sidebar.classList.toggle('show');
                    backdrop.classList.toggle('show');
                });

                backdrop.addEventListener('click', function () {
                    sidebar.classList.remove('show');
                    backdrop.classList.remove('show');
                });
            }
        });
    </script>

    <?= $this->renderSection('scripts') ?>

</body>

</html>
