<?php 
$pageTitle = 'Master Jurusan';
include __DIR__ . '/../partials/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../partials/sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Master Jurusan</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="/admin/master" class="btn btn-sm btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="bi bi-plus-circle"></i> Tambah Jurusan
                    </button>
                </div>
            </div>

            <!-- Stats -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h6 class="card-title">Total Jurusan Aktif</h6>
                            <h2 class="mb-0"><?= count(array_filter($jurusanList, fn($j) => $j['status'] === 'aktif')) ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card text-white bg-secondary">
                        <div class="card-body">
                            <h6 class="card-title">Total Jurusan Diarsipkan</h6>
                            <h2 class="mb-0"><?= count(array_filter($jurusanList, fn($j) => $j['status'] === 'arsip')) ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-building"></i> Daftar Jurusan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="tableJurusan">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Kode</th>
                                    <th>Nama Jurusan</th>
                                    <th>Jumlah Pengusul</th>
                                    <th>Status</th>
                                    <th>Terakhir Diubah</th>
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($jurusanList)): ?>
                                    <?php 
                                    $no = 1;
                                    foreach ($jurusanList as $jur): 
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><code><?= htmlspecialchars($jur['kode'] ?? '') ?></code></td>
                                            <td><strong><?= htmlspecialchars($jur['nama_jurusan']) ?></strong></td>
                                            <td>
                                                <span class="badge bg-info"><?= $jur['jumlah_pengusul'] ?? 0 ?> user</span>
                                            </td>
                                            <td>
                                                <?php if ($jur['status'] === 'aktif'): ?>
                                                    <span class="badge bg-success">Aktif</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Arsip</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= isset($jur['updated_at']) ? date('d M Y', strtotime($jur['updated_at'])) : '-' ?>
                                                </small>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-warning" onclick="editJurusan(<?= htmlspecialchars(json_encode($jur)) ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <?php if ($jur['status'] === 'aktif'): ?>
                                                    <button class="btn btn-sm btn-secondary" onclick="arsipJurusan(<?= $jur['id'] ?>)">
                                                        <i class="bi bi-archive"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-success" onclick="aktifkanJurusan(<?= $jur['id'] ?>)">
                                                        <i class="bi bi-arrow-counterclockwise"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox display-4"></i>
                                            <p class="mt-2">Belum ada data jurusan.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/admin/master/jurusan/tambah">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Jurusan Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kode" class="form-label">Kode Jurusan</label>
                        <input type="text" class="form-control" id="kode" name="kode" placeholder="TI, TS, dll" required>
                        <small class="text-muted">Kode singkatan jurusan</small>
                    </div>
                    <div class="mb-3">
                        <label for="nama_jurusan" class="form-label">Nama Jurusan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_jurusan" name="nama_jurusan" required>
                        <small class="text-muted">Contoh: Teknik Informatika</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/admin/master/jurusan/edit">
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Edit Jurusan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_kode" class="form-label">Kode Jurusan</label>
                        <input type="text" class="form-control" id="edit_kode" name="kode" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nama_jurusan" class="form-label">Nama Jurusan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nama_jurusan" name="nama_jurusan" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editJurusan(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_kode').value = data.kode || '';
    document.getElementById('edit_nama_jurusan').value = data.nama_jurusan;
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}

function arsipJurusan(id) {
    if (confirm('Apakah Anda yakin ingin mengarsipkan jurusan ini? Jurusan yang diarsipkan tidak akan muncul saat pembuatan user baru.')) {
        window.location.href = `/admin/master/jurusan/arsip/${id}`;
    }
}

function aktifkanJurusan(id) {
    if (confirm('Apakah Anda yakin ingin mengaktifkan kembali jurusan ini?')) {
        window.location.href = `/admin/master/jurusan/aktifkan/${id}`;
    }
}

// DataTable
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined' && $.fn.DataTable) {
        $('#tableJurusan').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            order: [[2, 'asc']]
        });
    }
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>