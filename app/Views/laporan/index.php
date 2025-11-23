<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Laporan Kinerja Institusi</h1>
        <p class="text-slate-500 mt-1">Rekapitulasi realisasi kegiatan dan serapan anggaran tahun berjalan.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl p-6 text-white shadow-lg shadow-blue-600/20 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl -mr-8 -mt-8"></div>
            <div class="relative z-10">
                <p class="text-blue-200 text-xs font-bold uppercase tracking-wider mb-1">Total Kegiatan</p>
                <h3 class="text-4xl font-extrabold"><?php echo $stats['total']; ?></h3>
                <div class="mt-4 text-xs bg-blue-500/30 inline-block px-2 py-1 rounded">Semua Status</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden">
            <div class="absolute right-0 bottom-0 w-24 h-24 bg-emerald-50 rounded-full blur-xl"></div>
            <div class="relative z-10">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Kegiatan Selesai</p>
                <h3 class="text-4xl font-extrabold text-emerald-600"><?php echo $stats['selesai']; ?></h3>
                <p class="text-xs text-emerald-600 mt-2 flex items-center">
                    <span class="material-icons text-sm mr-1">check_circle</span> Fully Audited
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm relative overflow-hidden">
             <div class="absolute right-0 bottom-0 w-24 h-24 bg-amber-50 rounded-full blur-xl"></div>
            <div class="relative z-10">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Dana Terserap</p>
                <h3 class="text-4xl font-extrabold text-slate-800">Rp <?php echo number_format($stats['dana'] / 1000000, 0, ',', '.'); ?> <span class="text-lg text-slate-400 font-medium">Juta</span></h3>
                <p class="text-xs text-amber-600 mt-2 flex items-center">
                    <span class="material-icons text-sm mr-1">trending_up</span> Realisasi Anggaran
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="font-bold text-slate-800 mb-6 flex items-center">
                <span class="material-icons text-slate-400 mr-2">history</span> Aktivitas Terbaru
            </h3>
            <div class="space-y-4">
                <?php foreach ($recent as $r): ?>
                <div class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-xl transition-colors border border-transparent hover:border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs">
                            <?php echo substr($r['nama_kegiatan'], 0, 2); ?>
                        </div>
                        <div>
                            <div class="font-bold text-slate-700 text-sm"><?php echo htmlspecialchars($r['nama_kegiatan']); ?></div>
                            <div class="text-xs text-slate-400">Anggaran: Rp <?php echo number_format($r['nominal_pencairan'], 0, ',', '.'); ?></div>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide bg-blue-50 text-blue-600 border border-blue-100">
                        <?php echo $r['status_terkini']; ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="font-bold text-slate-800 mb-6 flex items-center">
                <span class="material-icons text-slate-400 mr-2">pie_chart</span> Distribusi Anggaran
            </h3>
            
            <div class="space-y-5">
                <div>
                    <div class="flex justify-between text-xs font-bold text-slate-600 mb-1">
                        <span>Belanja Barang</span>
                        <span>45%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 45%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-xs font-bold text-slate-600 mb-1">
                        <span>Belanja Jasa</span>
                        <span>30%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: 30%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-xs font-bold text-slate-600 mb-1">
                        <span>Perjalanan Dinas</span>
                        <span>25%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-amber-500 h-2 rounded-full" style="width: 25%"></div>
                    </div>
                </div>
            </div>

            <div class="mt-8 p-4 bg-slate-50 rounded-xl border border-slate-100">
                <p class="text-xs text-slate-500 leading-relaxed text-center">
                    Data di atas adalah akumulasi real-time dari seluruh usulan yang telah disetujui (Status: Disetujui, Pencairan, LPJ, Selesai).
                </p>
            </div>
        </div>

    </div>
</div>
<?php include __DIR__.'/../partials/footer.php'; ?>