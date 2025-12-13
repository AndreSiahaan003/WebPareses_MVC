<?php
// --- LOGIKA PENGELOMPOKAN ---
$groupedPemilih = [];
if (!empty($pemilih)) {
    foreach ($pemilih as $p) {
        $unsurKey = $p['unsur'];
        $groupedPemilih[$unsurKey][] = $p;
    }
    ksort($groupedPemilih); // Urutkan Abjad Unsur
}

$isFirstGroup = true; // Penanda untuk grup pertama (agar tidak ada jarak di paling atas)
?>

<style>
    /* Style Header Hijau Keren */
    .header-unsur {
        background: linear-gradient(45deg, #198754, #20c997) !important;
        color: white !important;
        border: none;
    }

    /* Style Baris Pemisah (Jarak) */
    .spacer-row td {
        background-color: transparent !important;
        border: none !important;
        height: 30px;
        /* Atur jarak di sini */
    }

    /* Agar Card terlihat menyatu per grup */
    .group-container {
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 30px;
        /* Jarak luar antar grup */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
</style>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Manajemen Pemilih</h2>
            <p class="text-muted mb-0">Data dikelompokkan berdasarkan Unsur.</p>
        </div>
        <a href="<?php echo BASE_URL; ?>/admin/createPemilih" class="btn btn-primary shadow-sm px-4 rounded-pill">
            <i class="bi bi-plus-lg me-2"></i> Tambah Pemilih
        </a>
    </div>

    <?php if (isset($pesan) && $pesan): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> <?php echo $pesan; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($groupedPemilih)): ?>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-25"></i>
                Belum ada data pemilih.
            </div>
        </div>

    <?php else: ?>

        <?php foreach ($groupedPemilih as $unsurName => $listPemilih): ?>

            <div class="group-container bg-white">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">

                        <thead>
                            <tr class="header-unsur">
                                <th colspan="5" class="py-3 ps-4 border-0 bg-primary">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-people-fill me-2 text-light"></i>
                                        <span class="fw-bold text-light fs-6"><?php echo htmlspecialchars($unsurName); ?></span>
                                        <span class="badge bg-white text-success ms-3 rounded-pill shadow-sm">
                                            <?php echo count($listPemilih); ?> Orang
                                        </span>
                                    </div>
                                </th>
                            </tr>
                            <tr class="bg-light  text-uppercase">
                                <th class="ps-4 py-2" style="width: 30%;">Nama Lengkap</th>
                                <th class="py-2" style="width: 25%;">Daerah / Lembaga</th>
                                <th class="py-2" style="width: 20%;">Resort</th>
                                <th class="py-2 text-center" style="width: 10%;">Status</th>
                                <th class="py-2 text-center" style="width: 15%;">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($listPemilih as $p): ?>
                                <tr class="border-bottom hover-row">
                                    <td class="ps-4 text-secondary">
                                        <?php echo htmlspecialchars($p['nama']); ?>
                                    </td>
                                    <td class="text-secondary">
                                        <?php echo htmlspecialchars($p['daerah_lembaga']); ?>
                                    </td>
                                    <td class="text-secondary">
                                        <?php echo !empty($p['resort']) ? htmlspecialchars($p['resort']) : '<span class="text-muted opacity-50">-</span>'; ?>
                                    </td>

                                    <td class="text-center">
                                        <?php if ($p['status_vote'] == 1): ?>
                                            <span class="badge bg-success rounded-pill">Sudah</span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-secondary border rounded-pill">Belum</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="<?php echo BASE_URL; ?>/admin/viewQr/<?php echo $p['id']; ?>"
                                                class="btn btn-sm btn-outline-info" title="Lihat Barcode">
                                                <i class="bi bi-qr-code"></i>
                                            </a>
                                            <a href="<?php echo BASE_URL; ?>/admin/editPemilih/<?php echo $p['id']; ?>"
                                                class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="<?php echo BASE_URL; ?>/admin/deletePemilih/<?php echo $p['id']; ?>"
                                                method="POST" onsubmit="return confirm('Hapus data ini?');">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>

    <div class="text-center mt-4 mb-5 text-muted small">
        Total Data Keseluruhan: <strong><?php echo count($pemilih); ?></strong> Pemilih
    </div>
</div>