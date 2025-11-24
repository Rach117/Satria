<?php 
$pageTitle = 'Riwayat LPJ';
include __DIR__ . '/../partials/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../partials/sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Riwayat LPJ Selesai</h1>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h6 class="card-title">Total LPJ Selesai</h6>
                            <h2 class="mb-0"><?= count($riwayatLpj) ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h6 class="card-title">Total Dana Terverifikasi</h6>
                            <h2 class="mb-0">
                                Rp <?= number_format($totalDanaVerifikasi ?? 0, 0, ',', '.') ?>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h6 class="card-title">Rata-rata Waktu Verifikasi</h6>
                            <h2 class="mb-0"><?= $rataRataHari ?? '-' ?> hari</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter -->
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="/bendahara/riwayat" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Jurusan</label>
                            <select name="jurusan" class="form-select">
                                <option value="">Semua Jurusan</option>
                                <?php foreach ($jurusanList as $jur): ?>
                                    <option value="<?= $jur['id'] ?>" <?= (isset($_GET['jurusan']) && $_GET['jurusan'] == $jur['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($jur['nama_jurusan']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Bulan</label>
                            <input type="month" name="bulan" class="form-control" value="<?= $_GET['bulan'] ?? '' ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tahun</label>
                            <select name="tahun" class="form-select">
                                <option value="">Semua Tahun</option>
                                <?php 
                                $currentYear = date('Y');
                                for ($i = $currentYear; $i >= $currentYear - 5; $i--): 
                                ?>
                                    <option value="<?= $i ?>" <?= (isset($_GET['tahun']) && $_GET['tahun'] == $i) ? 'selected' : '' ?>>
                                        <?= $i ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-filter"></i> Filter
                            </button>
                            <a href="/bendahara/riwayat" class="btn btn-secondary">
                                <i class="bi bi-x"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Riwayat Table -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Daftar Riwayat LPJ</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($riwayatLpj)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">No</th>
                                        <th>Nama Kegiatan</th>
                                        <th>Pengusul</th>
                                        <th>Jurusan</th>
                                        <th>Total RAB</th>
                                        <th>Dana Dicairkan</th>
                                        <th>Tanggal Pencairan</th>
                                        <th>Tanggal Verifikasi LPJ</th>
                                        <th width="100">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    foreach ($riwayatLpj as $lpj): 
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td>
                                                <strong><?= htmlspecialchars($lpj['nama_kegiatan']) ?></strong>
                                                <br>
                                                <small class="text-muted">ID: <?= $lpj['pengajuan_id'] ?></small>
                                            </td>
                                            <td><?= htmlspecialchars($lpj['nama_pengusul']) ?></td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?= htmlspecialchars($lpj['nama_jurusan']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <strong>Rp <?= number_format($lpj['total_rab'], 0, ',', '.') ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">
                                                    Rp <?= number_format($lpj['total_dicairkan'], 0, ',', '.') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?= date('d M Y', strtotime($lpj['tanggal_pencairan_pertama'])) ?>
                                                <?php if ($lpj['jumlah_pencairan'] > 1): ?>
                                                    <br><small class="text-muted">(<?= $lpj['jumlah_pencairan'] ?>x pencairan)</small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="text-success">
                                                    <i class="bi bi-check-circle-fill"></i>
                                                    <?= date('d M Y', strtotime($lpj['tanggal_verifikasi_lpj'])) ?>
                                                </span>
                                                <br>
                                                <small class="text-muted">
                                                    <?php
                                                    $diff = (strtotime($lpj['tanggal_verifikasi_lpj']) - strtotime($lpj['tanggal_pencairan_pertama'])) / 86400;
                                                    echo round($diff) . ' hari';
                                                    ?>
                                                </small>
                                            </td>
                                            <td>
                                                <a href="/bendahara/lpj/detail/<?= $lpj['pengajuan_id'] ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="table-secondary">
                                    <tr>
                                        <th colspan="4" class="text-end">Total:</th>
                                        <th>
                                            Rp <?= number_format(array_sum(array_column($riwayatLpj, 'total_rab')), 0, ',', '.') ?>
                                        </th>
                                        <th colspan="4">
                                            Rp <?= number_format(array_sum(array_column($riwayatLpj, 'total_dicairkan')), 0, ',', '.') ?>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Export Button -->
                        <div class="mt-3 d-flex justify-content-end">
                            <button class="btn btn-success" onclick="exportToExcel()">
                                <i class="bi bi-file-earmark-excel"></i> Export ke Excel
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <p class="text-muted mt-3">Belum ada riwayat LPJ yang selesai.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Statistics -->
            <?php if (!empty($riwayatLpj)): ?>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">LPJ per Jurusan</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="chartJurusan"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Trend Verifikasi LPJ (6 Bulan Terakhir)</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="chartTrend"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
<?php if (!empty($riwayatLpj)): ?>
// Chart LPJ per Jurusan
const jurusanData = <?= json_encode($chartJurusanData ?? []) ?>;
if (jurusanData.length > 0) {
    const ctxJurusan = document.getElementById('chartJurusan').getContext('2d');
    new Chart(ctxJurusan, {
        type: 'pie',
        data: {
            labels: jurusanData.map(d => d.nama_jurusan),
            datasets: [{
                data: jurusanData.map(d => d.total),
                backgroundColor: [
                    '#0d6efd', '#6610f2', '#6f42c1', '#d63384', 
                    '#dc3545', '#fd7e14', '#ffc107', '#20c997'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

// Chart Trend
const trendData = <?= json_encode($chartTrendData ?? []) ?>;
if (trendData.length > 0) {
    const ctxTrend = document.getElementById('chartTrend').getContext('2d');
    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: trendData.map(d => d.bulan),
            datasets: [{
                label: 'Jumlah LPJ Selesai',
                data: trendData.map(d => d.total),
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}
<?php endif; ?>

function exportToExcel() {
    // Simple export using data URL
    const table = document.querySelector('.table-responsive table');
    let html = '<table>';
    
    // Headers
    html += '<tr>';
    table.querySelectorAll('thead th').forEach(th => {
        html += '<th>' + th.textContent + '</th>';
    });
    html += '</tr>';
    
    // Data rows
    table.querySelectorAll('tbody tr').forEach(tr => {
        html += '<tr>';
        tr.querySelectorAll('td').forEach(td => {
            html += '<td>' + td.textContent.trim() + '</td>';
        });
        html += '</tr>';
    });
    
    html += '</table>';
    
    const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'riwayat_lpj_' + new Date().toISOString().slice(0,10) + '.xls';
    a.click();
}
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>