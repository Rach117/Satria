<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-5xl mx-auto">
    
    <div class="flex justify-between items-end mb-8">
        <div>
            <div class="flex items-center text-xs text-slate-500 mb-2">
                <span class="material-icons text-[14px] mr-1">home</span> Home
                <span class="mx-2">/</span>
                <span>Pengaturan</span>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Profil Pengguna</h1>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden relative">
        
        <div class="h-48 bg-gradient-to-r from-slate-800 to-blue-900 relative">
            <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-40 h-40 bg-blue-400 opacity-10 rounded-full blur-2xl -ml-10 -mb-10"></div>
        </div>

        <div class="px-8 relative">
            <div class="flex flex-col md:flex-row items-start md:items-end -mt-16 mb-8">
                
                <div class="relative z-10">
                    <div class="w-32 h-32 rounded-full bg-white p-1.5 shadow-lg ring-1 ring-slate-100">
                        <div class="w-full h-full rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-4xl font-bold border border-slate-200">
                            <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                        </div>
                    </div>
                    <div class="absolute bottom-2 right-2 w-6 h-6 bg-emerald-500 border-4 border-white rounded-full" title="Online"></div>
                </div>

                <div class="mt-4 md:mt-0 md:ml-12 md:mb-1 flex-1 relative z-0">
                    <h2 class="text-3xl font-bold text-slate-900"><?php echo htmlspecialchars($user['username']); ?></h2>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-600 text-white shadow-sm">
                            <span class="material-icons text-[12px] mr-1.5">badge</span>
                            <?php echo htmlspecialchars($user['role']); ?>
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                            <span class="material-icons text-[12px] mr-1.5 text-emerald-500">verified</span>
                            Akun Terverifikasi
                        </span>
                    </div>
                </div>

                <div class="mt-6 md:mt-0 md:mb-4 hidden md:block">
                    <button onclick="document.getElementById('modalEditProfile').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 text-slate-700 text-sm font-bold rounded-lg hover:bg-slate-50 hover:text-blue-700 hover:border-blue-300 transition-all shadow-sm">
                        <span class="material-icons text-sm mr-2">edit</span> Edit Profil
                    </button>
                </div>
            </div>
        </div>

        <hr class="border-slate-100">

        <div class="p-8 bg-slate-50/50">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-6 border-l-4 border-blue-600 pl-3">Informasi Akun</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-start transition-all hover:shadow-md">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 mr-4 flex-shrink-0">
                        <span class="material-icons">email</span>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase">Alamat Email</label>
                        <div class="text-slate-800 font-semibold mt-1 text-sm md:text-base truncate">
                            <?php echo htmlspecialchars($user['email']); ?>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-start transition-all hover:shadow-md">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 mr-4 flex-shrink-0">
                        <span class="material-icons">apartment</span>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase">Unit / Jurusan</label>
                        <div class="text-slate-800 font-semibold mt-1 text-sm md:text-base">
                            <?php echo $user['nama_jurusan'] ? htmlspecialchars($user['nama_jurusan']) : 'Pusat / General'; ?>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-start transition-all hover:shadow-md">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 mr-4 flex-shrink-0">
                        <span class="material-icons">gpp_good</span>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase">Status Keamanan</label>
                        <div class="text-emerald-700 font-bold mt-1 text-sm">
                            Aktif & Aman
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-start transition-all hover:shadow-md">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 mr-4 flex-shrink-0">
                        <span class="material-icons">history</span>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase">Terdaftar Sejak</label>
                        <div class="text-slate-800 font-semibold mt-1 text-sm">
                            <?php echo date('d F Y'); ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="px-8 py-6 bg-slate-50 border-t border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-xs text-slate-500 text-center md:text-left">
                <p>Terakhir login: <span class="font-mono text-slate-700"><?php echo date('d-m-Y H:i'); ?> WIB</span></p>
                <p>IP Address: <span class="font-mono text-slate-700"><?php echo $_SERVER['REMOTE_ADDR']; ?></span></p>
            </div>
            <button onclick="document.getElementById('modalPassword').classList.remove('hidden')" class="inline-flex items-center px-6 py-3 bg-slate-800 text-white text-sm font-bold rounded-lg shadow-md hover:bg-slate-900 hover:shadow-lg transition-all transform hover:-translate-y-0.5 w-full md:w-auto justify-center">
                <span class="material-icons text-sm mr-2">lock_reset</span> Ubah Password
            </button>
        </div>

    </div>
</div>

<div id="modalEditProfile" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md border border-slate-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-slate-900">Edit Informasi Akun</h3>
            <button onclick="document.getElementById('modalEditProfile').classList.add('hidden')" class="text-slate-400 hover:text-rose-500">
                <span class="material-icons">close</span>
            </button>
        </div>
        
        <form action="/profil/update-data" method="POST">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none font-bold text-slate-700">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Alamat Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-slate-700">
                </div>
                
                <div class="p-3 bg-blue-50 rounded-lg border border-blue-100 flex items-start gap-3">
                    <span class="material-icons text-blue-600 text-sm mt-0.5">info</span>
                    <p class="text-xs text-blue-700 leading-relaxed">Perubahan username akan langsung diterapkan pada sesi login berikutnya.</p>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="button" onclick="document.getElementById('modalEditProfile').classList.add('hidden')" class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 font-bold rounded-lg hover:bg-slate-50 transition-colors">Batal</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-700 text-white font-bold rounded-lg hover:bg-blue-800 shadow-lg shadow-blue-700/20 transition-colors">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<div id="modalPassword" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md">
        <h3 class="text-lg font-bold text-slate-900 mb-4">Ubah Password</h3>
        <form action="/profil/update-password" method="POST">
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Password Lama</label>
                    <input type="password" name="old_password" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Password Baru</label>
                    <input type="password" name="new_password" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 outline-none">
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="button" onclick="document.getElementById('modalPassword').classList.add('hidden')" class="flex-1 px-4 py-2 border border-slate-300 text-slate-700 font-bold rounded-lg hover:bg-slate-50">Batal</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__.'/../partials/footer.php'; ?>