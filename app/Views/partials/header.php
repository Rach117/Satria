<?php
// Logika Penentuan Judul Halaman Otomatis
$uri = $_SERVER['REQUEST_URI'];
$pageTitle = 'Dashboard';

if (strpos($uri, '/login') !== false) $pageTitle = 'Login Masuk';
elseif (strpos($uri, '/monitoring') !== false) $pageTitle = 'Monitoring Kegiatan';
elseif (strpos($uri, '/usulan/create') !== false) $pageTitle = 'Buat Usulan Baru';
elseif (strpos($uri, '/usulan/detail') !== false) $pageTitle = 'Detail Dokumen';
elseif (strpos($uri, '/verifikasi') !== false) $pageTitle = 'Verifikasi Dokumen';
elseif (strpos($uri, '/approval') !== false) $pageTitle = 'Persetujuan (Approval)';
elseif (strpos($uri, '/users') !== false) $pageTitle = 'Manajemen Pengguna';
elseif (strpos($uri, '/master') !== false) $pageTitle = 'Master Data';
elseif (strpos($uri, '/profil') !== false) $pageTitle = 'Pengaturan Akun';
elseif (strpos($uri, '/notifikasi') !== false) $pageTitle = 'Pusat Notifikasi';
elseif (strpos($uri, '/laporan') !== false) $pageTitle = 'Laporan Kinerja';
elseif (strpos($uri, '/audit') !== false) $pageTitle = 'Audit Log System';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?php echo $pageTitle; ?> - SATRIA System</title>
    
    <link href="/css/style.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Animations Keyframes */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translate3d(0, -20px, 0); }
            to { opacity: 1; transform: translate3d(0, 0, 0); }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Utility Classes for Animation */
        .animate-fade-in-down { animation: fadeInDown 0.5s ease-out forwards; }
        .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
        
        /* Glassmorphism Utility */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-800 antialiased h-full selection:bg-blue-100 selection:text-blue-900">