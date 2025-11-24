<?php 
$pageTitle = 'Pengajuan Usulan Kegiatan';
include __DIR__ . '/../partials/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../partials/sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Pengajuan Usulan Kegiatan</h1>
            </div>

            <!-- Step Progress -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="step-item text-center flex-fill" id="step-indicator-1">
                            <div class="step-number active">1</div>
                            <div class="step-label">KAK</div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-item text-center flex-fill" id="step-indicator-2">
                            <div class="step-number">2</div>
                            <div class="step-label">IKU & Renstra</div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-item text-center flex-fill" id="step-indicator-3">
                            <div class="step-number">3</div>
                            <div class="step-label">RAB</div>
                        </div>
                    </div>
                </div>
            </div>

            <form id="wizardForm" method="POST" action="/usulan/save">
                <!-- Step 1: KAK -->
                <div class="wizard-step active" id="step1">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Step 1: Kerangka Acuan Kegiatan (KAK)</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="nama_kegiatan" class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_kegiatan" name="nama_kegiatan" required>
                            </div>

                            <div class="mb-3">
                                <label for="gambaran_umum" class="form-label">Gambaran Umum <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="gambaran_umum" name="gambaran_umum" rows="4" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="penerima_manfaat" class="form-label">Penerima Manfaat <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="penerima_manfaat" name="penerima_manfaat" rows="3" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="strategi_pencapaian" class="form-label">Strategi Pencapaian Keluaran <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="strategi_pencapaian" name="strategi_pencapaian" rows="3" required></textarea>
                            </div>

                            <!-- Metode Pelaksanaan (Multiple) -->
                            <div class="mb-3">
                                <label class="form-label">Metode Pelaksanaan <span class="text-danger">*</span></label>
                                <div id="metode-container">
                                    <div class="input-group mb-2 metode-item">
                                        <input type="text" class="form-control" name="metode_pelaksanaan[]" placeholder="Metode pelaksanaan" required>
                                        <button class="btn btn-outline-danger remove-metode" type="button" style="display:none;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="add-metode">
                                    <i class="bi bi-plus-circle"></i> Tambah Metode
                                </button>
                            </div>

                            <!-- Tahapan Pelaksanaan (Multiple) -->
                            <div class="mb-3">
                                <label class="form-label">Tahapan Pelaksanaan <span class="text-danger">*</span></label>
                                <div id="tahapan-container">
                                    <div class="input-group mb-2 tahapan-item">
                                        <input type="text" class="form-control" name="tahapan_pelaksanaan[]" placeholder="Tahapan pelaksanaan" required>
                                        <button class="btn btn-outline-danger remove-tahapan" type="button" style="display:none;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="add-tahapan">
                                    <i class="bi bi-plus-circle"></i> Tambah Tahapan
                                </button>
                            </div>

                            <!-- Indikator Kinerja (Multiple) -->
                            <div class="mb-3">
                                <label class="form-label">Indikator Kinerja <span class="text-danger">*</span></label>
                                <div id="indikator-container">
                                    <div class="card mb-2 indikator-item">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label class="form-label">Indikator Keberhasilan</label>
                                                    <input type="text" class="form-control" name="indikator_keberhasilan[]" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Bulan Target</label>
                                                    <input type="month" class="form-control" name="bulan_target[]" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Bobot (%)</label>
                                                    <input type="number" class="form-control" name="bobot_keberhasilan[]" min="0" max="100" required>
                                                </div>
                                                <div class="col-md-1 d-flex align-items-end">
                                                    <button class="btn btn-outline-danger remove-indikator" type="button" style="display:none;">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="add-indikator">
                                    <i class="bi bi-plus-circle"></i> Tambah Indikator
                                </button>
                            </div>

                            <!-- Kurun Waktu Pelaksanaan -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" class="btn btn-primary" id="next-step-1">
                            Selanjutnya <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: IKU & Renstra -->
                <div class="wizard-step" id="step2" style="display:none;">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Step 2: Indikator Kinerja Utama (IKU) & Target Renstra</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Pilih IKU yang relevan dengan kegiatan Anda dan tentukan bobot persentasenya.</p>
                            
                            <div id="iku-list">
                                <?php foreach ($ikuList as $iku): ?>
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input iku-checkbox" type="checkbox" 
                                                   name="iku_id[]" value="<?= $iku['id'] ?>" 
                                                   id="iku-<?= $iku['id'] ?>">
                                            <label class="form-check-label" for="iku-<?= $iku['id'] ?>">
                                                <?= htmlspecialchars($iku['nama_iku']) ?>
                                            </label>
                                        </div>
                                        <div class="mt-2 iku-bobot-container" id="bobot-container-<?= $iku['id'] ?>" style="display:none;">
                                            <label class="form-label">Bobot Persentase (%)</label>
                                            <input type="number" class="form-control iku-bobot" 
                                                   name="iku_bobot[<?= $iku['id'] ?>]" 
                                                   min="0" max="100" step="0.1">
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="alert alert-info mt-3">
                                <strong>Total Bobot: <span id="total-bobot">0</span>%</strong>
                                <div class="text-muted small">Total bobot harus 100%</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-secondary" id="prev-step-2">
                            <i class="bi bi-arrow-left"></i> Sebelumnya
                        </button>
                        <button type="button" class="btn btn-primary" id="next-step-2">
                            Selanjutnya <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: RAB -->
                <div class="wizard-step" id="step3" style="display:none;">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Step 3: Rincian Anggaran Biaya (RAB)</h5>
                        </div>
                        <div class="card-body">
                            <!-- Belanja Barang -->
                            <div class="mb-4">
                                <h6 class="text-primary">Belanja Barang</h6>
                                <div id="belanja-barang-container"></div>
                                <button type="button" class="btn btn-sm btn-outline-primary add-rab-item" data-kategori="Belanja Barang">
                                    <i class="bi bi-plus-circle"></i> Tambah Item
                                </button>
                            </div>

                            <!-- Belanja Jasa -->
                            <div class="mb-4">
                                <h6 class="text-primary">Belanja Jasa</h6>
                                <div id="belanja-jasa-container"></div>
                                <button type="button" class="btn btn-sm btn-outline-primary add-rab-item" data-kategori="Belanja Jasa">
                                    <i class="bi bi-plus-circle"></i> Tambah Item
                                </button>
                            </div>

                            <!-- Belanja Perjalanan -->
                            <div class="mb-4">
                                <h6 class="text-primary">Belanja Perjalanan</h6>
                                <div id="belanja-perjalanan-container"></div>
                                <button type="button" class="btn btn-sm btn-outline-primary add-rab-item" data-kategori="Belanja Perjalanan">
                                    <i class="bi bi-plus-circle"></i> Tambah Item
                                </button>
                            </div>

                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="mb-0">Total Anggaran: <strong class="text-primary" id="total-anggaran">Rp 0</strong></h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <button type="button" class="btn btn-secondary" id="prev-step-3">
                            <i class="bi bi-arrow-left"></i> Sebelumnya
                        </button>
                        <div>
                            <button type="submit" name="action" value="draft" class="btn btn-outline-primary">
                                <i class="bi bi-save"></i> Simpan sebagai Draft
                            </button>
                            <button type="submit" name="action" value="submit" class="btn btn-success">
                                <i class="bi bi-send"></i> Ajukan Usulan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>
