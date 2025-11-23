<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12pt; line-height: 1.6; }
        .header { text-align: center; border-bottom: 2px solid #000; margin-bottom: 30px; padding-bottom: 10px; }
        .content { text-align: justify; margin: 20px 0; }
        .footer { margin-top: 50px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h3 style="margin:0">KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN</h3>
        <h2 style="margin:0">POLITEKNIK NEGERI JAKARTA</h2>
        <p style="margin:0; font-size: 10pt">Jl. Prof. Dr. G.A. Siwabessy, Kampus UI, Depok 16425</p>
    </div>

    <div style="text-align: center; font-weight: bold; text-decoration: underline; margin-bottom: 20px;">SURAT TEGURAN</div>
    
    <div style="margin-bottom: 20px;">
        Nomor: .../PL3/TU/<?= date('Y') ?><br>
        Hal: <strong>Keterlambatan Laporan Pertanggungjawaban (LPJ)</strong>
    </div>

    <div class="content">
        <p>Kepada Yth.<br>
        Penanggung Jawab Kegiatan <strong><?= htmlspecialchars($usulan['nama_kegiatan']) ?></strong><br>
        Sdr. <?= htmlspecialchars($usulan['username']) ?></p>

        <p>Dengan hormat,</p>
        <p>Berdasarkan hasil monitoring dan evaluasi administrasi keuangan, kami menemukan bahwa kegiatan tersebut di atas telah melewati batas waktu pelaporan (Deadline: <strong><?= date('d F Y', strtotime($usulan['tgl_batas_lpj'])) ?></strong>) dan hingga saat ini dokumen LPJ belum kami terima.</p>
        
        <p>Sehubungan dengan hal tersebut, kami memberikan <strong>TEGURAN PERTAMA</strong> agar Saudara segera menyelesaikan kewajiban administrasi selambat-lambatnya 3 (tiga) hari kerja setelah surat ini diterbitkan. Kelalaian lebih lanjut dapat berdampak pada penangguhan anggaran kegiatan Saudara di masa mendatang.</p>
        
        <p>Demikian disampaikan untuk menjadi perhatian dan dilaksanakan.</p>
    </div>

    <div class="footer">
        Depok, <?= date('d F Y') ?><br>
        Wakil Direktur II,<br><br><br><br>
        <strong>_______________________</strong><br>
        NIP. ..............................
    </div>
</body>
</html>