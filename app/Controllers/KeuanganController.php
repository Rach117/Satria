<?php
namespace App\Controllers;

use App\Models\UsulanModel;

class KeuanganController {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }

    // Halaman Pencairan Dana
    public function indexPencairan() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Bendahara') { header('Location: /login'); exit; }
        
        $model = new UsulanModel($this->db);
        $usulan = $model->getByStatus(['Disetujui']);
        
        require __DIR__ . '/../Views/keuangan/pencairan.php';
    }

    // Proses Cairkan Dana
    public function prosesPencairan($id) {
        if ($_SESSION['role'] !== 'Bendahara') return;
        
        // [SECURITY] Validasi Token CSRF Wajib
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('Security Alert: Invalid CSRF Token.');
        }

        // LOGIKA BISNIS: Hitung 14 Hari Kerja
        $tglCair = date('Y-m-d H:i:s');
        $hariKerja = 14;
        $currentDate = strtotime($tglCair);
        while ($hariKerja > 0) {
            $currentDate = strtotime('+1 day', $currentDate);
            if (date('N', $currentDate) < 6) { // 1-5 = Senin-Jumat
                $hariKerja--;
            }
        }
        $tglLPJ = date('Y-m-d H:i:s', $currentDate);

        // [REFACTORED] Delegasi ke Model (Tidak ada SQL di sini)
        $model = new UsulanModel($this->db);
        $model->cairkanDana($id, $tglCair, $tglLPJ);
        $model->addLog($id, $_SESSION['user_id'], 'Disetujui', 'Pencairan', 'Dana telah dicairkan oleh Bendahara.');

        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Dana berhasil dicairkan! Timer LPJ dimulai.'];
        header('Location: /pencairan');
        exit;
    }

    // Halaman LPJ
    public function indexLPJ() {
        if ($_SESSION['role'] !== 'Bendahara') { header('Location: /login'); exit; }
        
        $model = new UsulanModel($this->db);
        $usulan = $model->getByStatus(['Pencairan', 'LPJ']);
        
        require __DIR__ . '/../Views/keuangan/lpj.php';
    }

    // Finalisasi LPJ
    public function verifikasiLPJ($id) {
        if ($_SESSION['role'] !== 'Bendahara') return;

        // [SECURITY] Validasi Token CSRF Wajib (PENTING: Tambahkan ini di View juga nanti)
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
             die('Security Alert: Invalid CSRF Token.');
        }

        // [REFACTORED] Delegasi ke Model
        $model = new UsulanModel($this->db);
        $model->selesaikanLPJ($id);
        $model->addLog($id, $_SESSION['user_id'], 'LPJ', 'Selesai', 'LPJ diverifikasi. Kegiatan Selesai.');

        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'LPJ berhasil diverifikasi. Siklus selesai!'];
        header('Location: /lpj');
        exit;
    }
}