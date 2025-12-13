<?php require_once './views/layouts/header.php'; ?>

<style>
    /* --- RESET & LAYOUT UTAMA --- */
    body,
    html {
        height: 100%;
        overflow-x: hidden;
        background-color: #fff;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .app-container {
        min-height: calc(100vh - 76px);
        display: flex;
        align-items: stretch;

    }

    /* --- BAGIAN KIRI (VISUAL) --- */
    .brand-section {
        background: linear-gradient(135deg, #0f5132 0%, #198754 100%);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 3rem;
        position: relative;
        overflow: hidden;
        border-radius: 10px;
        margin-bottom: 30px;
    }

    .brand-section::before {
        content: '';
        position: absolute;
        width: 150%;
        height: 150%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
        top: -25%;
        left: -25%;
    }

    .brand-logo {
        width: 180px;
        height: 180px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 40px;
        padding: 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.2);
        animation: float 6s ease-in-out infinite;
        margin-bottom: 2rem;
    }

    .brand-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.2));
    }

    @keyframes float {
        0% {
            transform: translateY(0px);
        }

        50% {
            transform: translateY(-20px);
        }

        100% {
            transform: translateY(0px);
        }
    }

    /* --- BAGIAN KANAN (INTERAKSI) --- */
    .action-section {
        background-color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 3rem;
        position: relative;
    }

    .login-wrapper {
        width: 100%;
        max-width: 450px;
        text-align: center;
    }

    .status-indicator {
        display: inline-flex;
        align-items: center;
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 2rem;
    }

    .status-wait {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-ok {
        background-color: #d1e7dd;
        color: #0f5132;
    }

    .card-action {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        padding: 40px;
        border: 1px solid #f0f0f0;
        position: relative;
        overflow: hidden;
    }

    .card-action::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: #198754;
    }

    .btn-main {
        padding: 15px 0;
        border-radius: 12px;
        font-weight: 700;
        letter-spacing: 0.5px;
        font-size: 1.1rem;
        box-shadow: 0 10px 20px rgba(25, 135, 84, 0.2);
        transition: all 0.3s;
    }

    .btn-main:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(25, 135, 84, 0.3);
    }

    @media (max-width: 992px) {
        .app-container {
            flex-direction: column;
        }

        .brand-section {
            padding: 3rem 1rem;
            text-align: center;
            min-height: 40vh;
        }

        .brand-logo {
            width: 120px;
            height: 120px;
            margin-bottom: 1rem;
        }

        .action-section {
            padding: 2rem 1rem;
            flex-grow: 1;
            background: #f8f9fa;
            border-radius: 30px 30px 0 0;
            margin-top: -30px;
            z-index: 10;
        }

        .card-action {
            box-shadow: none;
            background: transparent;
            border: none;
            padding: 0;
        }
    }
</style>

<div class="app-container">

    <div class="col-lg-6 brand-section">
        <div class="brand-logo">
            <img src="<?php echo BASE_URL; ?>/assets/img/download.png" alt="Logo">
        </div>
        <div class="brand-text text-center">
            <h1 class="display-4 mb-3 fw-bold" style="text-shadow: 0 2px 10px rgba(0,0,0,0.2);">Sistem Pemilihan Online</h1>
            <p class="mb-4 fs-5 opacity-75">Pemilihan Cepat, Aman, dan Rahasia.</p>
        </div>
    </div>

    <div class="col-lg-6 action-section">
        <div class="login-wrapper">

            <?php if (isset($_SESSION['pemilih_id'])): ?>

                <div class="card-action">
                    <div class="status-indicator status-ok mb-4"><i class="bi bi-patch-check-fill me-2"></i> Terverifikasi</div>

                    <h2 class="fw-bold text-dark mb-2">Selamat Datang,</h2>
                    <h4 class="text-success mb-4 pb-3 border-bottom"><?php echo htmlspecialchars($_SESSION['pemilih_nama']); ?></h4>

                    <p class="text-muted mb-4">Silakan baca petunjuk sebelum masuk ke bilik suara.</p>

                    <a href="<?php echo BASE_URL; ?>/about/guide" class="btn btn-success btn-main w-100 text-white">
                        <i class="bi bi-book-half me-2"></i> BACA PETUNJUK & MULAI
                    </a>
                </div>

            <?php else: ?>

                <div class="card-action">
                    <div class="status-indicator status-wait mb-4"><i class="bi bi-hourglass-split me-2"></i> Menunggu Akses</div>

                    <div class="mb-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-qr-code-scan text-dark fs-1"></i>
                        </div>
                    </div>

                    <h3 class="fw-bold text-dark mb-2">Scan Barcode</h3>
                    <p class="text-muted mb-4">
                        Silahkan hubungi admin untuk melakukan registrasi pemilih dan dapatkan <strong> Barcode </strong> agar bisa masuk.
                    </p>

                    <div class="alert alert-light border text-start d-flex align-items-start">
                        <i class="bi bi-info-circle-fill text-primary mt-1 me-3"></i>
                        <small class="text-secondary">
                            Halaman ini akan otomatis terbuka dan masuk setelah Anda melakukan scan barcode.
                        </small>
                    </div>
                </div>

            <?php endif; ?>
            <div class="mt-4 text-muted small">&copy; <?php echo date('Y'); ?> Panitia Pemilihan</div>
        </div>
    </div>

</div>

<?php require_once './views/layouts/footer.php'; ?>