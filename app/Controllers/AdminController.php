<?php
namespace App\Controllers;

use App\Models\AdminModel; 
use PDO;

class AdminController
{
    private $db;
    public function __construct($db) { $this->db = $db; }

    private function checkAdmin() {
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'Admin') {
            header('Location: /login'); exit;
        }
    }

    // --- MANAJEMEN PENGGUNA (SEKARANG LENGKAP DENGAN EDIT) ---
    public function users() {
        $this->checkAdmin();
        $model = new AdminModel($this->db);
        $users = $model->getUsers($_GET['search'] ?? '', $_GET['jurusan'] ?? '');
        $jurusan = $model->getAllJurusan(); 
        require __DIR__ . '/../Views/admin/users.php';
    }

    public function createUser() {
        $this->checkAdmin();
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Security Alert');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username'   => trim($_POST['username']),
                'email'      => trim($_POST['email']),
                'password'   => password_hash($_POST['password'], PASSWORD_BCRYPT),
                'role'       => $_POST['role'],
                'jurusan_id' => !empty($_POST['jurusan_id']) ? $_POST['jurusan_id'] : null
            ];

            $model = new AdminModel($this->db);
            try {
                $model->createUser($data);
                $_SESSION['toast'] = ['type' => 'success', 'msg' => 'User berhasil ditambahkan'];
            } catch (\Exception $e) {
                $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Username/Email sudah ada!'];
            }
            header('Location: /users'); exit;
        }
    }

    // [NEW] UPDATE USER
    public function updateUser() {
        $this->checkAdmin();
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Security Alert');

        $id = $_POST['id'];
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
        
        $sql = "UPDATE users SET role = :role, jurusan_id = :jid";
        $params = ['role' => $_POST['role'], 'jid' => !empty($_POST['jurusan_id']) ? $_POST['jurusan_id'] : null, 'id' => $id];

        if ($password) {
            $sql .= ", password = :pwd";
            $params['pwd'] = $password;
        }
        $sql .= " WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Data User diperbarui.'];
        header('Location: /users'); exit;
    }

    public function deleteUser() {
        $this->checkAdmin();
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Security Alert');
        
        $model = new AdminModel($this->db);
        $model->softDeleteUser($_POST['id'] ?? 0);
        
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'User dinonaktifkan.'];
        header('Location: /users'); exit;
    }

    // --- MASTER DATA (JURUSAN & IKU) - FULL CRUD ---
    
    public function indexMaster() {
        $this->checkAdmin();
        require __DIR__ . '/../Views/admin/master_landing.php';
    }

    // JURUSAN
    public function jurusan() {
        $this->checkAdmin();
        $model = new AdminModel($this->db);
        $jurusan = $model->getAllJurusan(); 
        require __DIR__ . '/../Views/admin/jurusan.php';
    }

    public function storeJurusan() {
        $this->checkAdmin();
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');

        $nama = trim($_POST['nama_jurusan']);
        if (!empty($nama)) {
            $model = new AdminModel($this->db);
            $model->createJurusan($nama);
            $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Jurusan ditambahkan.'];
        }
        header('Location: /master/jurusan'); exit;
    }

    // [NEW] UPDATE JURUSAN
    public function updateJurusan() {
        $this->checkAdmin();
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');

        $stmt = $this->db->prepare("UPDATE master_jurusan SET nama_jurusan = ? WHERE id = ?");
        $stmt->execute([trim($_POST['nama_jurusan']), $_POST['id']]);
        
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Nama Jurusan diperbarui.'];
        header('Location: /master/jurusan'); exit;
    }

    public function deleteJurusan() {
        $this->checkAdmin();
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');

        $model = new AdminModel($this->db);
        if ($model->deleteJurusan($_POST['id'])) {
            $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Jurusan dihapus.'];
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Gagal! Ada User di jurusan ini.'];
        }
        header('Location: /master/jurusan'); exit;
    }

    // IKU
    public function iku() {
        $this->checkAdmin();
        $model = new AdminModel($this->db);
        $iku = $model->getAllIku(); 
        require __DIR__ . '/../Views/admin/iku.php';
    }

    public function storeIku() {
        $this->checkAdmin();
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');

        $deskripsi = trim($_POST['deskripsi_iku']);
        if (!empty($deskripsi)) {
            $model = new AdminModel($this->db);
            
            // [FIX] Cek hasil return dari model
            if ($model->createIku($deskripsi)) {
                $_SESSION['toast'] = ['type' => 'success', 'msg' => 'IKU berhasil ditambahkan.'];
            } else {
                $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Gagal! IKU tersebut sudah ada.'];
            }
        }
        header('Location: /master/iku');
        exit;
    }

    public function updateIku() {
        $this->checkAdmin();
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');

        $id = $_POST['id'];
        $deskripsi = trim($_POST['deskripsi_iku']);
        
        if (!empty($deskripsi)) {
            $model = new AdminModel($this->db);
            
            // [FIX] Gunakan method updateIku di model yang sudah divalidasi
            if ($model->updateIku($id, $deskripsi)) {
                $_SESSION['toast'] = ['type' => 'success', 'msg' => 'IKU berhasil diperbarui.'];
            } else {
                $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Gagal! Deskripsi IKU sudah digunakan.'];
            }
        }
        header('Location: /master/iku'); 
        exit;
    }

    public function deleteIku() {
        $this->checkAdmin();
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) die('Invalid Token');

        $model = new AdminModel($this->db);
        if ($model->deleteIku($_POST['id'])) {
            $_SESSION['toast'] = ['type' => 'success', 'msg' => 'IKU dihapus.'];
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Gagal! IKU sedang dipakai.'];
        }
        header('Location: /master/iku'); exit;
    }
}