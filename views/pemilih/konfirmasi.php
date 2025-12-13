<?php require_once './views/layouts/header.php'; ?>

<style>
    /* --- BACKGROUND GRADASI HALUS --- */
    body {
        background: linear-gradient(135deg, #f6f9fc 0%, #eef2f7 100%);
        min-height: 100vh;
    }

    /* --- KARTU GLASSMORPHISM FLUID --- */
    .card-confirm-premium {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 30px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        position: relative;
        z-index: 1;
    }

    /* Padding Card Dinamis: 30px di HP, 60px di Laptop */
    .card-body-responsive {
        padding: clamp(30px, 5vw, 60px) !important;
    }

    /* Hiasan Latar Belakang */
    .card-confirm-premium::before,
    .card-confirm-premium::after {
        content: '';
        position: absolute;
        z-index: -1;
        border-radius: 50%;
        filter: blur(60px);
        opacity: 0.15;
    }

    .card-confirm-premium::before {
        width: 200px;
        height: 200px;
        background: #0d6efd;
        top: -50px;
        left: -50px;
    }

    .card-confirm-premium::after {
        width: 180px;
        height: 180px;
        background: #ffc107;
        bottom: -40px;
        right: -40px;
    }

    /* --- IKON 3D FLUID --- */
    /* Ukuran lingkaran & ikon berubah sesuai layar */
    .icon-box-3d {
        width: clamp(80px, 12vw, 120px);
        /* Min 80px, Ideal 12%, Max 120px */
        height: clamp(80px, 12vw, 120px);
        margin: 0 auto clamp(15px, 3vw, 30px);

        background: linear-gradient(145deg, #ffffff, #e6e6e6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;

        font-size: clamp(3rem, 5vw, 4.5rem);
        /* Ukuran ikon di dalam */
        color: #0d6efd;
        box-shadow: 15px 15px 30px #d9d9d9, -15px -15px 30px #ffffff;
        animation: float-icon 4s ease-in-out infinite;
    }

    @keyframes float-icon {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    /* --- TYPOGRAPHY FLUID --- */
    .title-fluid {
        font-weight: 800;
        color: #212529;
        /* Font: Min 24px, Ideal 4vw, Max 36px */
        font-size: clamp(1.5rem, 4vw, 2.25rem);
        margin-bottom: clamp(10px, 2vw, 20px);
    }

    .text-fluid {
        color: #6c757d;
        /* Font: Min 14px, Ideal 2vw, Max 18px */
        font-size: clamp(0.9rem, 1.5vw, 1.15rem);
        line-height: 1.6;
        margin-bottom: clamp(20px, 4vw, 40px);
    }

    /* --- TOMBOL PREMIUM FLUID --- */
    .btn-premium {
        font-weight: 700;
        border-radius: 50px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
        z-index: 1;

        /* Ukuran Tombol Responsif */
        font-size: clamp(0.85rem, 1.5vw, 1rem);
        padding: clamp(12px, 2vw, 16px) 20px;
        min-height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-premium-yes {
        background: linear-gradient(45deg, #0d6efd, #0a58ca);
        border: none;
        color: white;
        box-shadow: 0 8px 20px rgba(13, 110, 253, 0.3);
    }

    .btn-premium-yes:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(13, 110, 253, 0.4);
    }

    .btn-premium-no {
        background: transparent;
        border: 2px solid #dee2e6;
        color: #6c757d;
    }

    .btn-premium-no:hover {
        background: #fff;
        border-color: #adb5bd;
        color: #343a40;
        transform: translateY(-3px);
    }
</style>

<div class="page-wrapper d-flex align-items-center" style="min-height: 85vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-11 col-md-10 col-lg-7">

                <div class="card card-confirm-premium">
                    <div class="card-body card-body-responsive text-center">

                        <div class="icon-box-3d">
                            <i class="bi bi-question"></i>
                        </div>

                        <h1 class="title-fluid">Konfirmasi Pilihan</h1>

                        <p class="text-fluid">
                            Apakah Anda yakin dengan pilihan Anda?<br>
                            Data akan dikirim ke server dan <span class="fw-bold text-danger">tidak dapat diubah kembali.</span>
                        </p>

                        <div class="row g-3 justify-content-center">

                            <div class="col-12 col-md-6 order-md-2">
                                <form action="<?php echo BASE_URL; ?>/vote/save" method="POST" class="w-100">
                                    <button type="submit" class="btn btn-premium btn-premium-yes w-100">
                                        YA, SAYA YAKIN <i class="bi bi-check-circle-fill ms-2"></i>
                                    </button>
                                </form>
                            </div>

                            <div class="col-12 col-md-6 order-md-1">
                                <a href="<?php echo BASE_URL; ?>/vote" class="btn btn-premium btn-premium-no w-100">
                                    <i class="bi bi-arrow-left me-2"></i> CEK KEMBALI
                                </a>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once './views/layouts/footer.php'; ?>