<?php
// app/Models/MasterDataModel.php
namespace App\Models;
use PDO;

class MasterDataModel {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }

    // ========== JURUSAN ==========
    public function getAllJurusan($includeInactive = false) {
        $sql = "SELECT * FROM master_jurusan";
        if (!$includeInactive) $sql .= " WHERE is_active = 1";
        $sql .= " ORDER BY nama_jurusan";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getJurusanById($id) {
        $stmt = $this->db->prepare("SELECT * FROM master_jurusan WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createJurusan($nama) {
        $stmt = $this->db->prepare("INSERT INTO master_jurusan (nama_jurusan) VALUES (?)");
        return $stmt->execute([$nama]);
    }

    public function updateJurusan($id, $nama) {
        $stmt = $this->db->prepare("UPDATE master_jurusan SET nama_jurusan = ? WHERE id = ?");
        return $stmt->execute([$nama, $id]);
    }

    public function toggleJurusanStatus($id) {
        $stmt = $this->db->prepare("UPDATE master_jurusan SET is_active = NOT is_active WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function deleteJurusan($id) {
        // Cek apakah ada user yang menggunakan jurusan ini
        $check = $this->db->prepare("SELECT COUNT(*) FROM users WHERE jurusan_id = ?");
        $check->execute([$id]);
        
        if ($check->fetchColumn() > 0) {
            return false; // Tidak bisa dihapus
        }
        
        $stmt = $this->db->prepare("DELETE FROM master_jurusan WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // ========== IKU ==========
    public function getAllIku($includeInactive = false) {
        $sql = "SELECT * FROM master_iku";
        if (!$includeInactive) $sql .= " WHERE is_active = 1";
        $sql .= " ORDER BY id";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getIkuById($id) {
        $stmt = $this->db->prepare("SELECT * FROM master_iku WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createIku($deskripsi) {
        try {
            $stmt = $this->db->prepare("INSERT INTO master_iku (deskripsi_iku) VALUES (?)");
            return $stmt->execute([$deskripsi]);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                return false;
            }
            throw $e;
        }
    }

    public function updateIku($id, $deskripsi) {
        try {
            $stmt = $this->db->prepare("UPDATE master_iku SET deskripsi_iku = ? WHERE id = ?");
            return $stmt->execute([$deskripsi, $id]);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                return false;
            }
            throw $e;
        }
    }

    public function toggleIkuStatus($id) {
        $stmt = $this->db->prepare("UPDATE master_iku SET is_active = NOT is_active WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function deleteIku($id) {
        // Cek apakah ada usulan yang menggunakan IKU ini
        $check = $this->db->prepare("SELECT COUNT(*) FROM usulan_iku WHERE iku_id = ?");
        $check->execute([$id]);
        
        if ($check->fetchColumn() > 0) {
            return false; // Tidak bisa dihapus, arsipkan saja
        }
        
        $stmt = $this->db->prepare("DELETE FROM master_iku WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // ========== SATUAN ANGGARAN ==========
    public function getAllSatuan($includeInactive = false) {
        $sql = "SELECT * FROM master_satuan";
        if (!$includeInactive) $sql .= " WHERE is_active = 1";
        $sql .= " ORDER BY nama_satuan";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSatuanById($id) {
        $stmt = $this->db->prepare("SELECT * FROM master_satuan WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createSatuan($nama) {
        try {
            $stmt = $this->db->prepare("INSERT INTO master_satuan (nama_satuan) VALUES (?)");
            return $stmt->execute([$nama]);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                return false;
            }
            throw $e;
        }
    }

    public function updateSatuan($id, $nama) {
        try {
            $stmt = $this->db->prepare("UPDATE master_satuan SET nama_satuan = ? WHERE id = ?");
            return $stmt->execute([$nama, $id]);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                return false;
            }
            throw $e;
        }
    }

    public function toggleSatuanStatus($id) {
        $stmt = $this->db->prepare("UPDATE master_satuan SET is_active = NOT is_active WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function deleteSatuan($id) {
        // Cek apakah ada RAB yang menggunakan satuan ini
        $check = $this->db->prepare("SELECT COUNT(*) FROM rab_detail WHERE satuan_id = ?");
        $check->execute([$id]);
        
        if ($check->fetchColumn() > 0) {
            return false; // Tidak bisa dihapus
        }
        
        $stmt = $this->db->prepare("DELETE FROM master_satuan WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // ========== STATISTIK UNTUK ADMIN ==========
    public function getStatsUsulan() {
        $stats = [];
        
        // Total Usulan
        $stats['total'] = $this->db->query("SELECT COUNT(*) FROM usulan_kegiatan")->fetchColumn();
        
        // Per Status
        $stmt = $this->db->query("SELECT status_usulan, COUNT(*) as count FROM usulan_kegiatan GROUP BY status_usulan");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $stats[strtolower($row['status_usulan'])] = $row['count'];
        }
        
        return $stats;
    }

    public function getUsulanPerPengusul($tahun = null) {
        $sql = "SELECT u.username, j.nama_jurusan, COUNT(uk.id) as total_usulan 
                FROM users u 
                LEFT JOIN usulan_kegiatan uk ON u.id = uk.user_id";
        
        if ($tahun) {
            $sql .= " AND YEAR(uk.created_at) = :tahun";
        }
        
        $sql .= " LEFT JOIN master_jurusan j ON u.jurusan_id = j.id 
                  WHERE u.role = 'Pengusul' 
                  GROUP BY u.id, u.username, j.nama_jurusan 
                  ORDER BY total_usulan DESC";
        
        $stmt = $this->db->prepare($sql);
        if ($tahun) {
            $stmt->execute(['tahun' => $tahun]);
        } else {
            $stmt->execute();
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}