<?php
// app/Controllers/BendaharaController.php
namespace App\Controllers;
use App\Models\PengajuanModel;

class BendaharaController {
    private $db;
    private $pengajuanModel;
    
    public function __construct($db) {
        $this->db = $db;
        $this->pengajuanModel = new PengajuanModel($db);
    }

    private function checkBendahara() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Bendahara') {
            header('Location: /login'); exit;
        }
    }

    // === DASHBOARD BENDAHARA ===
    public function dashboard() {
        $this->checkBendahara();
        
        $stats = $this->pengajuanModel->getStatsBendahara();
        
        require __DIR__ . '/../Views/dashboard/bendahara.php';
    }

    // === HALAMAN PENCAIRAN DANA ===
    public function pencairan() {
        $this->checkBendahara();
        
        // Ambil kegiatan yang sudah disetujui WD2 (siap dicairkan)
        $pengajuan_list = $this->pengajuanModel->getPengajuanByStatus('Disetujui');
        
        // Untuk setiap pengajuan, ambil RAB summary dan total pencairan
        foreach ($pengajuan_list as &$p) {
            $p['rab_summary'] = $this->pengajuanModel->getRabSummaryByUsulan($p['usulan_id']);
            $p['pencairan'] = $this->pengajuanModel->getPencairanByPengajuan($p['id']);
            
            // Hitung sisa yang belum dicairkan per kategori
            $p['sisa_per_kategori'] = [];
            foreach ($p['rab_summary'] as $rab) {
                $total_cair = $this->pengajuanModel->getTotalPencairanByKategori($p['id'], $rab['kategori_id']);
                $p['sisa_per_kategori'][$rab['kategori_id']] = $rab['total_rab'] - $total_cair;
            }
        }
        
        require __DIR__ . '/../Views/bendahara/pencairan.php';
    }

    // === FORM PENCAIRAN DANA (per Kategori) ===
    public function formPencairan() {
        $this->checkBendahara();
        
        $pengajuan_id = (int)$_GET['pengajuan_id'];
        $pengajuan = $this->pengajuanModel->getPengajuanById($pengajuan_id);
        
        if (!$pengajuan) die('Data tidak ditemukan');
        
        // Ambil RAB Summary
        $rab_summary = $this->pengajuanModel->getRabSummaryByUsulan($pengajuan['usulan_id']);
        
        // Hitung sisa per kategori
        $sisa_per_kategori = [];
        foreach ($rab_summary as $rab) {
            $total_cair = $this->pengajuanModel->getTotalPencairanByKategori($pengajuan_id, $rab['kategori_id']);
            $sisa_per_kategori[$rab['kategori_id']] = [
                'nama_kategori' => $rab['nama_kategori'],
                'total_rab' => $rab['total_rab'],
                'sudah_cair' => $total_cair,
                'sisa' => $rab['total_rab'] - $total_cair
            ];
        }
        
        require __DIR__ . '/../Views/bendahara/form_pencairan.php';
    }

    // === PROSES PENCAIRAN DANA ===
    public function prosesPencairan() {
        $this->checkBendahara();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        $pengajuan_id = (int)$_POST['pengajuan_id'];
        $kategori_id = (int)$_POST['kategori_id'];
        $nominal = (float)$_POST['nominal_pencairan'];
        
        // Validasi nominal tidak boleh melebihi sisa
        $pengajuan = $this->pengajuanModel->getPengajuanById($pengajuan_id);
        $rab_summary = $this->pengajuanModel->getRabSummaryByUsulan($pengajuan['usulan_id']);
        
        $rab_kategori = array_filter($rab_summary, function($r) use ($kategori_id) {
            return $r['kategori_id'] == $kategori_id;
        });
        $rab_kategori = reset($rab_kategori);
        
        $total_cair = $this->pengajuanModel->getTotalPencairanByKategori($pengajuan_id, $kategori_id);
        $sisa = $rab_kategori['total_rab'] - $total_cair;
        
        if ($nominal > $sisa) {
            $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Nominal melebihi sisa RAB kategori!'];
            header('Location: /bendahara/form-pencairan?pengajuan_id=' . $pengajuan_id); exit;
        }
        
        // Upload Bukti Transfer
        $bukti_path = null;
        if (isset($_FILES['bukti_transfer']) && $_FILES['bukti_transfer']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/../../public/uploads/bukti_transfer/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            
            $ext = pathinfo($_FILES['bukti_transfer']['name'], PATHINFO_EXTENSION);
            $filename = 'transfer_' . $pengajuan_id . '_k' . $kategori_id . '_' . time() . '.' . $ext;
            $target = $upload_dir . $filename;
            
            if (move_uploaded_file($_FILES['bukti_transfer']['tmp_name'], $target)) {
                $bukti_path = '/uploads/bukti_transfer/' . $filename;
            }
        }
        
        // Simpan Pencairan
        $this->pengajuanModel->createPencairan([
            'pengajuan_id' => $pengajuan_id,
            'kategori_id' => $kategori_id,
            'nominal_pencairan' => $nominal,
            'bukti_transfer_path' => $bukti_path,
            'tanggal_pencairan' => date('Y-m-d')
        ]);
        
        // Notifikasi ke Pengusul
        $this->db->prepare("INSERT INTO notifikasi (user_id, judul, pesan, link) VALUES (?, ?, ?, ?)")
                 ->execute([
                     $pengajuan['user_id'],
                     'Dana Telah Dicairkan',
                     'Dana kegiatan Anda telah dicairkan sebagian. Cek detail.',
                     '/pengajuan/detail?id=' . $pengajuan_id
                 ]);
        
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Pencairan berhasil dicatat!'];
        header('Location: /bendahara/pencairan'); exit;
    }

    // === MARK PENCAIRAN SELESAI ===
    public function selesaikanPencairan() {
        $this->checkBendahara();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        $pengajuan_id = (int)$_POST['pengajuan_id'];
        
        // Validasi semua kategori sudah dicairkan penuh
        $pengajuan = $this->pengajuanModel->getPengajuanById($pengajuan_id);
        $rab_summary = $this->pengajuanModel->getRabSummaryByUsulan($pengajuan['usulan_id']);
        
        $all_cleared = true;
        foreach ($rab_summary as $rab) {
            $total_cair = $this->pengajuanModel->getTotalPencairanByKategori($pengajuan_id, $rab['kategori_id']);
            if ($total_cair < $rab['total_rab']) {
                $all_cleared = false;
                break;
            }
        }
        
        if (!$all_cleared) {
            $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Masih ada kategori yang belum dicairkan penuh!'];
            header('Location: /bendahara/pencairan'); exit;
        }
        
        // Mark pencairan selesai
        $this->pengajuanModel->markPencairanSelesai($pengajuan_id);
        
        // Notifikasi ke Pengusul untuk upload LPJ
        $this->db->prepare("INSERT INTO notifikasi (user_id, judul, pesan, link) VALUES (?, ?, ?, ?)")
                 ->execute([
                     $pengajuan['user_id'],
                     'Silakan Upload LPJ',
                     'Pencairan dana selesai. Upload LPJ maksimal 14 hari kerja.',
                     '/pengajuan/upload-lpj?pengajuan_id=' . $pengajuan_id
                 ]);
        
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Pencairan selesai! Notifikasi LPJ dikirim ke pengusul.'];
        header('Location: /bendahara/pencairan'); exit;
    }

    // === HALAMAN MONITORING LPJ ===
    public function lpjList() {
        $this->checkBendahara();
        
        // Ambil semua pengajuan yang sudah ada pencairan (butuh LPJ)
        $lpj_list = $this->pengajuanModel->getPengajuanButuhLpj();
        
        // Untuk setiap pengajuan, cek status LPJ per kategori
        foreach ($lpj_list as &$item) {
            $item['lpj'] = $this->pengajuanModel->getLpjByPengajuan($item['id']);
            $item['rab_summary'] = $this->pengajuanModel->getRabSummaryByUsulan($item['usulan_id']);
        }
        
        require __DIR__ . '/../Views/bendahara/lpj_list.php';
    }

    // === DETAIL & VERIFIKASI LPJ ===
    public function lpjDetail() {
        $this->checkBendahara();
        
        $pengajuan_id = (int)$_GET['id'];
        $pengajuan = $this->pengajuanModel->getPengajuanById($pengajuan_id);
        
        if (!$pengajuan) die('Data tidak ditemukan');
        
        $lpj = $this->pengajuanModel->getLpjByPengajuan($pengajuan_id);
        $rab_summary = $this->pengajuanModel->getRabSummaryByUsulan($pengajuan['usulan_id']);
        
        require __DIR__ . '/../Views/bendahara/lpj_detail.php';
    }

    // === VERIFIKASI LPJ (Setuju/Revisi) ===
    public function verifikasiLpj() {
        $this->checkBendahara();
        
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');
        
        $lpj_id = (int)$_POST['lpj_id'];
        $status = $_POST['status']; // 'Disetujui' atau 'Direvisi'
        $catatan = $_POST['catatan'] ?? '';
        
        $this->pengajuanModel->updateStatusLpj($lpj_id, $status, $catatan);
        
        // Ambil data pengajuan untuk notifikasi
        $lpj = $this->db->prepare("SELECT l.*, p.usulan_id, u.user_id FROM lpj_kegiatan l JOIN pengajuan_kegiatan p ON l.pengajuan_id = p.id JOIN usulan_kegiatan u ON p.usulan_id = u.id WHERE l.id = ?");
        $lpj->execute([$lpj_id]);
        $lpj_data = $lpj->fetch(\PDO::FETCH_ASSOC);
        
        // Notifikasi ke Pengusul
        $pesan = ($status === 'Disetujui') ? 'LPJ Anda disetujui.' : 'LPJ Anda perlu direvisi. Cek catatan bendahara.';
        $this->db->prepare("INSERT INTO notifikasi (user_id, judul, pesan, link) VALUES (?, ?, ?, ?)")
                 ->execute([
                     $lpj_data['user_id'],
                     'Status LPJ Diperbarui',
                     $pesan,
                     '/pengajuan/upload-lpj?pengajuan_id=' . $lpj_data['pengajuan_id']
                 ]);
        
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Verifikasi LPJ berhasil!'];
        header('Location: /bendahara/lpj-detail?id=' . $lpj_data['pengajuan_id']); exit;
    }

    // === RIWAYAT LPJ SELESAI ===
    public function riwayatLpj() {
        $this->checkBendahara();
        
        // Ambil pengajuan yang semua LPJ-nya sudah disetujui
        $sql = "SELECT DISTINCT p.*, u.nama_kegiatan, us.username 
                FROM pengajuan_kegiatan p
                JOIN usulan_kegiatan u ON p.usulan_id = u.id
                JOIN users us ON u.user_id = us.id
                WHERE p.id IN (
                    SELECT pengajuan_id FROM lpj_kegiatan GROUP BY pengajuan_id HAVING MIN(status_lpj) = 'Disetujui'
                )
                ORDER BY p.created_at DESC";
        
        $riwayat = $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        
        require __DIR__ . '/../Views/bendahara/riwayat.php';
    }
}