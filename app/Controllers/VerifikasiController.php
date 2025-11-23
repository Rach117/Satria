<?php
// app/Controllers/VerifikasiController.php
namespace App\Controllers;

use PDO;
use App\Models\UsulanModel; // Gunakan Model

class VerifikasiController
{
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index($page = 1, $perPage = 10)
    {
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'Verifikator') {
            header('Location: /login'); exit;
        }

        // [ELITE REFACTORING] Gunakan Model
        $usulanModel = new UsulanModel($this->db);
        
        // Kita gunakan filter 'status' yang sudah didukung oleh Model
        $filters = [
            'status' => 'Verifikasi' 
        ];

        // Ambil data bersih dari Model
        $usulan = $usulanModel->getAllWithUser($filters, $page, $perPage);
        
        // Anda mungkin perlu menambahkan method countByStatus di Model nanti untuk pagination yang sempurna
        // Untuk sekarang, kita pakai countAllWithUser dengan filter yang sama
        $total = $usulanModel->countAllWithUser($filters); 

        require __DIR__ . '/../Views/verifikasi/index.php';
    }

    public function proses($id)
    {
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'Verifikator') {
            header('Location: /login');
            exit;
        }

        // Ambil data usulan
        $stmt = $this->db->prepare("SELECT * FROM usulan_kegiatan WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $usulan = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$usulan) {
            http_response_code(404);
            require __DIR__ . '/../Views/errors/404.php';
            exit;
        }
        require __DIR__ . '/../Views/verifikasi/proses.php';
    }

    public function aksi($id)
    {
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'Verifikator') {
            header('Location: /login');
            exit;
        }

        // [ELITE SECURITY] CSRF Check
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
             die('Security Alert: Invalid CSRF Token.');
        }

        $aksi = $_POST['aksi'] ?? '';
        $catatan = trim($_POST['catatan'] ?? '');
        $kode_mak = trim($_POST['kode_mak'] ?? '');
        $userId = $_SESSION['user_id'];

        $statusBaru = '';
        if ($aksi === 'setuju') {
            // Jika setuju, lempar ke WD2 (Sesuai Flow)
            $statusBaru = 'Menunggu WD2'; 
        } elseif ($aksi === 'revisi') {
            $statusBaru = 'Revisi';
        } elseif ($aksi === 'tolak') {
            $statusBaru = 'Ditolak'; // Gunakan status Ditolak
        }
        // Update status usulan
        $stmt = $this->db->prepare("UPDATE usulan_kegiatan SET status_terkini = :status, kode_mak = :kode_mak WHERE id = :id");
        $stmt->execute(['status' => $statusBaru, 'kode_mak' => $kode_mak, 'id' => $id]);
        
        // Log histori
        $logStmt = $this->db->prepare("INSERT INTO log_histori_usulan (usulan_id, user_id, status_lama, status_baru, catatan) VALUES (:usulan_id, :user_id, 'Verifikasi', :status_baru, :catatan)");
        $logStmt->execute(['usulan_id' => $id, 'user_id' => $userId, 'status_baru' => $statusBaru, 'catatan' => $catatan]);
        
        // Notifikasi ke pengusul
        $usulan = $this->db->prepare("SELECT user_id, nama_kegiatan FROM usulan_kegiatan WHERE id = :id");
        $usulan->execute(['id' => $id]);
        $u = $usulan->fetch(PDO::FETCH_ASSOC);
        $judul = 'Status Usulan Diperbarui';
        $pesan = "Usulan '{$u['nama_kegiatan']}' statusnya menjadi $statusBaru.";
        $link = "/usulan/detail?id=$id";
        $notifStmt = $this->db->prepare("INSERT INTO notifikasi (user_id, judul, pesan, link) VALUES (:user_id, :judul, :pesan, :link)");
        $notifStmt->execute([
            'user_id' => $u['user_id'],
            'judul' => $judul,
            'pesan' => $pesan,
            'link' => $link
        ]);
        
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Verifikasi berhasil disimpan!'];
        header('Location: /verifikasi');
        exit;
    }
}
