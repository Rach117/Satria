<?php
// public/index.php - FIXED ROUTING

require_once __DIR__ . '/../vendor/autoload.php';

// Load Environment Variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Konfigurasi Keamanan Session
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
ini_set('session.use_strict_mode', 1);
session_name('SATRIA_SESSION');
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

set_exception_handler(function ($e) {
    error_log($e->getMessage());
    http_response_code(500);
    require __DIR__ . '/../app/Views/errors/500.php';
    exit;
});

$config = require __DIR__ . '/../config/app.php';
date_default_timezone_set($config['timezone']);
$dbConfig = require __DIR__ . '/../config/database.php';
$dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
$db = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $dbConfig['options']);

// Import Controllers
use App\Controllers\{
    AuthController,
    DashboardController,
    MonitoringController,
    UsulanWizardController,
    PengajuanKegiatanController,
    VerifikasiController,
    PPKController,
    WD2Controller,
    BendaharaController,
    AdminController,
    DirekturController,
    NotifikasiController,
    PdfController,
    PageController
};

function sanitizeInput($data) { 
    return htmlspecialchars(strip_tags(trim($data))); 
}
$_GET = array_map('sanitizeInput', $_GET);
$_POST = array_map('sanitizeInput', $_POST);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// --- RATE LIMITING LOGIN ---
if ($uri === '/login' && $method === 'POST') {
    $rateLimitKey = 'login_attempts_' . $_SERVER['REMOTE_ADDR'];
    if (!isset($_SESSION[$rateLimitKey])) $_SESSION[$rateLimitKey] = ['count' => 0, 'last_attempt' => time()];
    $rateLimit = &$_SESSION[$rateLimitKey];
    if ((time() - $rateLimit['last_attempt']) > 300) $rateLimit['count'] = 0;
    if ($rateLimit['count'] >= 5) die('Terlalu banyak percobaan login. Tunggu 5 menit.');
    $rateLimit['count']++;
    $rateLimit['last_attempt'] = time();
}

// ==================== ROUTING ====================

