<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div id="card-area" class="card shadow-lg border-0 rounded-4 overflow-hidden">

                <div class="card-header bg-primary text-white py-3 text-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-qr-code me-2"></i>KARTU LOGIN PEMILIH</h5>
                </div>

                <div class="card-body p-4 bg-white text-center">

                    <h2 class="fw-bold text-dark mb-2 text-uppercase" style="font-size: 1.5rem;">
                        <?php echo htmlspecialchars($pemilih['nama']); ?>
                    </h2>

                    <div class="mb-4">
                        <span class="badge bg-secondary px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.9rem;">
                            <?php echo htmlspecialchars($pemilih['unsur']); ?>
                        </span>
                    </div>

                    <div class="d-flex justify-content-center mb-4">
                        <div id="qrcode" class="p-2 border rounded bg-white shadow-sm"></div>
                    </div>

                    <div class="alert alert-light border border-dashed small text-muted mb-0 text-center">
                        <strong class="text-dark">PENTING:</strong><br>
                        Scan kode ini untuk masuk ke bilik suara.<br>
                        <span class="text-danger fw-bold mt-1 d-block">
                            *Barcode ini hanya bisa digunakan 1 kali.
                        </span>
                    </div>

                    <div class="d-grid gap-2 mt-4" data-html2canvas-ignore="true">
                        <button onclick="downloadCard()" class="btn btn-success fw-bold">
                            <i class="bi bi-download me-2"></i> Unduh Kartu (PDF)
                        </button>
                        <a href="<?php echo BASE_URL; ?>/admin/pemilih" class="btn btn-outline-secondary">
                            Kembali ke Daftar
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // --------------------------------------------------------
    // 1. Generate QR Code Otomatis
    // --------------------------------------------------------
    new QRCode(document.getElementById("qrcode"), {
        text: "<?php echo $loginLink; ?>",
        width: 160, // Ukuran QR sedikit disesuaikan agar proporsional
        height: 160,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });

    // --------------------------------------------------------
    // 2. Fungsi Download PDF (Setting Rapi A5)
    // --------------------------------------------------------
    function downloadCard() {
        const element = document.getElementById('card-area');
        const btn = document.querySelector('button[onclick="downloadCard()"]');
        const originalText = btn.innerHTML;

        // Feedback Loading
        btn.innerHTML = "<i class='bi bi-hourglass-split'></i> Sedang Memproses...";
        btn.disabled = true;

        const opt = {
            margin: [20, 15, 20, 15],
            filename: 'Kartu_Akses_<?php echo preg_replace("/[^a-zA-Z0-9]/", "", $pemilih['nama']); ?>.pdf',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 2,
                useCORS: true,
                scrollY: 0,
            },
            jsPDF: {
                unit: 'mm',
                format: 'a5',
                orientation: 'portrait'
            }
        };

        html2pdf().set(opt).from(element).save().then(function() {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
</script>

<style>
    /* -------------------------------------------------------- */
    /* CSS KHUSUS TAMPILAN & CETAK (PDF)                        */
    /* -------------------------------------------------------- */

    /* 1. Paksa Warna Background Muncul */
    .bg-primary {
        background-color: #198754 !important;
        /* Hijau Khas Pemilu */
        color: white !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .bg-secondary {
        background-color: #6c757d !important;
        color: white !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* 2. Styling Container Kartu */
    #card-area {
        background-color: white !important;
        max-width: 420px;
        margin: 0 auto;
    }

    /* 3. Penyesuaian saat diproses html2pdf */
    @media print {
        #card-area {
            max-width: 100% !important;
            width: 100% !important;
            border: 2px solid #333 !important;
            box-shadow: none !important;
        }

        /* Pastikan text alignment tetap tengah di PDF */
        .text-center {
            text-align: center !important;
        }

        .d-flex {
            display: flex !important;
            justify-content: center !important;
        }
    }
</style>