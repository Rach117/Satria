<?php
// app/Controllers/PdfController.php
namespace App\Controllers;

use Mpdf\Mpdf;
use PDO;

class PdfController
{
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
    }
    public function kak($id)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login'); exit;
        }
        $stmt = $this->db->prepare("SELECT u.*, us.username, j.nama_jurusan FROM usulan_kegiatan u JOIN users us ON u.user_id = us.id LEFT JOIN master_jurusan j ON us.jurusan_id = j.id WHERE u.id = :id");
        $stmt->execute(['id' => $id]);
        $usulan = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$usulan) { http_response_code(404); exit; }
        $rab = $this->db->prepare("SELECT r.*, k.nama_kategori FROM rab_detail r JOIN master_kategori_anggaran k ON r.kategori_id = k.id WHERE r.usulan_id = :id");
        $rab->execute(['id' => $id]);
        $rabRows = $rab->fetchAll(PDO::FETCH_ASSOC);
        $mpdf = new Mpdf(['format' => 'A4']);
        ob_start();
        include __DIR__.'/../Views/pdf/kak.php';
        $html = ob_get_clean();
        $mpdf->WriteHTML($html);
        $mpdf->Output('KAK_'.$usulan['nama_kegiatan'].'.pdf', 'I');
        exit;
    }

    public function rab($id)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login'); exit;
        }
        $stmt = $this->db->prepare("SELECT u.*, us.username, j.nama_jurusan FROM usulan_kegiatan u JOIN users us ON u.user_id = us.id LEFT JOIN master_jurusan j ON us.jurusan_id = j.id WHERE u.id = :id");
        $stmt->execute(['id' => $id]);
        $usulan = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$usulan) { http_response_code(404); exit; }
        $rab = $this->db->prepare("SELECT r.*, k.nama_kategori FROM rab_detail r JOIN master_kategori_anggaran k ON r.kategori_id = k.id WHERE r.usulan_id = :id");
        $rab->execute(['id' => $id]);
        $rabRows = $rab->fetchAll(PDO::FETCH_ASSOC);
        $mpdf = new Mpdf(['format' => 'A4']);
        ob_start();
        include __DIR__.'/../Views/pdf/rab.php';
        $html = ob_get_clean();
        $mpdf->WriteHTML($html);
        $mpdf->Output('RAB_'.$usulan['nama_kegiatan'].'.pdf', 'I');
        exit;
    }

    public function suratTeguran($id)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login'); exit;
        }
        $stmt = $this->db->prepare("SELECT u.*, us.username, j.nama_jurusan FROM usulan_kegiatan u JOIN users us ON u.user_id = us.id LEFT JOIN master_jurusan j ON us.jurusan_id = j.id WHERE u.id = :id");
        $stmt->execute(['id' => $id]);
        $usulan = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$usulan) { http_response_code(404); exit; }
        $mpdf = new Mpdf(['format' => 'A4']);
        ob_start();
        include __DIR__.'/../Views/pdf/surat_teguran.php';
        $html = ob_get_clean();
        $mpdf->WriteHTML($html);
        $mpdf->Output('Surat_Teguran_'.$usulan['nama_kegiatan'].'.pdf', 'I');
        exit;
    }

    public function beritaAcara($id)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login'); exit;
        }
        $stmt = $this->db->prepare("SELECT u.*, us.username, j.nama_jurusan FROM usulan_kegiatan u JOIN users us ON u.user_id = us.id LEFT JOIN master_jurusan j ON us.jurusan_id = j.id WHERE u.id = :id");
        $stmt->execute(['id' => $id]);
        $usulan = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$usulan) { http_response_code(404); exit; }
        $mpdf = new Mpdf(['format' => 'A4']);
        if (!function_exists('tanggal_indo')) {
            function tanggal_indo($tgl) {
                $bulan = [1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
                $exp = explode('-', $tgl);
                return $exp[2].' '.$bulan[(int)$exp[1]].' '.$exp[0];
            }
        }
        ob_start();
        include __DIR__.'/../Views/pdf/berita_acara.php';
        $html = ob_get_clean();
        $mpdf->WriteHTML($html);
        $mpdf->Output('Berita_Acara_'.$usulan['nama_kegiatan'].'.pdf', 'I');
        exit;
    }
}
