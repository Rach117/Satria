<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    <div class="mb-6">
        <a href="/verifikasi" class="text-slate-500 hover:text-emerald-600 text-sm flex items-center font-bold transition-colors">
            <span class="material-icons text-sm mr-1">arrow_back</span> KEMBALI KE DAFTAR
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
                <div class="flex justify-between items-start border-b border-slate-100 pb-6 mb-6">
                    <div>
                        <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-1 block">Draft Usulan</span>
                        <h1 class="text-2xl font-extrabold text-slate-900"><?php echo htmlspecialchars($usulan['nama_kegiatan']); ?></h1>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-slate-400 uppercase font-bold">Estimasi Biaya</div>
                        <div class="text-xl font-black text-slate-700">Rp <?php echo number_format($usulan['nominal_pencairan'],0,',','.'); ?></div>
                    </div>
                </div>
                
                <div class="prose prose-slate max-w-none mb-8">
                    <h4 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-2">Substansi Kegiatan</h4>
                    <p class="text-slate-600 leading-relaxed bg-slate-50 p-5 rounded-xl border border-slate-100 text-sm">
                        <?php echo nl2br(htmlspecialchars($usulan['gambaran_umum'])); ?>
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="p-4 border border-slate-200 rounded-xl bg-white">
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Penerima Manfaat</label>
                        <span class="font-bold text-slate-700"><?php echo htmlspecialchars($usulan['penerima_manfaat']); ?></span>
                    </div>
                    <div class="p-4 border border-slate-200 rounded-xl bg-white">
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Pengusul</label>
                        <span class="font-bold text-slate-700"><?php echo htmlspecialchars($usulan['username']); ?></span>
                    </div>
                </div>
                
                <a href="/usulan/detail?id=<?php echo $usulan['id']; ?>" target="_blank" class="flex items-center justify-center p-4 border-2 border-dashed border-slate-300 rounded-xl hover:border-emerald-400 hover:bg-emerald-50 transition-all text-slate-500 hover:text-emerald-700 font-bold text-sm group">
                    <span class="material-icons mr-2 group-hover:scale-110 transition-transform">description</span>
                    Lihat Detail RAB & Dokumen Pendukung
                </a>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg border border-emerald-100 p-6 sticky top-24">
                <div class="flex items-center mb-6 text-emerald-700">
                    <span class="material-icons mr-2">fact_check</span>
                    <h3 class="font-extrabold text-lg">Konsol Verifikasi</h3>
                </div>
                
                <form method="post" action="/verifikasi/aksi?id=<?php echo $usulan['id']; ?>" class="space-y-5">
                    
                    <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-100">
                        <label class="block text-xs font-bold text-emerald-800 mb-2 uppercase">Kode Mata Anggaran (MAK)</label>
                        <input type="text" name="kode_mak" class="w-full px-3 py-2 border border-emerald-300 rounded-lg focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-600 text-sm font-mono font-bold placeholder-emerald-300" placeholder="XXX.XX.XX" value="<?php echo $usulan['kode_mak'] ?? ''; ?>">
                        <p class="text-[10px] text-emerald-600 mt-1">* Wajib diisi jika menyetujui.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Catatan Perbaikan / Penolakan</label>
                        <textarea name="catatan" rows="4" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition-all text-sm" placeholder="Instruksi revisi untuk pengusul..."></textarea>
                    </div>

                    <hr class="border-slate-100">

                    <div class="space-y-3">
                        <button type="submit" name="aksi" value="setuju" class="w-full py-3 bg-emerald-600 text-white font-bold rounded-xl shadow-lg hover:bg-emerald-700 hover:-translate-y-0.5 transition-all flex justify-center items-center">
                            <span class="material-icons text-sm mr-2">check_circle</span> Valid & Teruskan
                        </button>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <button type="submit" name="aksi" value="revisi" class="py-3 bg-amber-100 text-amber-700 font-bold rounded-xl hover:bg-amber-200 transition-all flex justify-center items-center">
                                <span class="material-icons text-sm mr-1">edit</span> Revisi
                            </button>
                            <button type="submit" name="aksi" value="tolak" class="py-3 bg-rose-100 text-rose-700 font-bold rounded-xl hover:bg-rose-200 transition-all flex justify-center items-center">
                                <span class="material-icons text-sm mr-1">close</span> Tolak
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__.'/../partials/footer.php'; ?>