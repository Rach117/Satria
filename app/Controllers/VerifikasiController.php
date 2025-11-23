<?php
// app/Controllers/VerifikasiController.php
namespace App\Controllers;
use App\Models\UsulanModel;

class VerifikasiController {
    private $db;
    private $usulanModel;
    
    public function __construct($db) {
        $this->db = $db;
        $this->usulanModel = new UsulanModel($db);
    }

    private function checkVerifikator() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Verifikator') {
            header('Location: /login'); exit;
        }
    }

    // === LIST USULAN MENUNGGU VERIFIKASI ===
    public function index() {
        $this->checkVerifikator();
        
        $usulan_list = $this->db->query("SELECT uk.*, us.username, j.nama_jurusan 
                                          FROM usulan_kegiatan uk 
                                          JOIN users us ON uk.user_id = us.id 
                                          LEFT JOIN master_jurusan j ON us.jurusan_id = j.id 
                                          WHERE uk.status_usulan = 'Diajukan' 
                                          ORDER BY uk.created_at ASC")->fetchAll(\PDO::FETCH_ASSOC);
        
        require __DIR__ . '/../Views/verifikasi/index.php';
    }

    // === FORM PROSES VERIFIKASI ===
    public function proses() {
        $this->checkVerifikator();
        
        $id = (int)$_GET['id'];
        $usulan = $this->usulanModel->getUsulanById($id);
        
        if (!$usulan || $usulan['status_usulan'] !== 'Diajukan') {
            die('Data tidak valid');
        }
        
        require __DIR__ . '/../Views/verifikasi/proses.php';
    }

    // === SETUJUI USULAN ===
    public function setujui() {
        $this->checkVerifikator();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        $id = (int)$_POST['usulan_id'];
        $kode_mak = $_POST['kode_mak'];
        $catatan = $_POST['catatan'] ?? '';
        
        $this->usulanModel->setujuUsulan($id, $kode_mak);
        
        // Log Histori
        $this->db->prepare("INSERT INTO log_histori (usulan_id, user_id, aksi, status_lama, status_baru, catatan) VALUES (?, ?, ?, ?, ?, ?)")
                 ->execute([$id, $_SESSION['user_id'], 'Verifikasi Disetujui', 'Diajukan', 'Disetujui', $catatan]);
        
        // Notifikasi ke Pengusul
        $usulan = $this->usulanModel->getUsulanById($id);
        $this->db->prepare("INSERT INTO notifikasi (user_id, judul, pesan, link) VALUES (?, ?, ?, ?)")
                 ->execute([
                     $usulan['user_id'],
                     'Usulan Disetujui Verifikator',
                     'Usulan Anda telah disetujui! Silakan lanjutkan dengan mengajukan kegiatan.',
                     '/pengajuan/list'
                 ]);
        
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Usulan berhasil disetujui!'];
        header('Location: /verifikasi'); exit;
    }

    // === REVISI USULAN ===
    public function revisi() {
        $this->checkVerifikator();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        $id = (int)$_POST['usulan_id'];
        $catatan = $_POST['catatan'];
        
        $this->usulanModel->revisiUsulan($id, $catatan);
        
        // Log Histori
        $this->db->prepare("INSERT INTO log_histori (usulan_id, user_id, aksi, status_lama, status_baru, catatan) VALUES (?, ?, ?, ?, ?, ?)")
                 ->execute([$id, $_SESSION['user_id'], 'Minta Revisi', 'Diajukan', 'Revisi', $catatan]);
        
        // Notifikasi ke Pengusul
        $usulan = $this->usulanModel->getUsulanById($id);
        $this->db->prepare("INSERT INTO notifikasi (user_id, judul, pesan, link) VALUES (?, ?, ?, ?)")
                 ->execute([
                     $usulan['user_id'],
                     'Usulan Perlu Revisi',
                     'Verifikator meminta revisi pada usulan Anda. Cek catatan.',
                     '/usulan/edit?id=' . $id
                 ]);
        
        $_SESSION['toast'] = ['type' => 'info', 'msg' => 'Usulan dikembalikan untuk revisi.'];
        header('Location: /verifikasi'); exit;
    }

    // === TOLAK USULAN ===
    public function tolak() {
        $this->checkVerifikator();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        $id = (int)$_POST['usulan_id'];
        $catatan = $_POST['catatan'];
        
        $this->usulanModel->tolakUsulan($id, $catatan);
        
        // Log Histori
        $this->db->prepare("INSERT INTO log_histori (usulan_id, user_id, aksi, status_lama, status_baru, catatan) VALUES (?, ?, ?, ?, ?, ?)")
                 ->execute([$id, $_SESSION['user_id'], 'Tolak Usulan', 'Diajukan', 'Ditolak', $catatan]);
        
        // Notifikasi ke Pengusul
        $usulan = $this->usulanModel->getUsulanById($id);
        $this->db->prepare("INSERT INTO notifikasi (user_id, judul, pesan, link) VALUES (?, ?, ?, ?)")
                 ->execute([
                     $usulan['user_id'],
                     'Usulan Ditolak',
                     'Usulan Anda ditolak oleh verifikator. Cek catatan untuk detail.',
                     '/usulan/detail?id=' . $id
                 ]);
        
        $_SESSION['toast'] = ['type' => 'warning', 'msg' => 'Usulan ditolak.'];
        header('Location: /verifikasi'); exit;
    }

    // === RIWAYAT VERIFIKASI ===
    public function riwayat() {
        $this->checkVerifikator();
        
        $sql = "SELECT uk.*, us.username, j.nama_jurusan 
                FROM usulan_kegiatan uk 
                JOIN users us ON uk.user_id = us.id 
                LEFT JOIN master_jurusan j ON us.jurusan_id = j.id 
                WHERE uk.status_usulan IN ('Disetujui', 'Revisi', 'Ditolak')
                ORDER BY uk.updated_at DESC";
        
        $riwayat = $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        
        // Hitung statistik
        $stats = [
            'disetujui' => $this->usulanModel->getUsulanByStatus('Disetujui'),
            'revisi' => $this->usulanModel->getUsulanByStatus('Revisi'),
            'ditolak' => $this->usulanModel->getUsulanByStatus('Ditolak')
        ];
        
        require __DIR__ . '/../Views/verifikasi/riwayat.php';
    }
}