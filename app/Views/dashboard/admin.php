<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    
    <div class="relative bg-gradient-to-r from-slate-800 to-slate-900 rounded-2xl p-8 md:p-10 text-white shadow-2xl mb-10 overflow-hidden">
        <div class="absolute right-0 top-0 h-full w-1/2 bg-white opacity-5 transform -skew-x-12 translate-x-20"></div>
        <div class="absolute bottom-[-40px] right-[10%] w-32 h-32 bg-blue-500 rounded-full blur-[60px] opacity-40"></div>

        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-xs font-bold mb-3">
                    <span class="w-2 h-2 rounded-full bg-blue-400 mr-2 animate-pulse"></span> Administrator Mode
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2">Dashboard Sistem</h1>
                <p class="text-slate-300 text-lg max-w-xl">Pusat kendali utama untuk manajemen pengguna, data master, dan pengawasan audit sistem SATRIA.</p>
            </div>
            <div class="flex gap-4">
                <div class="text-center px-6 py-3 bg-white/10 rounded-xl backdrop-blur-sm border border-white/10">
                    <div class="text-2xl font-bold text-white"><?php echo date('H:i'); ?></div>
                    <div class="text-xs text-slate-300 font-mono uppercase">Waktu Server</div>
                </div>
            </div>
        </div>
    </div>

    <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
        <span class="material-icons text-blue-600 mr-2">apps</span> Modul Manajemen
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        
        <a href="/users" class="group bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:shadow-xl hover:border-blue-300 transition-all duration-300 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity transform group-hover:scale-110 duration-500">
                <span class="material-icons text-8xl text-blue-600">people</span>
            </div>
            <div class="w-14 h-14 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors shadow-sm">
                <span class="material-icons text-2xl">manage_accounts</span>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2 group-hover:text-blue-700">Manajemen User</h3>
            <p class="text-slate-500 text-sm mb-6 leading-relaxed">Kelola akun, reset password, dan atur hak akses pengguna sistem.</p>
            <div class="flex items-center text-sm font-bold text-blue-600 group-hover:translate-x-2 transition-transform">
                Akses Menu <span class="material-icons text-sm ml-1">arrow_forward</span>
            </div>
        </a>

        <a href="/master" class="group bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:shadow-xl hover:border-emerald-300 transition-all duration-300 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity transform group-hover:scale-110 duration-500">
                <span class="material-icons text-8xl text-emerald-600">database</span>
            </div>
            <div class="w-14 h-14 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors shadow-sm">
                <span class="material-icons text-2xl">dns</span>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2 group-hover:text-emerald-700">Master Data</h3>
            <p class="text-slate-500 text-sm mb-6 leading-relaxed">Konfigurasi referensi Jurusan, IKU, dan Kategori Anggaran.</p>
            <div class="flex items-center text-sm font-bold text-emerald-600 group-hover:translate-x-2 transition-transform">
                Kelola Data <span class="material-icons text-sm ml-1">arrow_forward</span>
            </div>
        </a>

        <a href="/audit-log" class="group bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:shadow-xl hover:border-amber-300 transition-all duration-300 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity transform group-hover:scale-110 duration-500">
                <span class="material-icons text-8xl text-amber-600">security</span>
            </div>
            <div class="w-14 h-14 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4 group-hover:bg-amber-600 group-hover:text-white transition-colors shadow-sm">
                <span class="material-icons text-2xl">admin_panel_settings</span>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2 group-hover:text-amber-700">Audit & Keamanan</h3>
            <p class="text-slate-500 text-sm mb-6 leading-relaxed">Pantau log aktivitas user dan riwayat keamanan sistem secara real-time.</p>
            <div class="flex items-center text-sm font-bold text-amber-600 group-hover:translate-x-2 transition-transform">
                Buka Log <span class="material-icons text-sm ml-1">arrow_forward</span>
            </div>
        </a>

    </div>

    <div class="bg-slate-50 rounded-xl border border-slate-200 p-6">
        <div class="flex justify-between items-center mb-4">
            <h4 class="font-bold text-slate-700 flex items-center">
                <span class="material-icons text-slate-400 mr-2 text-sm">info</span> Status Sistem
            </h4>
            <span class="text-xs font-mono text-slate-400">PHP v<?php echo phpversion(); ?></span>
        </div>
        <div class="w-full bg-slate-200 rounded-full h-2.5 mb-1">
            <div class="bg-emerald-500 h-2.5 rounded-full" style="width: 100%"></div>
        </div>
        <div class="flex justify-between text-xs text-slate-500 mt-1">
            <span>Database Connection: <strong>Stabil</strong></span>
            <span>Mode: <strong>Production</strong></span>
        </div>
    </div>
</div>

<?php include __DIR__.'/../partials/footer.php'; ?>