// === WELCOME & AUTH ===
if ($uri === '/' || $uri === '/index.php') {
    require __DIR__ . '/../app/Views/welcome.php';

} elseif ($uri === '/login') {
    $auth = new AuthController($db);
    if ($method === 'POST') $auth->login(); 
    else $auth->showLogin();

} elseif ($uri === '/logout') {
    $auth = new AuthController($db);
    $auth->logout();

// === DASHBOARD ===
} elseif ($uri === '/dashboard') {
    $dashboard = new DashboardController($db);
    $dashboard->index();

// === MONITORING ===
} elseif ($uri === '/monitoring') {
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $monitor = new MonitoringController($db);
    $monitor->index($page, 10);

} elseif ($uri === '/monitoring/detail') {
    $id = (int)$_GET['id'];
    $monitor = new MonitoringController($db);
    $monitor->detail($id);

// === NOTIFIKASI ===
} elseif ($uri === '/notifikasi') {
    $notif = new NotifikasiController($db);
    $notif->index();

} elseif ($uri === '/notifikasi/read' && $method === 'POST') {
    $id = (int)$_GET['id'];
    $notif = new NotifikasiController($db);
    $notif->read($id);

// === USULAN (3-STEP WIZARD) ===
} elseif ($uri === '/usulan/create') {
    $wizard = new UsulanWizardController($db);
    if ($method === 'POST') {
        $wizard->saveDraft();
    } else {
        $wizard->create();
    }

} elseif ($uri === '/usulan/submit' && $method === 'POST') {
    $wizard = new UsulanWizardController($db);
    $wizard->submit();

} elseif ($uri === '/usulan/list') {
    $wizard = new UsulanWizardController($db);
    $wizard->listUsulan();

} elseif ($uri === '/usulan/detail') {
    $id = (int)$_GET['id'];
    $wizard = new UsulanWizardController($db);
    $wizard->detail($id);

} elseif ($uri === '/usulan/edit') {
    $id = (int)$_GET['id'];
    $wizard = new UsulanWizardController($db);
    if ($method === 'POST') {
        $wizard->update($id);
    } else {
        $wizard->edit($id);
    }

// === PENGAJUAN KEGIATAN ===
} elseif ($uri === '/pengajuan/list') {
    $pengajuan = new PengajuanKegiatanController($db);
    $pengajuan->listUsulanDisetujui();

} elseif ($uri === '/pengajuan/create') {
    $pengajuan = new PengajuanKegiatanController($db);
    if ($method === 'POST') {
        $pengajuan->store();
    } else {
        $pengajuan->create();
    }

} elseif ($uri === '/pengajuan/detail') {
    $id = (int)$_GET['id'];
    $pengajuan = new PengajuanKegiatanController($db);
    $pengajuan->detail($id);

} elseif ($uri === '/pengajuan/upload-lpj') {
    $pengajuan = new PengajuanKegiatanController($db);
    if ($method === 'POST') {
        $pengajuan->storeLpj();
    } else {
        $pengajuan->uploadLpj();
    }

// === VERIFIKASI ===
} elseif ($uri === '/verifikasi') {
    $verif = new VerifikasiController($db);
    $verif->index();

} elseif ($uri === '/verifikasi/proses') {
    $verif = new VerifikasiController($db);
    $verif->proses();

} elseif ($uri === '/verifikasi/setujui' && $method === 'POST') {
    $verif = new VerifikasiController($db);
    $verif->setujui();

} elseif ($uri === '/verifikasi/revisi' && $method === 'POST') {
    $verif = new VerifikasiController($db);
    $verif->revisi();

} elseif ($uri === '/verifikasi/tolak' && $method === 'POST') {
    $verif = new VerifikasiController($db);
    $verif->tolak();

} elseif ($uri === '/verifikasi/riwayat') {
    $verif = new VerifikasiController($db);
    $verif->riwayat();

// === PPK ===
} elseif ($uri === '/ppk') {
    $ppk = new PPKController($db);
    $ppk->index();

} elseif ($uri === '/ppk/proses') {
    $ppk = new PPKController($db);
    $ppk->proses();

} elseif ($uri === '/ppk/approve' && $method === 'POST') {
    $ppk = new PPKController($db);
    $ppk->approve();

} elseif ($uri === '/ppk/riwayat') {
    $ppk = new PPKController($db);
    $ppk->riwayat();

// === WD2 ===
} elseif ($uri === '/wd2') {
    $wd2 = new WD2Controller($db);
    $wd2->index();

} elseif ($uri === '/wd2/proses') {
    $wd2 = new WD2Controller($db);
    $wd2->proses();

} elseif ($uri === '/wd2/approve' && $method === 'POST') {
    $wd2 = new WD2Controller($db);
    $wd2->approve();

} elseif ($uri === '/wd2/riwayat') {
    $wd2 = new WD2Controller($db);
    $wd2->riwayat();

// === BENDAHARA ===
} elseif ($uri === '/bendahara/pencairan') {
    $bendahara = new BendaharaController($db);
    $bendahara->pencairan();

} elseif ($uri === '/bendahara/form-pencairan') {
    $bendahara = new BendaharaController($db);
    $bendahara->formPencairan();

} elseif ($uri === '/bendahara/proses-pencairan' && $method === 'POST') {
    $bendahara = new BendaharaController($db);
    $bendahara->prosesPencairan();

} elseif ($uri === '/bendahara/selesaikan-pencairan' && $method === 'POST') {
    $bendahara = new BendaharaController($db);
    $bendahara->selesaikanPencairan();

} elseif ($uri === '/bendahara/lpj-list') {
    $bendahara = new BendaharaController($db);
    $bendahara->lpjList();

} elseif ($uri === '/bendahara/lpj-detail') {
    $bendahara = new BendaharaController($db);
    $bendahara->lpjDetail();

} elseif ($uri === '/bendahara/verifikasi-lpj' && $method === 'POST') {
    $bendahara = new BendaharaController($db);
    $bendahara->verifikasiLpj();

} elseif ($uri === '/bendahara/riwayat') {
    $bendahara = new BendaharaController($db);
    $bendahara->riwayatLpj();

// === ADMIN ===
} elseif ($uri === '/users') {
    $admin = new AdminController($db);
    $admin->users();

} elseif ($uri === '/users/create' && $method === 'POST') {
    $admin = new AdminController($db);
    $admin->createUser();

} elseif ($uri === '/users/update' && $method === 'POST') {
    $admin = new AdminController($db);
    $admin->updateUser();

} elseif ($uri === '/users/toggle-status' && $method === 'POST') {
    $admin = new AdminController($db);
    $admin->toggleUserStatus();

} elseif ($uri === '/master') {
    $admin = new AdminController($db);
    $admin->masterData();

} elseif ($uri === '/master/jurusan') {
    $admin = new AdminController($db);
    $admin->jurusan();

} elseif ($uri === '/master/jurusan/create' && $method === 'POST') {
    $admin = new AdminController($db);
    $admin->createJurusan();

} elseif ($uri === '/master/jurusan/update' && $method === 'POST') {
    $admin = new AdminController($db);
    $admin->updateJurusan();

} elseif ($uri === '/master/jurusan/toggle' && $method === 'POST') {
    $admin = new AdminController($db);
    $admin->toggleJurusanStatus();

} elseif ($uri === '/master/iku') {
    $admin = new AdminController($db);
    $admin->iku();

} elseif ($uri === '/master/iku/create' && $method === 'POST') {
    $admin = new AdminController($db);
    $admin->createIku();

} elseif ($uri === '/master/iku/update' && $method === 'POST') {
    $admin = new AdminController($db);
    $admin->updateIku();

} elseif ($uri === '/master/iku/toggle' && $method === 'POST') {
    $admin = new AdminController($db);
    $admin->toggleIkuStatus();

} elseif ($uri === '/master/satuan') {
    $admin = new AdminController($db);
    $admin->satuan();

} elseif ($uri === '/master/satuan/create' && $method === 'POST') {
    $admin = new AdminController($db);
    $admin->createSatuan();

} elseif ($uri === '/master/satuan/update' && $method === 'POST') {
    $admin = new AdminController($db);
    $admin->updateSatuan();

} elseif ($uri === '/master/satuan/toggle' && $method === 'POST') {
    $admin = new AdminController($db);
    $admin->toggleSatuanStatus();

} elseif ($uri === '/admin/monitoring-kegiatan') {
    $admin = new AdminController($db);
    $admin->monitoringKegiatan();

// === DIREKTUR (READ-ONLY) ===
} elseif ($uri === '/direktur/monitoring' || $uri === '/monitoring') {
    // Direktur menggunakan monitoring yang sama dengan Admin
    $admin = new AdminController($db);
    $admin->monitoringKegiatan();

} elseif ($uri === '/direktur/users') {
    $direktur = new DirekturController($db);
    $direktur->users();

} elseif ($uri === '/direktur/master') {
    $direktur = new DirekturController($db);
    $direktur->masterData();

} elseif ($uri === '/direktur/master/jurusan') {
    $direktur = new DirekturController($db);
    $direktur->jurusan();

} elseif ($uri === '/direktur/master/iku') {
    $direktur = new DirekturController($db);
    $direktur->iku();

} elseif ($uri === '/direktur/master/satuan') {
    $direktur = new DirekturController($db);
    $direktur->satuan();

// === LAPORAN (Untuk Direktur & Admin) ===
} elseif ($uri === '/laporan') {
    require __DIR__ . '/../app/Views/laporan/index.php';

// === PDF GENERATOR ===
} elseif (strpos($uri, '/pdf/') === 0) {
    $id = (int)$_GET['id'];
    $pdf = new PdfController($db);
    if ($uri === '/pdf/kak') $pdf->kak($id);
    elseif ($uri === '/pdf/rab') $pdf->rab($id);
    elseif ($uri === '/pdf/surat-teguran') $pdf->suratTeguran($id);
    elseif ($uri === '/pdf/berita-acara') $pdf->beritaAcara($id);

// === PROFIL & PAGES ===
} elseif ($uri === '/profil') {
    $page = new PageController($db);
    $page->profil();

} elseif ($uri === '/profil/update-password' && $method === 'POST') {
    $page = new PageController($db);
    $page->updatePassword();

} elseif ($uri === '/profil/update-data' && $method === 'POST') {
    $page = new PageController($db);
    $page->updateProfile();

} elseif ($uri === '/bantuan') {
    $page = new PageController($db);
    $page->bantuan();

} elseif ($uri === '/syarat') {
    $page = new PageController($db);
    $page->syarat();

// === 404 ===
} else {
    http_response_code(404);
    require __DIR__ . '/../app/Views/errors/404.php';
}