</div>

<style>
.step-item {
    position: relative;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #dee2e6;
    color: #6c757d;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-bottom: 5px;
}

.step-number.active {
    background: #0d6efd;
    color: white;
}

.step-number.completed {
    background: #198754;
    color: white;
}

.step-line {
    height: 2px;
    background: #dee2e6;
    flex: 1;
    margin: 0 10px;
    margin-top: -25px;
}

.step-label {
    font-size: 0.9rem;
    color: #6c757d;
}

.wizard-step {
    animation: fadeIn 0.3s;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const satuanList = <?= json_encode($satuanList) ?>;

    // Step Navigation
    document.getElementById('next-step-1').addEventListener('click', () => {
        if (validateStep1()) {
            goToStep(2);
        }
    });

    document.getElementById('next-step-2').addEventListener('click', () => {
        if (validateStep2()) {
            goToStep(3);
        }
    });

    document.getElementById('prev-step-2').addEventListener('click', () => goToStep(1));
    document.getElementById('prev-step-3').addEventListener('click', () => goToStep(2));

    function goToStep(step) {
        document.getElementById(`step${currentStep}`).style.display = 'none';
        document.getElementById(`step-indicator-${currentStep}`).querySelector('.step-number').classList.remove('active');
        
        if (step > currentStep) {
            document.getElementById(`step-indicator-${currentStep}`).querySelector('.step-number').classList.add('completed');
        }
        
        currentStep = step;
        document.getElementById(`step${step}`).style.display = 'block';
        document.getElementById(`step-indicator-${step}`).querySelector('.step-number').classList.add('active');
    }

    function validateStep1() {
        const form = document.getElementById('step1');
        if (!form.querySelector('input[name="nama_kegiatan"]').value) {
            alert('Nama kegiatan harus diisi');
            return false;
        }
        return true;
    }

    function validateStep2() {
        const checkedIku = document.querySelectorAll('.iku-checkbox:checked').length;
        if (checkedIku === 0) {
            alert('Pilih minimal 1 IKU');
            return false;
        }

        const totalBobot = parseFloat(document.getElementById('total-bobot').textContent);
        if (Math.abs(totalBobot - 100) > 0.01) {
            alert('Total bobot harus 100%');
            return false;
        }
        return true;
    }

    // Add Metode
    document.getElementById('add-metode').addEventListener('click', function() {
        const container = document.getElementById('metode-container');
        const newItem = document.createElement('div');
        newItem.className = 'input-group mb-2 metode-item';
        newItem.innerHTML = `
            <input type="text" class="form-control" name="metode_pelaksanaan[]" placeholder="Metode pelaksanaan" required>
            <button class="btn btn-outline-danger remove-metode" type="button">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(newItem);
        updateRemoveButtons('metode');
    });

    // Add Tahapan
    document.getElementById('add-tahapan').addEventListener('click', function() {
        const container = document.getElementById('tahapan-container');
        const newItem = document.createElement('div');
        newItem.className = 'input-group mb-2 tahapan-item';
        newItem.innerHTML = `
            <input type="text" class="form-control" name="tahapan_pelaksanaan[]" placeholder="Tahapan pelaksanaan" required>
            <button class="btn btn-outline-danger remove-tahapan" type="button">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(newItem);
        updateRemoveButtons('tahapan');
    });

    // Add Indikator
    document.getElementById('add-indikator').addEventListener('click', function() {
        const container = document.getElementById('indikator-container');
        const newItem = document.createElement('div');
        newItem.className = 'card mb-2 indikator-item';
        newItem.innerHTML = `
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        <label class="form-label">Indikator Keberhasilan</label>
                        <input type="text" class="form-control" name="indikator_keberhasilan[]" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Bulan Target</label>
                        <input type="month" class="form-control" name="bulan_target[]" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Bobot (%)</label>
                        <input type="number" class="form-control" name="bobot_keberhasilan[]" min="0" max="100" required>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button class="btn btn-outline-danger remove-indikator" type="button">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(newItem);
        updateRemoveButtons('indikator');
    });

    // Remove handlers
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-metode') || e.target.closest('.remove-metode')) {
            e.target.closest('.metode-item').remove();
            updateRemoveButtons('metode');
        }
        if (e.target.classList.contains('remove-tahapan') || e.target.closest('.remove-tahapan')) {
            e.target.closest('.tahapan-item').remove();
            updateRemoveButtons('tahapan');
        }
        if (e.target.classList.contains('remove-indikator') || e.target.closest('.remove-indikator')) {
            e.target.closest('.indikator-item').remove();
            updateRemoveButtons('indikator');
        }
    });

    function updateRemoveButtons(type) {
        const items = document.querySelectorAll(`.${type}-item`);
        items.forEach((item, index) => {
            const btn = item.querySelector(`.remove-${type}`);
            if (btn) {
                btn.style.display = items.length > 1 ? 'block' : 'none';
            }
        });
    }

    // IKU Checkbox & Bobot
    document.querySelectorAll('.iku-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const bobotContainer = document.getElementById(`bobot-container-${this.value}`);
            if (this.checked) {
                bobotContainer.style.display = 'block';
                bobotContainer.querySelector('.iku-bobot').required = true;
            } else {
                bobotContainer.style.display = 'none';
                bobotContainer.querySelector('.iku-bobot').required = false;
                bobotContainer.querySelector('.iku-bobot').value = '';
            }
            updateTotalBobot();
        });
    });

    document.querySelectorAll('.iku-bobot').forEach(input => {
        input.addEventListener('input', updateTotalBobot);
    });

    function updateTotalBobot() {
        let total = 0;
        document.querySelectorAll('.iku-checkbox:checked').forEach(checkbox => {
            const bobotInput = document.querySelector(`input[name="iku_bobot[${checkbox.value}]"]`);
            if (bobotInput && bobotInput.value) {
                total += parseFloat(bobotInput.value);
            }
        });
        document.getElementById('total-bobot').textContent = total.toFixed(1);
    }

    // RAB Items
    document.querySelectorAll('.add-rab-item').forEach(btn => {
        btn.addEventListener('click', function() {
            const kategori = this.dataset.kategori;
            const containerId = kategori.toLowerCase().replace(/ /g, '-') + '-container';
            const container = document.getElementById(containerId);
            
            const newItem = document.createElement('div');
            newItem.className = 'card mb-2 rab-item';
            newItem.innerHTML = `
                <div class="card-body">
                    <input type="hidden" name="rab_kategori[]" value="${kategori}">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Uraian</label>
                            <input type="text" class="form-control" name="rab_uraian[]" required>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="form-label">Satuan</label>
                            <select class="form-select" name="rab_satuan[]" required>
                                <option value="">Pilih</option>
                                ${satuanList.map(s => `<option value="${s.nama_satuan}">${s.nama_satuan}</option>`).join('')}
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="form-label">Jumlah</label>
                            <input type="number" class="form-control rab-jumlah" name="rab_jumlah[]" min="1" required>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="form-label">Harga Satuan</label>
                            <input type="number" class="form-control rab-harga" name="rab_harga_satuan[]" min="0" required>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="form-label">Total</label>
                            <input type="text" class="form-control rab-total" readonly>
                        </div>
                        <div class="col-md-1 d-flex align-items-end mb-2">
                            <button class="btn btn-outline-danger remove-rab-item" type="button">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(newItem);
        });
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-rab-item') || e.target.closest('.remove-rab-item')) {
            e.target.closest('.rab-item').remove();
            updateTotalAnggaran();
        }
    });

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('rab-jumlah') || e.target.classList.contains('rab-harga')) {
            const card = e.target.closest('.card-body');
            const jumlah = parseFloat(card.querySelector('.rab-jumlah').value) || 0;
            const harga = parseFloat(card.querySelector('.rab-harga').value) || 0;
            const total = jumlah * harga;
            card.querySelector('.rab-total').value = 'Rp ' + total.toLocaleString('id-ID');
            updateTotalAnggaran();
        }
    });

    function updateTotalAnggaran() {
        let total = 0;
        document.querySelectorAll('.rab-item').forEach(item => {
            const jumlah = parseFloat(item.querySelector('.rab-jumlah').value) || 0;
            const harga = parseFloat(item.querySelector('.rab-harga').value) || 0;
            total += jumlah * harga;
        });
        document.getElementById('total-anggaran').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>