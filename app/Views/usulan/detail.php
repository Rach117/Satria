<?php 
$pageTitle = 'Detail Usulan Kegiatan';
include __DIR__ . '/../partials/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../partials/sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Detail Usulan Kegiatan</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="/usulan" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <?php if ($usulan['status'] === 'draft'): ?>
                        <a href="/usulan/edit/<?= $usulan['id'] ?>" class="btn btn-sm btn-warning ms-2">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <button type="button" class="btn btn-sm btn-primary ms-2" onclick="submitUsulan(<?= $usulan['id'] ?>)">
                            <i class="bi bi-send"></i> Ajukan
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="mb-3">
                <?php
                $statusColors = [
                    'draft' => 'secondary',
                    'diajukan' => 'info',
                    'revisi' => 'warning',
                    'disetujui' => 'success',
                    'ditolak' => 'danger'
                ];
                $color = $statusColors[$usulan['status']] ?? 'secondary';
                ?>
                <span class="badge bg-<?= $color ?> fs-6">Status: <?= strtoupper($usulan['status']) ?></span>
                <?php if (!empty($usulan['catatan_verifikator'])): ?>
                    <span class="badge bg-warning text-dark fs-6 ms-2">
                        <i class="bi bi-exclamation-triangle"></i> Ada Catatan
                    </span>
                <?php endif; ?>
            </div>

            <!-- Catatan Verifikator -->
            <?php if (!empty($usulan['catatan_verifikator'])): ?>
            <div class="alert alert-warning">
                <h6 class="alert-heading"><i class="bi bi-chat-left-text"></i> Catatan dari Verifikator:</h6>
                <p class="mb-0"><?= nl2br(htmlspecialchars($usulan['catatan_verifikator'])) ?></p>
            </div>
            <?php endif; ?>

            <!-- Catatan Penolakan -->
            <?php if (!empty($usulan['catatan_penolakan']) && $usulan['status'] === 'ditolak'): ?>
            <div class="alert alert-danger">
                <h6 class="alert-heading"><i class="bi bi-x-circle"></i> Alasan Penolakan:</h6>
                <p class="mb-0"><?= nl2br(htmlspecialchars($usulan['catatan_penolakan'])) ?></p>
            </div>
            <?php endif; ?>

            <!-- KAK Section -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-file-text"></i> Kerangka Acuan Kegiatan (KAK)</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Nama Kegiatan:</div>
                        <div class="col-md-9"><?= htmlspecialchars($usulan['nama_kegiatan']) ?></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Gambaran Umum:</div>
                        <div class="col-md-9"><?= nl2br(htmlspecialchars($usulan['gambaran_umum'])) ?></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Penerima Manfaat:</div>
                        <div class="col-md-9"><?= nl2br(htmlspecialchars($usulan['penerima_manfaat'])) ?></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Strategi Pencapaian:</div>
                        <div class="col-md-9"><?= nl2br(htmlspecialchars($usulan['strategi_pencapaian'])) ?></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Metode Pelaksanaan:</div>
                        <div class="col-md-9">
                            <?php 
                            $metode = json_decode($usulan['metode_pelaksanaan'], true);
                            if ($metode):
                            ?>
                                <ol class="mb-0">
                                    <?php foreach ($metode as $m): ?>
                                        <li><?= htmlspecialchars($m) ?></li>
                                    <?php endforeach; ?>
                                </ol>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Tahapan Pelaksanaan:</div>
                        <div class="col-md-9">
                            <?php 
                            $tahapan = json_decode($usulan['tahapan_pelaksanaan'], true);
                            if ($tahapan):
                            ?>
                                <ol class="mb-0">
                                    <?php foreach ($tahapan as $t): ?>
                                        <li><?= htmlspecialchars($t) ?></li>
                                    <?php endforeach; ?>
                                </ol>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3 fw-bold">Indikator Kinerja:</div>
                        <div class="col-md-9">
                            <?php 
                            $indikator = json_decode($usulan['indikator_kinerja'], true);
                            if ($indikator):
                            ?>
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Indikator</th>
                                            <th>Target Bulan</th>
                                            <th>Bobot</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($indikator as $ind): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($ind['indikator']) ?></td>
                                                <td><?= date('M Y', strtotime($ind['bulan_target'])) ?></td>
                                                <td><?= $ind['bobot'] ?>%</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 fw-bold">Waktu Pelaksanaan:</div>
                        <div class="col-md-9">
                            <?= date('d M Y', strtotime($usulan['tanggal_mulai'])) ?> 
                            s/d 
                            <?= date('d M Y', strtotime($usulan['tanggal_selesai'])) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- IKU Section -->
            <div class="card mb-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Indikator Kinerja Utama (IKU)</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($ikuData)): ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama IKU</th>
                                    <th width="150">Bobot</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ikuData as $iku): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($iku['nama_iku']) ?></td>
                                        <td><?= $iku['bobot'] ?>%</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr class="table-info">
                                    <th class="text-end">Total Bobot:</th>
                                    <th><?php 
                                        $totalBobot = array_sum(array_column($ikuData, 'bobot'));
                                        echo number_format($totalBobot, 1);
                                    ?>%</th>
                                </tr>
                            </tfoot>
                        </table>
                    <?php else: ?>
                        <p class="text-muted">Belum ada IKU yang dipilih.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- RAB Section -->
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-calculator"></i> Rincian Anggaran Biaya (RAB)</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($rabData)): ?>
                        <?php
                        $kategoriList = ['Belanja Barang', 'Belanja Jasa', 'Belanja Perjalanan'];
                        $totalKeseluruhan = 0;
                        
                        foreach ($kategoriList as $kategori):
                            $itemsKategori = array_filter($rabData, function($item) use ($kategori) {
                                return $item['kategori'] === $kategori;
                            });
                            
                            if (empty($itemsKategori)) continue;
                        ?>
                            <h6 class="text-primary mt-3 mb-2"><?= $kategori ?></h6>
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Uraian</th>
                                        <th>Satuan</th>
                                        <th>Jumlah</th>
                                        <th>Harga Satuan</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    $subtotal = 0;
                                    foreach ($itemsKategori as $item): 
                                        $total = $item['jumlah'] * $item['harga_satuan'];
                                        $subtotal += $total;
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($item['uraian']) ?></td>
                                            <td><?= htmlspecialchars($item['satuan']) ?></td>
                                            <td class="text-end"><?= number_format($item['jumlah'], 0, ',', '.') ?></td>
                                            <td class="text-end">Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?></td>
                                            <td class="text-end">Rp <?= number_format($total, 0, ',', '.') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-secondary">
                                        <th colspan="5" class="text-end">Subtotal <?= $kategori ?>:</th>
                                        <th class="text-end">Rp <?= number_format($subtotal, 0, ',', '.') ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        <?php 
                            $totalKeseluruhan += $subtotal;
                        endforeach; 
                        ?>

                        <div class="card bg-light mt-3">
                            <div class="card-body">
                                <h5 class="mb-0">Total Anggaran Keseluruhan: 
                                    <strong class="text-primary">Rp <?= number_format($totalKeseluruhan, 0, ',', '.') ?></strong>
                                </h5>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Belum ada RAB yang diinputkan.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Informasi Tambahan -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informasi Tambahan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Diajukan oleh:</strong> <?= htmlspecialchars($usulan['nama_pengusul'] ?? '') ?></p>
                            <p class="mb-1"><strong>Jurusan:</strong> <?= htmlspecialchars($usulan['nama_jurusan'] ?? '') ?></p>
                            <p class="mb-1"><strong>Tanggal Pengajuan:</strong> 
                                <?= isset($usulan['tanggal_pengajuan']) ? date('d M Y H:i', strtotime($usulan['tanggal_pengajuan'])) : '-' ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <?php if (!empty($usulan['kode_mak'])): ?>
                                <p class="mb-1"><strong>Kode MAK:</strong> <?= htmlspecialchars($usulan['kode_mak']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($usulan['tanggal_verifikasi'])): ?>
                                <p class="mb-1"><strong>Tanggal Verifikasi:</strong> 
                                    <?= date('d M Y H:i', strtotime($usulan['tanggal_verifikasi'])) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function submitUsulan(id) {
    if (confirm('Apakah Anda yakin ingin mengajukan usulan ini? Usulan yang sudah diajukan tidak dapat diedit lagi.')) {
        window.location.href = `/usulan/submit/${id}`;
    }
}
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>