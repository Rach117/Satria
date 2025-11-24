<?php
// app/Controllers/MonitoringController.php (UPDATED)
namespace App\Controllers;

use PDO;
use App\Models\UsulanModel;

class MonitoringController
{
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index($page = 1, $perPage = 10)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $usulanModel = new UsulanModel($this->db);

        // Filter berdasarkan role
        $filters = [
            'role'    => $_SESSION['role'] ?? '',
            'user_id' => $_SESSION['user_id'],
            'search'  => $_GET['q'] ?? '',
            'status'  => $_GET['status'] ?? '',
            'date'    => $_GET['date'] ?? ''
        ];

        // Ambil data usulan dan pengajuan
        $sql = "SELECT uk.*, us.username, j.nama_jurusan,
                pk.status_pengajuan, pk.id as pengajuan_id,
                pd.tanggal_pencairan as tanggal_pencairan_pertama,
                DATE_ADD(pd.tanggal_pencairan, INTERVAL 14 DAY) as tgl_batas_lpj
                FROM usulan_kegiatan uk 
                JOIN users us ON uk.user_id = us.id 
                LEFT JOIN master_jurusan j ON us.jurusan_id = j.id
                LEFT JOIN pengajuan_kegiatan pk ON uk.id = pk.usulan_id
                LEFT JOIN (
                    SELECT pengajuan_id, MIN(tanggal_pencairan) as tanggal_pencairan 
                    FROM pencairan_dana 
                    GROUP BY pengajuan_id
                ) pd ON pk.id = pd.pengajuan_id
                WHERE 1=1";
        
        $params = [];
        
        // Filter berdasarkan role
        if ($filters['role'] === 'Pengusul') {
            $sql .= " AND uk.user_id = :user_id";
            $params['user_id'] = $filters['user_id'];
        }
        
        // Filter search
        if ($filters['search']) {
            $sql .= " AND (uk.nama_kegiatan LIKE :search OR us.username LIKE :search)";
            $params['search'] = "%{$filters['search']}%";
        }
        
        // Filter status
        if ($filters['status']) {
            $sql .= " AND uk.status_usulan = :status";
            $params['status'] = $filters['status'];
        }
        
        // Filter date
        if ($filters['date']) {
            $sql .= " AND DATE(uk.created_at) = :date";
            $params['date'] = $filters['date'];
        }
        
        $sql .= " ORDER BY uk.created_at DESC LIMIT :offset, :perPage";
        
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', (int)$perPage, PDO::PARAM_INT);
        $stmt->execute();
        $usulan = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Count total untuk pagination
        $countSql = str_replace("SELECT uk.*", "SELECT COUNT(*) as total", explode("LIMIT", $sql)[0]);
        $countStmt = $this->db->prepare($countSql);
        foreach ($params as $key => $value) {
            $countStmt->bindValue(":$key", $value);
        }
        $countStmt->execute();
        $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        require __DIR__ . '/../Views/monitoring/index.php';
    }
}