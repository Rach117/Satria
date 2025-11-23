<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
        
        <div class="bg-slate-900 px-8 py-10 text-white text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            <span class="material-icons text-6xl text-slate-700 mb-4 block mx-auto">gavel</span>
            <h1 class="text-3xl font-extrabold tracking-tight relative z-10">Syarat & Ketentuan Penggunaan</h1>
            <p class="text-slate-400 mt-2 text-sm relative z-10">SATRIA - Sistem Administrasi TOR & LPJ</p>
        </div>

        <div class="p-8 md:p-12 prose prose-slate max-w-none">
            <div class="p-4 bg-amber-50 border-l-4 border-amber-400 rounded-r-lg mb-8 text-sm text-amber-800">
                <strong>Penting:</strong> Dengan mengakses dan menggunakan sistem ini, Anda dianggap telah membaca, memahami, dan menyetujui seluruh ketentuan di bawah ini.
            </div>

            <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center">
                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs mr-3">1</span>
                Akun & Keamanan
            </h3>
            <ul class="list-disc pl-12 mb-8 space-y-2 text-slate-600 text-sm">
                <li>Pengguna wajib menjaga kerahasiaan <strong>Username</strong> dan <strong>Password</strong>. Kelalaian yang menyebabkan penyalahgunaan akun adalah tanggung jawab pengguna sepenuhnya.</li>
                <li>Dilarang keras meminjamkan akun kepada pihak lain untuk tujuan manipulasi data atau persetujuan (approval) tanpa wewenang.</li>
                <li>Sistem mencatat alamat IP, waktu akses, dan aktivitas (Audit Log) untuk keperluan keamanan dan investigasi digital.</li>
            </ul>

            <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center">
                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs mr-3">2</span>
                Integritas Data & Dokumen
            </h3>
            <ul class="list-disc pl-12 mb-8 space-y-2 text-slate-600 text-sm">
                <li>Segala bentuk dokumen yang diunggah (TOR, RAB, Surat Pengantar, Bukti Transaksi) wajib merupakan dokumen <strong>asli dan sah</strong>.</li>
                <li>Manipulasi nilai anggaran (mark-up) atau pemalsuan dokumen pertanggungjawaban merupakan pelanggaran berat dan akan dikenakan sanksi akademik/administratif sesuai peraturan Politeknik Negeri Jakarta.</li>
            </ul>

            <hr class="border-slate-100 my-8">

            <div class="text-center text-xs text-slate-400">
                Terakhir diperbarui: <?php echo date('d F Y'); ?><br>
                &copy; 2025 Unit IT Politeknik Negeri Jakarta
            </div>
        </div>
    </div>
</div>
<?php include __DIR__.'/../partials/footer.php'; ?>