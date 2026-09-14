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
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #eee;
    }

    /* --- HEADER KATEGORI (TOMBOL KLIK) --- */
    .accordion-header {
        padding: 20px;
        cursor: pointer;
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

    .accordion-open .accordion-body {
        display: block;
    }

    .accordion-open .toggle-icon {
        transform: rotate(180deg);
    }

    /* --- KARTU CALON --- */
    .card-candidate {
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        background: #fff;
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
        cursor: pointer;
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

    .candidate-input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* Efek Checked */
    .section-pareses .candidate-input:checked+.card-candidate {
        background-color: #e7f1ff;
        border-color: var(--color-pareses);
        box-shadow: 0 0 0 2px var(--color-pareses) inset;
    }

    .section-majelis .candidate-input:checked+.card-candidate {
        background-color: #fff9db;
        border-color: var(--color-majelis);
        box-shadow: 0 0 0 2px var(--color-majelis) inset;
    }

    .section-bpk .candidate-input:checked+.card-candidate {
        background-color: #d1e7dd;
        border-color: var(--color-bpk);
        box-shadow: 0 0 0 2px var(--color-bpk) inset;
    }

    .check-icon {
        display: none;
        position: absolute;
        top: 25px;
        /* Disesuaikan agar ikon tidak tertutup dropdown */
        right: 20px;
        font-size: 1.5rem;
        z-index: 10;
    }

    .candidate-input:checked+.card-candidate .check-icon {
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

    .welcome-banner {
        background: white;
        padding: 20px 20px;
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
        text-align: center;
    }

    .welcome-subtitle {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 15px;
        display: block;
    }

    .rules-box {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 12px 15px;
        text-align: left;
        display: inline-block;
        width: 100%;
        max-width: 600px;
        border: 1px dashed #dee2e6;
    }

    .rules-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #495057;
        margin-bottom: 5px;
        display: block;
    }

    .rules-list {
        margin: 0;
        padding-left: 20px;
        font-size: 0.85rem;
        color: #6c757d;
    }

    .rules-list li {
        margin-bottom: 4px;
    }

    .highlight-rule {
        color: #dc3545;
        font-weight: 600;
    }
</style>

<div class="welcome-banner">
    <h5 class="fw-bold text-dark mb-2">Halo, <?php echo htmlspecialchars($_SESSION['pemilih_nama']); ?></h5>
    <span class="welcome-subtitle">
        Silakan klik kategori di bawah untuk membuka dan memilih calon.
    </span>
    <div class="rules-box">
        <span class="rules-title"><i class="bi bi-info-circle me-1"></i> Ketentuan Pemilihan:</span>
        <ul class="rules-list">
            <li><strong>Pareses:</strong> Wajib pilih tepat <span class="highlight-rule">1</span> calon.</li>
            <li><strong>Majelis Pusat:</strong> Wajib pilih tepat <span class="highlight-rule">1</span> calon.</li>
            <li><strong>BPK:</strong> Wajib pilih tepat <span class="highlight-rule">1</span> calon.</li>
        </ul>
    </div>
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

        <!-- PARESES -->
        <div class="section-container section-pareses" id="acc-pareses">
            <div class="accordion-header" onclick="toggleAccordion('acc-pareses')">
                <div>
                    <h5 class="cat-title text-primary">PARESES</h5>
                    <span class="cat-subtitle">Wajib Pilih 1 Calon</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="counter-badge badge-pareses"><span id="badge-pareses">0</span>/1</span>
                    <i class="bi bi-chevron-down toggle-icon"></i>
                </div>
            </div>

            <div class="accordion-body">
                <div class="row g-3">
                    <?php foreach ($pareses as $calon): ?>
                        <?php $isChecked = (isset($selected['pareses']) && $selected['pareses'] == $calon['id']) ? 'checked' : ''; ?>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="w-100 h-100 position-relative">

                                <input type="radio" name="pareses" id="pareses_<?php echo $calon['id']; ?>" value="<?php echo $calon['id']; ?>" class="candidate-input" <?php echo $isChecked; ?>>

                                <div class="card-candidate shadow-sm d-flex flex-column h-100">
                                    <i class="bi bi-check-circle-fill text-primary check-icon"></i>

                                    <label for="pareses_<?php echo $calon['id']; ?>" class="card-content flex-grow-1" style="margin-bottom:0;">
                                        <div class="candidate-name"><?php echo htmlspecialchars($calon['nama']); ?></div>
                                        <div class="candidate-info"><i class="bi bi-geo-alt me-1"></i> <?php echo htmlspecialchars($calon['daerah']); ?></div>
                                    </label>

                                    <!-- DROPDOWN PROFIL -->
                                    <div class="px-3 pb-3">
                                        <button class="btn btn-sm btn-light w-100 border text-muted" type="button" data-bs-toggle="collapse" data-bs-target="#profil_pareses_<?php echo $calon['id']; ?>" aria-expanded="false" style="font-size: 0.8rem;">
                                            <i class="bi bi-person-lines-fill me-1"></i> Lihat Profil
                                        </button>
                                        <div class="collapse mt-2" id="profil_pareses_<?php echo $calon['id']; ?>">
                                            <div class="bg-light border rounded p-2 text-start" style="font-size: 0.8rem; color: #555;">
                                                <strong class="text-dark">Biodata:</strong><br>
                                                <?php echo htmlspecialchars($calon['biodata'] ?? 'Data belum tersedia'); ?>
                                                <hr class="my-1 border-secondary opacity-25">
                                                <strong class="text-dark">Lama Jabatan:</strong><br>
                                                <?php echo htmlspecialchars($calon['lama_jabatan'] ?? '-'); ?>
                                                <hr class="my-1 border-secondary opacity-25">
                                                <strong class="text-dark">Riwayat Kerja:</strong><br>
                                                <?php echo nl2br(htmlspecialchars($calon['riwayat_kerja'] ?? '-')); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- MAJELIS -->
        <div class="section-container section-majelis" id="acc-majelis">
            <div class="accordion-header" onclick="toggleAccordion('acc-majelis')">
                <div>
                    <h5 class="cat-title text-warning" style="color:#bfa006!important">MAJELIS PUSAT</h5>
                    <span class="cat-subtitle">Wajib Pilih 1 Calon</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="counter-badge badge-majelis"><span id="badge-majelis">0</span>/1</span>
                    <i class="bi bi-chevron-down toggle-icon"></i>
                </div>
            </div>

            <div class="accordion-body">
                <div class="row g-3">
                    <?php foreach ($majelis as $calon): ?>
                        <?php $isChecked = (isset($selected['majelis']) && $selected['majelis'] == $calon['id']) ? 'checked' : ''; ?>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="w-100 h-100 position-relative">

                                <input type="radio" name="majelis" id="majelis_<?php echo $calon['id']; ?>" value="<?php echo $calon['id']; ?>" class="candidate-input" <?php echo $isChecked; ?>>

                                <div class="card-candidate shadow-sm d-flex flex-column h-100">
                                    <i class="bi bi-check-circle-fill text-warning check-icon"></i>

                                    <label for="majelis_<?php echo $calon['id']; ?>" class="card-content flex-grow-1" style="margin-bottom:0;">
                                        <div class="candidate-name"><?php echo htmlspecialchars($calon['nama']); ?></div>
                                        <div class="candidate-info"><?php echo htmlspecialchars($calon['keterangan']); ?></div>
                                    </label>

                                    <!-- DROPDOWN PROFIL -->
                                    <div class="px-3 pb-3">
                                        <button class="btn btn-sm btn-light w-100 border text-muted" type="button" data-bs-toggle="collapse" data-bs-target="#profil_majelis_<?php echo $calon['id']; ?>" aria-expanded="false" style="font-size: 0.8rem;">
                                            <i class="bi bi-person-lines-fill me-1"></i> Lihat Profil
                                        </button>
                                        <div class="collapse mt-2" id="profil_majelis_<?php echo $calon['id']; ?>">
                                            <div class="bg-light border rounded p-2 text-start" style="font-size: 0.8rem; color: #555;">
                                                <strong class="text-dark">Biodata:</strong><br>
                                                <?php echo htmlspecialchars($calon['biodata'] ?? 'Data belum tersedia'); ?>
                                                <hr class="my-1 border-secondary opacity-25">
                                                <strong class="text-dark">Lama Jabatan:</strong><br>
                                                <?php echo htmlspecialchars($calon['lama_jabatan'] ?? '-'); ?>
                                                <hr class="my-1 border-secondary opacity-25">
                                                <strong class="text-dark">Riwayat Kerja:</strong><br>
                                                <?php echo nl2br(htmlspecialchars($calon['riwayat_kerja'] ?? '-')); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- BPK -->
        <div class="section-container section-bpk" id="acc-bpk">
            <div class="accordion-header" onclick="toggleAccordion('acc-bpk')">
                <div>
                    <h5 class="cat-title text-success">BPK</h5>
                    <span class="cat-subtitle">Wajib Pilih 1 Calon</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="counter-badge badge-bpk"><span id="badge-bpk">0</span>/1</span>
                    <i class="bi bi-chevron-down toggle-icon"></i>
                </div>
            </div>

            <div class="accordion-body">
                <div class="row g-3">
                    <?php foreach ($bpk as $calon): ?>
                        <?php $isChecked = (isset($selected['bpk']) && $selected['bpk'] == $calon['id']) ? 'checked' : ''; ?>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="w-100 h-100 position-relative">

                                <input type="radio" name="bpk" id="bpk_<?php echo $calon['id']; ?>" value="<?php echo $calon['id']; ?>" class="candidate-input" <?php echo $isChecked; ?>>

                                <div class="card-candidate shadow-sm d-flex flex-column h-100">
                                    <i class="bi bi-check-circle-fill text-success check-icon"></i>

                                    <label for="bpk_<?php echo $calon['id']; ?>" class="card-content flex-grow-1" style="margin-bottom:0;">
                                        <div class="candidate-name"><?php echo htmlspecialchars($calon['nama']); ?></div>
                                        <div class="candidate-info"><?php echo htmlspecialchars($calon['keterangan']); ?></div>
                                    </label>

                                    <!-- DROPDOWN PROFIL -->
                                    <div class="px-3 pb-3">
                                        <button class="btn btn-sm btn-light w-100 border text-muted" type="button" data-bs-toggle="collapse" data-bs-target="#profil_bpk_<?php echo $calon['id']; ?>" aria-expanded="false" style="font-size: 0.8rem;">
                                            <i class="bi bi-person-lines-fill me-1"></i> Lihat Profil
                                        </button>
                                        <div class="collapse mt-2" id="profil_bpk_<?php echo $calon['id']; ?>">
                                            <div class="bg-light border rounded p-2 text-start" style="font-size: 0.8rem; color: #555;">
                                                <strong class="text-dark">Biodata:</strong><br>
                                                <?php echo htmlspecialchars($calon['biodata'] ?? 'Data belum tersedia'); ?>
                                                <hr class="my-1 border-secondary opacity-25">
                                                <strong class="text-dark">Lama Jabatan:</strong><br>
                                                <?php echo htmlspecialchars($calon['lama_jabatan'] ?? '-'); ?>
                                                <hr class="my-1 border-secondary opacity-25">
                                                <strong class="text-dark">Riwayat Kerja:</strong><br>
                                                <?php echo nl2br(htmlspecialchars($calon['riwayat_kerja'] ?? '-')); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        window.toggleAccordion = function(id) {
            const section = document.getElementById(id);
            document.querySelectorAll('.section-container').forEach(el => {
                if (el.id !== id) el.classList.remove('accordion-open');
            });
            section.classList.toggle('accordion-open');
        }

        function updateCounters() {
            const pCount = document.querySelectorAll('input[name="pareses"]:checked').length;
            const pBadge = document.getElementById('badge-pareses');
            if (pBadge) {
                pBadge.innerText = pCount;
                pBadge.parentElement.className = pCount === 1 ? "counter-badge bg-success text-white" : "counter-badge badge-pareses";
            }

            const mCount = document.querySelectorAll('input[name="majelis"]:checked').length;
            const mBadge = document.getElementById('badge-majelis');
            if (mBadge) {
                mBadge.innerText = mCount;
                mBadge.parentElement.className = mCount === 1 ? "counter-badge bg-success text-white" : "counter-badge badge-majelis";
            }

            const bCount = document.querySelectorAll('input[name="bpk"]:checked').length;
            const bBadge = document.getElementById('badge-bpk');
            if (bBadge) {
                bBadge.innerText = bCount;
                bBadge.parentElement.className = bCount === 1 ? "counter-badge bg-success text-white" : "counter-badge badge-bpk";
            }
        }

        const allInputs = document.querySelectorAll('.candidate-input');
        allInputs.forEach(cb => {
            cb.addEventListener('change', updateCounters);
        });

        updateCounters();
    });
</script>

<style>
    .floating-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        padding: 10px 15px;
        box-shadow: 0 -5px 25px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        border-top: 1px solid #eee;
    }

    .footer-content {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
    }

    .btn-responsive {
        transition: all 0.2s;
    }

    @media (max-width: 576px) {
        .floating-footer {
            padding: 8px 12px;
        }

        .btn-responsive {
            font-size: 0.85rem;
            padding: 8px 25px !important;
            width: 100%;
        }

        .footer-content {
            justify-content: center;
        }
    }

    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        border: 1px solid var(--bs-primary) !important;
    }
</style>

<?php require_once './views/layouts/footer.php'; ?>