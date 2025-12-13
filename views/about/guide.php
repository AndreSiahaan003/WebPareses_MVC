<style>
    /* --- CSS RESPONSIF (FLUID TYPOGRAPHY) --- */

    /* Judul Halaman Utama */
    .guide-title {
        font-weight: 800;
        color: #212529;
        /* Min 24px, Ideal 5vw, Max 40px */
        font-size: clamp(1.5rem, 5vw, 2.5rem);
        margin-bottom: 10px;
    }

    /* Deskripsi Sub-judul */
    .guide-subtitle {
        color: #6c757d;
        /* Min 14px, Ideal 2vw, Max 18px */
        font-size: clamp(0.85rem, 2vw, 1.1rem);
        max-width: 700px;
        margin: 0 auto;
    }

    /* Kartu Langkah */
    .step-card {
        border: none;
        background: #fff;
        border-radius: 16px;
        /* Padding mengecil di HP (20px), membesar di Laptop (30px) */
        padding: clamp(20px, 3vw, 30px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        height: 100%;
        position: relative;
        transition: transform 0.3s;
        text-align: center;
    }

    .step-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    /* Lingkaran Angka */
    .step-number {
        /* Diameter mengecil/membesar otomatis */
        width: clamp(45px, 6vw, 60px);
        height: clamp(45px, 6vw, 60px);

        margin: 0 auto 20px;
        background: #e9ecef;
        color: #198754;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;

        /* Font angka responsif */
        font-size: clamp(1rem, 2vw, 1.4rem);
        border: 2px solid #198754;
    }

    /* Ikon Langkah */
    .step-icon {
        /* Ikon mengecil di HP (2rem), membesar di Laptop (3rem) */
        font-size: clamp(2rem, 5vw, 3rem);
        color: #198754;
        margin-bottom: 15px;
        display: block;
    }

    /* Judul Langkah (h5) */
    .step-title {
        font-weight: 700;
        font-size: clamp(1rem, 1.5vw, 1.25rem);
        margin-bottom: 10px;
    }

    /* Teks Penjelasan Langkah */
    .step-desc {
        color: #6c757d;
        font-size: clamp(0.8rem, 1.2vw, 0.95rem);
        line-height: 1.5;
        margin-bottom: 0;
    }

    /* Tombol Lanjut Besar & Fluid */
    .btn-start-vote {
        /* Padding responsif: AtasBawah(12-16px), KiriKanan(30-50px) */
        padding: clamp(12px, 1.5vw, 16px) clamp(30px, 4vw, 50px);

        /* Font tombol responsif */
        font-size: clamp(0.9rem, 2vw, 1.2rem);

        font-weight: bold;
        border-radius: 50px;
        letter-spacing: 1px;
        box-shadow: 0 10px 25px rgba(25, 135, 84, 0.3);
        transition: all 0.3s;
        white-space: nowrap;
        /* Agar teks tidak turun baris */
    }

    .btn-start-vote:hover {
        transform: scale(1.03);
        box-shadow: 0 15px 35px rgba(25, 135, 84, 0.4);
    }
</style>

<div class="container my-5">

    <div class="text-center mb-5">
        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-3">Panduan Pemilihan</span>
        <h2 class="guide-title">Tata Cara Memberikan Suara</h2>
        <p class="guide-subtitle">
            Ikuti 4 langkah mudah berikut untuk menggunakan hak suara Anda dengan benar dan sah dalam sistem E-Voting.
        </p>
    </div>

    <div class="row g-3 g-md-4 mb-5">
        <div class="col-6 col-md-3">
            <div class="step-card">
                <div class="step-number">1</div>
                <i class="bi bi-qr-code-scan step-icon"></i>
                <h5 class="step-title">Scan Masuk</h5>
                <p class="step-desc">Anda sudah berhasil melewati tahap ini dengan memindai barcode.</p>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="step-card">
                <div class="step-number">2</div>
                <i class="bi bi-check-square-fill step-icon"></i>
                <h5 class="step-title">Pilih Calon</h5>
                <p class="step-desc">
                    Pilih <strong>1-16 Pareses</strong>,<br>
                    Tepat <strong>15 Majelis</strong>,<br>
                    Tepat <strong>3 BPK</strong>.
                </p>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="step-card">
                <div class="step-number">3</div>
                <i class="bi bi-send-fill step-icon"></i>
                <h5 class="step-title">Konfirmasi</h5>
                <p class="step-desc">Tekan tombol "Kirim" dan periksa kembali pilihan Anda di halaman konfirmasi.</p>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="step-card">
                <div class="step-number">4</div>
                <i class="bi bi-file-earmark-check-fill step-icon"></i>
                <h5 class="step-title">Selesai</h5>
                <p class="step-desc">Unduh bukti pemilihan digital Anda, lalu Anda akan otomatis keluar.</p>
            </div>
        </div>

    </div>

    <div class="text-center mt-5 p-4 p-md-5 bg-light rounded-4 border border-dashed">

        <?php if (isset($_SESSION['pemilih_id'])): ?>

            <h4 class="fw-bold text-dark mb-3 fs-4">Sudah Mengerti?</h4>
            <p class="text-muted mb-4 small">Jika sudah paham, silakan lanjutkan ke bilik suara digital.</p>

            <a href="<?php echo BASE_URL; ?>/vote" class="btn btn-success btn-start-vote text-white">
                <i class="bi bi-box-arrow-in-right me-2"></i> SAYA MENGERTI, LANJUT
            </a>

        <?php else: ?>

            <p class="text-muted small mb-3">Silakan login terlebih dahulu untuk memulai.</p>
            <a href="<?php echo BASE_URL; ?>" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-bold btn-sm">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Beranda
            </a>

        <?php endif; ?>

    </div>

</div>