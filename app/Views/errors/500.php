<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>500 Internal Server Error - SATRIA</title>
    <link href="/css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-900 text-white min-h-screen flex flex-col items-center justify-center text-center px-6 relative overflow-hidden">
    
    <div class="absolute top-0 left-0 w-full h-full opacity-20 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[60%] h-[60%] rounded-full bg-rose-600 blur-[150px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[60%] h-[60%] rounded-full bg-blue-900 blur-[150px]"></div>
    </div>

    <div class="relative z-10 max-w-lg mx-auto">
        <div class="mb-6 inline-flex p-4 rounded-full bg-white/5 border border-white/10 backdrop-blur-sm animate-pulse">
            <span class="material-icons text-4xl text-rose-400">dns</span>
        </div>
        
        <h1 class="text-8xl font-black mb-2 tracking-tighter text-transparent bg-clip-text bg-gradient-to-b from-white to-slate-400">500</h1>
        <h2 class="text-2xl font-bold mb-4">Gangguan Sistem Internal</h2>
        
        <p class="text-slate-400 mb-8 leading-relaxed">
            Maaf, server kami sedang mengalami kendala teknis tak terduga. Tim IT PNJ telah dinotifikasi otomatis dan sedang bekerja memperbaikinya.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="location.reload()" class="px-8 py-3 bg-white text-slate-900 font-bold rounded-full hover:bg-slate-200 transition-all flex items-center justify-center">
                <span class="material-icons text-sm mr-2">refresh</span> Coba Lagi
            </button>
            <a href="/" class="px-8 py-3 bg-white/10 border border-white/20 text-white font-bold rounded-full hover:bg-white/20 transition-all flex items-center justify-center">
                Kembali ke Depan
            </a>
        </div>

        <div class="mt-12 pt-8 border-t border-white/10">
            <p class="text-xs text-slate-500 font-mono">Error Reference: <?php echo date('Ymd-His'); ?>-XCRASH</p>
        </div>
    </div>
</body>
</html>