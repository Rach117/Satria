<?php
namespace App\Controllers;

use PDO;
use Exception;

class UsulanController
{
    private $db;
    public function __construct($db) { $this->db = $db; }

    // CREATE (Sama seperti sebelumnya, saya ringkas untuk hemat space)
    public function create() {
        if (!isset($_SESSION['user_id'])) { header('Location: /login'); exit; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processStore($_POST, 'create'); // Refactored logic
        } else {
            $jurusan  = $this->db->query("SELECT * FROM master_jurusan")->fetchAll(PDO::FETCH_ASSOC);
            $iku      = $this->db->query("SELECT * FROM master_iku")->fetchAll(PDO::FETCH_ASSOC);
            $kategori = $this->db->query("SELECT * FROM master_kategori_anggaran")->fetchAll(PDO::FETCH_ASSOC);
            require __DIR__ . '/../Views/usulan/wizard.php';
        }
    }

    // [NEW] EDIT FORM (Menampilkan data lama untuk direvisi)
    public function edit($id) {
        if (!isset($_SESSION['user_id'])) { header('Location: /login'); exit; }

        // 1. Ambil Data Usulan & Pastikan Milik User Sendiri
        $stmt = $this->db->prepare("SELECT * FROM usulan_kegiatan WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
        $usulan = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usulan) die("Akses Ditolak atau Data Tidak Ditemukan.");
        
        // Hanya boleh edit jika status Draft atau Revisi
        if (!in_array($usulan['status_terkini'], ['Draft', 'Revisi'])) {
            die("Usulan sedang diproses dan tidak dapat diedit.");
        }

        // 2. Ambil Data Relasi (RAB & IKU)
        $rab = $this->db->prepare("SELECT * FROM rab_detail WHERE usulan_id = ?");
        $rab->execute([$id]);
        $rabData = $rab->fetchAll(PDO::FETCH_ASSOC);

        $ikuRel = $this->db->prepare("SELECT iku_id FROM tor_iku WHERE usulan_id = ?");
        $ikuRel->execute([$id]);
        $selectedIku = $ikuRel->fetchAll(PDO::FETCH_COLUMN); // Array sederhana [1, 3, 5]

        // 3. Master Data untuk Dropdown
        $jurusan  = $this->db->query("SELECT * FROM master_jurusan")->fetchAll(PDO::FETCH_ASSOC);
        $iku      = $this->db->query("SELECT * FROM master_iku")->fetchAll(PDO::FETCH_ASSOC);
        $kategori = $this->db->query("SELECT * FROM master_kategori_anggaran")->fetchAll(PDO::FETCH_ASSOC);

        // 4. Render View Edit (Gunakan wizard.php tapi diisi value)
        $isEdit = true; // Flag untuk view
        require __DIR__ . '/../Views/usulan/wizard.php';
    }

    // [NEW] UPDATE PROCESS (Menangani POST dari Edit)
    public function update($id) {
        if (!isset($_SESSION['user_id'])) { header('Location: /login'); exit; }
        
        // Validasi kepemilikan
        $stmt = $this->db->prepare("SELECT id FROM usulan_kegiatan WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
        if (!$stmt->fetch()) die("Forbidden");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processStore($_POST, 'update', $id);
        }
    }

