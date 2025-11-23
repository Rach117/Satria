<?php
// app/Controllers/PPKController.php
namespace App\Controllers;
use App\Models\PengajuanModel;

class PPKController {
    private $db;
    private $pengajuanModel;
    
    public function __construct($db) {
        $this->db = $db;
        $this->pengajuanModel = new PengajuanModel($db);
    }

    private function checkPPK() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'PPK') {
            header('Location: /login'); exit;
        }
    }

    // === LIST PENGAJUAN MENUNGGU PPK ===
    public function index() {
        $this->checkPPK();
        
        $pengajuan_list = $this->pengajuanModel->getPengajuanByStatus('Menunggu PPK');
        
        require __DIR__ . '/../Views/ppk/index.php';
    }

    // === FORM PROSES PENGAJUAN ===
    public function proses() {
        $this->checkPPK();
        
        $id = (int)$_GET['id'];
        $pengajuan = $this->pengajuanModel->getPengajuanById($id);
        
        if (!$pengajuan || $pengajuan['status_pengajuan'] !== 'Menunggu PPK') {
            die('Data tidak valid');
        }
        
        require __DIR__ . '/../Views/ppk/proses.php';
    }

    // === SETUJUI PENGAJUAN ===
    public function approve() {
        $this->checkPPK();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        $id = (int)$_POST['pengajuan_id'];
        $rekomendasi = $_POST['rekomendasi'] ?? '';
        
        // Update status ke "Menunggu WD2"
        $this->pengajuanModel->updateStatus($id, 'Menunggu WD2', $rekomendasi, 'PPK');
        
        // Log Histori
        $this->db->prepare("INSERT INTO log_histori (pengajuan_id, user_id, aksi, status_lama, status_baru, catatan) VALUES (?, ?, ?, ?, ?, ?)")
                 ->execute([$id, $_SESSION['user_id'], 'Approve PPK', 'Menunggu PPK','Menunggu WD2', $rekomendasi]);
        
        // Notifikasi ke WD2
        $wd2 = $this->db->query("SELECT id FROM users WHERE role = 'WD2' AND is_active = 1 LIMIT 1")->fetch(\PDO::FETCH_ASSOC);
        if ($wd2) {
            $this->db->prepare("INSERT INTO notifikasi (user_id, judul, pesan, link) VALUES (?, ?, ?, ?)")
                     ->execute([
                         $wd2['id'],
                         'Pengajuan Baru dari PPK',
                         'Ada pengajuan kegiatan yang perlu disetujui',
                         '/wd2/proses?id=' . $id
                     ]);
        }
        
        // Notifikasi ke Pengusul
        $pengajuan = $this->pengajuanModel->getPengajuanById($id);
        $this->db->prepare("INSERT INTO notifikasi (user_id, judul, pesan, link) VALUES (?, ?, ?, ?)")
                 ->execute([
                     $pengajuan['user_id'],
                     'Pengajuan Disetujui PPK',
                     'Pengajuan kegiatan Anda telah disetujui PPK dan diteruskan ke WD2',
                     '/pengajuan/detail?id=' . $id
                 ]);
        
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Pengajuan berhasil disetujui dan diteruskan ke WD2!'];
        header('Location: /ppk'); exit;
    }

    // === RIWAYAT PENGAJUAN YANG DISETUJUI ===
    public function riwayat() {
        $this->checkPPK();
        
        // Ambil pengajuan yang sudah melewati tahap PPK
        $sql = "SELECT p.*, u.nama_kegiatan, us.username 
                FROM pengajuan_kegiatan p 
                JOIN usulan_kegiatan u ON p.usulan_id = u.id 
                JOIN users us ON u.user_id = us.id 
                WHERE p.status_pengajuan IN ('Menunggu WD2', 'Disetujui')
                ORDER BY p.updated_at DESC";
        
        $riwayat = $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        
        require __DIR__ . '/../Views/ppk/riwayat.php';
    }
}