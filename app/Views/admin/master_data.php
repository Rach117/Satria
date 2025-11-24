<?php 
$pageTitle = 'Master Data';
include __DIR__ . '/../partials/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../partials/sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Master Data</h1>
            </div>

            <div class="row">
                <!-- Jurusan Card -->
                <div class="col-md-4 mb-3">
                    <div class="card h-100 border-primary">
                        <div class="card-body text-center">
                            <i class="bi bi-building display-1 text-primary"></i>
                            <h5 class="card-title mt-3">Master Jurusan</h5>
                            <p class="card-text text-muted">
                                Kelola data jurusan/program studi
                            </p>
                            <div class="d-grid gap-2">
                                <a href="/admin/master/jurusan" class="btn btn-primary">
                                    <i class="bi bi-gear"></i> Kelola Jurusan
                                </a>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> Total: <?= $totalJurusan ?? 0 ?> jurusan
                            </small>
                        </div>
                    </div>
                </div>

                <!-- IKU Card -->
                <div class="col-md-4 mb-3">
                    <div class="card h-100 border-success">
                        <div class="card-body text-center">
                            <i class="bi bi-bar-chart-line display-1 text-success"></i>
                            <h5 class="card-title mt-3">Master IKU</h5>
                            <p class="card-text text-muted">
                                Kelola Indikator Kinerja Utama
                            </p>
                            <div class="d-grid gap-2">
                                <a href="/admin/master/iku" class="btn btn-success">
                                    <i class="bi bi-gear"></i> Kelola IKU
                                </a>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> Aktif: <?= $totalIkuAktif ?? 0 ?> | Arsip: <?= $totalIkuArsip ?? 0 ?>
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Satuan Anggaran Card -->
                <div class="col-md-4 mb-3">
                    <div class="card h-100 border-info">
                        <div class="card-body text-center">
                            <i class="bi bi-calculator display-1 text-info"></i>
                            <h5 class="card-title mt-3">Master Satuan</h5>
                            <p class="card-text text-muted">
                                Kelola satuan anggaran RAB
                            </p>
                            <div class="d-grid gap-2">
                                <a href="/admin/master/satuan" class="btn btn-info text-white">
                                    <i class="bi bi-gear"></i> Kelola Satuan
                                </a>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> Aktif: <?= $totalSatuanAktif ?? 0 ?> | Arsip: <?= $totalSatuanArsip ?? 0 ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mt-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-graph-up"></i> Statistik Master Data</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="p-3 border rounded">
                                <h4 class="text-primary"><?= $totalJurusan ?? 0 ?></h4>
                                <p class="text-muted mb-0">Total Jurusan</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 border rounded">
                                <h4 class="text-success"><?= $totalIkuAktif ?? 0 ?></h4>
                                <p class="text-muted mb-0">IKU Aktif</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 border rounded">
                                <h4 class="text-info"><?= $totalSatuanAktif ?? 0 ?></h4>
                                <p class="text-muted mb-0">Satuan Aktif</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 border rounded">
                                <h4 class="text-warning"><?= ($totalIkuArsip ?? 0) + ($totalSatuanArsip ?? 0) ?></h4>
                                <p class="text-muted mb-0">Total Diarsipkan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Updates -->
            <?php if (!empty($recentUpdates)): ?>
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-clock-history"></i> Aktivitas Terbaru</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentUpdates as $update): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">
                                        <?php if ($update['tipe'] === 'jurusan'): ?>
                                            <i class="bi bi-building text-primary"></i>
                                        <?php elseif ($update['tipe'] === 'iku'): ?>
                                            <i class="bi bi-bar-chart-line text-success"></i>
                                        <?php else: ?>
                                            <i class="bi bi-calculator text-info"></i>
                                        <?php endif; ?>
                                        <?= htmlspecialchars($update['aksi']) ?>
                                    </h6>
                                    <small><?= date('d M Y H:i', strtotime($update['waktu'])) ?></small>
                                </div>
                                <p class="mb-1"><?= htmlspecialchars($update['keterangan']) ?></p>
                                <small class="text-muted">oleh <?= htmlspecialchars($update['user']) ?></small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Help Section -->
            <div class="alert alert-info mt-4">
                <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Petunjuk Penggunaan</h6>
                <ul class="mb-0">
                    <li><strong>Jurusan:</strong> Kelola daftar jurusan yang ada di PNJ. Jurusan yang diarsipkan tidak akan muncul saat pembuatan user baru.</li>
                    <li><strong>IKU:</strong> Kelola Indikator Kinerja Utama. IKU yang tidak aktif tidak akan muncul di form pengajuan usulan.</li>
                    <li><strong>Satuan:</strong> Kelola satuan anggaran untuk RAB (contoh: ORG, PP, LS, dll). Satuan yang diarsipkan tidak akan muncul di form RAB.</li>
                </ul>
            </div>
        </main>
    </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>