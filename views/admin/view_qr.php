<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5 text-center">

            <div id="card-area" class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="bi bi-qr-code me-2"></i>KARTU LOGIN PEMILIH</h5>
                </div>
                <div class="card-body p-4">

                    <h3 class="fw-bold text-dark"><?php echo htmlspecialchars($pemilih['nama']); ?></h3>
                    <span class="badge bg-secondary mb-4"><?php echo htmlspecialchars($pemilih['unsur']); ?></span>

                    <div class="d-flex justify-content-center my-3">
                        <div id="qrcode" class="p-2 border rounded bg-white"></div>
                    </div>

                    <p class="text-danger small fw-bold">
                        *Barcode ini hanya bisa digunakan 1 kali.
                    </p>

                    <div class="d-grid gap-2 mt-4" data-html2canvas-ignore="true">
                        <button onclick="downloadCard()" class="btn btn-success">
                            <i class="bi bi-download"></i> Unduh Kartu (PDF)
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
    // 1. Generate QR Code
    new QRCode(document.getElementById("qrcode"), {
        text: "<?php echo $loginLink; ?>",
        width: 180,
        height: 180,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });

    // 2. Fungsi Download PDF
    function downloadCard() {
        const element = document.getElementById('card-area');
        const btn = document.querySelector('button[onclick="downloadCard()"]');

        // Ubah teks tombol saat proses
        const originalText = btn.innerHTML;
        btn.innerHTML = "<i class='bi bi-hourglass-split'></i> Memproses...";
        btn.disabled = true;

        const opt = {
            margin: 10,
            filename: 'Kartu_Akses_<?php echo preg_replace("/[^a-zA-Z0-9]/", "", $pemilih['nama']); ?>.pdf', // Nama file bersih
            image: {
                type: 'jpeg',
                quality: 1.0
            },
            html2canvas: {
                scale: 4, // Kualitas HD
                useCORS: true,
                scrollY: 0,
                backgroundColor: '#ffffff'
            },
            jsPDF: {
                unit: 'mm',
                format: 'a5',
                orientation: 'portrait'
            } // Ukuran kertas A5 (Kecil pas untuk kartu)
        };

        html2pdf().set(opt).from(element).save().then(function() {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
</script>

<style>
    /* Pastikan warna background tercetak akurat */
    #card-area {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        background-color: white !important;
    }

    /* Paksa header biru tetap biru */
    .bg-primary {
        background-color: linear-gradient(to right, #198754, #42d392) !important;
        color: white !important;
    }
</style>