<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    <div class="relative bg-gradient-to-r from-emerald-700 to-teal-600 rounded-2xl p-10 text-white shadow-2xl mb-10 overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        <div class="relative z-10">
            <h1 class="text-4xl font-extrabold tracking-tight mb-2">Dashboard Verifikasi 🛡️</h1>
            <p class="text-emerald-50 text-lg max-w-2xl">Anda adalah garda terdepan kualitas administrasi. Pastikan setiap dokumen memenuhi standar kepatuhan.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <a href="/verifikasi" class="flex flex-col p-8 bg-white rounded-2xl shadow-lg border border-emerald-100 hover:border-emerald-500 transition-all group cursor-pointer relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-bl-full -mr-10 -mt-10 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <div class="w-16 h-16 rounded-2xl bg-emerald-600 text-white flex items-center justify-center mb-6 shadow-lg shadow-emerald-600/30">
                    <span class="material-icons text-3xl">fact_check</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-2 group-hover:text-emerald-700">Verifikasi Usulan Masuk</h3>
                <p class="text-slate-500 mb-6">Periksa kelengkapan TOR, RAB, dan IKU dari pengusul.</p>
                <span class="inline-flex items-center font-bold text-emerald-600 group-hover:translate-x-2 transition-transform">
                    Mulai Memeriksa <span class="material-icons ml-2">arrow_forward</span>
                </span>
            </div>
        </a>

        <div class="grid grid-rows-2 gap-6">
            <a href="/monitoring" class="p-6 bg-white rounded-xl shadow-sm border border-slate-200 hover:shadow-md flex items-center justify-between group">
                <div>
                    <h4 class="font-bold text-slate-700 text-lg group-hover:text-blue-600">Monitoring Global</h4>
                    <p class="text-slate-400 text-sm">Pantau seluruh aktivitas sistem.</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <span class="material-icons">visibility</span>
                </div>
            </a>
            <div class="p-6 bg-emerald-50/50 rounded-xl border border-emerald-100 flex flex-col justify-center">
                <span class="text-emerald-800 font-bold text-sm uppercase tracking-wider mb-1">Tips Verifikator</span>
                <p class="text-emerald-600 text-sm italic">"Pastikan mata anggaran sesuai dengan SBM (Standar Biaya Masukan) tahun berjalan."</p>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__.'/../partials/footer.php'; ?>