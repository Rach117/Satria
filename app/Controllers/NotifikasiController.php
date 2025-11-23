<?php
// app/Controllers/NotifikasiController.php
namespace App\Controllers;

use PDO;

class NotifikasiController
{
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index($page = 1, $perPage = 10)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $userId = $_SESSION['user_id'];
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM notifikasi WHERE user_id = :uid ORDER BY created_at DESC LIMIT :offset, :perPage";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':perPage', (int)$perPage, PDO::PARAM_INT);
        $stmt->execute();
        $notifikasi = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $countSql = "SELECT COUNT(*) FROM notifikasi WHERE user_id = :uid";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute([':uid' => $userId]);
        $total = $countStmt->fetchColumn();
        require __DIR__ . '/../Views/notifikasi/index.php';
    }

    public function read($id)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $userId = $_SESSION['user_id'];
        $stmt = $this->db->prepare("UPDATE notifikasi SET is_read = 1 WHERE id = :id AND user_id = :uid");
        $stmt->execute(['id' => $id, 'uid' => $userId]);
        header('Location: /notifikasi');
        exit;
    }

    // Fungsi untuk mengirim email notifikasi
    private function sendEmailNotification($to, $subject, $message)
    {
        $headers = "From: no-reply@satria.com\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        mail($to, $subject, $message, $headers);
    }

    // Contoh penggunaan fungsi pengiriman email
    public function notifyApproval($userId, $judul, $pesan, $link = null)
    {
        // Simpan notifikasi ke database
        $stmt = $this->db->prepare("INSERT INTO notifikasi (user_id, judul, pesan, link, created_at) VALUES (:user_id, :judul, :pesan, :link, NOW())");
        $stmt->execute([
            'user_id' => $userId,
            'judul' => $judul,
            'pesan' => $pesan,
            'link' => $link
        ]);

        // Ambil email pengguna
        $userStmt = $this->db->prepare("SELECT email FROM users WHERE id = :id");
        $userStmt->execute(['id' => $userId]);
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Kirim email notifikasi
            $this->sendEmailNotification($user['email'], $judul, $pesan);
        }
    }
}