    // [NEW] DELETE DRAFT
    public function delete($id) {
        if (!isset($_SESSION['user_id'])) { header('Location: /login'); exit; }
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');

        // Hanya hapus jika Draft/Ditolak (Safety)
        $stmt = $this->db->prepare("SELECT status_terkini FROM usulan_kegiatan WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
        $row = $stmt->fetch();

        if ($row && in_array($row['status_terkini'], ['Draft', 'Ditolak', 'Revisi'])) {
            // Cascade delete akan otomatis menghapus RAB & IKU karena setting DB
            $this->db->prepare("DELETE FROM usulan_kegiatan WHERE id = ?")->execute([$id]);
            $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Usulan dihapus.'];
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Gagal hapus. Status tidak valid.'];
        }
        header('Location: /monitoring'); exit;
    }

    // [CORE LOGIC] Menangani Simpan Baru & Update dalam satu fungsi
    private function processStore($data, $mode, $id = null) {
        if ($data['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');

        try {
            $this->db->beginTransaction();

            if ($mode === 'create') {
                // INSERT
                $sqlUsulan = "INSERT INTO usulan_kegiatan (user_id, nama_kegiatan, gambaran_umum, penerima_manfaat, target_luaran, status_terkini) VALUES (:uid, :nama, :umum, :manfaat, :luaran, 'Draft')";
                $params = [
                    'uid' => $_SESSION['user_id'],
                    'nama' => trim($data['nama_kegiatan']),
                    'umum' => trim($data['gambaran_umum']),
                    'manfaat' => trim($data['penerima_manfaat']),
                    'luaran' => trim($data['target_luaran'] ?? '')
                ];
                $this->db->prepare($sqlUsulan)->execute($params);
                $usulanId = $this->db->lastInsertId();
                
                // Log
                $this->db->prepare("INSERT INTO log_histori_usulan (usulan_id, user_id, status_baru, catatan) VALUES (?, ?, 'Draft', 'Usulan dibuat')")->execute([$usulanId, $_SESSION['user_id']]);

            } else {
                // UPDATE
                $usulanId = $id;
                $sqlUsulan = "UPDATE usulan_kegiatan SET nama_kegiatan = :nama, gambaran_umum = :umum, penerima_manfaat = :manfaat, target_luaran = :luaran, status_terkini = 'Verifikasi' WHERE id = :id"; // Otomatis ubah ke Verifikasi saat submit revisi
                $params = [
                    'nama' => trim($data['nama_kegiatan']),
                    'umum' => trim($data['gambaran_umum']),
                    'manfaat' => trim($data['penerima_manfaat']),
                    'luaran' => trim($data['target_luaran'] ?? ''),
                    'id' => $id
                ];
                $this->db->prepare($sqlUsulan)->execute($params);

                // Hapus Detail Lama (Full Replacement Strategy)
                $this->db->prepare("DELETE FROM tor_iku WHERE usulan_id = ?")->execute([$id]);
                $this->db->prepare("DELETE FROM rab_detail WHERE usulan_id = ?")->execute([$id]);

                // Log
                $this->db->prepare("INSERT INTO log_histori_usulan (usulan_id, user_id, status_baru, catatan) VALUES (?, ?, 'Verifikasi', 'Usulan direvisi user')")->execute([$usulanId, $_SESSION['user_id']]);
            }

            // --- Insert Detail Baru (Sama untuk Create/Update) ---
            
            // Insert IKU
            if (isset($data['iku_id']) && is_array($data['iku_id'])) {
                $stmtIku = $this->db->prepare("INSERT INTO tor_iku (usulan_id, iku_id) VALUES (?, ?)");
                foreach ($data['iku_id'] as $ikuId) {
                    $stmtIku->execute([$usulanId, $ikuId]);
                }
            }

            // Insert RAB
            if (isset($data['uraian']) && is_array($data['uraian'])) {
                $stmtRab = $this->db->prepare("INSERT INTO rab_detail (usulan_id, kategori_id, uraian, volume, satuan, harga_satuan, total) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $countItems = count($data['uraian']);
                for ($i = 0; $i < $countItems; $i++) {
                    $total = (int)$data['volume'][$i] * (float)$data['harga_satuan'][$i];
                    if (!empty($data['uraian'][$i])) {
                        $stmtRab->execute([
                            $usulanId, 
                            $data['kategori_id'][$i], 
                            $data['uraian'][$i], 
                            (int)$data['volume'][$i], 
                            $data['satuan'][$i], 
                            (float)$data['harga_satuan'][$i], 
                            $total
                        ]);
                    }
                }
            }

            $this->db->commit();
            $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Data berhasil disimpan!'];
            header('Location: /monitoring'); exit;

        } catch (Exception $e) {
            $this->db->rollBack();
            die("Error: " . $e->getMessage());
        }
    }

    // DETAIL READ (Tetap ada)
    public function detail($id) {
        // ... (Logika detail sama seperti file Anda sebelumnya) ...
        if (!isset($_SESSION['user_id'])) { header('Location: /login'); exit; }
        $stmt = $this->db->prepare("SELECT u.*, us.username FROM usulan_kegiatan u JOIN users us ON u.user_id = us.id WHERE u.id = :id");
        $stmt->execute(['id' => $id]);
        $usulan = $stmt->fetch(PDO::FETCH_ASSOC);
        // ... dst (copy dari file lama) ...
        require __DIR__ . '/../Views/usulan/detail.php';
    }
}