<?php
// app/Controllers/MonitoringController.php
namespace App\Controllers;

use PDO;
use App\Models\UsulanModel; // Import Model Baru

class MonitoringController
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

        // [ELITE REFACTORING] Menggunakan Model, bukan Query SQL mentah
        $usulanModel = new UsulanModel($this->db);

        // Siapkan Filter dari Input User & Sesi
        $filters = [
            'role'    => $_SESSION['role'] ?? '',
            'user_id' => $_SESSION['user_id'],
            'search'  => $_GET['q'] ?? '',
            'status'  => $_GET['status'] ?? '',
            'date'    => $_GET['date'] ?? ''
        ];

        // Ambil Data via Model
        $usulan = $usulanModel->getAllWithUser($filters, $page, $perPage);
        $total  = $usulanModel->countAllWithUser($filters);

        require __DIR__ . '/../Views/monitoring/index.php';
    }
}