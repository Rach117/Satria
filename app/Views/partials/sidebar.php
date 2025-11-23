<?php
// app/Views/partials/sidebar.php

// Pastikan Header Global dimuat untuk CSS
include __DIR__ . '/header.php';

$role = $_SESSION['role'] ?? '';
$uri = $_SERVER['REQUEST_URI'];

// Definisi Menu
$menus = [
    'Admin' => [
        ['label' => 'Dashboard', 'url' => '/dashboard', 'icon' => 'dashboard'],
        ['label' => 'Manajemen Pengguna', 'url' => '/users', 'icon' => 'manage_accounts'],
        ['label' => 'Master Data', 'url' => '/master', 'icon' => 'dns'],
        ['label' => 'Monitoring Global', 'url' => '/monitoring', 'icon' => 'visibility'],
        ['label' => 'Log Audit', 'url' => '/audit-log', 'icon' => 'security'],
    ],
    'Pengusul' => [
        ['label' => 'Dashboard', 'url' => '/dashboard', 'icon' => 'dashboard'],
        ['label' => 'Buat Usulan', 'url' => '/usulan/create', 'icon' => 'post_add'],
        ['label' => 'Monitoring', 'url' => '/monitoring', 'icon' => 'history'],
        ['label' => 'Notifikasi', 'url' => '/notifikasi', 'icon' => 'notifications'],
    ],
    'Verifikator' => [
        ['label' => 'Dashboard', 'url' => '/dashboard', 'icon' => 'dashboard'],
        ['label' => 'Verifikasi Usulan', 'url' => '/verifikasi', 'icon' => 'fact_check'],
        ['label' => 'Monitoring', 'url' => '/monitoring', 'icon' => 'visibility'],
        ['label' => 'Notifikasi', 'url' => '/notifikasi', 'icon' => 'notifications'],
    ],
    'WD2' => [
        ['label' => 'Dashboard', 'url' => '/dashboard', 'icon' => 'dashboard'],
        ['label' => 'Approval', 'url' => '/approval', 'icon' => 'gavel'],
        ['label' => 'Monitoring', 'url' => '/monitoring', 'icon' => 'visibility'],
        ['label' => 'Notifikasi', 'url' => '/notifikasi', 'icon' => 'notifications'],
    ],
    'PPK' => [
        ['label' => 'Dashboard', 'url' => '/dashboard', 'icon' => 'dashboard'],
        ['label' => 'Approval', 'url' => '/approval', 'icon' => 'verified_user'],
        ['label' => 'Monitoring', 'url' => '/monitoring', 'icon' => 'visibility'],
        ['label' => 'Notifikasi', 'url' => '/notifikasi', 'icon' => 'notifications'],
    ],
    'Bendahara' => [
        ['label' => 'Dashboard', 'url' => '/dashboard', 'icon' => 'dashboard'],
        ['label' => 'Pencairan Dana', 'url' => '/pencairan', 'icon' => 'payments'],
        ['label' => 'LPJ', 'url' => '/lpj', 'icon' => 'receipt_long'],
        ['label' => 'Monitoring', 'url' => '/monitoring', 'icon' => 'visibility'],
        ['label' => 'Notifikasi', 'url' => '/notifikasi', 'icon' => 'notifications'],
    ],
    'Direktur' => [
        ['label' => 'Dashboard', 'url' => '/dashboard', 'icon' => 'dashboard'],
        ['label' => 'Monitoring', 'url' => '/monitoring', 'icon' => 'pie_chart'],
        ['label' => 'Laporan', 'url' => '/laporan', 'icon' => 'analytics'],
    ],
];
$menu = $menus[$role] ?? [];
?>

