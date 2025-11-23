<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SATRIA System</title>
    <link href="/css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center relative overflow-hidden">

    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-blue-600/20 blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-emerald-600/10 blur-[120px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col md:flex-row m-4">
        
        <div class="hidden md:flex flex-col justify-between w-1/2 bg-gradient-to-br from-blue-800 to-slate-900 p-10 text-white relative">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            
            <div>
                <div class="flex items-center space-x-3 mb-6">
                    <img src="/logo_pnj.png" alt="Logo" class="w-10 h-10 brightness-200 drop-shadow-lg">
                    <span class="text-2xl font-extrabold tracking-tighter">SATRIA</span>
                </div>
                <h2 class="text-3xl font-bold leading-tight mb-4">Excellence in <br>Administration.</h2>
                <p class="text-blue-200 text-sm leading-relaxed opacity-90">Sistem terintegrasi untuk pengelolaan TOR, RAB, dan LPJ yang transparan, akuntabel, dan efisien.</p>
            </div>

            <div class="text-xs text-blue-300/60 font-mono">
                v1.0 Enterprise Edition
            </div>
        </div>

        <div class="w-full md:w-1/2 p-10 md:p-12 bg-white">
            <div class="text-center md:text-left mb-8">
                <h3 class="text-2xl font-extrabold text-slate-900">Selamat Datang</h3>
                <p class="text-slate-500 text-sm mt-1">Silakan masuk dengan kredensial Anda.</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-r text-sm font-medium flex items-start animate-pulse">
                    <span class="material-icons text-sm mr-2 mt-0.5">error</span>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="/login" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-2 tracking-wider">Username / Email</label>
                    <div class="relative">
                        <span class="material-icons absolute left-3 top-3 text-slate-400 text-lg">person</span>
                        <input type="text" name="username" required class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all outline-none font-medium text-slate-800" placeholder="NIP atau Email PNJ">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-2 tracking-wider">Password</label>
                    <div class="relative">
                        <span class="material-icons absolute left-3 top-3 text-slate-400 text-lg">lock</span>
                        <input type="password" name="password" required class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all outline-none font-medium text-slate-800" placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center text-slate-500 cursor-pointer hover:text-slate-700">
                        <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                        <span class="ml-2">Ingat Saya</span>
                </div>

                <button type="submit" class="w-full py-3.5 bg-blue-800 text-white rounded-lg font-bold shadow-lg shadow-blue-800/30 hover:bg-blue-900 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 flex justify-center items-center group">
                    Masuk Aplikasi <span class="material-icons ml-2 text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-xs text-slate-400">Butuh bantuan akses? <a href="mailto:it@pnj.ac.id" class="text-blue-600 font-bold hover:underline">Hubungi IT Support</a></p>
            </div>
        </div>
    </div>
    
    <div class="absolute bottom-6 text-slate-500 text-xs font-medium opacity-60">
        &copy; 2025 Politeknik Negeri Jakarta. All Rights Reserved.
    </div>

</body>
</html>