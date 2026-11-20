<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Sistem Serah Terima GRN</title>
    <link rel="icon" type="image/png" href="<?= base_url('logoglobal.png?v=999') ?>">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --theme-navy: #1e3a8a;
            --theme-navy-dark: #172554;
            --theme-blue-primary: #2563eb;
            --theme-blue-hover: #1d4ed8;
            --theme-bg-light: #f4f8fd;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--theme-bg-light);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1.5rem;
            color: #1e293b;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            padding: 2.5rem 2rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px -5px rgba(30, 58, 138, 0.08), 0 8px 10px -6px rgba(30, 58, 138, 0.04);
        }

        .login-logo {
            display: block;
            height: 48px;
            width: auto;
            margin: 0 auto 1.25rem;
            object-fit: contain;
        }

        .login-title {
            text-align: center;
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--theme-navy);
            margin-bottom: 0.35rem;
            letter-spacing: -0.02em;
        }

        .login-subtitle {
            text-align: center;
            color: #64748b;
            font-size: 0.875rem;
            margin-bottom: 1.75rem;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.4rem;
        }

        .form-control {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 0.65rem 0.9rem;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--theme-blue-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .btn-login {
            background-color: var(--theme-navy);
            border: none;
            border-radius: 8px;
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: 600;
            padding: 0.75rem 1rem;
            width: 100%;
            transition: background 0.2s ease, transform 0.1s ease;
        }

        .btn-login:hover {
            background-color: var(--theme-blue-hover);
            color: #ffffff;
        }

        .btn-login:active {
            transform: scale(0.99);
        }

        .alert-custom {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>
</head>

<body>

    <div class="login-card">

        <img 
            src="<?= base_url('logoglobal.png') ?>" 
            alt="Global Bangunan"
            class="login-logo"
        >

        <h1 class="login-title">
            Sistem Serah Terima GRN
        </h1>

        <p class="login-subtitle">
            Silakan masukkan akun Anda untuk melanjutkan
        </p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-custom" role="alert">
                <span><?= esc(session()->getFlashdata('error')) ?></span>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success py-2 px-3 small border-0 mb-3" role="alert">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('login') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    class="form-control"
                    placeholder="Masukkan username Anda"
                    value="<?= esc(old('username')) ?>"
                    required
                    autofocus
                >
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="Masukkan password Anda"
                    required
                >
            </div>

            <button type="submit" class="btn-login">
                Masuk
            </button>
        </form>

    </div>

</body>

</html>
