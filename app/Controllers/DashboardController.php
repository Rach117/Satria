<?php
// app/Controllers/DashboardController.php
namespace App\Controllers;
use App\Models\UsulanModel;
use App\Models\PengajuanModel;
use App\Models\MasterDataModel;

class DashboardController
{
    private $db;
    
    public function __construct($db = null) {
        $this->db = $db;
    }
    
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        
        $role = $_SESSION['role'] ?? '';
        
        // Inisialisasi data berdasarkan role
        switch ($role) {
            case 'Admin':
                $this->loadAdminDashboard();
                break;
            case 'Pengusul':
                $this->loadPengusulDashboard();
                break;
            case 'Verifikator':
                $this->loadVerifikatorDashboard();
                break;
            case 'WD2':
                $this->loadWD2Dashboard();
                break;
            case 'PPK':
                $this->loadPPKDashboard();
                break;
            case 'Bendahara':
                $this->loadBendaharaDashboard();
                break;
            case 'Direktur':
                $this->loadDirekturDashboard();
                break;
            default:
                http_response_code(403);
                require __DIR__ . '/../Views/errors/403.php';
        }
    }
    
    private function loadAdminDashboard() {
        $masterModel = new MasterDataModel($this->db);
        $stats = $masterModel->getStatsUsulan();
        $tahun = $_GET['tahun'] ?? date('Y');
        $grafik_data = $masterModel->getUsulanPerPengusul($tahun);
        
        require __DIR__ . '/../Views/dashboard/admin.php';
    }
    
    private function loadPengusulDashboard() {
        $usulanModel = new UsulanModel($this->db);
        $stats = $usulanModel->getStatsByUser($_SESSION['user_id']);
        
        require __DIR__ . '/../Views/dashboard/pengusul.php';
    }
    
    private function loadVerifikatorDashboard() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM usulan_kegiatan WHERE status_usulan = 'Diajukan'");
        $pending = $stmt->fetch(\PDO::FETCH_ASSOC);
        $stats = ['pending' => $pending['total']];
        
        require __DIR__ . '/../Views/dashboard/verifikator.php';
    }
    
    private function loadWD2Dashboard() {
        // Ambil usulan yang sedang menunggu approval
        $stmt = $this->db->prepare("
            SELECT uk.*, us.username, j.nama_jurusan 
            FROM usulan_kegiatan uk 
            JOIN users us ON uk.user_id = us.id 
            LEFT JOIN master_jurusan j ON us.jurusan_id = j.id 
            ORDER BY uk.created_at DESC 
            LIMIT 10
        ");
        $stmt->execute();
        $usulan = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        require __DIR__ . '/../Views/dashboard/wd2.php';
    }
    
    private function loadPPKDashboard() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM pengajuan_kegiatan WHERE status_pengajuan = 'Menunggu PPK'");
        $pending = $stmt->fetch(\PDO::FETCH_ASSOC);
        $stats = ['pending' => $pending['total']];
        
        require __DIR__ . '/../Views/dashboard/ppk.php';
    }
    
    private function loadBendaharaDashboard() {
        $pengajuanModel = new PengajuanModel($this->db);
        $stats = $pengajuanModel->getStatsBendahara();
        
        require __DIR__ . '/../Views/dashboard/bendahara.php';
    }
    
    private function loadDirekturDashboard() {
        $masterModel = new MasterDataModel($this->db);
        $stats = $masterModel->getStatsUsulan();
        
        // Total dana yang dicairkan
        $total_pencairan = $this->db->query("SELECT COALESCE(SUM(nominal_pencairan), 0) as total FROM pencairan_dana")->fetch(\PDO::FETCH_ASSOC);
        $stats['total_pencairan'] = $total_pencairan['total'];
        
        require __DIR__ . '/../Views/dashboard/direktur.php';
    }
}