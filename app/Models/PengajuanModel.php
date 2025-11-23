<?php
// app/Models/PengajuanModel.php
namespace App\Models;
use PDO;

class PengajuanModel {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }

    // === CREATE PENGAJUAN KEGIATAN ===
    public function createPengajuan($data) {
        $sql = "INSERT INTO pengajuan_kegiatan 
                (usulan_id, penanggung_jawab, pelaksana, waktu_pelaksanaan_mulai, waktu_pelaksanaan_selesai, surat_pengantar_path) 
                VALUES (:usulan_id, :pj, :pelaksana, :mulai, :selesai, :surat)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'usulan_id' => $data['usulan_id'],
            'pj' => $data['penanggung_jawab'],
            'pelaksana' => $data['pelaksana'],
            'mulai' => $data['waktu_mulai'],
            'selesai' => $data['waktu_selesai'],
            'surat' => $data['surat_pengantar_path'] ?? null
        ]);
        
        return $this->db->lastInsertId();
    }

    // === GET PENGAJUAN BY ID ===
    public function getPengajuanById($id) {
        $sql = "SELECT p.*, u.nama_kegiatan, u.nominal_rab, u.user_id, us.username, j.nama_jurusan 
                FROM pengajuan_kegiatan p 
                JOIN usulan_kegiatan u ON p.usulan_id = u.id 
                JOIN users us ON u.user_id = us.id 
                LEFT JOIN master_jurusan j ON us.jurusan_id = j.id 
                WHERE p.id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // === GET PENGAJUAN BY STATUS ===
    public function getPengajuanByStatus($status) {
        $sql = "SELECT p.*, u.nama_kegiatan, u.nominal_rab, us.username 
                FROM pengajuan_kegiatan p 
                JOIN usulan_kegiatan u ON p.usulan_id = u.id 
                JOIN users us ON u.user_id = us.id 
                WHERE p.status_pengajuan = ? 
                ORDER BY p.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // === GET PENGAJUAN BY USER ===
    public function getPengajuanByUser($user_id) {
        $sql = "SELECT p.*, u.nama_kegiatan, u.nominal_rab 
                FROM pengajuan_kegiatan p 
                JOIN usulan_kegiatan u ON p.usulan_id = u.id 
                WHERE u.user_id = ? 
                ORDER BY p.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // === UPDATE STATUS PENGAJUAN ===
    public function updateStatus($id, $status, $rekomendasi = null, $role = null) {
        $sql = "UPDATE pengajuan_kegiatan SET status_pengajuan = ?";
        $params = [$status];
        
        if ($rekomendasi && $role === 'PPK') {
            $sql .= ", rekomendasi_ppk = ?";
            $params[] = $rekomendasi;
        } elseif ($rekomendasi && $role === 'WD2') {
            $sql .= ", rekomendasi_wd2 = ?";
            $params[] = $rekomendasi;
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    // === GET RAB SUMMARY PER KATEGORI (untuk pencairan) ===
    public function getRabSummaryByUsulan($usulan_id) {
        $sql = "SELECT k.id as kategori_id, k.nama_kategori, SUM(r.total) as total_rab 
                FROM rab_detail r 
                JOIN master_kategori_anggaran k ON r.kategori_id = k.id 
                WHERE r.usulan_id = ? 
                GROUP BY k.id, k.nama_kategori";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usulan_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // === GET TOTAL PENCAIRAN PER KATEGORI ===
    public function getTotalPencairanByKategori($pengajuan_id, $kategori_id) {
        $sql = "SELECT COALESCE(SUM(nominal_pencairan), 0) as total 
                FROM pencairan_dana 
                WHERE pengajuan_id = ? AND kategori_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$pengajuan_id, $kategori_id]);
        return $stmt->fetchColumn();
    }

    // === CREATE PENCAIRAN DANA ===
    public function createPencairan($data) {
        $sql = "INSERT INTO pencairan_dana 
                (pengajuan_id, kategori_id, nominal_pencairan, bukti_transfer_path, tanggal_pencairan) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['pengajuan_id'],
            $data['kategori_id'],
            $data['nominal_pencairan'],
            $data['bukti_transfer_path'] ?? null,
            $data['tanggal_pencairan']
        ]);
    }

    // === GET PENCAIRAN BY PENGAJUAN ===
    public function getPencairanByPengajuan($pengajuan_id) {
        $sql = "SELECT p.*, k.nama_kategori 
                FROM pencairan_dana p 
                JOIN master_kategori_anggaran k ON p.kategori_id = k.id 
                WHERE p.pengajuan_id = ? 
                ORDER BY p.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$pengajuan_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // === MARK PENCAIRAN SELESAI ===
    public function markPencairanSelesai($pengajuan_id) {
        $sql = "UPDATE pencairan_dana SET status = 'Selesai' WHERE pengajuan_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$pengajuan_id]);
    }

    // === CREATE LPJ ===
    public function createLpj($data) {
        $sql = "INSERT INTO lpj_kegiatan 
                (pengajuan_id, kategori_id, nominal_lpj, bukti_lpj_path) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['pengajuan_id'],
            $data['kategori_id'],
            $data['nominal_lpj'],
            $data['bukti_lpj_path']
        ]);
    }

    // === GET LPJ BY PENGAJUAN ===
    public function getLpjByPengajuan($pengajuan_id) {
        $sql = "SELECT l.*, k.nama_kategori 
                FROM lpj_kegiatan l 
                JOIN master_kategori_anggaran k ON l.kategori_id = k.id 
                WHERE l.pengajuan_id = ? 
                ORDER BY k.id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$pengajuan_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // === UPDATE STATUS LPJ ===
    public function updateStatusLpj($lpj_id, $status, $catatan = null) {
        $sql = "UPDATE lpj_kegiatan SET status_lpj = ?, catatan_bendahara = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $catatan, $lpj_id]);
    }

    // === GET PENGAJUAN BUTUH LPJ (14 hari setelah pencairan pertama) ===
    public function getPengajuanButuhLpj() {
        $sql = "SELECT DISTINCT p.*, u.nama_kegiatan, us.username, 
                MIN(pc.tanggal_pencairan) as tanggal_pencairan_pertama,
                DATE_ADD(MIN(pc.tanggal_pencairan), INTERVAL 14 DAY) as batas_lpj
                FROM pengajuan_kegiatan p
                JOIN usulan_kegiatan u ON p.usulan_id = u.id
                JOIN users us ON u.user_id = us.id
                JOIN pencairan_dana pc ON p.id = pc.pengajuan_id
                WHERE p.status_pengajuan = 'Disetujui'
                GROUP BY p.id
                HAVING MIN(pc.tanggal_pencairan) IS NOT NULL";
        
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // === CHECK APAKAH SEMUA LPJ SUDAH DISETUJUI ===
    public function isAllLpjApproved($pengajuan_id) {
        // Ambil jumlah kategori yang ada di RAB
        $usulan_id = $this->db->prepare("SELECT usulan_id FROM pengajuan_kegiatan WHERE id = ?");
        $usulan_id->execute([$pengajuan_id]);
        $usulan = $usulan_id->fetchColumn();
        
        $total_kategori = $this->db->prepare("SELECT COUNT(DISTINCT kategori_id) FROM rab_detail WHERE usulan_id = ?");
        $total_kategori->execute([$usulan]);
        $jumlah_kategori = $total_kategori->fetchColumn();
        
        // Cek jumlah LPJ yang sudah disetujui
        $lpj_approved = $this->db->prepare("SELECT COUNT(*) FROM lpj_kegiatan WHERE pengajuan_id = ? AND status_lpj = 'Disetujui'");
        $lpj_approved->execute([$pengajuan_id]);
        $jumlah_approved = $lpj_approved->fetchColumn();
        
        return $jumlah_kategori == $jumlah_approved;
    }

    // === GET STATISTIK UNTUK BENDAHARA ===
    public function getStatsBendahara() {
        $stats = [];
        
        // Total Dana Tersedia (dummy - seharusnya dari anggaran DIPA)
        $stats['dana_tersedia'] = 5000000000; // 5 Milyar
        
        // Total Dana Keluar
        $stats['dana_keluar'] = $this->db->query("SELECT COALESCE(SUM(nominal_pencairan), 0) FROM pencairan_dana")->fetchColumn();
        
        // Total Kegiatan Menunggu Pencairan
        $stats['kegiatan_pending'] = $this->db->query("SELECT COUNT(*) FROM pengajuan_kegiatan WHERE status_pengajuan = 'Disetujui'")->fetchColumn();
        
        // Total Kegiatan Menunggu LPJ
        $stats['lpj_pending'] = $this->db->query("SELECT COUNT(DISTINCT pengajuan_id) FROM pencairan_dana pc WHERE NOT EXISTS (SELECT 1 FROM lpj_kegiatan l WHERE l.pengajuan_id = pc.pengajuan_id AND l.status_lpj = 'Disetujui')")->fetchColumn();
        
        return $stats;
    }
}