<aside class="fixed top-0 left-0 z-50 w-64 h-screen bg-white border-r border-slate-200 shadow-[4px_0_24px_rgba(0,0,0,0.02)] flex flex-col transition-transform duration-300">
    
    <div class="flex items-center justify-center h-20 border-b border-slate-100 shrink-0">
        <img src="/logo_pnj.png" alt="Logo PNJ" class="w-9 h-9 mr-3 drop-shadow-sm">
        <div class="flex flex-col">
            <span class="text-xl font-extrabold text-slate-900 tracking-tight leading-none">SATRIA</span>
            <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mt-0.5">Enterprise</span>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto px-4 py-6 space-y-1 custom-scrollbar hover:overflow-y-auto">
        <div class="px-3 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Menu Utama</div>
        
        <ul class="space-y-1">
            <?php foreach ($menu as $item): 
                $isActive = ($uri === $item['url']) || (strpos($uri, $item['url']) === 0 && $item['url'] !== '/dashboard');
            ?>
            <li>
                <a href="<?php echo $item['url']; ?>" 
                   class="flex items-center px-3 py-3 text-sm font-semibold rounded-xl transition-all duration-200 group relative overflow-hidden whitespace-nowrap
                   <?php echo $isActive 
                       ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' 
                       : 'text-slate-600 hover:bg-slate-50 hover:text-blue-700'; ?>">
                    
                    <?php if($isActive): ?>
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-500"></div>
                    <?php endif; ?>

                    <div class="w-6 flex justify-center mr-3 relative z-10">
                        <span class="material-icons text-[20px] transition-colors
                            <?php echo $isActive ? 'text-white' : 'text-slate-400 group-hover:text-blue-600'; ?>">
                            <?php echo $item['icon']; ?>
                        </span>
                    </div>

                    <span class="relative z-10 font-medium tracking-wide"><?php echo $item['label']; ?></span>
                    
                    <?php if($isActive): ?>
                        <span class="material-icons text-[16px] absolute right-3 z-10 text-blue-300">chevron_right</span>
                    <?php endif; ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>

        <div class="my-6 border-t border-slate-100"></div>

        <div class="px-3 mb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pengaturan</div>
        <ul class="space-y-1">
            <li>
                <?php $isProfil = ($uri === '/profil'); ?>
                <a href="/profil" class="flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200 group whitespace-nowrap
                   <?php echo $isProfil ? 'bg-slate-100 text-slate-800 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'; ?>">
                    
                    <div class="w-6 flex justify-center mr-3">
                        <span class="material-icons text-[20px] <?php echo $isProfil?'text-slate-800':'text-slate-400 group-hover:text-slate-600'; ?>">account_circle</span>
                    </div>
                    <span>Profil Saya</span>
                </a>
            </li>
            <li>
                <a href="mailto:it@pnj.ac.id" class="flex items-center px-3 py-3 text-sm font-medium rounded-xl text-slate-600 hover:bg-amber-50 hover:text-amber-700 transition-all group whitespace-nowrap">
                    <div class="w-6 flex justify-center mr-3">
                        <span class="material-icons text-[20px] text-slate-400 group-hover:text-amber-500">bug_report</span>
                    </div>
                    <span>Lapor Masalah</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="p-4 border-t border-slate-100 bg-white shrink-0">
        <button onclick="document.getElementById('modalLogout').classList.remove('hidden')" class="flex items-center justify-center w-full p-3 text-sm font-bold text-rose-600 bg-rose-50 rounded-xl hover:bg-rose-600 hover:text-white hover:shadow-lg hover:shadow-rose-600/20 transition-all duration-300 group">
            <span class="material-icons text-[20px] mr-2 transition-transform group-hover:-translate-x-1">logout</span>
            Keluar Sistem
        </button>
    </div>
</aside>

<div class="ml-64 min-h-screen flex flex-col relative selection:bg-blue-100">

    <div id="modalLogout" class="fixed inset-0 z-[999] hidden flex items-center justify-center bg-slate-900/80 backdrop-blur-sm transition-opacity duration-300 px-4">
    
    <div class="relative bg-white rounded-3xl shadow-2xl p-8 w-full max-w-sm text-center transform transition-all scale-100 animate-fade-in-down overflow-hidden border border-slate-100">
        
        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-rose-500 via-red-500 to-orange-500"></div>

        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-rose-50 mb-6 ring-8 ring-rose-50/50 shadow-inner group cursor-default">
            <span class="material-icons text-4xl text-rose-600 group-hover:scale-110 group-hover:rotate-12 transition-transform duration-500">logout</span>
        </div>

        <h3 class="text-2xl font-black text-slate-800 mb-2 tracking-tight">Berhenti Sesi?</h3>
        <p class="text-slate-500 text-sm mb-8 leading-relaxed px-2">
            Anda akan keluar dari sistem <span class="font-bold text-slate-800">SATRIA</span>. Pastikan pekerjaan Anda sudah tersimpan aman.
        </p>

        <div class="grid grid-cols-2 gap-3">
            <button onclick="document.getElementById('modalLogout').classList.add('hidden')" class="px-4 py-3.5 bg-slate-50 text-slate-600 font-bold rounded-xl hover:bg-slate-100 hover:text-slate-900 transition-all border border-slate-200 focus:ring-2 focus:ring-slate-200">
                Batal
            </button>
            <a href="/logout" class="px-4 py-3.5 bg-gradient-to-r from-rose-600 to-red-600 text-white font-bold rounded-xl hover:from-rose-700 hover:to-red-700 hover:shadow-lg hover:shadow-rose-600/30 transition-all flex items-center justify-center group">
                <span>Keluar</span>
                <span class="material-icons text-sm ml-2 group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
            </div>
        </div>
    </div>