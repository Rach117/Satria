<?php
// app/Controllers/UsulanWizardController.php
namespace App\Controllers;

use PDO;

class UsulanWizardController
{
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create()
    {
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'Pengusul') {
            header('Location: /login');
            exit;
        }
        // Ambil master data
        $iku = $this->db->query('SELECT * FROM master_iku')->fetchAll(PDO::FETCH_ASSOC);
        $jurusan = $this->db->query('SELECT * FROM master_jurusan')->fetchAll(PDO::FETCH_ASSOC);
        $kategori = $this->db->query('SELECT * FROM master_kategori_anggaran')->fetchAll(PDO::FETCH_ASSOC);
        require __DIR__ . '/../Views/usulan/wizard.php';
    }

    // Proses simpan usulan (step terakhir)
    public function store()
    {

        // 1. Validasi Sesi
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'Pengusul') {
            header('Location: /login'); exit;
        }

        // 2. [ELITE SECURITY] Validasi CSRF
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('Security Alert: Invalid CSRF Token. Pengajuan ditolak.');
        }

        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'Pengusul') {
            header('Location: /login');
            exit;
        }
        $userId = $_SESSION['user_id'];
        $nama_kegiatan = trim($_POST['nama_kegiatan'] ?? '');
        $penerima_manfaat = trim($_POST['penerima_manfaat'] ?? '');
        $gambaran_umum = trim($_POST['gambaran_umum'] ?? '');
        $iku_id = $_POST['iku_id'] ?? [];
        $kategori_id = $_POST['kategori_id'] ?? [];
        $uraian = $_POST['uraian'] ?? [];
        $volume = $_POST['volume'] ?? [];
        $satuan = $_POST['satuan'] ?? [];
        $harga_satuan = $_POST['harga_satuan'] ?? [];
        $total = $_POST['total'] ?? [];
        // Validasi minimal
        if (!$nama_kegiatan || !$penerima_manfaat || !$gambaran_umum || empty($iku_id) || empty($kategori_id)) {
            $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Data tidak lengkap!'];
            header('Location: /usulan/create');
            exit;
        }
        // Validasi file upload
        $file = $_FILES['surat_pengantar'] ?? null;
        $allowed = ['application/pdf']; // Hanya izinkan PDF
        if (!$file || $file['error'] !== UPLOAD_ERR_OK || $file['size'] > 5*1024*1024 || !in_array(mime_content_type($file['tmp_name']), $allowed)) {
            $_SESSION['toast'] = ['type' => 'error', 'msg' => 'File harus berupa PDF dan maksimal 5MB!'];
            header('Location: /usulan/create');
            exit;
        }
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newName = 'surat_' . bin2hex(random_bytes(8)) . '.' . $ext;
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $dest = $uploadDir . $newName;
        move_uploaded_file($file['tmp_name'], $dest);
        // Simpan ke DB (transaksi)
        $this->db->beginTransaction();
        $stmt = $this->db->prepare("INSERT INTO usulan_kegiatan (user_id, nama_kegiatan, gambaran_umum, penerima_manfaat, metode_pelaksanaan, tahapan_pelaksanaan, kurun_waktu_pelaksanaan, status_terkini) VALUES (:uid, :nama, :gambaran, :penerima, '', '', '', 'Verifikasi')");
        $stmt->execute([
            'uid' => $userId,
            'nama' => $nama_kegiatan,
            'gambaran' => $gambaran_umum,
            'penerima' => $penerima_manfaat
        ]);
        $usulanId = $this->db->lastInsertId();
        // RAB
        $rabStmt = $this->db->prepare("INSERT INTO rab_detail (usulan_id, kategori_id, uraian, volume, satuan, harga_satuan, total) VALUES (:usulan_id, :kategori_id, :uraian, :volume, :satuan, :harga_satuan, :total)");
        for ($i=0; $i<count($kategori_id); $i++) {
            $rabStmt->execute([
                'usulan_id' => $usulanId,
                'kategori_id' => $kategori_id[$i],
                'uraian' => $uraian[$i],
                'volume' => $volume[$i],
                'satuan' => $satuan[$i],
                'harga_satuan' => $harga_satuan[$i],
                'total' => ($volume[$i] * $harga_satuan[$i])
            ]);
        }
        // IKU
        $ikuStmt = $this->db->prepare("INSERT INTO tor_iku (usulan_id, iku_id) VALUES (:usulan_id, :iku_id)");
        foreach ($iku_id as $ikuid) {
            $ikuStmt->execute(['usulan_id' => $usulanId, 'iku_id' => $ikuid]);
        }
        // Dokumen pendukung
        $dokStmt = $this->db->prepare("INSERT INTO dokumen_pendukung (usulan_id, jenis_dokumen, file_path, versi) VALUES (:usulan_id, 'Surat Pengantar', :file_path, 1)");
        $dokStmt->execute(['usulan_id' => $usulanId, 'file_path' => '/uploads/' . $newName]);
        $this->db->commit();
        $_SESSION['toast'] = ['type' => 'success', 'msg' => 'Usulan berhasil disimpan!'];
        header('Location: /monitoring');
        exit;
    }
}
