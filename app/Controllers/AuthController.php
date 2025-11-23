<?php
namespace App\Controllers;

use App\Models\User;

class AuthController
{
    private $db;
    public function __construct($db) { $this->db = $db; }

    // [ELITE UPDATE] Menangani Flash Message dari Session
    public function showLogin() 
    { 
        // Cek apakah ada pesan error dari percobaan login sebelumnya
        if (isset($_SESSION['error'])) {
            $error = $_SESSION['error'];
            unset($_SESSION['error']); // Hapus pesan agar tidak muncul terus menerus
        }
        
        require __DIR__ . '/../Views/auth/login.php'; 
    }

    public function login()
    {
        // 1. Validasi CSRF Token (Wajib)
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('Security Alert: Invalid CSRF Token. Permintaan ditolak demi keamanan.');
        }

        $usernameOrEmail = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $userModel = new User($this->db);
        $user = $userModel->findByUsernameOrEmail($usernameOrEmail);
        
        // 2. Verifikasi Kredensial
        if ($user && password_verify($password, $user['password'])) {
            // Login Sukses
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            
            // Regenerasi ID Session untuk mencegah Session Fixation
            session_regenerate_id(true);
            
            // Catat Audit Log Login
            $this->logAudit($user['id'], 'Login Berhasil');

            header('Location: /dashboard');
            exit;
        } else {
            // [ELITE UPDATE] Login Gagal: Gunakan Session Flash & Redirect
            // Jangan me-require view langsung di sini (anti-pattern)
            $_SESSION['error'] = 'Username/email atau password salah.';
            
            // Opsional: Catat percobaan gagal (jika user ditemukan)
            if ($user) {
                $this->logAudit($user['id'], 'Gagal Login (Wrong Password)');
            }

            header('Location: /login');
            exit;
        }
    }
    
    public function logout()
    {
       if (isset($_SESSION['user_id'])) {
           $this->logAudit($_SESSION['user_id'], 'Logout System');
       }
       session_destroy();
       header('Location: /');
       exit;
    }

    private function logAudit($userId, $aksi)
    {
       $ip = $_SERVER['REMOTE_ADDR'];
       $stmt = $this->db->prepare("INSERT INTO log_audit_sistem (user_id, aksi, ip_address) VALUES (?, ?, ?)");
       $stmt->execute([$userId, $aksi, $ip]);
    }
}