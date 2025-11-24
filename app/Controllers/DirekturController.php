<?php
// app/Controllers/DirekturController.php
namespace App\Controllers;
use App\Models\MasterDataModel;
use App\Models\PengajuanModel;

class DirekturController {
    private $db;
    private $masterModel;
    private $pengajuanModel;
    
    public function __construct($db) {
        $this->db = $db;
        $this->masterModel = new MasterDataModel($db);
        $this->pengajuanModel = new PengajuanModel($db);
    }

    private function checkDirektur() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Direktur') {
            header('Location: /login'); exit;
        }
    }

    // === DASHBOARD DIREKTUR (READ-ONLY) ===
    public function dashboard() {
        $this->checkDirektur();
        
        // Ambil statistik usulan
        $stats = $this->masterModel->getStatsUsulan();
        
        // Grafik per pengusul
        $tahun = $_GET['tahun'] ?? date('Y');
        $grafik_data = $this->masterModel->getUsulanPerPengusul($tahun);
        
        // Total dana yang sudah dicairkan
        $total_pencairan = $this->db->query("SELECT COALESCE(SUM(nominal_pencairan), 0) as total FROM pencairan_dana")->fetch(\PDO::FETCH_ASSOC);
        $stats['total_pencairan'] = $total_pencairan['total'];
        
        // Total kegiatan selesai
        $total_selesai = $this->db->query("SELECT COUNT(*) as total FROM pengajuan_kegiatan WHERE id IN (SELECT pengajuan_id FROM lpj_kegiatan GROUP BY pengajuan_id HAVING MIN(status_lpj) = 'Disetujui')")->fetch(\PDO::FETCH_ASSOC);
        $stats['kegiatan_selesai'] = $total_selesai['total'];
        
        require __DIR__ . '/../Views/dashboard/direktur.php';
    }

    // === MONITORING KEGIATAN (READ-ONLY) ===
    public function monitoringKegiatan() {
        $this->checkDirektur();
        
        $jurusan_filter = $_GET['jurusan'] ?? '';
        $status_filter = $_GET['status'] ?? '';
        $tahun_filter = $_GET['tahun'] ?? '';
        
        $sql = "SELECT pk.*, uk.nama_kegiatan, us.username, j.nama_jurusan 
                FROM pengajuan_kegiatan pk 
                JOIN usulan_kegiatan uk ON pk.usulan_id = uk.id 
                JOIN users us ON uk.user_id = us.id 
                LEFT JOIN master_jurusan j ON us.jurusan_id = j.id 
                WHERE 1=1";
        $params = [];
        
        if ($jurusan_filter) {
            $sql .= " AND us.jurusan_id = :jurusan";
            $params['jurusan'] = $jurusan_filter;
        }
        
        if ($status_filter) {
            $sql .= " AND pk.status_pengajuan = :status";
            $params['status'] = $status_filter;
        }
        
        if ($tahun_filter) {
            $sql .= " AND YEAR(pk.created_at) = :tahun";
            $params['tahun'] = $tahun_filter;
        }
        
        $sql .= " ORDER BY pk.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $kegiatan_list = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $jurusan_list = $this->masterModel->getAllJurusan();
        
        require __DIR__ . '/../Views/admin/monitoring_kegiatan.php';
    }

    // === MANAJEMEN USER (READ-ONLY) ===
    public function users() {
        $this->checkDirektur();
        
        $search = $_GET['search'] ?? '';
        $jurusan_filter = $_GET['jurusan'] ?? '';
        
        $sql = "SELECT u.*, j.nama_jurusan 
                FROM users u 
                LEFT JOIN master_jurusan j ON u.jurusan_id = j.id 
                WHERE 1=1";
        $params = [];
        
        if ($search) {
            $sql .= " AND (u.username LIKE :search OR u.email LIKE :search)";
            $params['search'] = "%$search%";
        }
        
        if ($jurusan_filter) {
            $sql .= " AND u.jurusan_id = :jurusan";
            $params['jurusan'] = $jurusan_filter;
        }
        
        $sql .= " ORDER BY u.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $jurusan_list = $this->masterModel->getAllJurusan();
        
        // Set flag read-only
        $readonly = true;
        
        require __DIR__ . '/../Views/admin/users.php';
    }

    // === MASTER DATA (READ-ONLY) ===
    public function masterData() {
        $this->checkDirektur();
        
        // Set flag read-only
        $readonly = true;
        
        require __DIR__ . '/../Views/admin/master_data.php';
    }

    public function jurusan() {
        $this->checkDirektur();
        
        $jurusan_list = $this->masterModel->getAllJurusan(true);
        $readonly = true;
        
        require __DIR__ . '/../Views/admin/master_jurusan.php';
    }

    public function iku() {
        $this->checkDirektur();
        
        $iku_list = $this->masterModel->getAllIku(true);
        $readonly = true;
        
        require __DIR__ . '/../Views/admin/master_iku.php';
    }

    public function satuan() {
        $this->checkDirektur();
        
        $satuan_list = $this->masterModel->getAllSatuan(true);
        $readonly = true;
        
        require __DIR__ . '/../Views/admin/master_satuan.php';
    }
}