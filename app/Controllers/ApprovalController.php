<?php
namespace App\Controllers;
use PDO;

class ApprovalController
{
    private $db;
    public function __construct($db) { $this->db = $db; }

    // ... method index & proses tetap sama ...
    public function index($page = 1, $perPage = 10)
    {
        // ... kode asli index ...
        if (!isset($_SESSION['user_id'])) { header('Location: /login'); exit; }
        $role = $_SESSION['role'];
        $targetStatus = ($role === 'WD2') ? 'Menunggu WD2' : 'Menunggu PPK';
        
        $offset = ($page - 1) * $perPage;
        $stmt = $this->db->prepare("SELECT u.*, us.username FROM usulan_kegiatan u JOIN users us ON u.user_id = us.id WHERE u.status_terkini = :status ORDER BY u.id DESC LIMIT :offset, :perPage");
        $stmt->bindValue(':status', $targetStatus);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', (int)$perPage, PDO::PARAM_INT);
        $stmt->execute();
        $usulan = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require __DIR__ . '/../Views/approval/index.php';
    }

    public function proses($id)
    {
        // ... kode asli proses ...
        if (!isset($_SESSION['user_id'])) exit;
        $stmt = $this->db->prepare("SELECT u.*, us.username FROM usulan_kegiatan u JOIN users us ON u.user_id = us.id WHERE u.id = :id");
        $stmt->execute(['id' => $id]);
        $usulan = $stmt->fetch(PDO::FETCH_ASSOC);
        require __DIR__ . '/../Views/approval/proses.php';
    }

    public function aksi($id)
    {
        if (!isset($_SESSION['user_id'])) exit;

        // [ELITE SECURITY FIX] Validasi CSRF Token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('Security Alert: Invalid CSRF Token. Aksi Approval ditolak.');
        }

        $aksi = $_POST['aksi'] ?? '';
        $catatan = trim($_POST['catatan'] ?? '');
        $role = $_SESSION['role'];
        $userId = $_SESSION['user_id'];
        
        // ... Logika bisnis approval selanjutnya (sama seperti sebelumnya) ...
        // 1. Tentukan Status Baru
        $statusBaru = '';
        $pesanNotif = '';
        
        if ($aksi === 'acc') {
            if ($role === 'WD2') {
                $statusBaru = 'Menunggu PPK';
                $pesanNotif = "Usulan Anda telah disetujui WD2 dan diteruskan ke PPK.";
            } elseif ($role === 'PPK') {
                $statusBaru = 'Disetujui'; 
                $pesanNotif = "SELAMAT! Usulan Anda telah DISETUJUI PPK dan siap untuk pencairan.";
            }
        } elseif ($aksi === 'revisi') {
            $statusBaru = 'Revisi';
            $pesanNotif = "Usulan dikembalikan oleh $role untuk direvisi. Cek catatan.";
        }

        // 2. Update DB
        $this->db->prepare("UPDATE usulan_kegiatan SET status_terkini = :st WHERE id = :id")
                 ->execute(['st' => $statusBaru, 'id' => $id]);

        // 3. Log Audit Histori
        $this->db->prepare("INSERT INTO log_histori_usulan (usulan_id, user_id, status_lama, status_baru, catatan) VALUES (?, ?, ?, ?, ?)")
                 ->execute([$id, $userId, 'Approval ' . $role, $statusBaru, $catatan]);
                 
        // 4. Notifikasi
        $getOwner = $this->db->prepare("SELECT user_id, nama_kegiatan FROM usulan_kegiatan WHERE id = ?");
        $getOwner->execute([$id]);
        $owner = $getOwner->fetch(PDO::FETCH_ASSOC);

        if ($owner) {
            $judul = "Update Status: $statusBaru";
            $link = "/usulan/detail?id=$id";
            $this->db->prepare("INSERT INTO notifikasi (user_id, judul, pesan, link) VALUES (?, ?, ?, ?)")
                     ->execute([$owner['user_id'], $judul, $pesanNotif, $link]);
        }

        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Keputusan berhasil disimpan!'];
        header('Location: /approval');
        exit;
    }
}