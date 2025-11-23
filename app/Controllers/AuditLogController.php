<?php
namespace App\Controllers;

use App\Models\AdminModel;

class AuditLogController
{
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index($page = 1, $perPage = 20)
    {
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'Admin') {
            header('Location: /login'); exit;
        }

        $filters = [
            'user'   => $_GET['user'] ?? '',
            'action' => $_GET['action'] ?? '',
            'date'   => $_GET['date'] ?? ''
        ];

        $model = new AdminModel($this->db);
        $logs = $model->getAuditLogs($filters, $page, $perPage);

        // Untuk hitung total halaman bisa ditambahkan count di model, 
        // sementara kita ambil simplenya dulu atau gunakan metode count terpisah jika perlu pagination akurat.
        // Untuk efisiensi waktu, query count sederhana bisa ditaruh di model jika ingin sempurna.
        
        require __DIR__ . '/../Views/admin/audit_log.php';
    }

    public function export()
    {
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'Admin') {
            header('Location: /login'); exit;
        }

        $model = new AdminModel($this->db);
        $logs = $model->getAllLogsForExport();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="audit_log.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Waktu', 'User', 'Aksi', 'IP Address']);

        foreach ($logs as $log) {
            fputcsv($output, $log);
        }

        fclose($output);
        exit;
    }
}