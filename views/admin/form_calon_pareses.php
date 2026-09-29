<?php
$isEdit = $calon !== null;
$formAction = $isEdit ? (BASE_URL . '/admin/updatePareses/' . $calon['id']) : (BASE_URL . '/admin/storePareses');
$pageTitle = $isEdit ? 'Edit Calon Pareses' : 'Tambah Calon Pareses';
?>

<h1><?php echo $pageTitle; ?></h1>
<hr>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm mb-5">
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="<?php echo $formAction; ?>" method="POST" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label for="nama" class="form-label fw-bold">Nama Calon</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $isEdit ? htmlspecialchars($calon['nama']) : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="daerah" class="form-label fw-bold">Daerah</label>
                        <input type="text" class="form-control" id="daerah" name="daerah" value="<?php echo $isEdit ? htmlspecialchars($calon['daerah']) : ''; ?>" required>
                    </div>

                    <!-- FOTO -->
                    <div class="mb-3 border-top pt-3 mt-4">
                        <label for="foto" class="form-label fw-bold text-secondary">Foto Calon (Opsional)</label>
                        <?php if ($isEdit && !empty($calon['foto'])): ?>
                            <div class="mb-2">
                                <img src="<?php echo BASE_URL; ?>/uploads/calon/<?php echo htmlspecialchars($calon['foto']); ?>" alt="Foto Calon" class="img-thumbnail" width="150">
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/jpeg, image/png, image/jpg">
                        <small class="text-muted">Format: JPG, JPEG, PNG. Max 2MB.</small>
                    </div>

                    <div class="mb-3">
                        <label for="biodata" class="form-label fw-bold text-secondary">Biodata (Opsional)</label>
                        <textarea class="form-control" id="biodata" name="biodata" rows="3" placeholder="Masukkan ringkasan biodata calon..."><?php echo $isEdit ? htmlspecialchars($calon['biodata'] ?? '') : ''; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="lama_jabatan" class="form-label fw-bold text-secondary">Lama Jabatan (Opsional)</label>
                        <input type="text" class="form-control" id="lama_jabatan" name="lama_jabatan" value="<?php echo $isEdit ? htmlspecialchars($calon['lama_jabatan'] ?? '') : ''; ?>" placeholder="Contoh: 2018 - 2024">
                    </div>

                    <div class="mb-4">
                        <label for="riwayat_kerja" class="form-label fw-bold text-secondary">Riwayat Kerja (Opsional)</label>
                        <textarea class="form-control" id="riwayat_kerja" name="riwayat_kerja" rows="4" placeholder="Masukkan riwayat pekerjaan atau pelayanan..."><?php echo $isEdit ? htmlspecialchars($calon['riwayat_kerja'] ?? '') : ''; ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4"><?php echo $isEdit ? 'Update Data' : 'Simpan Calon'; ?></button>
                        <a href="<?php echo BASE_URL; ?>/admin/pareses" class="btn btn-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>