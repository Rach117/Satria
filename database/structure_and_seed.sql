-- database/structure_and_seed_v2.sql
SET time_zone = '+07:00';
DROP DATABASE IF EXISTS satria;
CREATE DATABASE IF NOT EXISTS satria DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE satria;

-- 1. Master Jurusan (dengan status)
CREATE TABLE master_jurusan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_jurusan VARCHAR(100) NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO master_jurusan (nama_jurusan) VALUES
('Teknik Sipil'),('Teknik Mesin'),('Teknik Elektro'),('Teknik Informatika'),
('Akuntansi'),('Administrasi Niaga'),('Teknik Grafika dan Penerbitan');

-- 2. Master IKU (dengan status & bobot)
CREATE TABLE master_iku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    deskripsi_iku VARCHAR(255) NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_deskripsi (deskripsi_iku)
);

INSERT INTO master_iku (deskripsi_iku) VALUES
('Lulusan Mendapat Pekerjaan'),('Melanjutkan Studi'),('Menjadi Wiraswasta'),
('Pembelajaran di Luar Program Studi'),('Magang Wajib di Luar Prodi'),
('Mahasiswa Inbound'),('Mahasiswa Meraih Prestasi'),('Tridharma di PT Lain');

-- 3. Master Satuan Anggaran (BARU)
CREATE TABLE master_satuan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_satuan VARCHAR(50) NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_satuan (nama_satuan)
);

INSERT INTO master_satuan (nama_satuan) VALUES
('Orang'),('Paket'),('Unit'),('Buah'),('Rim'),('Dos'),('Lusin'),('Kg'),('Liter'),('LS (Lump Sum)'),('OK (Orang Kegiatan)'),('PP (Pergi Pulang)');

-- 4. Master Kategori Anggaran (Fixed 3 kategori)
CREATE TABLE master_kategori_anggaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
);

INSERT INTO master_kategori_anggaran (nama_kategori) VALUES
('Belanja Barang'),('Belanja Jasa'),('Belanja Perjalanan');

-- 5. Users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Pengusul','Verifikator','WD2','PPK','Bendahara','Admin','Direktur') NOT NULL,
    jurusan_id INT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (jurusan_id) REFERENCES master_jurusan(id)
);

-- Password semua akun: demo123
INSERT INTO users (username, email, password, role, jurusan_id) VALUES
('admin', 'admin@pnj.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', NULL),
('pengusul1', 'pengusul1@pnj.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pengusul', 4),
('verifikator1', 'verif1@pnj.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Verifikator', NULL),
('wd2', 'wd2@pnj.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'WD2', NULL),
('ppk', 'ppk@pnj.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'PPK', NULL),
('bendahara1', 'bendahara@pnj.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Bendahara', NULL),
('direktur', 'direktur@pnj.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Direktur', NULL);

-- 6. Usulan Kegiatan (Data KAK + Status Verifikasi)
CREATE TABLE usulan_kegiatan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nama_kegiatan VARCHAR(255) NOT NULL,
    gambaran_umum TEXT,
    penerima_manfaat VARCHAR(255),
    target_luaran VARCHAR(255),
    kode_mak VARCHAR(50),
    nominal_rab DECIMAL(18,2) DEFAULT 0,
    status_usulan ENUM('Draft','Diajukan','Disetujui','Revisi','Ditolak') DEFAULT 'Draft',
    catatan_verifikator TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- 7. Metode Pelaksanaan (Multi)
CREATE TABLE usulan_metode (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usulan_id INT NOT NULL,
    metode TEXT NOT NULL,
    urutan INT DEFAULT 1,
    FOREIGN KEY (usulan_id) REFERENCES usulan_kegiatan(id) ON DELETE CASCADE
);

-- 8. Tahapan Pelaksanaan (Multi)
CREATE TABLE usulan_tahapan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usulan_id INT NOT NULL,
    tahapan TEXT NOT NULL,
    urutan INT DEFAULT 1,
    FOREIGN KEY (usulan_id) REFERENCES usulan_kegiatan(id) ON DELETE CASCADE
);

-- 9. Indikator Kinerja (Custom per Usulan)
CREATE TABLE usulan_indikator (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usulan_id INT NOT NULL,
    indikator VARCHAR(255) NOT NULL,
    bulan_target VARCHAR(20),
    bobot_persen INT,
    FOREIGN KEY (usulan_id) REFERENCES usulan_kegiatan(id) ON DELETE CASCADE
);

-- 10. Kurun Waktu Pelaksanaan
CREATE TABLE usulan_waktu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usulan_id INT NOT NULL,
    tanggal_mulai DATE,
    tanggal_selesai DATE,
    FOREIGN KEY (usulan_id) REFERENCES usulan_kegiatan(id) ON DELETE CASCADE
);

