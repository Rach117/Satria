<?php
// app/Controllers/UsulanWizardController.php
namespace App\Controllers;
use App\Models\UsulanModel;

class UsulanWizardController {
    private $db;
    private $usulanModel;
    
    public function __construct($db) {
        $this->db = $db;
        $this->usulanModel = new UsulanModel($db);
    }

    private function checkPengusul() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Pengusul') {
            header('Location: /login'); exit;
        }
    }

    // === STEP 1: Tampilkan Form ===
    public function create() {
        $this->checkPengusul();
        
        // Ambil Master Data
        $iku = $this->db->query("SELECT * FROM master_iku WHERE is_active = 1 ORDER BY id")->fetchAll(\PDO::FETCH_ASSOC);
        $satuan = $this->db->query("SELECT * FROM master_satuan WHERE is_active = 1 ORDER BY nama_satuan")->fetchAll(\PDO::FETCH_ASSOC);
        $kategori = $this->db->query("SELECT * FROM master_kategori_anggaran ORDER BY id")->fetchAll(\PDO::FETCH_ASSOC);
        
        require __DIR__ . '/../Views/usulan/wizard.php';
    }

    // === STEP 2: Simpan Draft ===
    public function saveDraft() {
        $this->checkPengusul();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        try {
            $this->db->beginTransaction();
            
            // 1. Simpan Data KAK
            $usulan_id = $this->usulanModel->createUsulan([
                'user_id' => $_SESSION['user_id'],
                'nama_kegiatan' => $_POST['nama_kegiatan'],
                'gambaran_umum' => $_POST['gambaran_umum'],
                'penerima_manfaat' => $_POST['penerima_manfaat'],
                'target_luaran' => $_POST['target_luaran'] ?? ''
            ]);
            
            // 2. Simpan Metode (Multi)
            if (!empty($_POST['metode'])) {
                $this->usulanModel->saveMetode($usulan_id, $_POST['metode']);
            }
            
            // 3. Simpan Tahapan (Multi)
            if (!empty($_POST['tahapan'])) {
                $this->usulanModel->saveTahapan($usulan_id, $_POST['tahapan']);
            }
            
            // 4. Simpan Indikator Kinerja
            if (!empty($_POST['indikator'])) {
                $this->usulanModel->saveIndikator(
                    $usulan_id,
                    $_POST['indikator'],
                    $_POST['bulan_target'] ?? [],
                    $_POST['bobot_indikator'] ?? []
                );
            }
            
            // 5. Simpan Waktu Pelaksanaan
            if (!empty($_POST['tanggal_mulai']) && !empty($_POST['tanggal_selesai'])) {
                $this->usulanModel->saveWaktu($usulan_id, $_POST['tanggal_mulai'], $_POST['tanggal_selesai']);
            }
            
            // 6. Simpan IKU Terpilih (dengan bobot)
            if (!empty($_POST['iku_id'])) {
                $this->usulanModel->saveIku($usulan_id, $_POST['iku_id'], $_POST['bobot_iku'] ?? []);
            }
            
            // 7. Simpan RAB
            if (!empty($_POST['uraian'])) {
                $this->usulanModel->saveRab($usulan_id, [
                    'kategori_id' => $_POST['kategori_id'],
                    'uraian' => $_POST['uraian'],
                    'volume' => $_POST['volume'],
                    'satuan_id' => $_POST['satuan_id'],
                    'harga_satuan' => $_POST['harga_satuan']
                ]);
            }
            
            $this->db->commit();
            $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Draft berhasil disimpan!'];
            header('Location: /usulan/list'); exit;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            die("Error: " . $e->getMessage());
        }
    }

    // === STEP 3: Submit Usulan ===
    public function submit() {
        $this->checkPengusul();
        
        $usulan_id = (int)$_POST['usulan_id'];
        $this->usulanModel->submitUsulan($usulan_id);
        
        // Notif ke Verifikator
        $verifikator = $this->db->query("SELECT id FROM users WHERE role = 'Verifikator' LIMIT 1")->fetch(\PDO::FETCH_ASSOC);
        if ($verifikator) {
            $this->db->prepare("INSERT INTO notifikasi (user_id, judul, pesan, link) VALUES (?, ?, ?, ?)")
                     ->execute([
                         $verifikator['id'],
                         'Usulan Baru Masuk',
                         'Ada usulan baru yang perlu diverifikasi',
                         '/verifikasi/proses?id=' . $usulan_id
                     ]);
        }
        
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Usulan berhasil diajukan!'];
        header('Location: /usulan/list'); exit;
    }

    // === EDIT USULAN (Jika Draft/Revisi) ===
    public function edit($id) {
        $this->checkPengusul();
        
        $usulan = $this->usulanModel->getUsulanById($id);
        
        if (!$usulan || $usulan['user_id'] != $_SESSION['user_id']) {
            die('Akses Ditolak');
        }
        
        if (!in_array($usulan['status_usulan'], ['Draft', 'Revisi'])) {
            die('Usulan tidak dapat diedit');
        }
        
        // Load Master Data
        $iku = $this->db->query("SELECT * FROM master_iku WHERE is_active = 1")->fetchAll(\PDO::FETCH_ASSOC);
        $satuan = $this->db->query("SELECT * FROM master_satuan WHERE is_active = 1")->fetchAll(\PDO::FETCH_ASSOC);
        $kategori = $this->db->query("SELECT * FROM master_kategori_anggaran")->fetchAll(\PDO::FETCH_ASSOC);
        
        $isEdit = true;
        require __DIR__ . '/../Views/usulan/wizard.php';
    }

    // === UPDATE USULAN ===
    public function update($id) {
        $this->checkPengusul();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        try {
            $this->db->beginTransaction();
            
            // Update KAK
            $this->db->prepare("UPDATE usulan_kegiatan SET nama_kegiatan = ?, gambaran_umum = ?, penerima_manfaat = ?, target_luaran = ? WHERE id = ?")
                     ->execute([
                         $_POST['nama_kegiatan'],
                         $_POST['gambaran_umum'],
                         $_POST['penerima_manfaat'],
                         $_POST['target_luaran'] ?? '',
                         $id
                     ]);
            
            // Update relasi lainnya (sama seperti create)
            if (!empty($_POST['metode'])) $this->usulanModel->saveMetode($id, $_POST['metode']);
            if (!empty($_POST['tahapan'])) $this->usulanModel->saveTahapan($id, $_POST['tahapan']);
            if (!empty($_POST['indikator'])) {
                $this->usulanModel->saveIndikator($id, $_POST['indikator'], $_POST['bulan_target'] ?? [], $_POST['bobot_indikator'] ?? []);
            }
            if (!empty($_POST['tanggal_mulai'])) {
                $this->usulanModel->saveWaktu($id, $_POST['tanggal_mulai'], $_POST['tanggal_selesai']);
            }
            if (!empty($_POST['iku_id'])) {
                $this->usulanModel->saveIku($id, $_POST['iku_id'], $_POST['bobot_iku'] ?? []);
            }
            if (!empty($_POST['uraian'])) {
                $this->usulanModel->saveRab($id, [
                    'kategori_id' => $_POST['kategori_id'],
                    'uraian' => $_POST['uraian'],
                    'volume' => $_POST['volume'],
                    'satuan_id' => $_POST['satuan_id'],
                    'harga_satuan' => $_POST['harga_satuan']
                ]);
            }
            
            $this->db->commit();
            $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Usulan berhasil diperbarui!'];
            header('Location: /usulan/list'); exit;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            die("Error: " . $e->getMessage());
        }
    }

    // === LIST USULAN MILIK USER ===
    public function listUsulan() {
        $this->checkPengusul();
        
        $usulan = $this->usulanModel->getUsulanByUser($_SESSION['user_id']);
        require __DIR__ . '/../Views/usulan/list.php';
    }

    // === DETAIL USULAN ===
    public function detail($id) {
        $this->checkPengusul();
        
        $usulan = $this->usulanModel->getUsulanById($id);
        
        if (!$usulan || $usulan['user_id'] != $_SESSION['user_id']) {
            die('Akses Ditolak');
        }
        
        require __DIR__ . '/../Views/usulan/detail.php';
    }
}