<?php
// app/Controllers/DashboardController.php
namespace App\Controllers;

class DashboardController
{
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $role = $_SESSION['role'] ?? '';
        switch ($role) {
            case 'Admin':
                require __DIR__ . '/../Views/dashboard/admin.php';
                break;
            case 'Pengusul':
                require __DIR__ . '/../Views/dashboard/pengusul.php';
                break;
            case 'Verifikator':
                require __DIR__ . '/../Views/dashboard/verifikator.php';
                break;
            case 'WD2':
                require __DIR__ . '/../Views/dashboard/wd2.php';
                break;
            case 'PPK':
                require __DIR__ . '/../Views/dashboard/ppk.php';
                break;
            case 'Bendahara':
                require __DIR__ . '/../Views/dashboard/bendahara.php';
                break;
            case 'Direktur':
                require __DIR__ . '/../Views/dashboard/direktur.php';
                break;
            default:
                http_response_code(403);
                require __DIR__ . '/../Views/errors/403.php';
        }
    }
}
