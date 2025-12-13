<?php
$isEdit = $calon !== null;
$formAction = $isEdit ? (BASE_URL . '/admin/updatePareses/' . $calon['id']) : (BASE_URL . '/admin/storePareses');
$pageTitle = $isEdit ? 'Edit Calon Pareses' : 'Tambah Calon Pareses';
?>

<h1><?php echo $pageTitle; ?></h1>
<hr>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="<?php echo $formAction; ?>" method="POST">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Calon</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $isEdit ? htmlspecialchars($calon['nama']) : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="daerah" class="form-label">Daerah</label>
                        <input type="text" class="form-control" id="daerah" name="daerah" value="<?php echo $isEdit ? htmlspecialchars($calon['daerah']) : ''; ?>" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><?php echo $isEdit ? 'Update Data' : 'Simpan Calon'; ?></button>
                        <a href="<?php echo BASE_URL; ?>/admin/pareses" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>