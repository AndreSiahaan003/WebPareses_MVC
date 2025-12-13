<?php
// Menggunakan __DIR__ untuk memastikan jalur file layout benar
require_once __DIR__ . '/../layouts/header.php';
?>

<style>
    /* --- STYLE HALAMAN ERROR --- */

    .error-page-wrapper {
        min-height: 80vh;
        /* Tinggi layar agar vertikal tengah */
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #ffffff 0%, #fff5f5 100%);
    }

    .card-denied {
        border: none;
        border-radius: 24px;
        background: #ffffff;
        box-shadow: 0 20px 50px rgba(220, 53, 69, 0.15);
        position: relative;
        overflow: hidden;
        max-width: 480px;
        width: 100%;
        padding: 3rem 2rem;
    }

    /* Garis Merah di Atas */
    .card-denied::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 8px;
        background: #dc3545;
    }

    /* Animasi Ikon */
    .icon-circle {
        width: 100px;
        height: 100px;
        background-color: #fde8e8;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px auto;
        animation: pulse-red 2s infinite;
    }

    .icon-circle i {
        font-size: 3.5rem;
        color: #dc3545;
    }

    @keyframes pulse-red {
        0% {
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4);
        }

        70% {
            box-shadow: 0 0 0 15px rgba(220, 53, 69, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
        }
    }

    /* Style Tombol Kembali */
    .btn-back {
        border-radius: 50px;
        padding: 12px 25px;
        font-weight: 600;
        transition: all 0.3s;
        border: 2px solid #6c757d;
        color: #6c757d;
        background: transparent;
    }

    .btn-back:hover {
        background: #6c757d;
        color: #fff;
        transform: translateX(-5px);
        /* Efek geser sedikit ke kiri */
    }
</style>

<div class="error-page-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 d-flex justify-content-center">

                <div class="card card-denied text-center">

                    <div class="icon-circle">
                        <i class="bi bi-x-lg"></i>
                    </div>

                    <h2 class="fw-bold text-danger mb-2">Akses Ditolak</h2>
                    <p class="text-uppercase text-muted small fw-bold mb-4" style="letter-spacing: 1px;">Token Invalid / Expired</p>

                    <p class="text-secondary mb-4 fs-5 px-2">
                        Maaf, barcode ini <strong>sudah digunakan</strong> untuk memilih atau data tidak ditemukan dalam sistem.
                    </p>

                    <div class="bg-light p-3 rounded-3 border mb-4 text-start">
                        <div class="d-flex">
                            <i class="bi bi-info-circle-fill text-warning fs-4 me-3 mt-1"></i>
                            <div class="small text-muted" style="line-height: 1.4;">
                                Demi keamanan, setiap kode QR hanya berlaku untuk <strong>satu kali</strong> proses pemilihan. Hubungi panitia jika ini adalah kesalahan.
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <a href="<?php echo BASE_URL; ?>" class="btn btn-back">
                            <i class="bi bi-arrow-left me-2"></i> Kembali ke Halaman Utama
                        </a>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>