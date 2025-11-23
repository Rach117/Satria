<?php
namespace App\Controllers;

use App\Models\UsulanModel;

class LaporanController {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function index() {
        if (!isset($_SESSION['user_id'])) { header('Location: /login'); exit; }
        
        // [REFACTORED] Jauh lebih bersih & mudah dibaca
        $model = new UsulanModel($this->db);
        
        $stats  = $model->getDashboardStats();
        $recent = $model->getRecentActivity(5);
        
        require __DIR__ . '/../Views/laporan/index.php';
    }
}