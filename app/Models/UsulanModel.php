<?php
// app/Models/UsulanModel.php
namespace App\Models;

use PDO;

class UsulanModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Mengambil data usulan dengan filter dinamis, pagination, dan join user.
     * [ELITE ARCHITECTURE]: Enkapsulasi logika query kompleks.
     */
    public function getAllWithUser($filters = [], $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        // Base Query
        $sql = "SELECT u.*, us.username FROM usulan_kegiatan u JOIN users us ON u.user_id = us.id WHERE 1=1";
        $params = [];

        // Filter by Role (Security Scope)
        if (!empty($filters['role']) && $filters['role'] === 'Pengusul' && !empty($filters['user_id'])) {
            $sql .= " AND u.user_id = :uid";
            $params['uid'] = $filters['user_id'];
        }

        // Filter by Search Keyword
        if (!empty($filters['search'])) {
            $sql .= " AND (u.nama_kegiatan LIKE :q OR us.username LIKE :q)";
            $params['q'] = "%" . $filters['search'] . "%";
        }

        // Filter by Status
        if (!empty($filters['status'])) {
            $sql .= " AND u.status_terkini = :status";
            $params['status'] = $filters['status'];
        }

        // Filter by Date
        if (!empty($filters['date'])) {
            $sql .= " AND DATE(u.created_at) = :fdate"; 
            $params['fdate'] = $filters['date'];
        }

        // Pagination & Sorting
        $sql .= " ORDER BY u.id DESC LIMIT :offset, :perPage";

        // Execute
        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue(":$k", $v);
        }
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', (int)$perPage, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAllWithUser($filters = [])
    {
        $sql = "SELECT COUNT(*) FROM usulan_kegiatan u JOIN users us ON u.user_id = us.id WHERE 1=1";
        $params = [];

        // Re-apply filters logic (Duplicated logic could be extracted to private method for DRY)
        if (!empty($filters['role']) && $filters['role'] === 'Pengusul' && !empty($filters['user_id'])) {
            $sql .= " AND u.user_id = :uid";
            $params['uid'] = $filters['user_id'];
        }
        if (!empty($filters['search'])) {
            $sql .= " AND (u.nama_kegiatan LIKE :q OR us.username LIKE :q)";
            $params['q'] = "%" . $filters['search'] . "%";
        }
        if (!empty($filters['status'])) {
            $sql .= " AND u.status_terkini = :status";
            $params['status'] = $filters['status'];
        }
        if (!empty($filters['date'])) {
            $sql .= " AND DATE(u.created_at) = :fdate";
            $params['fdate'] = $filters['date'];
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue(":$k", $v);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    /** * [ELITE UPDATE] Mengambil statistik dashboard untuk Direktur
     */
    public function getDashboardStats()
    {
        return [
            'total'   => $this->db->query("SELECT COUNT(*) FROM usulan_kegiatan")->fetchColumn(),
            'selesai' => $this->db->query("SELECT COUNT(*) FROM usulan_kegiatan WHERE status_terkini='Selesai'")->fetchColumn(),
            // Menghitung dana terserap (yang sudah cair/selesai)
            'dana'    => $this->db->query("SELECT SUM(nominal_pencairan) FROM usulan_kegiatan WHERE status_terkini IN ('Pencairan','LPJ','Selesai')")->fetchColumn() ?: 0
        ];
    }

    /**
     * [ELITE UPDATE] Mengambil data untuk Bendahara dengan filter status spesifik
     */
    public function getByStatus($statuses = [])
    {
        // Membuat placeholder dinamis (?,?,?)
        $placeholders = implode(',', array_fill(0, count($statuses), '?'));
        
        $sql = "SELECT u.*, us.username 
                FROM usulan_kegiatan u 
                JOIN users us ON u.user_id = us.id 
                WHERE u.status_terkini IN ($placeholders) 
                ORDER BY u.id DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute($statuses);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * [ELITE UPDATE] Mengambil 5 aktivitas terakhir
     */
    public function getRecentActivity($limit = 5)
    {
        $stmt = $this->db->prepare("SELECT nama_kegiatan, status_terkini, nominal_pencairan FROM usulan_kegiatan ORDER BY id DESC LIMIT :lim");
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ... (Tambahkan di dalam class UsulanModel) ...

    public function cairkanDana($id, $tglCair, $tglLpj)
    {
        $sql = "UPDATE usulan_kegiatan SET status_terkini = 'Pencairan', tgl_pencairan = :tc, tgl_batas_lpj = :tl WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['tc' => $tglCair, 'tl' => $tglLpj, 'id' => $id]);
    }

    public function selesaikanLPJ($id)
    {
        $stmt = $this->db->prepare("UPDATE usulan_kegiatan SET status_terkini = 'Selesai' WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function addLog($usulanId, $userId, $old, $new, $note)
    {
        $sql = "INSERT INTO log_histori_usulan (usulan_id, user_id, status_lama, status_baru, catatan) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usulanId, $userId, $old, $new, $note]);
    }
}