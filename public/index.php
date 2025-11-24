<?php
// public/index.php

require_once __DIR__ . '/../vendor/autoload.php';
// [ELITE INFRA] Load Environment Variables
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
    // Log error sebenarnya ke file server (jangan tampilkan ke user)
    error_log($e->getMessage());
    
    // Tampilkan halaman cantik 500
    http_response_code(500);
    require __DIR__ . '/../app/Views/errors/500.php';
    exit;
});

$config = require __DIR__ . '/../config/app.php';
date_default_timezone_set($config['timezone']);
$dbConfig = require __DIR__ . '/../config/database.php';
$dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
$db = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $dbConfig['options']);

// Import Semua Controller
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\MonitoringController;
use App\Controllers\UsulanController;
use App\Controllers\UsulanWizardController;
use App\Controllers\VerifikasiController;
use App\Controllers\ApprovalController;
use App\Controllers\NotifikasiController;
use App\Controllers\AdminController;
use App\Controllers\KeuanganController; // Baru
use App\Controllers\LaporanController;  // Baru
use App\Controllers\PdfController;
use App\Controllers\DirekturController;

function sanitizeInput($data) { return htmlspecialchars(strip_tags(trim($data))); }
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

// --- ROUTING ---

if ($uri === '/' || $uri === '/index.php') {
    require __DIR__ . '/../app/Views/welcome.php';

} elseif ($uri === '/login') {
    $auth = new AuthController($db);
    if ($method === 'POST') $auth->login(); else $auth->showLogin();

} elseif ($uri === '/logout') {
    $auth = new AuthController($db);
    $auth->logout();

// DASHBOARD & CORE
} elseif ($uri === '/dashboard') {
    $dashboard = new DashboardController($db); // Tambahkan $db parameter
    $dashboard->index();
} elseif ($uri === '/monitoring') {
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $monitor = new MonitoringController($db);
    $monitor->index($page, 10);
} elseif ($uri === '/notifikasi') {
    $notif = new NotifikasiController($db);
    $notif->index();
} elseif ($uri === '/notifikasi/read' && $method === 'POST') {
    $id = (int)$_GET['id'];
    $notif = new NotifikasiController($db);
    $notif->read($id);

// MODUL PENGUSUL
} elseif ($uri === '/usulan/create') {
    $wizard = new UsulanWizardController($db);
    if ($method === 'POST') $wizard->store(); else $wizard->create();
} elseif ($uri === '/usulan/detail') {
    $id = (int)$_GET['id'];
    $usulan = new UsulanController($db);
    $usulan->detail($id);

// MODUL VERIFIKASI
} elseif ($uri === '/verifikasi') {
    $verif = new VerifikasiController($db);
    $verif->index();
} elseif ($uri === '/verifikasi/proses') {
    $id = (int)$_GET['id'];
    $verif = new VerifikasiController($db);
    $verif->proses($id);
} elseif ($uri === '/verifikasi/aksi' && $method === 'POST') {
    $id = (int)$_GET['id'];
    $verif = new VerifikasiController($db);
    $verif->aksi($id);

// MODUL APPROVAL (WD2/PPK)
} elseif ($uri === '/approval') {
    $app = new ApprovalController($db);
    $app->index();
} elseif ($uri === '/approval/proses') {
    $id = (int)$_GET['id'];
    $app = new ApprovalController($db);
    $app->proses($id);
} elseif ($uri === '/approval/aksi' && $method === 'POST') {
    $id = (int)$_GET['id'];
    $app = new ApprovalController($db);
    $app->aksi($id);

// MODUL KEUANGAN (BENDAHARA) - FIXED
} elseif ($uri === '/pencairan') {
    $keu = new KeuanganController($db);
    $keu->indexPencairan();
} elseif ($uri === '/pencairan/proses' && $method === 'POST') {
    $id = (int)$_GET['id'];
    $keu = new KeuanganController($db);
    $keu->prosesPencairan($id);
} elseif ($uri === '/lpj') {
    $keu = new KeuanganController($db);
    $keu->indexLPJ();
} elseif ($uri === '/lpj/verifikasi' && $method === 'POST') {
    $id = (int)$_GET['id'];
    $keu = new KeuanganController($db);
    $keu->verifikasiLPJ($id);

// MODUL DIREKTUR
} elseif ($uri === '/direktur/monitoring') {
    $direktur = new DirekturController($db);
    $direktur->monitoringKegiatan();
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

// MODUL ADMIN
} elseif ($uri === '/users') {
    $admin = new AdminController($db);
    $admin->users();
} elseif ($uri === '/users/create' && $method === 'POST') { // RUTE BARU
    $admin = new AdminController($db);
    $admin->createUser();
} elseif ($uri === '/users/delete' && $method === 'POST') { // RUTE BARU
    $admin = new AdminController($db);
    $admin->deleteUser();
} elseif ($uri === '/master') {
    $admin = new AdminController($db);
    $admin->indexMaster(); // Panggil halaman landing master
} elseif ($uri === '/master/iku/store' && $method === 'POST') {
    $admin = new AdminController($db);
    $admin->createIku();
} elseif ($uri === '/master/iku/update' && $method === 'POST') {
    $admin = new AdminController($db);
    $admin->updateIku();
} elseif ($uri === '/master/iku/toggle' && $method === 'POST') {
    $admin = new AdminController($db);
    $admin->toggleIkuStatus();
} elseif ($uri === '/master/jurusan/store' && $method === 'POST') {
    $admin = new AdminController($db);
    $admin->createJurusan();
} elseif ($uri === '/master/jurusan/update' && $method === 'POST') {
    $admin = new AdminController($db);
    $admin->updateJurusan();
} elseif ($uri === '/master/jurusan/toggle' && $method === 'POST') {
    $admin = new AdminController($db);
    $admin->toggleJurusanStatus();
} elseif ($uri === '/master/satuan' && $method === 'GET') {
    $admin = new AdminController($db);
    $admin->satuan();
} elseif ($uri === '/master/satuan/store' && $method === 'POST') {
    $admin = new AdminController($db);
    $admin->createSatuan();
} elseif ($uri === '/master/satuan/update' && $method === 'POST') {
    $admin = new AdminController($db);
    $admin->updateSatuan();
} elseif ($uri === '/master/satuan/toggle' && $method === 'POST') {
    $admin = new AdminController($db);
    $admin->toggleSatuanStatus();
} elseif ($uri === '/audit-log') {
    $audit = new \App\Controllers\AuditLogController($db);
    $audit->index();
} elseif ($uri === '/audit-log/export') {
    $audit = new \App\Controllers\AuditLogController($db);
    $audit->export();

// PDF GENERATOR
} elseif (strpos($uri, '/pdf/') === 0) {
    $id = (int)$_GET['id'];
    $pdf = new PdfController($db);
    if ($uri === '/pdf/kak') $pdf->kak($id);
    elseif ($uri === '/pdf/rab') $pdf->rab($id);
    elseif ($uri === '/pdf/surat_teguran') $pdf->suratTeguran($id);
    elseif ($uri === '/pdf/berita_acara') $pdf->beritaAcara($id);

} elseif ($uri === '/profil') {
    $page = new \App\Controllers\PageController($db);
    $page->profil();
} elseif ($uri === '/bantuan') {
    $page = new \App\Controllers\PageController($db);
    $page->bantuan();
} elseif ($uri === '/syarat') {
    $page = new \App\Controllers\PageController($db);
    $page->syarat();

} elseif ($uri === '/profil/update-password' && $method === 'POST') {
    $page = new \App\Controllers\PageController($db);
    $page->updatePassword();
} elseif ($uri === '/profil/update-data' && $method === 'POST') {
    $page = new \App\Controllers\PageController($db);
    $page->updateProfile();
    
} else {
    http_response_code(404);
    require __DIR__ . '/../app/Views/errors/404.php';
}