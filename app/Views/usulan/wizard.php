<?php include __DIR__.'/../partials/sidebar.php'; ?>

<?php
// [ELITE LOGIC] Tentukan Mode: Create vs Edit
$isEditMode = isset($isEdit) && $isEdit;
$actionUrl = $isEditMode ? '/usulan/update?id=' . $usulan['id'] : '/usulan/create';
$title = $isEditMode ? 'Edit Usulan Kegiatan' : 'Buat Usulan Baru';
$subtitle = $isEditMode ? 'Lakukan revisi atau pembaruan data usulan Anda.' : 'Lengkapi formulir di bawah ini untuk mengajukan anggaran kegiatan.';
?>

<div class="p-8 max-w-6xl mx-auto">
    
    <div class="mb-10 text-center">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?php echo $title; ?></h1>
        <p class="text-slate-500 mt-2"><?php echo $subtitle; ?></p>
    </div>

    <div class="flex items-center justify-center mb-12">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold shadow-lg shadow-blue-600/30 ring-4 ring-blue-50">1</div>
            <div class="w-24 h-1 bg-blue-600 mx-2 rounded"></div>
            <div class="w-10 h-10 rounded-full bg-white border-2 border-blue-600 text-blue-600 flex items-center justify-center font-bold">2</div>
            <div class="w-24 h-1 bg-slate-200 mx-2 rounded"></div>
            <div class="w-10 h-10 rounded-full bg-white border-2 border-slate-200 text-slate-300 flex items-center justify-center font-bold">3</div>
        </div>
    </div>

    <form id="usulanForm" method="post" action="<?php echo $actionUrl; ?>" enctype="multipart/form-data" class="space-y-8">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <div class="bg-slate-50/50 px-8 py-6 border-b border-slate-100 flex items-center">
                <span class="material-icons text-blue-600 mr-3">assignment</span>
                <h2 class="text-lg font-bold text-slate-800">Informasi Dasar (TOR)</h2>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" required 
                           value="<?php echo $isEditMode ? htmlspecialchars($usulan['nama_kegiatan']) : ''; ?>"
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all font-medium" 
                           placeholder="Contoh: Workshop Teknologi 4.0">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Penerima Manfaat</label>
                    <input type="text" name="penerima_manfaat" required 
                           value="<?php echo $isEditMode ? htmlspecialchars($usulan['penerima_manfaat']) : ''; ?>"
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all" 
                           placeholder="Contoh: 50 Mahasiswa TI">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Target Luaran</label>
                    <input type="text" name="target_luaran" 
                           value="<?php echo $isEditMode ? htmlspecialchars($usulan['target_luaran'] ?? '') : ''; ?>"
                           class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all" 
                           placeholder="Contoh: Sertifikat Kompetensi">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Gambaran Umum & Latar Belakang</label>
                    <textarea name="gambaran_umum" required rows="4" 
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all" 
                              placeholder="Jelaskan mengapa kegiatan ini penting..."><?php echo $isEditMode ? htmlspecialchars($usulan['gambaran_umum']) : ''; ?></textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
             <div class="bg-slate-50/50 px-8 py-6 border-b border-slate-100 flex items-center">
                <span class="material-icons text-emerald-600 mr-3">analytics</span>
                <h2 class="text-lg font-bold text-slate-800">Indikator Kinerja (IKU)</h2>
            </div>
            <div class="p-8">
                <label class="block text-sm font-bold text-slate-700 mb-3">Pilih IKU Terkait (Multi-select)</label>
                <select name="iku_id[]" multiple required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 h-64 text-sm">
                    <?php foreach ($iku as $i): 
                        // Cek apakah selected (Logic Edit)
                        $isSelected = ($isEditMode && isset($selectedIku) && in_array($i['id'], $selectedIku)) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $i['id']; ?>" <?php echo $isSelected; ?> class="p-3 border-b border-slate-50 hover:bg-blue-50 cursor-pointer rounded">
                            • <?php echo htmlspecialchars($i['deskripsi_iku']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="text-xs text-slate-400 mt-2">* Tahan tombol CTRL (Windows) atau CMD (Mac) untuk memilih lebih dari satu.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
             <div class="bg-slate-50/50 px-8 py-6 border-b border-slate-100 flex justify-between items-center">
                <div class="flex items-center">
                    <span class="material-icons text-amber-600 mr-3">payments</span>
                    <h2 class="text-lg font-bold text-slate-800">Rencana Anggaran Biaya (RAB)</h2>
                </div>
                <button type="button" id="addRow" class="text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-lg transition-colors flex items-center border border-blue-200">
                    <span class="material-icons text-sm mr-1">add</span> Tambah Item
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs">
                        <tr>
                            <th class="px-4 py-3 w-48 pl-8">Kategori</th>
                            <th class="px-4 py-3">Uraian Belanja</th>
                            <th class="px-4 py-3 w-24">Vol</th>
                            <th class="px-4 py-3 w-32">Satuan</th>
                            <th class="px-4 py-3 w-40">Harga (@)</th>
                            <th class="px-4 py-3 w-40">Total</th>
                            <th class="px-4 py-3 w-10"></th>
                        </tr>
                    </thead>
                    <tbody id="rabBody" class="divide-y divide-slate-100 bg-white">
                        <?php 
                        // [ELITE LOGIC] Render Baris RAB
                        // Jika Edit Mode & ada data RAB, looping data tersebut.
                        // Jika Create Mode, tampilkan 1 baris kosong.
                        $rowsToRender = ($isEditMode && !empty($rabData)) ? $rabData : [null];
                        
                        foreach($rowsToRender as $row): 
                        ?>
                        <tr class="group hover:bg-slate-50 transition-colors">
                            <td class="p-3 pl-8">
                                <select name="kategori_id[]" class="w-full border-slate-200 rounded-lg text-sm focus:border-blue-500 focus:ring-0 bg-slate-50">
                                    <?php foreach ($kategori as $k): 
                                        $sel = ($row && $row['kategori_id'] == $k['id']) ? 'selected' : '';
                                    ?>
                                        <option value="<?php echo $k['id']; ?>" <?php echo $sel; ?>><?php echo htmlspecialchars($k['nama_kategori']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="p-3">
                                <input type="text" name="uraian[]" value="<?php echo $row ? htmlspecialchars($row['uraian']) : ''; ?>" class="w-full border-slate-200 rounded-lg text-sm focus:border-blue-500 focus:ring-0" required placeholder="Nama item...">
                            </td>
                            <td class="p-3">
                                <input type="number" name="volume[]" value="<?php echo $row ? $row['volume'] : '1'; ?>" class="w-full border-slate-200 rounded-lg text-sm focus:border-blue-500 focus:ring-0 volume text-center" min="1" required>
                            </td>
                            <td class="p-3">
                                <input type="text" name="satuan[]" value="<?php echo $row ? htmlspecialchars($row['satuan']) : ''; ?>" class="w-full border-slate-200 rounded-lg text-sm focus:border-blue-500 focus:ring-0 text-center" required placeholder="Pcs/Rim">
                            </td>
                            <td class="p-3">
                                <input type="number" name="harga_satuan[]" value="<?php echo $row ? $row['harga_satuan'] : ''; ?>" class="w-full border-slate-200 rounded-lg text-sm focus:border-blue-500 focus:ring-0 harga_satuan text-right" min="0" required placeholder="0">
                            </td>
                            <td class="p-3">
                                <input type="text" name="total[]" class="w-full bg-slate-100 border-transparent rounded-lg text-sm font-bold text-slate-700 total cursor-not-allowed text-right" readonly>
                            </td>
                            <td class="p-3 text-center">
                                <button type="button" class="removeRow text-slate-300 hover:text-rose-500 transition-colors p-2 rounded-full hover:bg-rose-50"><span class="material-icons text-lg">delete</span></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="bg-slate-50 px-8 py-4 border-t border-slate-100 text-right">
                 <p class="text-sm text-slate-500">Total Pengajuan: <span class="text-xl font-extrabold text-slate-800 ml-2" id="grandTotal">Rp 0</span></p>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pt-6 pb-20">
             <div class="flex-1 text-sm text-slate-500">
                <span class="font-bold text-rose-500">*</span> Pastikan seluruh data sudah benar sebelum menyimpan.
            </div>
            <a href="/dashboard" class="px-6 py-3 bg-white border border-slate-300 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition-all">Batal</a>
            <button type="submit" class="px-8 py-3 bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-700/30 hover:bg-blue-800 hover:-translate-y-1 transition-all flex items-center">
                <span class="material-icons text-sm mr-2">save</span> 
                <?php echo $isEditMode ? 'Simpan Perubahan' : 'Simpan & Ajukan'; ?>
            </button>
        </div>
    </form>
</div>

<script>
function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
}

function updateTotal(row) {
    const vol = row.querySelector('.volume').valueAsNumber || 0;
    const harga = row.querySelector('.harga_satuan').valueAsNumber || 0;
    const total = vol * harga;
    row.querySelector('.total').value = formatRupiah(total);
    
    // Calc Grand Total
    let grand = 0;
    document.querySelectorAll('#rabBody tr').forEach(r => {
        const v = r.querySelector('.volume').valueAsNumber || 0;
        const h = r.querySelector('.harga_satuan').valueAsNumber || 0;
        grand += (v * h);
    });
    document.getElementById('grandTotal').innerText = formatRupiah(grand);
}

function attachEvents(row) {
    row.querySelectorAll('.volume, .harga_satuan').forEach(input => {
        input.addEventListener('input', () => updateTotal(row));
    });
    row.querySelector('.removeRow').addEventListener('click', () => {
        if (document.querySelectorAll('#rabBody tr').length > 1) {
            row.remove();
            updateTotal(document.querySelector('#rabBody tr')); // Trigger recalc
        }
    });
    
    // [ELITE UX] Trigger calculation on init (for Edit Mode to show totals immediately)
    updateTotal(row);
}

// Init Events for existing rows (Edit Mode or Initial Empty Row)
document.querySelectorAll('#rabBody tr').forEach(row => { attachEvents(row); });

// Add Row Logic
document.getElementById('addRow').addEventListener('click', () => {
    const tbody = document.getElementById('rabBody');
    // Clone the first row to keep structure
    const clone = tbody.rows[0].cloneNode(true);
    
    // Reset values for new row
    clone.querySelectorAll('input').forEach(i => i.value = '');
    clone.querySelector('.volume').value = 1;
    clone.querySelector('.total').value = '';
    
    // Append and attach events
    tbody.appendChild(clone);
    attachEvents(clone);
});
</script>
<?php include __DIR__.'/../partials/footer.php'; ?>