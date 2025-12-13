<?php
$isEdit = $calon !== null;
$formAction = $isEdit ? (BASE_URL . '/admin/updateBPK/' . $calon['id']) : (BASE_URL . '/admin/storeBPK');
$pageTitle = $isEdit ? 'Edit Calon BPK' : 'Tambah Calon BPK';
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
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <input type="text" class="form-control" id="keterangan" name="keterangan" value="<?php echo $isEdit ? htmlspecialchars($calon['keterangan']) : ''; ?>" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><?php echo $isEdit ? 'Update Data' : 'Simpan Calon'; ?></button>
                        <a href="<?php echo BASE_URL; ?>/admin/bpk" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>