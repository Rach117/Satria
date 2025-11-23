<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-5xl mx-auto">
    <div class="mb-2">
        <a href="/master" class="text-slate-500 hover:text-blue-600 text-sm flex items-center font-medium transition-colors">
            <span class="material-icons text-sm mr-1">arrow_back</span> Kembali ke Menu Utama
        </a>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Master Data Jurusan</h1>
            <p class="text-slate-500 mt-1">Daftar referensi Program Studi dan Unit Kerja.</p>
        </div>
        <button onclick="openModal()" class="inline-flex items-center px-5 py-2.5 bg-blue-700 text-white text-sm font-bold rounded-lg shadow-lg hover:bg-blue-800 hover:-translate-y-0.5 transition-all">
            <span class="material-icons text-sm mr-2">add</span> Tambah Jurusan
        </button>
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
                        <th class="px-6 py-4 w-16 text-center">ID</th>
                        <th class="px-6 py-4">Nama Jurusan / Unit</th>
                        <th class="px-6 py-4 text-right w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($jurusan)): ?>
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-slate-400">
                                <span class="material-icons text-4xl mb-2 block">folder_off</span>
                                Belum ada data jurusan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($jurusan as $j): ?>
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4 text-center text-slate-400 font-mono text-xs bg-slate-50/50">
                                <?php echo $j['id']; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-700 text-base group-hover:text-blue-700 transition-colors">
                                    <?php echo htmlspecialchars($j['nama_jurusan']); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button onclick="editJurusan(<?php echo htmlspecialchars(json_encode($j)); ?>)" class="w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition-all" title="Edit">
                                        <span class="material-icons text-sm">edit</span>
                                    </button>
                                    
                                    <form action="/master/jurusan/delete" method="POST" onsubmit="return confirm('Hapus jurusan ini? Warning: User terkait mungkin error.')">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <input type="hidden" name="id" value="<?php echo $j['id']; ?>">
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition-all" title="Hapus">
                                            <span class="material-icons text-sm">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalJurusan" class="fixed inset-0 z-[99] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">
            
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800 flex items-center" id="modalTitle">
                    <span class="material-icons text-blue-600 mr-2">add_business</span> Tambah Jurusan
                </h3>
                <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <span class="material-icons">close</span>
                </button>
            </div>

            <form id="formJurusan" action="/master/jurusan/store" method="POST" class="p-6 space-y-4">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="id" id="jurusanId"> 
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nama Jurusan / Unit</label>
                    <input type="text" name="nama_jurusan" id="namaJurusan" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none text-sm font-medium" placeholder="Contoh: Teknik Mesin">
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2.5 border border-slate-300 text-slate-600 font-bold rounded-lg hover:bg-slate-50 text-sm transition-colors">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 text-sm shadow-md transition-colors">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('modalJurusan');
    const form = document.getElementById('formJurusan');
    const title = document.getElementById('modalTitle');
    const inputId = document.getElementById('jurusanId');
    const inputNama = document.getElementById('namaJurusan');

    function openModal() {
        // Reset ke Mode Tambah
        form.action = '/master/jurusan/store';
        title.innerHTML = '<span class="material-icons text-blue-600 mr-2">add_business</span> Tambah Jurusan';
        inputId.value = '';
        inputNama.value = '';
        modal.classList.remove('hidden');
    }

    function editJurusan(data) {
        // Set ke Mode Edit
        form.action = '/master/jurusan/update';
        title.innerHTML = '<span class="material-icons text-amber-600 mr-2">edit</span> Edit Jurusan';
        inputId.value = data.id;
        inputNama.value = data.nama_jurusan;
        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }
</script>
<?php include __DIR__.'/../partials/footer.php'; ?>