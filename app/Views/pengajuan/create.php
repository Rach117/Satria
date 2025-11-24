<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-4xl mx-auto">
    <div class="mb-8">
        <a href="/pengajuan/list" class="text-slate-500 hover:text-blue-600 text-sm flex items-center font-bold transition-colors mb-4">
            <span class="material-icons text-sm mr-1">arrow_back</span> Kembali
        </a>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pengajuan Kegiatan</h1>
        <p class="text-slate-500 mt-1">Lengkapi data pelaksanaan kegiatan di bawah ini.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-8">
        <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-600 rounded-r-lg">
            <h3 class="font-bold text-blue-900 mb-1"><?php echo htmlspecialchars($usulan['nama_kegiatan']); ?></h3>
            <p class="text-sm text-blue-700">Anggaran: Rp <?php echo number_format($usulan['nominal_rab'], 0, ',', '.'); ?></p>
        </div>

        <form method="POST" action="/pengajuan/store" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="usulan_id" value="<?php echo $usulan['id']; ?>">

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Penanggung Jawab Kegiatan</label>
                <input type="text" name="penanggung_jawab" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all" placeholder="Nama lengkap penanggung jawab">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Pelaksana Kegiatan</label>
                <input type="text" name="pelaksana" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all" placeholder="Nama pelaksana / tim pelaksana">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Waktu Mulai</label>
                    <input type="date" name="waktu_mulai" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Waktu Selesai</label>
                    <input type="date" name="waktu_selesai" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Surat Pengantar (PDF/DOC)</label>
                <input type="file" name="surat_pengantar" accept=".pdf,.doc,.docx" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all text-sm">
                <p class="text-xs text-slate-400 mt-1">* Opsional. Format: PDF, DOC, DOCX. Max 2MB</p>
            </div>

            <div class="flex gap-4 pt-4">
                <a href="/pengajuan/list" class="flex-1 px-6 py-3 bg-white border border-slate-300 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition-all text-center">
                    Batal
                </a>
                <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg transition-all">
                    <span class="material-icons text-sm mr-2 align-middle">send</span> Ajukan Kegiatan
                </button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__.'/../partials/footer.php'; ?>