<?php
// Mengambil data session
$nama_pemilih = isset($_SESSION['pemilih_nama']) ? htmlspecialchars($_SESSION['pemilih_nama']) : 'Pemilih';
date_default_timezone_set('Asia/Jakarta');
$waktu_vote = date('d M Y, H:i') . ' WIB';
$kode_referensi = strtoupper(uniqid('VOTE-'));

// [PENTING] Pastikan variabel $pilihan dikirim dari Controller. 
// Jika belum ada (misal akses langsung), set array kosong agar tidak error.
$pilihan = isset($pilihan) ? $pilihan : [];
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<style>
    /* --- STYLE TETAP SAMA SEPERTI SEBELUMNYA --- */
    .page-wrapper {
        min-height: 90vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background-color: #f8f9fa;
    }

    .card-rounded {
        border-radius: 20px;
        overflow: hidden;
        width: 100%;
        max-width: 480px;
    }

    .card-header {
        padding: clamp(20px, 4vw, 30px) 10px !important;
    }

    .icon-check {
        font-size: clamp(3rem, 8vw, 4.5rem);
        color: #fff;
    }

    .title-thankyou {
        font-size: clamp(1.5rem, 4vw, 2rem);
        font-weight: 800;
        margin-bottom: 5px;
    }

    .subtitle-thankyou {
        font-size: clamp(0.85rem, 2vw, 1rem);
        opacity: 0.8;
    }

    .receipt-box {
        background-color: #f8f9fa !important;
        border: 2px dashed #198754 !important;
        border-radius: 12px;
        padding: clamp(15px, 3vw, 25px);
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

    .label-text {
        font-size: clamp(0.7rem, 2vw, 0.85rem);
        text-transform: uppercase;
        font-weight: 700;
        color: #6c757d;
    }

    .value-text {
        font-size: clamp(0.9rem, 2.5vw, 1.1rem);
        font-weight: 700;
        color: #212529;
        text-align: right;
    }

    .btn-action {
        padding: clamp(10px, 2vw, 14px) 20px;
        font-size: clamp(0.9rem, 2vw, 1rem);
        border-radius: 50px;
    }

    /* --- CSS KHUSUS PDF --- */
    #receipt-content {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        background-color: #ffffff !important;
        margin: 0 auto;
    }

    .bg-success {
        background-color: #198754 !important;
        color: #ffffff !important;
    }

    .text-success {
        color: #198754 !important;
    }

    /* --- TAMBAHAN CSS UNTUK TABEL PILIHAN --- */
    .vote-list-header {
        font-size: 0.85rem;
        font-weight: bold;
        text-transform: uppercase;
        margin-bottom: 10px;
        border-bottom: 2px solid #eee;
        padding-bottom: 5px;
        text-align: left;
    }

    .vote-table {
        width: 100%;
        font-size: 0.8rem;
    }

    .vote-table td {
        padding: 4px 0;
        vertical-align: top;
    }

    .badge-cat {
        font-size: 0.7rem;
        font-weight: bold;
        color: #198754;
        text-transform: uppercase;
        display: block;
        margin-bottom: 2px;
    }

    /* --- CSS KHUSUS SAAT DICETAK/PDF --- */
    /* Ini akan mengabaikan batasan lebar HP saat jadi PDF */
    @media print {
        .card-rounded {
            max-width: 100% !important;
            /* Lebarkan full */
            width: 100% !important;
            border: none !important;
            box-shadow: none !important;
        }

        .receipt-box {
            border: 2px solid #198754 !important;
            /* Pastikan border terlihat */
        }
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

            <div class="mb-4 text-start">
                <div class="vote-list-header">
                    <i class="bi bi-list-check me-1"></i> Rincian Calon Dipilih
                </div>

                <?php if (!empty($pilihan)): ?>
                    <table class="vote-table">
                        <?php $no = 1;
                        foreach ($pilihan as $p): ?>
                            <tr>
                                <td style="width: 25px; color: #888;"><?php echo $no++; ?>.</td>
                                <td>
                                    <span class="badge-cat"><?php echo htmlspecialchars($p['kategori']); ?></span>
                                    <span style="font-weight: 600; color: #333;">
                                        <?php echo htmlspecialchars($p['nama_calon']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else: ?>
                    <p class="text-center text-muted small fst-italic py-2">
                        Data rincian pilihan tidak tersedia.
                    </p>
                <?php endif; ?>

                <div style="border-top: 1px dashed #ccc; margin-top: 15px;"></div>
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
        // Ambil elemen yang mau dicetak
        const element = document.getElementById('receipt-content');

        // Ambil tombol untuk efek loading
        const btn = document.querySelector('button[onclick="downloadProof()"]');
        const originalText = btn.innerHTML;

        // Feedback Loading
        btn.innerHTML = "<i class='bi bi-hourglass-split'></i> Sedang Membuat PDF...";
        btn.disabled = true;

        // --- KONFIGURASI BARU (AGAR TIDAK TERPOTONG) ---
        const opt = {
            // 1. Margin: Atas, Kiri, Bawah, Kanan (dalam mm)
            margin: [10, 10, 10, 10],

            // 2. Nama File
            filename: 'Bukti_Pilih_<?php echo $kode_referensi; ?>.pdf',

            // 3. Kualitas Gambar
            image: {
                type: 'jpeg',
                quality: 0.98
            },

            // 4. Setting Canvas (PENTING!)
            html2canvas: {
                scale: 2, // Skala 2 sudah cukup tajam & file tidak berat
                useCORS: true, // Agar icon/gambar termuat
                scrollY: 0, // Paksa scroll ke paling atas
                // Hapus windowWidth agar dia menyesuaikan lebar konten asli
            },

            // 5. Ukuran Kertas
            jsPDF: {
                unit: 'mm',
                format: 'a4', // Ganti ke A4 agar muat banyak
                orientation: 'portrait'
            }
        };

        // Eksekusi
        html2pdf().set(opt).from(element).save().then(function() {
            // Balikkan tombol seperti semula setelah selesai
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }
</script>