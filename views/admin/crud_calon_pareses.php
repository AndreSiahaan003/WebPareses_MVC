<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Manajemen Calon Pareses</h1>
        <a href="<?php echo BASE_URL; ?>/admin/createPareses" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Calon
        </a>
    </div>

    <?php if (isset($pesan) && $pesan): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $pesan; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-striped table-hover table-bordered align-middle" id="datatablesSimple">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Calon</th>
                            <th>Daerah</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($calon)): ?>
                            <tr>
                                <td colspan="3" class="text-center py-4">Belum ada data calon.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($calon as $c): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($c['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($c['daerah']); ?></td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">

                                            <a href="<?php echo BASE_URL; ?>/admin/editPareses/<?php echo $c['id']; ?>"
                                                class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <form action="<?php echo BASE_URL; ?>/admin/deletePareses/<?php echo $c['id']; ?>"
                                                method="POST" onsubmit="return confirm('Anda yakin ingin menghapus calon ini?');">
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3">
            <small class="text-muted">Total Calon: <strong><?php echo count($calon); ?></strong></small>
        </div>
    </div>

</div>