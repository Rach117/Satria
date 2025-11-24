<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-6xl mx-auto">
    <div class="mb-2">
        <a href="/master" class="text-slate-500 hover:text-blue-600 text-sm flex items-center font-medium transition-colors">
            <span class="material-icons text-sm mr-1">arrow_back</span> Kembali ke Menu Utama
        </a>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Master Satuan Anggaran</h1>
            <p class="text-slate-500 mt-1">Kelola satuan yang digunakan dalam RAB (Orang, Paket, LS, dll).</p>
        </div>
        <?php if (!isset($readonly) || !$readonly): ?>
        <button onclick="openModal()" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-lg shadow-lg hover:bg-indigo-700 hover:-translate-y-0.5 transition-all">
            <span class="material-icons text-sm mr-2">add_box</span> Tambah Satuan Baru
        </button>
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION['toast'])): ?>
        <div class="mb-4 p-4 rounded-lg bg-<?php echo $_SESSION['toast']['type'] == 'success' ? 'emerald' : 'rose'; ?>-100 text-<?php echo $_SESSION['toast']['type'] == 'success' ? 'emerald' : 'rose'; ?>-700 border border-<?php echo $_SESSION['toast']['type'] == 'success' ? 'emerald' : 'rose'; ?>-200 text-sm font-bold">
            <?php echo $_SESSION['toast']['msg']; unset($_SESSION['toast']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 w-12 text-center">No</th>
                        <th class="px-6 py-4">Nama Satuan</th>
                        <th class="px-6 py-4 text-center w-32">Status</th>
                        <?php if (!isset($readonly) || !$readonly): ?>
                        <th class="px-6 py-4 text-right w-32">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($satuan_list)): ?>
                         <tr>
                            <td colspan="<?php echo (!isset($readonly) || !$readonly) ? '4' : '3'; ?>" class="px-6 py-12 text-center text-slate-400">
                                <span class="material-icons text-4xl mb-2 block">inventory_2</span>
                                Belum ada data satuan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($satuan_list as $index => $s): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-center text-slate-400 font-mono text-xs">
                                <?php echo $index + 1; ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-slate-700 font-bold text-base"><?php echo htmlspecialchars($s['nama_satuan']); ?></span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if($s['is_active']): ?>
                                    <span class="inline-flex px-2.5 py-1 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 uppercase">Aktif</span>
                                <?php else: ?>
                                    <span class="inline-flex px-2.5 py-1 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 uppercase">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <?php if (!isset($readonly) || !$readonly): ?>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button onclick="editSatuan(<?php echo htmlspecialchars(json_encode($s)); ?>)" class="text-slate-400 hover:text-blue-600 transition-colors p-1" title="Edit">
                                        <span class="material-icons text-sm">edit</span>
                                    </button>
                                    
                                    <form action="/master/satuan/toggle" method="POST" class="inline-block">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <input type="hidden" name="satuan_id" value="<?php echo $s['id']; ?>">
                                        <button type="submit" class="text-slate-400 hover:text-amber-600 transition-colors p-1" title="<?php echo $s['is_active'] ? 'Nonaktifkan' : 'Aktifkan'; ?>">
                                            <span class="material-icons text-sm"><?php echo $s['is_active'] ? 'visibility_off' : 'visibility'; ?></span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if (!isset($readonly) || !$readonly): ?>
    <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-100">
        <div class="flex items-start">
            <span class="material-icons text-blue-600 mr-3 mt-0.5">info</span>
            <div class="text-sm text-blue-800">
                <p class="font-bold mb-1">Catatan Penting:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Satuan yang dinonaktifkan tidak akan muncul di form RAB</li>
                    <li>Satuan tidak dapat dihapus jika sudah digunakan dalam RAB yang ada</li>
                    <li>Contoh satuan: Orang, Paket, Unit, LS (Lump Sum), PP (Pergi Pulang), dll</li>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php if (!isset($readonly) || !$readonly): ?>
<!-- Modal -->
<div id="modalSatuan" class="fixed inset-0 z-[99] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-100">
            
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800 flex items-center" id="modalTitle">
                    <span class="material-icons text-indigo-600 mr-2">add_box</span> Tambah Satuan
                </h3>
                <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <span class="material-icons">close</span>
                </button>
            </div>

            <form id="formSatuan" action="/master/satuan/store" method="POST" class="p-6 space-y-4">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="satuan_id" id="satuanId">
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Satuan</label>
                    <input type="text" name="nama_satuan" id="namaSatuan" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 outline-none text-sm font-medium" placeholder="Contoh: Orang, Paket, LS, dll">
                    <p class="text-xs text-slate-400 mt-1">* Harus unik, tidak boleh duplikat dengan satuan lain</p>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2.5 border border-slate-300 text-slate-600 font-bold rounded-lg hover:bg-slate-50 text-sm transition-colors">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 text-sm shadow-md transition-colors">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('modalSatuan');
    const form = document.getElementById('formSatuan');
    const title = document.getElementById('modalTitle');
    const inputId = document.getElementById('satuanId');
    const inputNama = document.getElementById('namaSatuan');

    function openModal() {
        form.action = '/master/satuan/store';
        title.innerHTML = '<span class="material-icons text-indigo-600 mr-2">add_box</span> Tambah Satuan';
        inputId.value = '';
        inputNama.value = '';
        modal.classList.remove('hidden');
    }

    function editSatuan(data) {
        form.action = '/master/satuan/update';
        title.innerHTML = '<span class="material-icons text-amber-600 mr-2">edit</span> Edit Satuan';
        inputId.value = data.id;
        inputNama.value = data.nama_satuan;
        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }
</script>
<?php endif; ?>

<?php include __DIR__.'/../partials/footer.php'; ?>