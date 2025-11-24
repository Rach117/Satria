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
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        // Same query as Admin, excluding Draft
        $sql = "SELECT uk.*, us.username, j.nama_jurusan, pk.status_pengajuan
                FROM usulan_kegiatan uk 
                JOIN users us ON uk.user_id = us.id 
                LEFT JOIN master_jurusan j ON us.jurusan_id = j.id 
                LEFT JOIN pengajuan_kegiatan pk ON uk.id = pk.usulan_id
                WHERE uk.status_usulan != 'Draft'";
        
        $params = [];
        
        if ($jurusan_filter) {
            $sql .= " AND us.jurusan_id = :jurusan";
            $params['jurusan'] = $jurusan_filter;
        }
        
        if ($status_filter) {
            $sql .= " AND uk.status_usulan = :status";
            $params['status'] = $status_filter;
        }
        
        if ($tahun_filter) {
            $sql .= " AND YEAR(uk.created_at) = :tahun";
            $params['tahun'] = $tahun_filter;
        }
        
        $countSql = "SELECT COUNT(*) as total FROM ($sql) as count_query";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = $countStmt->fetch(\PDO::FETCH_ASSOC)['total'];
        
        $sql .= " ORDER BY uk.created_at DESC LIMIT :offset, :perPage";
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->bindValue(':perPage', $perPage, \PDO::PARAM_INT);
        $stmt->execute();
        $kegiatan_list = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $stats = [
            'total' => $this->db->query("SELECT COUNT(*) FROM usulan_kegiatan WHERE status_usulan != 'Draft'")->fetchColumn(),
            'disetujui' => $this->db->query("SELECT COUNT(*) FROM usulan_kegiatan WHERE status_usulan = 'Disetujui'")->fetchColumn(),
            'revisi' => $this->db->query("SELECT COUNT(*) FROM usulan_kegiatan WHERE status_usulan = 'Revisi'")->fetchColumn(),
            'ditolak' => $this->db->query("SELECT COUNT(*) FROM usulan_kegiatan WHERE status_usulan = 'Ditolak'")->fetchColumn(),
        ];
        
        $jurusan_list = $this->masterModel->getAllJurusan();
        
        $pagination = [
            'current_page' => $page,
            'total_pages' => ceil($total / $perPage),
            'total_items' => $total
        ];
        
        $readonly = true; // Flag untuk view
        
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