<?php 
$pageTitle = 'Monitoring Kegiatan';
include __DIR__ . '/../partials/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../partials/sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Monitoring Kegiatan Global</h1>
            </div>

            <!-- Filter Section -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="/admin/monitoring-kegiatan" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Jurusan</label>
                            <select name="jurusan" class="form-select">
                                <option value="">Semua Jurusan</option>
                                <?php foreach ($jurusan_list as $jur): ?>
                                    <option value="<?= $jur['id'] ?>" <?= (isset($_GET['jurusan']) && $_GET['jurusan'] == $jur['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($jur['nama_jurusan']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status Usulan</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="Diajukan" <?= (isset($_GET['status']) && $_GET['status'] == 'Diajukan') ? 'selected' : '' ?>>Diajukan</option>
                                <option value="Revisi" <?= (isset($_GET['status']) && $_GET['status'] == 'Revisi') ? 'selected' : '' ?>>Revisi</option>
                                <option value="Disetujui" <?= (isset($_GET['status']) && $_GET['status'] == 'Disetujui') ? 'selected' : '' ?>>Disetujui</option>
                                <option value="Ditolak" <?= (isset($_GET['status']) && $_GET['status'] == 'Ditolak') ? 'selected' : '' ?>>Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tahun</label>
                            <select name="tahun" class="form-select">
                                <option value="">Semua Tahun</option>
                                <?php 
                                $currentYear = date('Y');
                                for ($year = $currentYear; $year >= $currentYear - 5; $year--): 
                                ?>
                                    <option value="<?= $year ?>" <?= (isset($_GET['tahun']) && $_GET['tahun'] == $year) ? 'selected' : '' ?>>
                                        <?= $year ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-filter"></i> Filter
                            </button>
                            <a href="/admin/monitoring-kegiatan" class="btn btn-secondary">
                                <i class="bi bi-x"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h6 class="card-title">Total Diajukan</h6>
                            <h2 class="mb-0"><?= $stats['total'] ?? 0 ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h6 class="card-title">Disetujui</h6>
                            <h2 class="mb-0"><?= $stats['disetujui'] ?? 0 ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h6 class="card-title">Revisi</h6>
                            <h2 class="mb-0"><?= $stats['revisi'] ?? 0 ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <h6 class="card-title">Ditolak</h6>
                            <h2 class="mb-0"><?= $stats['ditolak'] ?? 0 ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-list-check"></i> Daftar Kegiatan</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($kegiatan_list)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">No</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Pengusul</th>
                                        <th>Jurusan</th>
                                        <th>Status Usulan</th>
                                        <th>Status Pengajuan</th>
                                        <th>Tanggal</th>
                                        <th width="100">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    foreach ($kegiatan_list as $kegiatan): 
                                        // Status Badge Classes
                                        $statusUsulanClass = match($kegiatan['status_usulan']) {
                                            'Diajukan' => 'bg-info',
                                            'Disetujui' => 'bg-success',
                                            'Revisi' => 'bg-warning',
                                            'Ditolak' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                        
                                        $statusPengajuanClass = match($kegiatan['status_pengajuan'] ?? '') {
                                            'Menunggu PPK' => 'bg-primary',
                                            'Menunggu WD2' => 'bg-info',
                                            'Disetujui' => 'bg-success',
                                            'Ditolak' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td>
                                                <strong><?= htmlspecialchars($kegiatan['nama_kegiatan']) ?></strong>
                                                <br>
                                                <small class="text-muted">ID: #<?= $kegiatan['id'] ?></small>
                                            </td>
                                            <td><?= htmlspecialchars($kegiatan['username']) ?></td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?= htmlspecialchars($kegiatan['nama_jurusan'] ?? '-') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge <?= $statusUsulanClass ?>">
                                                    <?= $kegiatan['status_usulan'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($kegiatan['status_pengajuan']): ?>
                                                    <span class="badge <?= $statusPengajuanClass ?>">
                                                        <?= $kegiatan['status_pengajuan'] ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Belum Diajukan</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('d M Y', strtotime($kegiatan['created_at'])) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <a href="/monitoring/detail?id=<?= $kegiatan['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                        <nav aria-label="Page navigation" class="mt-3">
                            <ul class="pagination justify-content-center">
                                <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                    <li class="page-item <?= ($i == $pagination['current_page']) ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?><?= isset($_GET['jurusan']) ? '&jurusan='.$_GET['jurusan'] : '' ?><?= isset($_GET['status']) ? '&status='.$_GET['status'] : '' ?><?= isset($_GET['tahun']) ? '&tahun='.$_GET['tahun'] : '' ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <p class="text-muted mt-3">Tidak ada data kegiatan yang ditemukan.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>