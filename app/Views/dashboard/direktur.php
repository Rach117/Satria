<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    <div class="relative bg-slate-900 rounded-2xl p-10 text-white shadow-2xl mb-10 overflow-hidden">
        <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-blue-900 to-transparent opacity-50"></div>
        <div class="relative z-10">
            <h1 class="text-4xl font-extrabold tracking-tight mb-2">Dashboard Eksekutif</h1>
            <p class="text-slate-400 text-lg">Ringkasan performa serapan anggaran dan kinerja institusi.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <a href="/laporan" class="p-8 bg-white rounded-2xl shadow-lg border border-slate-200 hover:border-blue-500 transition-all group flex items-center">
            <div class="w-20 h-20 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mr-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <span class="material-icons text-4xl">pie_chart</span>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-slate-800 group-hover:text-blue-700">Laporan Kinerja</h3>
                <p class="text-slate-500 mt-1">Analisis statistik kegiatan dan anggaran.</p>
            </div>
        </a>

        <a href="/monitoring" class="p-8 bg-white rounded-2xl shadow-lg border border-slate-200 hover:border-emerald-500 transition-all group flex items-center">
            <div class="w-20 h-20 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mr-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <span class="material-icons text-4xl">troubleshoot</span>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-slate-800 group-hover:text-emerald-700">Monitoring Global</h3>
                <p class="text-slate-500 mt-1">Pantau status seluruh usulan berjalan.</p>
            </div>
        </a>
    </div>
</div>
<?php include __DIR__.'/../partials/footer.php'; ?>