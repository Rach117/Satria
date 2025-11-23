<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SATRIA - Politeknik Negeri Jakarta</title>
    
    <link href="/css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Hide Scrollbar but allow scroll */
        ::-webkit-scrollbar { width: 8px; background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
    </style>
</head>
<body class="bg-slate-900 text-slate-200 antialiased selection:bg-blue-500 selection:text-white">

    <nav class="fixed w-full z-50 bg-slate-900/80 backdrop-blur-md border-b border-slate-800 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="/logo_pnj.png" alt="Logo PNJ" class="w-9 h-9 brightness-200 drop-shadow-[0_0_10px_rgba(59,130,246,0.5)]">
                <span class="text-xl font-extrabold text-white tracking-tight">SATRIA</span>
            </div>

            <div class="hidden md:flex gap-8 text-sm font-medium text-slate-400">
                <a href="#fitur" class="hover:text-white transition-colors py-2">Fitur Unggulan</a>
                <a href="#alur" class="hover:text-white transition-colors py-2">Alur Kerja</a>
                <a href="/bantuan" class="hover:text-white transition-colors py-2">Pusat Bantuan</a>
            </div>

            <a href="/login" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-full hover:bg-blue-500 hover:shadow-lg hover:shadow-blue-600/20 transition-all transform hover:-translate-y-0.5">
                Masuk Sistem
            </a>
        </div>
    </nav>

    <section class="relative pt-40 pb-20 md:pt-48 md:pb-32 overflow-hidden min-h-screen flex flex-col justify-center">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[600px] bg-blue-600/10 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-[800px] h-[600px] bg-emerald-600/5 rounded-full blur-[120px] pointer-events-none"></div>
        
        <div class="max-w-5xl mx-auto px-6 text-center relative z-10">
            <div class="inline-flex items-center px-4 py-1.5 rounded-full border border-slate-700 bg-slate-800/50 backdrop-blur-md text-blue-400 text-xs font-bold mb-8 animate-fade-in-down">
                <span class="w-2 h-2 rounded-full bg-blue-500 mr-2 animate-pulse"></span>
                Sistem Administrasi Terintegrasi PNJ
            </div>
            
            <h1 class="text-5xl md:text-7xl font-black text-white tracking-tight mb-6 leading-[1.1]">
                Kelola Anggaran <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-cyan-400 to-emerald-400">Tanpa Hambatan.</span>
            </h1>
            
            <p class="text-lg md:text-xl text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                Platform digital resmi Politeknik Negeri Jakarta untuk pengajuan TOR, RAB, dan LPJ yang transparan, akuntabel, dan real-time.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="/login" class="group px-8 py-4 bg-blue-600 text-white font-bold rounded-full hover:bg-blue-500 transition-all shadow-xl shadow-blue-900/20 flex items-center justify-center">
                    <span class="material-icons mr-2 group-hover:animate-bounce">rocket_launch</span> Mulai Sekarang
                </a>
                <a href="/bantuan" class="group px-8 py-4 bg-slate-800 text-white border border-slate-700 font-bold rounded-full hover:bg-slate-700 hover:border-slate-600 transition-all flex items-center justify-center">
                    <span class="material-icons mr-2 text-slate-400 group-hover:text-white transition-colors">download</span> Panduan Pengguna
                </a>
            </div>
        </div>

        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce">
            <span class="material-icons text-slate-600">keyboard_arrow_down</span>
        </div>
    </section>

    <section id="fitur" class="py-24 bg-slate-900 relative border-t border-slate-800/50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">Fitur Unggulan</h2>
                <p class="text-slate-400 max-w-xl mx-auto">Kami mendesain sistem ini untuk memangkas birokrasi tanpa mengurangi akuntabilitas.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="group p-8 rounded-2xl bg-slate-800/50 border border-slate-700 hover:border-blue-500/50 hover:bg-slate-800 transition-all duration-300">
                    <div class="w-14 h-14 rounded-xl bg-blue-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-icons text-3xl text-blue-400">description</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Digitalisasi Dokumen</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">Tidak ada lagi berkas hilang. Upload TOR, RAB, dan Surat Pengantar dalam satu platform yang aman.</p>
                </div>

                <div class="group p-8 rounded-2xl bg-slate-800/50 border border-slate-700 hover:border-emerald-500/50 hover:bg-slate-800 transition-all duration-300">
                    <div class="w-14 h-14 rounded-xl bg-emerald-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-icons text-3xl text-emerald-400">sync</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Monitoring Real-time</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">Lacak posisi usulan Anda detik ini juga. Apakah sedang diverifikasi, disetujui, atau siap cair.</p>
                </div>

                <div class="group p-8 rounded-2xl bg-slate-800/50 border border-slate-700 hover:border-amber-500/50 hover:bg-slate-800 transition-all duration-300">
                    <div class="w-14 h-14 rounded-xl bg-amber-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <span class="material-icons text-3xl text-amber-400">gavel</span>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Audit & Kepatuhan</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">Setiap langkah tercatat dalam Audit Log. Memastikan kepatuhan terhadap standar IKU dan mata anggaran.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="alur" class="py-24 bg-slate-800/30 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5"></div>
        
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">Alur Kerja Sistem</h2>
                <p class="text-slate-400">Proses yang disederhanakan dari hulu ke hilir.</p>
            </div>

            <div class="relative">
                <div class="hidden md:block absolute top-1/2 left-0 w-full h-1 bg-gradient-to-r from-blue-900 via-blue-500 to-emerald-500 -translate-y-1/2 rounded opacity-20"></div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="relative text-center group">
                        <div class="w-16 h-16 mx-auto bg-slate-900 border-2 border-blue-500 rounded-full flex items-center justify-center mb-6 relative z-10 shadow-[0_0_20px_rgba(59,130,246,0.3)] group-hover:scale-110 transition-transform">
                            <span class="material-icons text-2xl text-blue-400">edit_note</span>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-2">1. Pengajuan</h4>
                        <p class="text-xs text-slate-400 px-4">Pengusul mengisi form wizard TOR & RAB dan upload dokumen.</p>
                    </div>

                    <div class="relative text-center group">
                        <div class="w-16 h-16 mx-auto bg-slate-900 border-2 border-blue-400 rounded-full flex items-center justify-center mb-6 relative z-10 shadow-[0_0_20px_rgba(96,165,250,0.3)] group-hover:scale-110 transition-transform">
                            <span class="material-icons text-2xl text-blue-300">fact_check</span>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-2">2. Verifikasi</h4>
                        <p class="text-xs text-slate-400 px-4">Verifikator memeriksa kelengkapan administrasi dan kode MAK.</p>
                    </div>

                    <div class="relative text-center group">
                        <div class="w-16 h-16 mx-auto bg-slate-900 border-2 border-indigo-500 rounded-full flex items-center justify-center mb-6 relative z-10 shadow-[0_0_20px_rgba(99,102,241,0.3)] group-hover:scale-110 transition-transform">
                            <span class="material-icons text-2xl text-indigo-400">thumb_up</span>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-2">3. Persetujuan</h4>
                        <p class="text-xs text-slate-400 px-4">WD2 dan PPK memberikan persetujuan digital berjenjang.</p>
                    </div>

                    <div class="relative text-center group">
                        <div class="w-16 h-16 mx-auto bg-slate-900 border-2 border-emerald-500 rounded-full flex items-center justify-center mb-6 relative z-10 shadow-[0_0_20px_rgba(16,185,129,0.3)] group-hover:scale-110 transition-transform">
                            <span class="material-icons text-2xl text-emerald-400">payments</span>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-2">4. Pencairan & LPJ</h4>
                        <p class="text-xs text-slate-400 px-4">Dana cair, kegiatan berjalan, dan LPJ diunggah tepat waktu.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 border-t border-slate-800 py-10 text-center md:text-left">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <div class="flex items-center justify-center md:justify-start gap-2 mb-2">
                    <img src="/logo_pnj.png" alt="Logo" class="w-6 h-6 grayscale opacity-50">
                    <span class="font-bold text-slate-300 tracking-tight">SATRIA System</span>
                </div>
                <p class="text-xs text-slate-500">© 2025 Politeknik Negeri Jakarta. All rights reserved.</p>
            </div>
            
            <div class="flex gap-6 text-xs font-medium text-slate-400">
                <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                <a href="#" class="hover:text-white transition-colors">Syarat Penggunaan</a>
                <a href="mailto:it@pnj.ac.id" class="hover:text-white transition-colors">Kontak Support</a>
            </div>
        </div>
    </footer>

</body>
</html>