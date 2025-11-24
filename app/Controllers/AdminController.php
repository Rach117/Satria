<?php
// app/Controllers/AdminController.php
namespace App\Controllers;
use App\Models\MasterDataModel;

class AdminController {
    private $db;
    private $masterModel;
    
    public function __construct($db) {
        $this->db = $db;
        $this->masterModel = new MasterDataModel($db);
    }

    private function checkAdmin() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
            header('Location: /login'); exit;
        }
    }

    // === DASHBOARD ADMIN ===
    public function dashboard() {
        $this->checkAdmin();
        
        $stats = $this->masterModel->getStatsUsulan();
        
        // Grafik per pengusul (bisa filter tahun)
        $tahun = $_GET['tahun'] ?? date('Y');
        $grafik_data = $this->masterModel->getUsulanPerPengusul($tahun);
        
        require __DIR__ . '/../Views/dashboard/admin.php';
    }

    // ========== MANAJEMEN PENGGUNA ==========
    
    public function users() {
        $this->checkAdmin();
        
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
        
        require __DIR__ . '/../Views/admin/users.php';
    }

    public function createUser() {
        $this->checkAdmin();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role = $_POST['role'];
        $jurusan_id = ($role === 'Pengusul') ? ($_POST['jurusan_id'] ?? null) : null;
        
        try {
            $stmt = $this->db->prepare("INSERT INTO users (username, email, password, role, jurusan_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$username, $email, $password, $role, $jurusan_id]);
            
            $_SESSION['toast'] = ['type' => 'success', 'msg' => 'User berhasil ditambahkan!'];
        } catch (\Exception $e) {
            $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Username/Email sudah ada!'];
        }
        
        header('Location: /admin/users'); exit;
    }

    public function updateUser() {
        $this->checkAdmin();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        $id = (int)$_POST['user_id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $jurusan_id = ($role === 'Pengusul') ? ($_POST['jurusan_id'] ?? null) : null;
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        $sql = "UPDATE users SET username = ?, email = ?, role = ?, jurusan_id = ?, is_active = ? WHERE id = ?";
        $params = [$username, $email, $role, $jurusan_id, $is_active, $id];
        
        // Jika ada password baru
        if (!empty($_POST['password'])) {
            $sql = "UPDATE users SET username = ?, email = ?, password = ?, role = ?, jurusan_id = ?, is_active = ? WHERE id = ?";
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $params = [$username, $email, $password, $role, $jurusan_id, $is_active, $id];
        }
        
        $this->db->prepare($sql)->execute($params);
        
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'User berhasil diperbarui!'];
        header('Location: /admin/users'); exit;
    }

    public function toggleUserStatus() {
        $this->checkAdmin();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        $id = (int)$_POST['user_id'];
        $this->db->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ?")->execute([$id]);
        
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Status user diperbarui!'];
        header('Location: /admin/users'); exit;
    }

    // ========== MASTER DATA ==========
    
    public function masterData() {
        $this->checkAdmin();
        require __DIR__ . '/../Views/admin/master_data.php';
    }

    // --- JURUSAN ---
    
    public function jurusan() {
        $this->checkAdmin();
        
        $jurusan_list = $this->masterModel->getAllJurusan(true); // Include inactive
        
        require __DIR__ . '/../Views/admin/master_jurusan.php';
    }

    public function createJurusan() {
        $this->checkAdmin();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        $this->masterModel->createJurusan($_POST['nama_jurusan']);
        
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Jurusan berhasil ditambahkan!'];
        header('Location: /admin/master/jurusan'); exit;
    }

    public function updateJurusan() {
        $this->checkAdmin();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        $this->masterModel->updateJurusan($_POST['jurusan_id'], $_POST['nama_jurusan']);
        
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Jurusan berhasil diperbarui!'];
        header('Location: /admin/master/jurusan'); exit;
    }

    public function toggleJurusanStatus() {
        $this->checkAdmin();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        $this->masterModel->toggleJurusanStatus($_POST['jurusan_id']);
        
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Status jurusan diperbarui!'];
        header('Location: /admin/master/jurusan'); exit;
    }

    // --- IKU ---
    
    public function iku() {
        $this->checkAdmin();
        
        $iku_list = $this->masterModel->getAllIku(true); // Include inactive
        
        require __DIR__ . '/../Views/admin/master_iku.php';
    }

    public function createIku() {
        $this->checkAdmin();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        if ($this->masterModel->createIku($_POST['deskripsi_iku'])) {
            $_SESSION['toast'] = ['type' => 'success', 'msg' => 'IKU berhasil ditambahkan!'];
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'msg' => 'IKU sudah ada!'];
        }
        
        header('Location: /admin/master/iku'); exit;
    }

    public function updateIku() {
        $this->checkAdmin();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        if ($this->masterModel->updateIku($_POST['iku_id'], $_POST['deskripsi_iku'])) {
            $_SESSION['toast'] = ['type' => 'success', 'msg' => 'IKU berhasil diperbarui!'];
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'msg' => 'IKU sudah ada!'];
        }
        
        header('Location: /admin/master/iku'); exit;
    }

    public function toggleIkuStatus() {
        $this->checkAdmin();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        $this->masterModel->toggleIkuStatus($_POST['iku_id']);
        
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Status IKU diperbarui!'];
        header('Location: /admin/master/iku'); exit;
    }

    // --- SATUAN ANGGARAN ---
    
    public function satuan() {
        $this->checkAdmin();
        
        $satuan_list = $this->masterModel->getAllSatuan(true); // Include inactive
        
        require __DIR__ . '/../Views/admin/master_satuan.php';
    }

    public function createSatuan() {
        $this->checkAdmin();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        if ($this->masterModel->createSatuan($_POST['nama_satuan'])) {
            $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Satuan berhasil ditambahkan!'];
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Satuan sudah ada!'];
        }
        
        header('Location: /admin/master/satuan'); exit;
    }

    public function updateSatuan() {
        $this->checkAdmin();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        if ($this->masterModel->updateSatuan($_POST['satuan_id'], $_POST['nama_satuan'])) {
            $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Satuan berhasil diperbarui!'];
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Satuan sudah ada!'];
        }
        
        header('Location: /admin/master/satuan'); exit;
    }

    public function toggleSatuanStatus() {
        $this->checkAdmin();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        $this->masterModel->toggleSatuanStatus($_POST['satuan_id']);
        
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Status satuan diperbarui!'];
        header('Location: /admin/master/satuan'); exit;
    }

    // === MONITORING KEGIATAN (dengan Filter) ===
    
    public function monitoringKegiatan() {
    $this->checkAdmin();
    
    $jurusan_filter = $_GET['jurusan'] ?? '';
    $status_filter = $_GET['status'] ?? '';
    $tahun_filter = $_GET['tahun'] ?? '';
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $perPage = 20;
    $offset = ($page - 1) * $perPage;
    
    // Build query with CRITICAL filter: Exclude Draft status
    $sql = "SELECT uk.*, us.username, j.nama_jurusan, pk.status_pengajuan
            FROM usulan_kegiatan uk 
            JOIN users us ON uk.user_id = us.id 
            LEFT JOIN master_jurusan j ON us.jurusan_id = j.id 
            LEFT JOIN pengajuan_kegiatan pk ON uk.id = pk.usulan_id
            WHERE uk.status_usulan != 'Draft'"; // CRITICAL: Exclude Draft
    
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
    
    // Count total for pagination
    $countSql = "SELECT COUNT(*) as total FROM ($sql) as count_query";
    $countStmt = $this->db->prepare($countSql);
    $countStmt->execute($params);
    $total = $countStmt->fetch(\PDO::FETCH_ASSOC)['total'];
    
    // Add pagination
    $sql .= " ORDER BY uk.created_at DESC LIMIT :offset, :perPage";
    
    $stmt = $this->db->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }
    $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
    $stmt->bindValue(':perPage', $perPage, \PDO::PARAM_INT);
    $stmt->execute();
    $kegiatan_list = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
    // Get stats (excluding Draft)
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
    
    require __DIR__ . '/../Views/admin/monitoring_kegiatan.php';
}
}