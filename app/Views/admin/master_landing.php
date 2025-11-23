<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    
    <div class="relative bg-slate-900 rounded-2xl p-10 text-white shadow-xl mb-10 overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500 opacity-10 rounded-full blur-3xl -mr-16 -mt-16"></div>
        <div class="relative z-10">
            <div class="inline-flex items-center px-3 py-1 rounded-full bg-slate-800 border border-slate-700 text-blue-400 text-xs font-bold mb-3">
                <span class="material-icons text-sm mr-2">settings</span> Konfigurasi Sistem
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight mb-2">Master Data System</h1>
            <p class="text-slate-400 text-lg max-w-2xl">Pusat kendali referensi data untuk standarisasi input di seluruh aplikasi.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <a href="/master/jurusan" class="group bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-lg hover:border-blue-400 transition-all duration-300 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <span class="material-icons text-8xl text-blue-600">school</span>
            </div>
            <div class="w-14 h-14 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <span class="material-icons text-2xl">domain</span>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-1 group-hover:text-blue-700">Unit & Jurusan</h3>
            <p class="text-slate-500 text-sm mb-4">8 Data Terdaftar</p>
            <span class="text-blue-600 font-bold text-sm flex items-center group-hover:translate-x-2 transition-transform">
                Kelola <span class="material-icons text-sm ml-1">arrow_forward</span>
            </span>
        </a>

        <a href="/master/iku" class="group bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:shadow-lg hover:border-emerald-400 transition-all duration-300 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <span class="material-icons text-8xl text-emerald-600">analytics</span>
            </div>
            <div class="w-14 h-14 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <span class="material-icons text-2xl">bar_chart</span>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-1 group-hover:text-emerald-700">Indikator Kinerja (IKU)</h3>
            <p class="text-slate-500 text-sm mb-4">18 Indikator Aktif</p>
            <span class="text-emerald-600 font-bold text-sm flex items-center group-hover:translate-x-2 transition-transform">
                Kelola <span class="material-icons text-sm ml-1">arrow_forward</span>
            </span>
        </a>

        <div class="group bg-slate-50 p-6 rounded-2xl border border-slate-200 opacity-75 cursor-not-allowed relative overflow-hidden grayscale">
            <div class="absolute top-3 right-3">
                <span class="px-2 py-1 bg-slate-200 text-slate-500 text-[10px] font-bold rounded uppercase">System Locked</span>
            </div>
            <div class="w-14 h-14 rounded-xl bg-white text-slate-400 flex items-center justify-center mb-4 border border-slate-200">
                <span class="material-icons text-2xl">account_balance_wallet</span>
            </div>
            <h3 class="text-xl font-bold text-slate-500 mb-1">Kategori Anggaran</h3>
            <p class="text-slate-400 text-sm mb-4">Standar Biaya Masukan (SBM)</p>
            <span class="text-slate-400 font-bold text-sm flex items-center">
                <span class="material-icons text-sm mr-1">lock</span> Terkunci
            </span>
        </div>

    </div>
</div>
<?php include __DIR__.'/../partials/footer.php'; ?>