-- 11. IKU Terpilih (dengan bobot %)
CREATE TABLE usulan_iku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usulan_id INT NOT NULL,
    iku_id INT NOT NULL,
    bobot_persen INT NOT NULL,
    FOREIGN KEY (usulan_id) REFERENCES usulan_kegiatan(id) ON DELETE CASCADE,
    FOREIGN KEY (iku_id) REFERENCES master_iku(id)
);

-- 12. RAB Detail
CREATE TABLE rab_detail (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usulan_id INT NOT NULL,
    kategori_id INT NOT NULL,
    uraian VARCHAR(255) NOT NULL,
    volume INT NOT NULL,
    satuan_id INT NOT NULL,
    harga_satuan DECIMAL(18,2) NOT NULL,
    total DECIMAL(18,2) NOT NULL,
    FOREIGN KEY (usulan_id) REFERENCES usulan_kegiatan(id) ON DELETE CASCADE,
    FOREIGN KEY (kategori_id) REFERENCES master_kategori_anggaran(id),
    FOREIGN KEY (satuan_id) REFERENCES master_satuan(id)
);

-- 13. Pengajuan Kegiatan (Setelah usulan disetujui)
CREATE TABLE pengajuan_kegiatan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usulan_id INT NOT NULL,
    penanggung_jawab VARCHAR(100),
    pelaksana VARCHAR(100),
    waktu_pelaksanaan_mulai DATE,
    waktu_pelaksanaan_selesai DATE,
    surat_pengantar_path VARCHAR(255),
    status_pengajuan ENUM('Menunggu PPK','Menunggu WD2','Disetujui','Ditolak') DEFAULT 'Menunggu PPK',
    rekomendasi_ppk TEXT,
    rekomendasi_wd2 TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usulan_id) REFERENCES usulan_kegiatan(id) ON DELETE CASCADE
);

-- 14. Pencairan Dana (Bertahap per Kategori)
CREATE TABLE pencairan_dana (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pengajuan_id INT NOT NULL,
    kategori_id INT NOT NULL,
    nominal_pencairan DECIMAL(18,2) NOT NULL,
    bukti_transfer_path VARCHAR(255),
    tanggal_pencairan DATE,
    status ENUM('Pending','Selesai') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pengajuan_id) REFERENCES pengajuan_kegiatan(id),
    FOREIGN KEY (kategori_id) REFERENCES master_kategori_anggaran(id)
);

-- 15. LPJ (Per Kategori)
CREATE TABLE lpj_kegiatan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pengajuan_id INT NOT NULL,
    kategori_id INT NOT NULL,
    nominal_lpj DECIMAL(18,2) NOT NULL,
    bukti_lpj_path VARCHAR(255),
    status_lpj ENUM('Pending','Direvisi','Disetujui') DEFAULT 'Pending',
    catatan_bendahara TEXT,
    tanggal_upload TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pengajuan_id) REFERENCES pengajuan_kegiatan(id),
    FOREIGN KEY (kategori_id) REFERENCES master_kategori_anggaran(id)
);

-- 16. Notifikasi (Volatile - bisa dihapus user)
CREATE TABLE notifikasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    judul VARCHAR(255) NOT NULL,
    pesan TEXT,
    link VARCHAR(255),
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 17. Log Histori (Audit Trail)
CREATE TABLE log_histori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usulan_id INT,
    pengajuan_id INT,
    user_id INT NOT NULL,
    aksi VARCHAR(100),
    status_lama VARCHAR(50),
    status_baru VARCHAR(50),
    catatan TEXT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usulan_id) REFERENCES usulan_kegiatan(id) ON DELETE CASCADE,
    FOREIGN KEY (pengajuan_id) REFERENCES pengajuan_kegiatan(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);