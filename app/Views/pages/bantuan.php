<?php include __DIR__.'/../partials/sidebar.php'; ?>
<div class="p-8 max-w-5xl mx-auto">
    <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
            <span class="material-icons text-3xl">help_outline</span>
        </div>
        <h1 class="text-3xl font-extrabold text-slate-900">Pusat Bantuan</h1>
        <p class="text-slate-500 mt-2 max-w-lg mx-auto">Unduh template dokumen resmi dan panduan penggunaan sistem SATRIA.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="#" class="group bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:border-blue-400 hover:shadow-lg transition-all text-center">
            <div class="w-12 h-12 mx-auto bg-slate-50 rounded-xl flex items-center justify-center mb-4 text-slate-400 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <span class="material-icons">description</span>
            </div>
            <h3 class="font-bold text-slate-800 mb-1">Format Surat Pengantar</h3>
            <p class="text-xs text-slate-400 mb-4">DOCX • Updated 2025</p>
            <span class="text-blue-600 text-sm font-bold group-hover:underline">Download File</span>
        </a>
        
        <a href="#" class="group bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:border-emerald-400 hover:shadow-lg transition-all text-center">
             <div class="w-12 h-12 mx-auto bg-slate-50 rounded-xl flex items-center justify-center mb-4 text-slate-400 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <span class="material-icons">table_view</span>
            </div>
            <h3 class="font-bold text-slate-800 mb-1">Template RAB Excel</h3>
            <p class="text-xs text-slate-400 mb-4">XLSX • Auto-Formula</p>
            <span class="text-emerald-600 text-sm font-bold group-hover:underline">Download File</span>
        </a>

        <a href="#" class="group bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:border-amber-400 hover:shadow-lg transition-all text-center">
             <div class="w-12 h-12 mx-auto bg-slate-50 rounded-xl flex items-center justify-center mb-4 text-slate-400 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                <span class="material-icons">menu_book</span>
            </div>
            <h3 class="font-bold text-slate-800 mb-1">User Manual v1.0</h3>
            <p class="text-xs text-slate-400 mb-4">PDF • Panduan Lengkap</p>
            <span class="text-amber-600 text-sm font-bold group-hover:underline">Download File</span>
        </a>
    </div>

    <div class="mt-12 border-t border-slate-200 pt-10">
        <h3 class="text-lg font-bold text-slate-900 mb-6">Pertanyaan Umum (FAQ)</h3>
        <div class="space-y-4">
            <div class="bg-white border border-slate-200 rounded-xl p-5">
                <h4 class="font-bold text-slate-800 text-sm mb-2">Bagaimana jika usulan saya ditolak?</h4>
                <p class="text-slate-600 text-sm">Periksa catatan yang diberikan oleh verifikator/pimpinan di menu "Detail Usulan". Lakukan revisi dokumen dan ajukan ulang melalui menu Edit.</p>
            </div>
            <div class="bg-white border border-slate-200 rounded-xl p-5">
                <h4 class="font-bold text-slate-800 text-sm mb-2">Berapa lama proses pencairan dana?</h4>
                <p class="text-slate-600 text-sm">Sesuai SOP, proses pencairan memakan waktu maksimal 3 hari kerja setelah status berubah menjadi "Disetujui" oleh PPK.</p>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__.'/../partials/footer.php'; ?>