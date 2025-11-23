<?php
namespace App\Controllers;
use PDO;

class PageController {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function profil() {
        if (!isset($_SESSION['user_id'])) { header('Location: /login'); exit; }
        
        // Ambil data user lengkap
        $stmt = $this->db->prepare("SELECT u.*, m.nama_jurusan FROM users u LEFT JOIN master_jurusan m ON u.jurusan_id = m.id WHERE u.id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        require __DIR__ . '/../Views/pages/profil.php';
    }

    // [BARU] Method Update Profil (Username & Email)
    public function updateProfile() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') { 
            header('Location: /login'); exit; 
        }

        $userId = $_SESSION['user_id'];
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);

        // Validasi Sederhana
        if (empty($username) || empty($email)) {
            $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Username dan Email tidak boleh kosong!'];
            header('Location: /profil'); exit;
        }

        try {
            // Update DB
            $stmt = $this->db->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $stmt->execute([$username, $email, $userId]);
            
            // Catat Audit Log
            $this->db->prepare("INSERT INTO log_audit_sistem (user_id, aksi, ip_address) VALUES (?, 'Update Profil Akun', ?)")
                     ->execute([$userId, $_SERVER['REMOTE_ADDR']]);

            $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Profil berhasil diperbarui.'];
        } catch (\Exception $e) {
            // Menangkap error Duplicate Entry (jika username/email sudah ada)
            $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Gagal: Username atau Email sudah digunakan user lain.'];
        }

        header('Location: /profil');
        exit;
    }

    // Method untuk memproses Ganti Password
    public function updatePassword() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') { 
            header('Location: /login'); exit; 
        }

        $oldPass = $_POST['old_password'];
        $newPass = $_POST['new_password'];
        $userId = $_SESSION['user_id'];

        // 1. Cek Password Lama
        $stmt = $this->db->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $currentHash = $stmt->fetchColumn();

        if (!password_verify($oldPass, $currentHash)) {
            $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Password lama salah!'];
        } else {
            // 2. Update Password Baru
            $newHash = password_hash($newPass, PASSWORD_BCRYPT);
            $upd = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $upd->execute([$newHash, $userId]);
            
            // 3. Catat Audit
            $ip = $_SERVER['REMOTE_ADDR'];
            $this->db->prepare("INSERT INTO log_audit_sistem (user_id, aksi, ip_address) VALUES (?, 'Ubah Password Mandiri', ?)")
                     ->execute([$userId, $ip]);

            $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Password berhasil diperbarui.'];
        }

        header('Location: /profil');
        exit;
    }

    public function bantuan() { require __DIR__ . '/../Views/pages/bantuan.php'; }
    public function syarat() { require __DIR__ . '/../Views/pages/syarat.php'; }
}