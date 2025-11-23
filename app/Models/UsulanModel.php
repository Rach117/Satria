<?php
// app/Models/UsulanModel.php
namespace App\Models;
use PDO;

class UsulanModel {
    private $db;
    public function __construct($db) { $this->db = $db; }

    // === CREATE USULAN (Step 1: KAK) ===
    public function createUsulan($data) {
        $sql = "INSERT INTO usulan_kegiatan (user_id, nama_kegiatan, gambaran_umum, penerima_manfaat, target_luaran, nominal_rab) 
                VALUES (:uid, :nama, :umum, :manfaat, :luaran, :nominal)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'uid' => $data['user_id'],
            'nama' => $data['nama_kegiatan'],
            'umum' => $data['gambaran_umum'],
            'manfaat' => $data['penerima_manfaat'],
            'luaran' => $data['target_luaran'] ?? '',
            'nominal' => $data['nominal_rab'] ?? 0
        ]);
        return $this->db->lastInsertId();
    }

    // === SAVE METODE (Multi) ===
    public function saveMetode($usulan_id, $metodeArray) {
        $this->db->prepare("DELETE FROM usulan_metode WHERE usulan_id = ?")->execute([$usulan_id]);
        $stmt = $this->db->prepare("INSERT INTO usulan_metode (usulan_id, metode, urutan) VALUES (?,?,?)");
        foreach ($metodeArray as $i => $m) {
            if (!empty($m)) $stmt->execute([$usulan_id, $m, $i+1]);
        }
    }

    // === SAVE TAHAPAN (Multi) ===
    public function saveTahapan($usulan_id, $tahapanArray) {
        $this->db->prepare("DELETE FROM usulan_tahapan WHERE usulan_id = ?")->execute([$usulan_id]);
        $stmt = $this->db->prepare("INSERT INTO usulan_tahapan (usulan_id, tahapan, urutan) VALUES (?,?,?)");
        foreach ($tahapanArray as $i => $t) {
            if (!empty($t)) $stmt->execute([$usulan_id, $t, $i+1]);
        }
    }

    // === SAVE INDIKATOR KINERJA ===
    public function saveIndikator($usulan_id, $indikatorArray, $bulanArray, $bobotArray) {
        $this->db->prepare("DELETE FROM usulan_indikator WHERE usulan_id = ?")->execute([$usulan_id]);
        $stmt = $this->db->prepare("INSERT INTO usulan_indikator (usulan_id, indikator, bulan_target, bobot_persen) VALUES (?,?,?,?)");
        $count = count($indikatorArray);
        for ($i = 0; $i < $count; $i++) {
            if (!empty($indikatorArray[$i])) {
                $stmt->execute([$usulan_id, $indikatorArray[$i], $bulanArray[$i] ?? null, $bobotArray[$i] ?? 0]);
            }
        }
    }

    // === SAVE WAKTU PELAKSANAAN ===
    public function saveWaktu($usulan_id, $mulai, $selesai) {
        $this->db->prepare("DELETE FROM usulan_waktu WHERE usulan_id = ?")->execute([$usulan_id]);
        $stmt = $this->db->prepare("INSERT INTO usulan_waktu (usulan_id, tanggal_mulai, tanggal_selesai) VALUES (?,?,?)");
        $stmt->execute([$usulan_id, $mulai, $selesai]);
    }

    // === SAVE IKU (dengan bobot %) ===
    public function saveIku($usulan_id, $ikuIds, $bobotPersen) {
        $this->db->prepare("DELETE FROM usulan_iku WHERE usulan_id = ?")->execute([$usulan_id]);
        $stmt = $this->db->prepare("INSERT INTO usulan_iku (usulan_id, iku_id, bobot_persen) VALUES (?,?,?)");
        $count = count($ikuIds);
        for ($i = 0; $i < $count; $i++) {
            $stmt->execute([$usulan_id, $ikuIds[$i], $bobotPersen[$i] ?? 0]);
        }
    }

    // === SAVE RAB (dengan satuan_id) ===
    public function saveRab($usulan_id, $data) {
        $this->db->prepare("DELETE FROM rab_detail WHERE usulan_id = ?")->execute([$usulan_id]);
        $stmt = $this->db->prepare("INSERT INTO rab_detail (usulan_id, kategori_id, uraian, volume, satuan_id, harga_satuan, total) VALUES (?,?,?,?,?,?,?)");
        
        $count = count($data['uraian']);
        $totalRab = 0;
        
        for ($i = 0; $i < $count; $i++) {
            if (!empty($data['uraian'][$i])) {
                $total = $data['volume'][$i] * $data['harga_satuan'][$i];
                $totalRab += $total;
                $stmt->execute([
                    $usulan_id,
                    $data['kategori_id'][$i],
                    $data['uraian'][$i],
                    $data['volume'][$i],
                    $data['satuan_id'][$i],
                    $data['harga_satuan'][$i],
                    $total
                ]);
            }
        }
        
        // Update nominal_rab di usulan_kegiatan
        $this->db->prepare("UPDATE usulan_kegiatan SET nominal_rab = ? WHERE id = ?")->execute([$totalRab, $usulan_id]);
    }

    // === SUBMIT USULAN (Change status to Diajukan) ===
    public function submitUsulan($usulan_id) {
        $this->db->prepare("UPDATE usulan_kegiatan SET status_usulan = 'Diajukan' WHERE id = ?")->execute([$usulan_id]);
    }

    // === GET USULAN LENGKAP ===
    public function getUsulanById($id) {
        $stmt = $this->db->prepare("SELECT u.*, us.username, j.nama_jurusan FROM usulan_kegiatan u 
                                     JOIN users us ON u.user_id = us.id 
                                     LEFT JOIN master_jurusan j ON us.jurusan_id = j.id 
                                     WHERE u.id = ?");
        $stmt->execute([$id]);
        $usulan = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$usulan) return null;
        
        // Ambil detail relasi
        $usulan['metode'] = $this->db->prepare("SELECT * FROM usulan_metode WHERE usulan_id = ? ORDER BY urutan")->execute([$id]) ? $this->db->query("SELECT * FROM usulan_metode WHERE usulan_id = {$id} ORDER BY urutan")->fetchAll(PDO::FETCH_ASSOC) : [];
        
        $usulan['tahapan'] = $this->db->query("SELECT * FROM usulan_tahapan WHERE usulan_id = {$id} ORDER BY urutan")->fetchAll(PDO::FETCH_ASSOC);
        
        $usulan['indikator'] = $this->db->query("SELECT * FROM usulan_indikator WHERE usulan_id = {$id}")->fetchAll(PDO::FETCH_ASSOC);
        
        $usulan['waktu'] = $this->db->query("SELECT * FROM usulan_waktu WHERE usulan_id = {$id}")->fetch(PDO::FETCH_ASSOC);
        
        $usulan['iku'] = $this->db->query("SELECT ui.*, mi.deskripsi_iku FROM usulan_iku ui JOIN master_iku mi ON ui.iku_id = mi.id WHERE ui.usulan_id = {$id}")->fetchAll(PDO::FETCH_ASSOC);
        
        $usulan['rab'] = $this->db->query("SELECT r.*, k.nama_kategori, s.nama_satuan FROM rab_detail r JOIN master_kategori_anggaran k ON r.kategori_id = k.id JOIN master_satuan s ON r.satuan_id = s.id WHERE r.usulan_id = {$id}")->fetchAll(PDO::FETCH_ASSOC);
        
        return $usulan;
    }

    // === GET USULAN BY USER ===
    public function getUsulanByUser($user_id, $filters = []) {
        $sql = "SELECT * FROM usulan_kegiatan WHERE user_id = :uid";
        $params = ['uid' => $user_id];
        
        if (!empty($filters['status'])) {
            $sql .= " AND status_usulan = :status";
            $params['status'] = $filters['status'];
        }
        
        $sql .= " ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // === VERIFIKATOR ACTIONS ===
    public function setujuUsulan($id, $kode_mak) {
        $this->db->prepare("UPDATE usulan_kegiatan SET status_usulan = 'Disetujui', kode_mak = ? WHERE id = ?")->execute([$kode_mak, $id]);
    }

    public function revisiUsulan($id, $catatan) {
        $this->db->prepare("UPDATE usulan_kegiatan SET status_usulan = 'Revisi', catatan_verifikator = ? WHERE id = ?")->execute([$catatan, $id]);
    }

    public function tolakUsulan($id, $catatan) {
        $this->db->prepare("UPDATE usulan_kegiatan SET status_usulan = 'Ditolak', catatan_verifikator = ? WHERE id = ?")->execute([$catatan, $id]);
    }

    // === STATISTIK ===
    public function getStatsByUser($user_id) {
        $stmt = $this->db->prepare("SELECT status_usulan, COUNT(*) as count FROM usulan_kegiatan WHERE user_id = ? GROUP BY status_usulan");
        $stmt->execute([$user_id]);
        $result = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $result[$row['status_usulan']] = $row['count'];
        }
        return $result;
    }

    public function getTotalUsulan() {
        return $this->db->query("SELECT COUNT(*) FROM usulan_kegiatan")->fetchColumn();
    }

    public function getUsulanByStatus($status) {
        return $this->db->prepare("SELECT COUNT(*) FROM usulan_kegiatan WHERE status_usulan = ?")->execute([$status]) ? $this->db->query("SELECT COUNT(*) FROM usulan_kegiatan WHERE status_usulan = '{$status}'")->fetchColumn() : 0;
    }
}