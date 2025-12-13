<?php
$isEdit = isset($pemilih) && $pemilih !== null;
$formAction = $isEdit ? (BASE_URL . '/admin/updatePemilih/' . $pemilih['id']) : (BASE_URL . '/admin/storePemilih');
$pageTitle = $isEdit ? 'Edit Data Pemilih' : 'Tambah Pemilih Baru';

// Nilai default
$selectedUnsur = $isEdit ? $pemilih['unsur'] : '';
$selectedValue = $isEdit ? htmlspecialchars($pemilih['daerah_lembaga']) : '';
?>

<style>
    .unique-label {
        font-weight: 600;
        color: #198754;
    }

    .card-form {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border: none;
    }

    .form-header {
        background-color: #f0fff4;
        border-bottom: 2px solid #198754;
        padding: 1.5rem;
        border-radius: 12px 12px 0 0;
    }

    .dynamic-input-block {
        border: 1px dashed #d1e7dd;
        padding: 20px;
        border-radius: 8px;
        margin-top: 15px;
        background-color: #f8fcf8;
    }
</style>

<div class="row justify-content-center my-5">
    <div class="col-md-9">
        <div class="card card-form">

            <div class="form-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-person-lines-fill me-2 text-success"></i><?php echo $pageTitle; ?>
                    </h3>
                    <?php if ($isEdit): ?>
                        <span class="badge bg-secondary">ID: #<?php echo $pemilih['id']; ?></span>
                    <?php endif; ?>
                </div>
                <p class="text-muted mb-0 small mt-2">Lengkapi data pemilih sesuai unsur dan lembaga yang valid.</p>
            </div>

            <div class="card-body p-4 p-md-5">
                <?php if (isset($error) && $error): ?>
                    <div class="alert alert-danger shadow-sm border-0 rounded-3 mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form id="formRegistrasi" action="<?php echo $formAction; ?>" method="POST">

                    <div class="mb-4">
                        <label for="nama" class="form-label unique-label"><i class="bi bi-tag me-1"></i>Nama Lengkap</label>
                        <input type="text" class="form-control form-control-lg" id="nama" name="nama"
                            value="<?php echo $isEdit ? htmlspecialchars($pemilih['nama']) : ''; ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="unsur" class="form-label fw-bold"><i class="bi bi-list-stars me-1"></i>Pilih Unsur</label>
                        <select class="form-select form-select-lg" id="unsur" name="unsur" required>
                            <option value="" disabled <?php echo !$isEdit ? 'selected' : ''; ?>>-- Pilih Unsur --</option>

                            <option value="Ketua Umum Lembaga Tingkat Pusat" <?php echo ($selectedUnsur == 'Ketua Umum Lembaga Tingkat Pusat') ? 'selected' : ''; ?>>Ketua Umum Lembaga Tingkat Pusat</option>

                            <option value="UNSUR BADAN USAHA LEMBAGA YANG DITUNJUK PUCUK PIMPINAN" <?php echo ($selectedUnsur == 'UNSUR BADAN USAHA LEMBAGA YANG DITUNJUK PUCUK PIMPINAN') ? 'selected' : ''; ?>>Unsur Badan Usaha Lembaga Yang Ditunjuk Pucuk Pimpinan</option>

                            <optgroup label="============================================================================================">
                                <option value="Pendeta" <?php echo ($selectedUnsur == 'Pendeta') ? 'selected' : ''; ?>>Pendeta</option>
                                <option value="Anggota Jemaat" <?php echo ($selectedUnsur == 'Anggota Jemaat') ? 'selected' : ''; ?>>Anggota Jemaat</option>
                            </optgroup>
                            <optgroup label="============================================================================================">
                                <option value="Guru Jemaat" <?php echo ($selectedUnsur == 'Guru Jemaat') ? 'selected' : ''; ?>>Guru Jemaat</option>
                                <option value="Penatua" <?php echo ($selectedUnsur == 'Penatua') ? 'selected' : ''; ?>>Penatua</option>
                                <option value="Lembaga PA" <?php echo ($selectedUnsur == 'Lembaga PA') ? 'selected' : ''; ?>>Lembaga PA</option>
                                <option value="Lembaga PPR" <?php echo ($selectedUnsur == 'Lembaga PPR') ? 'selected' : ''; ?>>Lembaga PPR</option>
                                <option value="Lembaga Naposo Bulung" <?php echo ($selectedUnsur == 'Lembaga Naposo Bulung') ? 'selected' : ''; ?>>Lembaga Naposo Bulung</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="dynamic-input-block mb-4">
                        <label class="form-label fw-bold text-dark mb-3"><i class="bi bi-pin-map me-1"></i> Detail Daerah / Lembaga</label>

                        <div class="mb-3" id="daerahLembagaGroup_Lembaga" style="display: none;">
                            <label class="form-label small text-muted">Pilih Lembaga Pusat</label>
                            <select class="form-select" id="daerah_lembaga_lembaga" name="daerah_lembaga">
                                <option value="" disabled selected>-- Pilih Lembaga --</option>
                                <?php foreach (["Persatuan Ama", "Persatuan Perempuan", "PNB", "PGJ"] as $opt): ?>
                                    <option value="<?php echo $opt; ?>" <?php echo ($selectedValue == $opt) ? 'selected' : ''; ?>><?php echo $opt; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3" id="daerahLembagaGroup_Departemen" style="display: none;">
                            <label class="form-label small text-muted">Pilih Jabatan / Departemen</label>
                            <select class="form-select" id="daerah_lembaga_departemen" name="daerah_lembaga">
                                <option value="" disabled selected>-- Pilih Jabatan --</option>
                                <?php
                                $deptOptions = [
                                    "Kepala Departemen Marturia",
                                    "Kepala Departemen Koinonia",
                                    "Kepala Departemen Diakonia"

                                ];
                                foreach ($deptOptions as $opt): ?>
                                    <option value="<?php echo $opt; ?>" <?php echo ($selectedValue == $opt) ? 'selected' : ''; ?>><?php echo $opt; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3" id="daerahLembagaGroup_Text" style="display: none;">
                            <label class="form-label small text-muted">Nama Daerah / Lembaga</label>
                            <input type="text" class="form-control" id="daerah_lembaga_text" name="daerah_lembaga"
                                value="<?php echo $selectedValue; ?>" placeholder="Contoh: Jakarta I">
                        </div>

                        <div class="mb-3" id="resortGroup" style="display: none;">
                            <label class="form-label fw-bold text-dark mt-2"><i class="bi bi-map me-1 small"></i> Resort</label>
                            <input type="text" class="form-control" id="resort" name="resort"
                                value="<?php echo $isEdit ? htmlspecialchars($pemilih['resort']) : ''; ?>" placeholder="Masukkan Nama Resort">
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-success btn-lg px-4 shadow-sm">
                            <i class="bi bi-save me-2"></i> <?php echo $isEdit ? 'Simpan Data' : 'Simpan Pemilih'; ?>
                        </button>
                        <a href="<?php echo BASE_URL; ?>/admin/pemilih" class="btn btn-outline-secondary btn-lg">Batal</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>