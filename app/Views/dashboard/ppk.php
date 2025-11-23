<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    <div class="relative bg-gradient-to-r from-violet-800 to-purple-900 rounded-2xl p-10 text-white shadow-2xl mb-10 overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl -mr-16 -mt-16"></div>
        <div class="relative z-10">
            <div class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold mb-3">
                <span class="material-icons text-sm mr-2">gavel</span> Pejabat Pembuat Komitmen (PPK)
            </div>
            <h1 class="text-4xl font-extrabold tracking-tight mb-2">Validasi Komitmen</h1>
            <p class="text-violet-100 text-lg max-w-2xl">Anda memegang kendali akhir atas validitas belanja dan kontrak kegiatan sebelum pencairan.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <a href="/approval" class="col-span-2 group flex items-center p-8 bg-white rounded-2xl shadow-lg border border-violet-100 hover:border-violet-500 transition-all cursor-pointer relative overflow-hidden">
            <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:scale-110">
                <span class="material-icons text-9xl text-violet-600">verified_user</span>
            </div>
            <div class="mr-6">
                <div class="w-16 h-16 rounded-2xl bg-violet-600 text-white flex items-center justify-center shadow-lg shadow-violet-600/30">
                    <span class="material-icons text-3xl">signature</span>
                </div>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-bold text-slate-800 mb-1 group-hover:text-violet-700">Validasi Akhir (Approval)</h3>
                <p class="text-slate-500 mb-4">Setujui usulan kegiatan untuk menerbitkan perintah bayar.</p>
                <span class="inline-flex items-center font-bold text-violet-600 group-hover:translate-x-2 transition-transform">
                    Buka Dokumen <span class="material-icons ml-2">arrow_forward</span>
                </span>
            </div>
        </a>

        <a href="/monitoring" class="p-8 bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-md flex flex-col justify-between group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <span class="material-icons">insights</span>
                </div>
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-xl group-hover:text-blue-600">Monitoring Realisasi</h4>
                <p class="text-slate-400 text-sm mt-1">Pantau serapan anggaran per jurusan.</p>
            </div>
        </a>
    </div>
</div>
<?php include __DIR__.'/../partials/footer.php'; ?>