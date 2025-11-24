<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    <div class="relative bg-gradient-to-r from-slate-800 to-blue-900 rounded-2xl p-10 text-white shadow-2xl mb-10 overflow-hidden">
        <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-blue-900 to-transparent opacity-50"></div>
        <div class="absolute bottom-[-40px] right-[10%] w-32 h-32 bg-blue-500 rounded-full blur-[60px] opacity-40"></div>
        
        <div class="relative z-10">
            <div class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold mb-3">
                <span class="material-icons text-sm mr-2">account_balance</span> Dashboard Eksekutif
            </div>
            <h1 class="text-4xl font-extrabold tracking-tight mb-2">Monitoring Kinerja Institusi</h1>
            <p class="text-slate-300 text-lg max-w-2xl">Ringkasan performa serapan anggaran dan realisasi kegiatan secara komprehensif.</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <span class="material-icons">assignment</span>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase">Total Usulan</span>
            </div>
            <div class="text-3xl font-extrabold text-slate-800"><?php echo $stats['total'] ?? 0; ?></div>
            <div class="text-xs text-slate-500 mt-1">Semua status</div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <span class="material-icons">check_circle</span>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase">Selesai</span>
            </div>
            <div class="text-3xl font-extrabold text-emerald-600"><?php echo $stats['kegiatan_selesai'] ?? 0; ?></div>
            <div class="text-xs text-slate-500 mt-1">LPJ disetujui</div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <span class="material-icons">hourglass_empty</span>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase">Proses</span>
            </div>
            <div class="text-3xl font-extrabold text-amber-600"><?php echo ($stats['diajukan'] ?? 0) + ($stats['Disetujui'] ?? 0); ?></div>
            <div class="text-xs text-slate-500 mt-1">Sedang berjalan</div>
        </div>

        <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl p-6 shadow-lg text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-white/20 text-white flex items-center justify-center">
                    <span class="material-icons">payments</span>
                </div>
                <span class="text-xs font-bold text-blue-200 uppercase">Dana Terserap</span>
            </div>
            <div class="text-2xl font-extrabold">Rp <?php echo number_format(($stats['total_pencairan'] ?? 0) / 1000000, 1); ?> M</div>
            <div class="text-xs text-blue-200 mt-1">Realisasi anggaran</div>
        </div>
    </div>

    <!-- Action Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <a href="/monitoring" class="group p-8 bg-white rounded-2xl shadow-lg border border-slate-200 hover:border-blue-500 hover:shadow-xl transition-all cursor-pointer flex items-center relative overflow-hidden">
            <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:scale-110">
                <span class="material-icons text-9xl text-blue-600">troubleshoot</span>
            </div>
            <div class="mr-6 relative z-10">
                <div class="w-20 h-20 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-600/30">
                    <span class="material-icons text-4xl">visibility</span>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-bold text-slate-800 mb-1 group-hover:text-blue-700">Monitoring Global</h3>
                <p class="text-slate-500 mb-4">Pantau status seluruh usulan dan kegiatan berjalan secara real-time.</p>
                <span class="inline-flex items-center font-bold text-blue-600 group-hover:translate-x-2 transition-transform">
                    Lihat Detail <span class="material-icons ml-2">arrow_forward</span>
                </span>
            </div>
        </a>

        <a href="/laporan" class="group p-8 bg-white rounded-2xl shadow-lg border border-slate-200 hover:border-emerald-500 hover:shadow-xl transition-all cursor-pointer flex items-center relative overflow-hidden">
            <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:scale-110">
                <span class="material-icons text-9xl text-emerald-600">analytics</span>
            </div>
            <div class="mr-6 relative z-10">
                <div class="w-20 h-20 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-lg shadow-emerald-600/30">
                    <span class="material-icons text-4xl">pie_chart</span>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-bold text-slate-800 mb-1 group-hover:text-emerald-700">Laporan Kinerja</h3>
                <p class="text-slate-500 mb-4">Analisis statistik dan rekapitulasi anggaran per unit kerja.</p>
                <span class="inline-flex items-center font-bold text-emerald-600 group-hover:translate-x-2 transition-transform">
                    Lihat Laporan <span class="material-icons ml-2">arrow_forward</span>
                </span>
            </div>
        </a>
    </div>
</div>

<?php include __DIR__.'/../partials/footer.php'; ?>