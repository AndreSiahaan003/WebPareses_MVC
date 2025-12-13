<?php
// Mengambil data session
$nama_pemilih = isset($_SESSION['pemilih_nama']) ? htmlspecialchars($_SESSION['pemilih_nama']) : 'Pemilih';
date_default_timezone_set('Asia/Jakarta');
$waktu_vote = date('d M Y, H:i') . ' WIB';
$kode_referensi = strtoupper(uniqid('VOTE-'));
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<style>
    /* --- RESET & FLUID TYPOGRAPHY --- */
    .page-wrapper {
        min-height: 90vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background-color: #f8f9fa;
    }

    /* Style Card Utama */
    .card-rounded {
        border-radius: 20px;
        overflow: hidden;
        /* Lebar kartu maksimal di layar besar, tapi full di HP */
        width: 100%;
        max-width: 480px;
    }

    /* Header Hijau */
    .card-header {
        padding: clamp(20px, 4vw, 30px) 10px !important;
    }

    .icon-check {
        /* Ikon mengecil di HP (3rem), membesar di Laptop (4.5rem) */
        font-size: clamp(3rem, 8vw, 4.5rem);
        color: #fff;
    }

    .title-thankyou {
        font-size: clamp(1.5rem, 4vw, 2rem);
        /* Judul responsif */
        font-weight: 800;
        margin-bottom: 5px;
    }

    .subtitle-thankyou {
        font-size: clamp(0.85rem, 2vw, 1rem);
        opacity: 0.8;
    }

    /* Style Struk (Receipt Box) */
    .receipt-box {
        background-color: #f8f9fa !important;
        border: 2px dashed #198754 !important;
        border-radius: 12px;
        padding: clamp(15px, 3vw, 25px);
        /* Padding dalam struk responsif */
        margin-bottom: 25px;
        -webkit-print-color-adjust: exact !important;
    }

    .receipt-item {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 10px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .receipt-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    /* Font Label & Value di Struk */
    .label-text {
        font-size: clamp(0.7rem, 2vw, 0.85rem);
        text-transform: uppercase;
        font-weight: 700;
        color: #6c757d;
    }

    .value-text {
        font-size: clamp(0.9rem, 2.5vw, 1.1rem);
        /* Nilai lebih besar */
        font-weight: 700;
        color: #212529;
        text-align: right;
    }

    /* Tombol Responsif */
    .btn-action {
        padding: clamp(10px, 2vw, 14px) 20px;
        font-size: clamp(0.9rem, 2vw, 1rem);
        border-radius: 50px;
    }

    /* --- CSS KHUSUS PDF (Agar tidak buram & warna tajam) --- */
    #receipt-content {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
        background-color: #ffffff !important;
        margin: 0 auto;
    }

    /* Paksa warna hijau */
    .bg-success {
        background-color: #198754 !important;
        color: #ffffff !important;
    }

    .text-success {
        color: #198754 !important;
    }
</style>


<div class="page-wrapper">

    <div id="receipt-content" class="card shadow-lg border-0 card-rounded fade-in-up">

        <div class="card-header bg-success text-white text-center">
            <div class="mb-2"><i class="bi bi-check-circle-fill icon-check"></i></div>
            <h3 class="title-thankyou">Suara Diterima!</h3>
            <p class="subtitle-thankyou">Terima kasih atas partisipasi Anda</p>
        </div>

        <div class="card-body p-4">

            <p class="text-center text-muted mb-4" style="font-size: clamp(0.9rem, 2vw, 1rem);">
                Berikut adalah bukti digital partisipasi pemilihan Anda.<br>
                <small class="text-secondary">Simpan bukti ini sebagai konfirmasi.</small>
            </p>

            <div class="receipt-box">
                <div class="receipt-item">
                    <span class="label-text">Nama Pemilih</span>
                    <span class="value-text"><?php echo $nama_pemilih; ?></span>
                </div>
                <div class="receipt-item">
                    <span class="label-text">Waktu</span>
                    <span class="value-text"><?php echo $waktu_vote; ?></span>
                </div>
                <div class="receipt-item">
                    <span class="label-text">Kode Ref</span>
                    <span class="value-text text-success" style="letter-spacing: 1px;"><?php echo $kode_referensi; ?></span>
                </div>
            </div>

            <div class="d-grid gap-3" data-html2canvas-ignore="true">

                <a href="<?php echo BASE_URL; ?>/vote/logout" class="btn btn-success btn-action fw-bold shadow-sm">
                    <i class="bi bi-box-arrow-right me-2"></i> Selesai & Keluar
                </a>

                <button onclick="downloadProof()" class="btn btn-outline-secondary btn-action border">
                    <i class="bi bi-download me-2"></i> Unduh Bukti (PDF)
                </button>

            </div>

        </div>
    </div>

</div>

<div class="text-center pb-4 text-muted small">
    &copy; <?php echo date('Y'); ?> Panitia Pemilihan. Hak Cipta Dilindungi.
</div>

<script>
    function downloadProof() {
        const element = document.getElementById('receipt-content');
        const btn = document.querySelector('button[onclick="downloadProof()"]');
        const originalText = btn.innerHTML;

        // Feedback Loading
        btn.innerHTML = "<i class='bi bi-hourglass-split'></i> Memproses HD...";
        btn.disabled = true;

        const opt = {
            margin: [0, 0], // Margin 0 agar full card
            filename: 'Bukti_Pilih_<?php echo $kode_referensi; ?>.pdf',
            image: {
                type: 'jpeg',
                quality: 1.0
            }, // Kualitas Max

            html2canvas: {
                scale: 4, // Resolusi Tinggi (Agar tidak pecah)
                useCORS: true,
                scrollY: 0,
                backgroundColor: '#ffffff',
                // Trik agar PDF tetap rapi walau dibuka di HP
                windowWidth: 500
            },

            jsPDF: {
                unit: 'mm',
                format: 'a5', // Kertas A5 pas untuk struk
                orientation: 'portrait'
            }
        };

        html2pdf().set(opt).from(element).save().then(function() {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
</script>