<?php 
$pageTitle = 'Proses Verifikasi Usulan';
include __DIR__ . '/../partials/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../partials/sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Proses Verifikasi Usulan</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="/verifikasi" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="mb-3">
                <span class="badge bg-info fs-6">Status: MENUNGGU VERIFIKASI</span>
            </div>

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

                    <!-- Catatan KAK -->
                    <div class="mt-3 p-3 bg-light rounded">
                        <label class="form-label fw-bold text-danger">Catatan untuk KAK (jika perlu revisi):</label>
                        <textarea class="form-control" id="catatan-kak" rows="2" placeholder="Berikan catatan revisi untuk bagian KAK..."></textarea>
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

                        <!-- Catatan IKU -->
                        <div class="mt-3 p-3 bg-light rounded">
                            <label class="form-label fw-bold text-danger">Catatan untuk IKU (jika perlu revisi):</label>
                            <textarea class="form-control" id="catatan-iku" rows="2" placeholder="Berikan catatan revisi untuk IKU..."></textarea>
                        </div>
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

                        <!-- Catatan RAB -->
                        <div class="mt-3 p-3 bg-light rounded">
                            <label class="form-label fw-bold text-danger">Catatan untuk RAB (jika perlu revisi):</label>
                            <textarea class="form-control" id="catatan-rab" rows="2" placeholder="Berikan catatan revisi untuk RAB..."></textarea>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Belum ada RAB yang diinputkan.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Form Verifikasi -->
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-check2-square"></i> Keputusan Verifikasi</h5>
                </div>
                <div class="card-body">
                    <form id="form-verifikasi" method="POST">
                        <input type="hidden" name="usulan_id" value="<?= $usulan['id'] ?>">
                        <input type="hidden" name="catatan_kak" id="hidden-catatan-kak">
                        <input type="hidden" name="catatan_iku" id="hidden-catatan-iku">
                        <input type="hidden" name="catatan_rab" id="hidden-catatan-rab">

                        <!-- Input Kode MAK (untuk setuju) -->
                        <div class="mb-3" id="kode-mak-container" style="display:none;">
                            <label for="kode_mak" class="form-label fw-bold">Kode MAK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="kode_mak" name="kode_mak" placeholder="Masukkan kode MAK">
                            <small class="text-muted">Kode Mata Anggaran Kegiatan harus diisi untuk menyetujui usulan.</small>
                        </div>

                        <!-- Catatan Penolakan -->
                        <div class="mb-3" id="catatan-penolakan-container" style="display:none;">
                            <label for="catatan_penolakan" class="form-label fw-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="catatan_penolakan" name="catatan_penolakan" rows="3" placeholder="Jelaskan alasan penolakan usulan ini..."></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-danger" onclick="showTolakForm()">
                                <i class="bi bi-x-circle"></i> Tolak Usulan
                            </button>
                            <button type="button" class="btn btn-warning" onclick="showRevisiForm()">
                                <i class="bi bi-arrow-clockwise"></i> Minta Revisi
                            </button>
                            <button type="button" class="btn btn-success" onclick="showSetujuForm()">
                                <i class="bi bi-check-circle"></i> Setuju Usulan
                            </button>
                        </div>

                        <div id="action-buttons" class="mt-3" style="display:none;">
                            <hr>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" onclick="cancelAction()">
                                    <i class="bi bi-x"></i> Batal
                                </button>
                                <button type="submit" class="btn btn-primary" id="submit-button">
                                    <i class="bi bi-send"></i> Kirim Keputusan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informasi Pengusul -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-person"></i> Informasi Pengusul</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>Nama:</strong> <?= htmlspecialchars($usulan['nama_pengusul'] ?? '') ?></p>
                    <p class="mb-1"><strong>Jurusan:</strong> <?= htmlspecialchars($usulan['nama_jurusan'] ?? '') ?></p>
                    <p class="mb-0"><strong>Tanggal Pengajuan:</strong> 
                        <?= isset($usulan['tanggal_pengajuan']) ? date('d M Y H:i', strtotime($usulan['tanggal_pengajuan'])) : '-' ?>
                    </p>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
let currentAction = '';

function showSetujuForm() {
    currentAction = 'setuju';
    document.getElementById('kode-mak-container').style.display = 'block';
    document.getElementById('catatan-penolakan-container').style.display = 'none';
    document.getElementById('action-buttons').style.display = 'block';
    document.getElementById('form-verifikasi').action = '/verifikasi/setuju';
    
    // Clear catatan fields untuk setuju
    document.getElementById('catatan-kak').value = '';
    document.getElementById('catatan-iku').value = '';
    document.getElementById('catatan-rab').value = '';
}

function showRevisiForm() {
    currentAction = 'revisi';
    document.getElementById('kode-mak-container').style.display = 'none';
    document.getElementById('catatan-penolakan-container').style.display = 'none';
    document.getElementById('action-buttons').style.display = 'block';
    document.getElementById('form-verifikasi').action = '/verifikasi/revisi';
    
    alert('Berikan catatan revisi pada bagian KAK, IKU, atau RAB yang perlu diperbaiki.');
}

function showTolakForm() {
    currentAction = 'tolak';
    document.getElementById('kode-mak-container').style.display = 'none';
    document.getElementById('catatan-penolakan-container').style.display = 'block';
    document.getElementById('action-buttons').style.display = 'block';
    document.getElementById('form-verifikasi').action = '/verifikasi/tolak';
}

function cancelAction() {
    currentAction = '';
    document.getElementById('kode-mak-container').style.display = 'none';
    document.getElementById('catatan-penolakan-container').style.display = 'none';
    document.getElementById('action-buttons').style.display = 'none';
    document.getElementById('form-verifikasi').reset();
}

document.getElementById('form-verifikasi').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Collect all catatan
    document.getElementById('hidden-catatan-kak').value = document.getElementById('catatan-kak').value;
    document.getElementById('hidden-catatan-iku').value = document.getElementById('catatan-iku').value;
    document.getElementById('hidden-catatan-rab').value = document.getElementById('catatan-rab').value;
    
    if (currentAction === 'setuju') {
        const kodeMak = document.getElementById('kode_mak').value.trim();
        if (!kodeMak) {
            alert('Kode MAK harus diisi untuk menyetujui usulan.');
            return;
        }
        if (confirm('Apakah Anda yakin ingin menyetujui usulan ini?')) {
            this.submit();
        }
    } else if (currentAction === 'revisi') {
        const catatanKak = document.getElementById('catatan-kak').value.trim();
        const catatanIku = document.getElementById('catatan-iku').value.trim();
        const catatanRab = document.getElementById('catatan-rab').value.trim();
        
        if (!catatanKak && !catatanIku && !catatanRab) {
            alert('Berikan minimal satu catatan revisi pada bagian KAK, IKU, atau RAB.');
            return;
        }
        
        if (confirm('Apakah Anda yakin ingin meminta revisi untuk usulan ini?')) {
            this.submit();
        }
    } else if (currentAction === 'tolak') {
        const catatanPenolakan = document.getElementById('catatan_penolakan').value.trim();
        if (!catatanPenolakan) {
            alert('Alasan penolakan harus diisi.');
            return;
        }
        if (confirm('Apakah Anda yakin ingin menolak usulan ini?')) {
            this.submit();
        }
    }
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>