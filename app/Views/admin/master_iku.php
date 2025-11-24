<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-6xl mx-auto">
    <div class="mb-2">
        <a href="/master" class="text-slate-500 hover:text-blue-600 text-sm flex items-center font-medium transition-colors">
            <span class="material-icons text-sm mr-1">arrow_back</span> Kembali ke Menu Utama
        </a>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Master Indikator Kinerja Utama (IKU)</h1>
            <p class="text-slate-500 mt-1">Kelola IKU yang tersedia untuk pemilihan usulan kegiatan.</p>
        </div>
        <?php if (!isset($readonly) || !$readonly): ?>
        <button onclick="openModal()" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-lg shadow-lg hover:bg-emerald-700 hover:-translate-y-0.5 transition-all">
            <span class="material-icons text-sm mr-2">add_circle</span> Tambah IKU Baru
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
                        <th class="px-6 py-4">Deskripsi IKU</th>
                        <th class="px-6 py-4 text-center w-32">Status</th>
                        <?php if (!isset($readonly) || !$readonly): ?>
                        <th class="px-6 py-4 text-right w-32">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($iku_list)): ?>
                         <tr>
                            <td colspan="<?php echo (!isset($readonly) || !$readonly) ? '4' : '3'; ?>" class="px-6 py-12 text-center text-slate-400">
                                <span class="material-icons text-4xl mb-2 block">assignment_late</span>
                                Belum ada data IKU.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($iku_list as $index => $i): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-center text-slate-400 font-mono text-xs">
                                <?php echo $index + 1; ?>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-slate-700 font-medium leading-relaxed"><?php echo htmlspecialchars($i['deskripsi_iku']); ?></p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if($i['is_active']): ?>
                                    <span class="inline-flex px-2.5 py-1 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 uppercase">Aktif</span>
                                <?php else: ?>
                                    <span class="inline-flex px-2.5 py-1 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 uppercase">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <?php if (!isset($readonly) || !$readonly): ?>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button onclick="editIku(<?php echo htmlspecialchars(json_encode($i)); ?>)" class="text-slate-400 hover:text-blue-600 transition-colors p-1" title="Edit">
                                        <span class="material-icons text-sm">edit</span>
                                    </button>
                                    
                                    <form action="/master/iku/toggle" method="POST" class="inline-block">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <input type="hidden" name="iku_id" value="<?php echo $i['id']; ?>">
                                        <button type="submit" class="text-slate-400 hover:text-amber-600 transition-colors p-1" title="<?php echo $i['is_active'] ? 'Nonaktifkan' : 'Aktifkan'; ?>">
                                            <span class="material-icons text-sm"><?php echo $i['is_active'] ? 'visibility_off' : 'visibility'; ?></span>
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
</div>

<?php if (!isset($readonly) || !$readonly): ?>
<!-- Modal -->
<div id="modalIku" class="fixed inset-0 z-[99] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">
            
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800 flex items-center" id="modalTitle">
                    <span class="material-icons text-emerald-600 mr-2">add_circle</span> Tambah IKU
                </h3>
                <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <span class="material-icons">close</span>
                </button>
            </div>

            <form id="formIku" action="/master/iku/store" method="POST" class="p-6 space-y-4">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="iku_id" id="ikuId">
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Deskripsi IKU</label>
                    <textarea name="deskripsi_iku" id="deskripsiIku" required rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 outline-none text-sm font-medium" placeholder="Contoh: Jumlah Mahasiswa yang berwirausaha..."></textarea>
                    <p class="text-xs text-slate-400 mt-1">* Harus unik, tidak boleh duplikat dengan IKU lain</p>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2.5 border border-slate-300 text-slate-600 font-bold rounded-lg hover:bg-slate-50 text-sm transition-colors">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 text-sm shadow-md transition-colors">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('modalIku');
    const form = document.getElementById('formIku');
    const title = document.getElementById('modalTitle');
    const inputId = document.getElementById('ikuId');
    const inputDeskripsi = document.getElementById('deskripsiIku');

    function openModal() {
        form.action = '/master/iku/store';
        title.innerHTML = '<span class="material-icons text-emerald-600 mr-2">add_circle</span> Tambah IKU';
        inputId.value = '';
        inputDeskripsi.value = '';
        modal.classList.remove('hidden');
    }

    function editIku(data) {
        form.action = '/master/iku/update';
        title.innerHTML = '<span class="material-icons text-amber-600 mr-2">edit</span> Edit IKU';
        inputId.value = data.id;
        inputDeskripsi.value = data.deskripsi_iku;
        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }
</script>
<?php endif; ?>

<?php include __DIR__.'/../partials/footer.php'; ?>