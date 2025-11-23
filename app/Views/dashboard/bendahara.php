<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    <div class="relative bg-gradient-to-r from-amber-500 to-orange-600 rounded-2xl p-10 text-white shadow-2xl mb-10 overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/diamond-upholstery.png')] opacity-10"></div>
        <div class="relative z-10">
            <h1 class="text-4xl font-extrabold tracking-tight mb-2">Bendahara Pengeluaran 💰</h1>
            <p class="text-amber-50 text-lg">Kelola pencairan dana dan verifikasi pertanggungjawaban (LPJ) kegiatan.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <a href="/pencairan" class="flex items-center p-6 bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-xl hover:border-amber-400 transition-all group">
            <div class="w-16 h-16 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center mr-6 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                <span class="material-icons text-3xl">payments</span>
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-800 group-hover:text-amber-700">Pencairan Dana</h3>
                <p class="text-slate-500 text-sm mb-2">Proses pembayaran untuk usulan yang disetujui.</p>
                <span class="text-amber-600 font-bold text-sm flex items-center">Akses Menu <span class="material-icons text-sm ml-1">arrow_forward</span></span>
            </div>
        </a>

        <a href="/lpj" class="flex items-center p-6 bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-xl hover:border-emerald-400 transition-all group">
            <div class="w-16 h-16 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mr-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <span class="material-icons text-3xl">receipt_long</span>
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-800 group-hover:text-emerald-700">Verifikasi LPJ</h3>
                <p class="text-slate-500 text-sm mb-2">Validasi dokumen pertanggungjawaban.</p>
                <span class="text-emerald-600 font-bold text-sm flex items-center">Akses Menu <span class="material-icons text-sm ml-1">arrow_forward</span></span>
            </div>
        </a>
    </div>
</div>
<?php include __DIR__.'/../partials/footer.php'; ?>