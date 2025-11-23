<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12pt; line-height: 1.6; color: #000; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 30px; }
        .header img { height: 80px; }
        .header h2 { margin: 0; font-size: 14pt; text-transform: uppercase; }
        .header p { margin: 0; font-size: 11pt; }
        .title { text-align: center; font-weight: bold; text-decoration: underline; margin-bottom: 30px; font-size: 14pt; }
        .content { text-align: justify; margin-bottom: 40px; }
        .signature-table { width: 100%; margin-top: 50px; }
        .signature-table td { text-align: center; vertical-align: top; width: 50%; }
        .sign-space { height: 80px; }
        .box-info { border: 1px solid #000; padding: 15px; margin: 20px 0; background-color: #f9f9f9; }
    </style>
</head>
<body>
    <div class="header">
        <img src="logo_pnj.png" alt="Logo PNJ">
        <h2>KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</h2>
        <h2>POLITEKNIK NEGERI JAKARTA</h2>
        <p>Jl. Prof. Dr. G.A. Siwabessy, Kampus Universitas Indonesia Depok 16425</p>
    </div>

    <div class="title">BERITA ACARA PENYELESAIAN KEGIATAN</div>

    <div class="content">
        <p>Pada hari ini, <strong><?= tanggal_indo(date('Y-m-d')) ?></strong>, bertempat di Kampus Politeknik Negeri Jakarta, yang bertanda tangan di bawah ini:</p>

        <div class="box-info">
            <table>
                <tr><td width="120">Nama Kegiatan</td><td>: <strong><?= htmlspecialchars($usulan['nama_kegiatan']) ?></strong></td></tr>
                <tr><td>Penanggung Jawab</td><td>: <?= htmlspecialchars($usulan['username']) ?></td></tr>
                <tr><td>Total Anggaran</td><td>: Rp <?= number_format($usulan['nominal_pencairan'], 0, ',', '.') ?></td></tr>
            </table>
        </div>

        <p>Menyatakan bahwa kegiatan tersebut di atas telah <strong>SELESAI DILAKSANAKAN</strong> sesuai dengan Kerangka Acuan Kerja (TOR) dan anggaran yang disetujui. Seluruh dokumen pertanggungjawaban (LPJ) fisik maupun softcopy telah diserahkan dan diverifikasi kelengkapannya.</p>
        
        <p>Demikian berita acara ini dibuat dengan sesungguhnya untuk dipergunakan sebagaimana mestinya.</p>
    </div>

    <table class="signature-table">
        <tr>
            <td>
                Mengetahui,<br>
                Bendahara Pengeluaran Pembantu<br>
                <div class="sign-space"></div>
                <strong>__________________________</strong><br>
                NIP. .................................
            </td>
            <td>
                Depok, <?= date('d F Y') ?><br>
                Penanggung Jawab Kegiatan<br>
                <div class="sign-space"></div>
                <strong><?= htmlspecialchars($usulan['username']) ?></strong><br>
                NIP. .................................
            </td>
        </tr>
    </table>
</body>
</html>