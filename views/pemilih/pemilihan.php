<?php require_once './views/layouts/header.php'; ?>

<style>
    :root {
        --bg-soft: #f4f7f6;
        --color-pareses: #0d6efd;
        --color-majelis: #ffc107;
        --color-bpk: #198754;
    }

    body {
        background-color: var(--bg-soft);
        padding-bottom: 160px;
    }

    /* --- HEADER SAMBUTAN --- */
    .welcome-banner {
        background: white;
        padding: 20px 15px;
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
        text-align: center;
    }

    /* --- SECTION CONTAINER --- */
    .section-container {
        margin-bottom: 20px;
        background: white;
        border-radius: 16px;
        overflow: hidden;
        /* Agar konten tidak bocor saat ditutup */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #eee;
    }

    /* --- HEADER KATEGORI (TOMBOL KLIK) --- */
    .accordion-header {
        padding: 20px;
        cursor: pointer;
        /* Menandakan bisa diklik */
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        transition: background 0.2s;
    }

    .accordion-header:hover {
        background-color: #f8f9fa;
    }

    /* Warna Border Kiri Header */
    .section-pareses .accordion-header {
        border-left: 6px solid var(--color-pareses);
    }

    .section-majelis .accordion-header {
        border-left: 6px solid var(--color-majelis);
    }

    .section-bpk .accordion-header {
        border-left: 6px solid var(--color-bpk);
    }

    /* Judul & Subjudul */
    .cat-title {
        font-weight: 800;
        font-size: 1.1rem;
        margin-bottom: 0;
        text-transform: uppercase;
    }

    .cat-subtitle {
        font-size: 0.75rem;
        color: #888;
        display: block;
    }

    /* Badge Counter */
    .counter-badge {
        padding: 5px 12px;
        border-radius: 50px;
        font-weight: bold;
        font-size: 0.85rem;
        min-width: 80px;
        text-align: center;
    }

    .badge-pareses {
        background: #e7f1ff;
        color: var(--color-pareses);
    }

    .badge-majelis {
        background: #fff9db;
        color: #bfa006;
    }

    .badge-bpk {
        background: #d1e7dd;
        color: var(--color-bpk);
    }

    /* Ikon Panah */
    .toggle-icon {
        transition: transform 0.3s ease;
        font-size: 1.2rem;
        color: #aaa;
        margin-left: 10px;
    }

    /* --- KONTEN CALON (ISI) --- */
    .accordion-body {
        display: none;
        /* DEFAULT SEMBUNYI */
        padding: 20px;
        border-top: 1px solid #f0f0f0;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Class untuk membuka */
    .accordion-open .accordion-body {
        display: block;
    }

    .accordion-open .toggle-icon {
        transform: rotate(180deg);
    }

    /* Putar panah */

    /* --- KARTU CALON --- */
    .card-candidate {
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        background: #fff;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.1s;
        min-height: 80px;
    }

    .card-content {
        padding: 15px;
        text-align: left;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .candidate-name {
        font-weight: 700;
        font-size: 1rem;
        color: #333;
        margin-bottom: 4px;
        line-height: 1.3;
    }

    .candidate-info {
        font-size: 0.85rem;
        color: #777;
    }

    .candidate-checkbox {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* Efek Checked */
    .section-pareses .candidate-checkbox:checked+.card-candidate {
        background-color: #e7f1ff;
        border-color: var(--color-pareses);
        box-shadow: 0 0 0 2px var(--color-pareses) inset;
    }

    .section-majelis .candidate-checkbox:checked+.card-candidate {
        background-color: #fff9db;
        border-color: var(--color-majelis);
        box-shadow: 0 0 0 2px var(--color-majelis) inset;
    }

    .section-bpk .candidate-checkbox:checked+.card-candidate {
        background-color: #d1e7dd;
        border-color: var(--color-bpk);
        box-shadow: 0 0 0 2px var(--color-bpk) inset;
    }

    .check-icon {
        display: none;
        position: absolute;
        top: 50%;
        right: 20px;
        transform: translateY(-50%);
        font-size: 1.8rem;
        z-index: 10;
    }

    .candidate-checkbox:checked+.card-candidate .check-icon {
        display: block;
    }

    /* Footer */
    .floating-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        padding: 15px;
        box-shadow: 0 -5px 25px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        border-top: 1px solid #eee;
    }

    .footer-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
    }

    .hidden-counter {
        display: none;
    }
</style>

<div class="welcome-banner">
    <h6 class="fw-bold text-dark mb-1">Halo, <?php echo htmlspecialchars($_SESSION['pemilih_nama']); ?></h6>
    <small class="text-muted">Klik kategori di bawah untuk melihat & memilih calon.</small>
</div>

<div class="container px-3">

    <div id="validationAlert" class="alert alert-danger shadow-sm border-0 rounded-3 mb-4 d-none" role="alert">
        <div class="d-flex">
            <i class="bi bi-exclamation-circle-fill fs-3 me-3 text-danger"></i>
            <div>
                <h6 class="alert-heading fw-bold mb-1">Pilihan Belum Lengkap</h6>
                <ul class="mb-0 ps-3 small" id="validationList"></ul>
            </div>
        </div>
    </div>

    <form id="formPemilihan" action="<?php echo BASE_URL; ?>/vote/submit" method="POST">

        <span id="count-pareses" class="hidden-counter">0</span>
        <span id="count-majelis" class="hidden-counter">0</span>
        <span id="count-bpk" class="hidden-counter">0</span>

        <div class="section-container section-pareses" id="acc-pareses">
            <div class="accordion-header" onclick="toggleAccordion('acc-pareses')">
                <div>
                    <h5 class="cat-title text-primary">PARESES</h5>
                    <span class="cat-subtitle">Min 1 - Max 16 Calon</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="counter-badge badge-pareses"><span id="badge-pareses">0</span>/16</span>
                    <i class="bi bi-chevron-down toggle-icon"></i>
                </div>
            </div>

            <div class="accordion-body">
                <div class="row g-3">
                    <?php foreach ($pareses as $calon): ?>
                        <?php $isChecked = in_array($calon['id'], $selected['pareses']) ? 'checked' : ''; ?>
                        <div class="col-12 col-md-4 col-lg-3">
                            <label class="w-100 h-100 position-relative">
                                <input type="checkbox" name="pareses[]" value="<?php echo $calon['id']; ?>" class="candidate-checkbox" <?php echo $isChecked; ?>>
                                <div class="card-candidate shadow-sm">
                                    <i class="bi bi-check-circle-fill text-primary check-icon"></i>
                                    <div class="card-content">
                                        <div class="candidate-name"><?php echo htmlspecialchars($calon['nama']); ?></div>
                                        <div class="candidate-info"><i class="bi bi-geo-alt me-1"></i> <?php echo htmlspecialchars($calon['daerah']); ?></div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>


        <div class="section-container section-majelis" id="acc-majelis">
            <div class="accordion-header" onclick="toggleAccordion('acc-majelis')">
                <div>
                    <h5 class="cat-title text-warning" style="color:#bfa006!important">MAJELIS PUSAT</h5>
                    <span class="cat-subtitle">Wajib Tepat 15 Calon</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="counter-badge badge-majelis"><span id="badge-majelis">0</span>/15</span>
                    <i class="bi bi-chevron-down toggle-icon"></i>
                </div>
            </div>

            <div class="accordion-body">
                <div class="row g-3">
                    <?php foreach ($majelis as $calon): ?>
                        <?php $isChecked = in_array($calon['id'], $selected['majelis']) ? 'checked' : ''; ?>
                        <div class="col-12 col-md-4 col-lg-3">
                            <label class="w-100 h-100 position-relative">
                                <input type="checkbox" name="majelis[]" value="<?php echo $calon['id']; ?>" class="candidate-checkbox" <?php echo $isChecked; ?>>
                                <div class="card-candidate shadow-sm">
                                    <i class="bi bi-check-circle-fill text-warning check-icon"></i>
                                    <div class="card-content">
                                        <div class="candidate-name"><?php echo htmlspecialchars($calon['nama']); ?></div>
                                        <div class="candidate-info"><?php echo htmlspecialchars($calon['keterangan']); ?></div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>


        <div class="section-container section-bpk" id="acc-bpk">
            <div class="accordion-header" onclick="toggleAccordion('acc-bpk')">
                <div>
                    <h5 class="cat-title text-success">BPK</h5>
                    <span class="cat-subtitle">Wajib Tepat 3 Calon</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="counter-badge badge-bpk"><span id="badge-bpk">0</span>/3</span>
                    <i class="bi bi-chevron-down toggle-icon"></i>
                </div>
            </div>

            <div class="accordion-body">
                <div class="row g-3">
                    <?php foreach ($bpk as $calon): ?>
                        <?php $isChecked = in_array($calon['id'], $selected['bpk']) ? 'checked' : ''; ?>
                        <div class="col-12 col-md-4 col-lg-3">
                            <label class="w-100 h-100 position-relative">
                                <input type="checkbox" name="bpk[]" value="<?php echo $calon['id']; ?>" class="candidate-checkbox" <?php echo $isChecked; ?>>
                                <div class="card-candidate shadow-sm">
                                    <i class="bi bi-check-circle-fill text-success check-icon"></i>
                                    <div class="card-content">
                                        <div class="candidate-name"><?php echo htmlspecialchars($calon['nama']); ?></div>
                                        <div class="candidate-info"><?php echo htmlspecialchars($calon['keterangan']); ?></div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center gap-3 mb-3">
            <a href="<?php echo BASE_URL; ?>/about/guide" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-bold">
                <i class="bi bi-arrow-left me-2"></i> Tata Cara Pemilihan
            </a>
        </div>

        <div class="floating-footer">
            <div class="footer-content">

                <div class="text-muted small d-none d-sm-block">
                    Pastikan pilihan sesuai
                </div>

                <button type="submit" class="btn btn-dark rounded-pill px-4 px-md-5 py-2 py-md-3 fw-bold shadow-lg btn-responsive">
                    KIRIM <span class="d-none d-sm-inline">SUARA</span> <i class="bi bi-send-fill ms-1"></i>
                </button>
            </div>
        </div>

    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // --- 1. FUNGSI ACCORDION ---
        window.toggleAccordion = function(id) {
            const section = document.getElementById(id);
            document.querySelectorAll('.section-container').forEach(el => {
                if (el.id !== id) el.classList.remove('accordion-open');
            });
            section.classList.toggle('accordion-open');
        }

        // --- 2. FUNGSI HITUNG REAL-TIME ---
        function updateCounters() {
            // A. Hitung PARESES
            const pCount = document.querySelectorAll('input[name="pareses[]"]:checked').length;
            const pBadge = document.getElementById('badge-pareses');
            if (pBadge) {
                pBadge.innerText = pCount;
                let parentClass = "counter-badge ";
                if (pCount > 16) parentClass += "bg-danger text-white";
                else if (pCount > 0) parentClass += "bg-primary text-white";
                else parentClass += "badge-pareses";
                pBadge.parentElement.className = parentClass;
            }

            // B. Hitung MAJELIS
            const mCount = document.querySelectorAll('input[name="majelis[]"]:checked').length;
            const mBadge = document.getElementById('badge-majelis');
            if (mBadge) {
                mBadge.innerText = mCount;
                let parentClass = "counter-badge ";
                if (mCount === 15) parentClass += "bg-success text-white";
                else if (mCount > 15) parentClass += "bg-danger text-white";
                else parentClass += "badge-majelis";
                mBadge.parentElement.className = parentClass;
            }

            // C. Hitung BPK
            const bCount = document.querySelectorAll('input[name="bpk[]"]:checked').length;
            const bBadge = document.getElementById('badge-bpk');
            if (bBadge) {
                bBadge.innerText = bCount;
                let parentClass = "counter-badge ";
                if (bCount === 3) parentClass += "bg-success text-white";
                else if (bCount > 3) parentClass += "bg-danger text-white";
                else parentClass += "badge-bpk";
                bBadge.parentElement.className = parentClass;
            }

            // Update Footer (Hanya muncul di Laptop karena di HP disembunyikan CSS di bawah)
            const ftP = document.getElementById('ft-count-pareses');
            if (ftP) ftP.innerText = pCount;
        }

        const allCheckboxes = document.querySelectorAll('.candidate-checkbox');
        allCheckboxes.forEach(cb => {
            cb.addEventListener('change', updateCounters);
        });

        updateCounters();
    });
</script>

<style>
    /* Style Footer Melayang */
    .floating-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(255, 255, 255, 0.9);
        /* Agak transparan */
        backdrop-filter: blur(10px);
        /* Efek kaca */
        padding: 10px 15px;
        /* Padding lebih kecil */
        box-shadow: 0 -5px 25px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        border-top: 1px solid #eee;
    }

    .footer-content {
        display: flex;
        justify-content: flex-end;
        /* Tombol rata kanan */
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Responsive Button Style */
    .btn-responsive {
        transition: all 0.2s;
    }

    /* KHUSUS TAMPILAN HP (Layar < 576px) */
    @media (max-width: 576px) {
        .floating-footer {
            padding: 8px 12px;
            /* Footer lebih tipis di HP */
        }

        .btn-responsive {
            font-size: 0.85rem;
            /* Font lebih kecil */
            padding: 8px 25px !important;
            /* Tombol lebih pendek */
            width: 100%;
            /* Tombol lebar penuh agar mudah ditekan jempol */
        }

        .footer-content {
            justify-content: center;
            /* Di HP tombol di tengah */
        }
    }

    /* Style Hover Kartu */
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        border: 1px solid var(--bs-primary) !important;
    }
</style>

<?php require_once './views/layouts/footer.php'; ?>