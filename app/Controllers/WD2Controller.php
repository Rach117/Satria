<?php
// app/Controllers/WD2Controller.php
namespace App\Controllers;
use App\Models\PengajuanModel;

class WD2Controller {
    private $db;
    private $pengajuanModel;
    
    public function __construct($db) {
        $this->db = $db;
        $this->pengajuanModel = new PengajuanModel($db);
    }

    private function checkWD2() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'WD2') {
            header('Location: /login'); exit;
        }
    }

    // === LIST PENGAJUAN MENUNGGU WD2 ===
    public function index() {
        $this->checkWD2();
        
        $pengajuan_list = $this->pengajuanModel->getPengajuanByStatus('Menunggu WD2');
        
        require __DIR__ . '/../Views/wd2/index.php';
    }

    // === FORM PROSES PENGAJUAN ===
    public function proses() {
        $this->checkWD2();
        
        $id = (int)$_GET['id'];
        $pengajuan = $this->pengajuanModel->getPengajuanById($id);
        
        if (!$pengajuan || $pengajuan['status_pengajuan'] !== 'Menunggu WD2') {
            die('Data tidak valid');
        }
        
        require __DIR__ . '/../Views/wd2/proses.php';
    }

    // === SETUJUI PENGAJUAN ===
    public function approve() {
        $this->checkWD2();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        $id = (int)$_POST['pengajuan_id'];
        $rekomendasi = $_POST['rekomendasi'] ?? '';
        
        // Update status ke "Disetujui"
        $this->pengajuanModel->updateStatus($id, 'Disetujui', $rekomendasi, 'WD2');
        
        // Log Histori
        $this->db->prepare("INSERT INTO log_histori (pengajuan_id, user_id, aksi, status_lama, status_baru, catatan) VALUES (?, ?, ?, ?, ?, ?)")
                 ->execute([$id, $_SESSION['user_id'], 'Approve WD2', 'Menunggu WD2', 'Disetujui', $rekomendasi]);
        
        // Notifikasi ke Bendahara
        $bendahara = $this->db->query("SELECT id FROM users WHERE role = 'Bendahara' AND is_active = 1 LIMIT 1")->fetch(\PDO::FETCH_ASSOC);
        if ($bendahara) {
            $this->db->prepare("INSERT INTO notifikasi (user_id, judul, pesan, link) VALUES (?, ?, ?, ?)")
                     ->execute([
                         $bendahara['id'],
                         'Kegiatan Siap Dicairkan',
                         'Ada kegiatan baru yang telah disetujui WD2, siap untuk pencairan dana',
                         '/bendahara/pencairan'
                     ]);
        }
        
        // Notifikasi ke Pengusul
        $pengajuan = $this->pengajuanModel->getPengajuanById($id);
        $this->db->prepare("INSERT INTO notifikasi (user_id, judul, pesan, link) VALUES (?, ?, ?, ?)")
                 ->execute([
                     $pengajuan['user_id'],
                     'Pengajuan Disetujui WD2',
                     'Selamat! Pengajuan kegiatan Anda telah disetujui WD2. Menunggu pencairan dana.',
                     '/pengajuan/detail?id=' . $id
                 ]);
        
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Pengajuan berhasil disetujui! Siap untuk pencairan.'];
        header('Location: /wd2'); exit;
    }

    // === RIWAYAT PENGAJUAN YANG DISETUJUI ===
    public function riwayat() {
        $this->checkWD2();
        
        $sql = "SELECT p.*, u.nama_kegiatan, us.username 
                FROM pengajuan_kegiatan p 
                JOIN usulan_kegiatan u ON p.usulan_id = u.id 
                JOIN users us ON u.user_id = us.id 
                WHERE p.status_pengajuan = 'Disetujui'
                ORDER BY p.updated_at DESC";
        
        $riwayat = $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        
        require __DIR__ . '/../Views/wd2/riwayat.php';
    }
}