<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    <div class="mb-6">
        <a href="/approval" class="text-slate-500 hover:text-indigo-600 text-sm flex items-center font-bold transition-colors">
            <span class="material-icons text-sm mr-1">arrow_back</span> KEMBALI KE ANTRIAN
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
                <div class="flex justify-between items-start mb-6 pb-6 border-b border-slate-100">
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-900 mb-2"><?php echo htmlspecialchars($usulan['nama_kegiatan']); ?></h1>
                        <div class="flex items-center gap-4 text-sm text-slate-500">
                            <span class="flex items-center"><span class="material-icons text-sm mr-1">person</span> <?php echo htmlspecialchars($usulan['username']); ?></span>
                            <span class="flex items-center"><span class="material-icons text-sm mr-1">calendar_today</span> <?php echo date('d M Y'); ?></span>
                        </div>
                    </div>
                    <div class="text-right">
                        <label class="text-xs font-bold text-slate-400 uppercase">Nilai Pengajuan</label>
                        <div class="text-2xl font-black text-emerald-600">Rp <?php echo number_format($usulan['nominal_pencairan'], 0, ',', '.'); ?></div>
                    </div>
                </div>

                <div class="prose prose-slate max-w-none">
                    <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-2">Gambaran Umum</h4>
                    <p class="text-slate-600 bg-slate-50 p-4 rounded-xl border border-slate-100 leading-relaxed">
                        <?php echo nl2br(htmlspecialchars($usulan['gambaran_umum'])); ?>
                    </p>
                </div>
                
                <div class="mt-8 grid grid-cols-2 gap-4">
                    <div class="p-4 border border-slate-200 rounded-xl">
                        <label class="text-xs font-bold text-slate-400 uppercase">Kode MAK</label>
                        <div class="font-mono font-bold text-slate-700 mt-1"><?php echo $usulan['kode_mak'] ?: '-'; ?></div>
                    </div>
                    <div class="p-4 border border-slate-200 rounded-xl">
                        <label class="text-xs font-bold text-slate-400 uppercase">Penerima Manfaat</label>
                        <div class="font-bold text-slate-700 mt-1"><?php echo htmlspecialchars($usulan['penerima_manfaat']); ?></div>
                    </div>
                </div>
            </div>
            
            <a href="/usulan/detail?id=<?php echo $usulan['id']; ?>" target="_blank" class="block text-center py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-colors text-sm">
                Lihat Dokumen RAB Lengkap <span class="material-icons text-sm ml-1 align-middle">open_in_new</span>
            </a>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg border border-indigo-100 p-6 sticky top-24">
                <div class="flex items-center mb-6 text-indigo-700">
                    <span class="material-icons mr-2">security</span>
                    <h3 class="font-extrabold text-lg">Keputusan Pimpinan</h3>
                </div>
                
                <form method="post" action="/approval/aksi?id=<?php echo $usulan['id']; ?>" class="space-y-4">
                    <div class="flex items-center mb-6 text-indigo-700">
                    <span class="material-icons mr-2">security</span>
                    <h3 class="font-extrabold text-lg">Keputusan Pimpinan</h3>
                </div>
                
                <form method="post" action="/approval/aksi?id=<?php echo $usulan['id']; ?>" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Catatan Keputusan</label>
                        <textarea name="catatan" rows="4" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all text-sm" placeholder="Tuliskan arahan atau alasan jika direvisi..."></textarea>
                    </div>

                    <div class="space-y-3 pt-2">
                        <button type="submit" name="aksi" value="acc" class="w-full py-3.5 bg-emerald-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/30 hover:bg-emerald-700 hover:-translate-y-0.5 transition-all flex justify-center items-center">
                            <span class="material-icons text-sm mr-2">check_circle</span> SETUJUI (ACC)
                        </button>
                        
                        <button type="submit" name="aksi" value="revisi" class="w-full py-3.5 bg-white border-2 border-amber-500 text-amber-600 font-bold rounded-xl hover:bg-amber-50 transition-all flex justify-center items-center">
                            <span class="material-icons text-sm mr-2">edit</span> Minta Revisi
                        </button>
                    </div>
                    
                    <p class="text-xs text-slate-400 text-center mt-4 leading-tight">
                        Dengan menyetujui, Anda bertanggung jawab atas substansi kegiatan ini sesuai wewenang jabatan.
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__.'/../partials/footer.php'; ?>