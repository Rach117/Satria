<?php
// app/Controllers/PengajuanKegiatanController.php
namespace App\Controllers;
use App\Models\PengajuanModel;
use App\Models\UsulanModel;

class PengajuanKegiatanController {
    private $db;
    private $pengajuanModel;
    private $usulanModel;
    
    public function __construct($db) {
        $this->db = $db;
        $this->pengajuanModel = new PengajuanModel($db);
        $this->usulanModel = new UsulanModel($db);
    }

    private function checkPengusul() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Pengusul') {
            header('Location: /login'); exit;
        }
    }

    // === HALAMAN LIST USULAN YANG DISETUJUI (Siap diajukan) ===
    public function listUsulanDisetujui() {
        $this->checkPengusul();
        
        $sql = "SELECT uk.*, 
                CASE WHEN pk.id IS NULL THEN 0 ELSE 1 END as sudah_diajukan,
                pk.id as pengajuan_id, pk.status_pengajuan
                FROM usulan_kegiatan uk
                LEFT JOIN pengajuan_kegiatan pk ON uk.id = pk.usulan_id
                WHERE uk.user_id = ? AND uk.status_usulan = 'Disetujui'
                ORDER BY uk.updated_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$_SESSION['user_id']]);
        $usulan_list = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        require __DIR__ . '/../Views/pengajuan/list.php';
    }

    // === FORM PENGAJUAN KEGIATAN ===
    public function create() {
        $this->checkPengusul();
        
        $usulan_id = (int)$_GET['usulan_id'];
        
        // Validasi usulan milik user dan statusnya disetujui
        $usulan = $this->usulanModel->getUsulanById($usulan_id);
        
        if (!$usulan || $usulan['user_id'] != $_SESSION['user_id'] || $usulan['status_usulan'] != 'Disetujui') {
            $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Usulan tidak valid!'];
            header('Location: /pengajuan/list'); exit;
        }
        
        // Cek apakah sudah pernah diajukan
        $check = $this->db->prepare("SELECT id FROM pengajuan_kegiatan WHERE usulan_id = ?");
        $check->execute([$usulan_id]);
        if ($check->fetch()) {
            $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Kegiatan ini sudah pernah diajukan!'];
            header('Location: /pengajuan/list'); exit;
        }
        
        require __DIR__ . '/../Views/pengajuan/create.php';
    }

    // === SIMPAN PENGAJUAN KEGIATAN ===
    public function store() {
        $this->checkPengusul();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        $usulan_id = (int)$_POST['usulan_id'];
        
        // Validasi kepemilikan
        $usulan = $this->db->prepare("SELECT user_id, status_usulan FROM usulan_kegiatan WHERE id = ?");
        $usulan->execute([$usulan_id]);
        $u = $usulan->fetch(\PDO::FETCH_ASSOC);
        
        if (!$u || $u['user_id'] != $_SESSION['user_id'] || $u['status_usulan'] != 'Disetujui') {
            die('Akses ditolak');
        }
        
        // Upload Surat Pengantar
        $surat_path = null;
        if (isset($_FILES['surat_pengantar']) && $_FILES['surat_pengantar']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/../../public/uploads/surat_pengantar/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            
            $ext = pathinfo($_FILES['surat_pengantar']['name'], PATHINFO_EXTENSION);
            $filename = 'surat_' . $usulan_id . '_' . time() . '.' . $ext;
            $target = $upload_dir . $filename;
            
            if (move_uploaded_file($_FILES['surat_pengantar']['tmp_name'], $target)) {
                $surat_path = '/uploads/surat_pengantar/' . $filename;
            }
        }
        
        try {
            $this->db->beginTransaction();
            
            // Simpan Pengajuan
            $pengajuan_id = $this->pengajuanModel->createPengajuan([
                'usulan_id' => $usulan_id,
                'penanggung_jawab' => $_POST['penanggung_jawab'],
                'pelaksana' => $_POST['pelaksana'],
                'waktu_mulai' => $_POST['waktu_mulai'],
                'waktu_selesai' => $_POST['waktu_selesai'],
                'surat_pengantar_path' => $surat_path
            ]);
            
            // Log Histori
            $this->db->prepare("INSERT INTO log_histori (usulan_id, pengajuan_id, user_id, aksi, status_baru) VALUES (?, ?, ?, ?, ?)")
                     ->execute([$usulan_id, $pengajuan_id, $_SESSION['user_id'], 'Ajukan Kegiatan', 'Menunggu PPK']);
            
            // Notifikasi ke PPK
            $ppk = $this->db->query("SELECT id FROM users WHERE role = 'PPK' AND is_active = 1 LIMIT 1")->fetch(\PDO::FETCH_ASSOC);
            if ($ppk) {
                $this->db->prepare("INSERT INTO notifikasi (user_id, judul, pesan, link) VALUES (?, ?, ?, ?)")
                         ->execute([
                             $ppk['id'],
                             'Pengajuan Kegiatan Baru',
                             'Ada pengajuan kegiatan yang perlu disetujui',
                             '/ppk/proses?id=' . $pengajuan_id
                         ]);
            }
            
            $this->db->commit();
            $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Pengajuan kegiatan berhasil dikirim ke PPK!'];
            header('Location: /monitoring/pengajuan'); exit;
            
        } catch (\Exception $e) {
            $this->db->rollBack();
            die("Error: " . $e->getMessage());
        }
    }

    // === HALAMAN MONITORING PENGAJUAN (untuk Pengusul) ===
    public function monitoringPengajuan() {
        $this->checkPengusul();
        
        $pengajuan_list = $this->pengajuanModel->getPengajuanByUser($_SESSION['user_id']);
        
        require __DIR__ . '/../Views/pengajuan/monitoring.php';
    }

    // === DETAIL PENGAJUAN ===
    public function detail($id) {
        $this->checkPengusul();
        
        $pengajuan = $this->pengajuanModel->getPengajuanById($id);
        
        if (!$pengajuan || $pengajuan['user_id'] != $_SESSION['user_id']) {
            die('Akses ditolak');
        }
        
        // Ambil data pencairan (jika ada)
        $pencairan = $this->pengajuanModel->getPencairanByPengajuan($id);
        
        // Ambil data LPJ (jika ada)
        $lpj = $this->pengajuanModel->getLpjByPengajuan($id);
        
        // Ambil RAB Summary
        $rab_summary = $this->pengajuanModel->getRabSummaryByUsulan($pengajuan['usulan_id']);
        
        require __DIR__ . '/../Views/pengajuan/detail.php';
    }

    // === HALAMAN UPLOAD LPJ (untuk Pengusul) ===
    public function uploadLpj() {
        $this->checkPengusul();
        
        $pengajuan_id = (int)$_GET['pengajuan_id'];
        
        // Validasi kepemilikan
        $pengajuan = $this->pengajuanModel->getPengajuanById($pengajuan_id);
        
        if (!$pengajuan || $pengajuan['user_id'] != $_SESSION['user_id']) {
            die('Akses ditolak');
        }
        
        // Cek apakah sudah ada pencairan
        $pencairan = $this->pengajuanModel->getPencairanByPengajuan($pengajuan_id);
        if (empty($pencairan)) {
            $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Belum ada pencairan dana!'];
            header('Location: /pengajuan/detail?id=' . $pengajuan_id); exit;
        }
        
        // Ambil RAB Summary per kategori
        $rab_summary = $this->pengajuanModel->getRabSummaryByUsulan($pengajuan['usulan_id']);
        
        // Ambil LPJ yang sudah diupload (jika ada)
        $lpj_uploaded = $this->pengajuanModel->getLpjByPengajuan($pengajuan_id);
        
        require __DIR__ . '/../Views/pengajuan/upload_lpj.php';
    }

    // === SIMPAN LPJ (per Kategori) ===
    public function storeLpj() {
        $this->checkPengusul();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        $pengajuan_id = (int)$_POST['pengajuan_id'];
        $kategori_id = (int)$_POST['kategori_id'];
        $nominal_lpj = (float)$_POST['nominal_lpj'];
        
        // Validasi kepemilikan
        $pengajuan = $this->pengajuanModel->getPengajuanById($pengajuan_id);
        if (!$pengajuan || $pengajuan['user_id'] != $_SESSION['user_id']) {
            die('Akses ditolak');
        }
        
        // Validasi nominal LPJ tidak boleh lebih dari RAB kategori
        $rab_summary = $this->pengajuanModel->getRabSummaryByUsulan($pengajuan['usulan_id']);
        $rab_kategori = array_filter($rab_summary, function($r) use ($kategori_id) {
            return $r['kategori_id'] == $kategori_id;
        });
        
        $rab_kategori = reset($rab_kategori);
        
        if (!$rab_kategori || $nominal_lpj > $rab_kategori['total_rab']) {
            $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Nominal LPJ melebihi RAB kategori!'];
            header('Location: /pengajuan/upload-lpj?pengajuan_id=' . $pengajuan_id); exit;
        }
        
        // Upload Bukti LPJ
        $bukti_path = null;
        if (isset($_FILES['bukti_lpj']) && $_FILES['bukti_lpj']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/../../public/uploads/lpj/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            
            $ext = pathinfo($_FILES['bukti_lpj']['name'], PATHINFO_EXTENSION);
            $filename = 'lpj_' . $pengajuan_id . '_k' . $kategori_id . '_' . time() . '.' . $ext;
            $target = $upload_dir . $filename;
            
            if (move_uploaded_file($_FILES['bukti_lpj']['tmp_name'], $target)) {
                $bukti_path = '/uploads/lpj/' . $filename;
            }
        }
        
        // Simpan LPJ
        $this->pengajuanModel->createLpj([
            'pengajuan_id' => $pengajuan_id,
            'kategori_id' => $kategori_id,
            'nominal_lpj' => $nominal_lpj,
            'bukti_lpj_path' => $bukti_path
        ]);
        
        // Notifikasi ke Bendahara
        $bendahara = $this->db->query("SELECT id FROM users WHERE role = 'Bendahara' AND is_active = 1 LIMIT 1")->fetch(\PDO::FETCH_ASSOC);
        if ($bendahara) {
            $this->db->prepare("INSERT INTO notifikasi (user_id, judul, pesan, link) VALUES (?, ?, ?, ?)")
                     ->execute([
                         $bendahara['id'],
                         'LPJ Baru Diunggah',
                         'Ada LPJ yang perlu diverifikasi',
                         '/bendahara/lpj-detail?id=' . $pengajuan_id
                     ]);
        }
        
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'LPJ berhasil diunggah!'];
        header('Location: /pengajuan/upload-lpj?pengajuan_id=' . $pengajuan_id); exit;
    }
}