<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.5; color: #000; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header img { height: 80px; }
        .header h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 11px; }
        .title { text-align: center; font-weight: bold; text-decoration: underline; margin: 20px 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #000; padding: 6px 10px; vertical-align: top; text-align: left; }
        th { background-color: #f0f0f0; font-weight: bold; }
        .no-border td { border: none; padding: 4px; }
        .signature { margin-top: 40px; width: 100%; }
        .signature td { border: none; text-align: center; vertical-align: bottom; height: 80px; }
    </style>
</head>
<body>
    <div class="header">
        <img src="logo_pnj.png" alt="Logo"> 
        <h2>KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</h2>
        <h2>POLITEKNIK NEGERI JAKARTA</h2>
        <p>Jl. Prof. Dr. G.A. Siwabessy, Kampus Universitas Indonesia Depok 16425</p>
        <p>Telepon (021) 7270036, Faksimili (021) 7270034</p>
        <p>Laman: http://www.pnj.ac.id, Surel: humas@pnj.ac.id</p>
    </div>

    <div class="title">KERANGKA ACUAN KEGIATAN (TOR)</div>

    <table class="no-border">
        <tr><td width="20%">Nama Kegiatan</td><td width="2%">:</td><td><?= htmlspecialchars($usulan['nama_kegiatan']) ?></td></tr>
        <tr><td>Unit Pengusul</td><td>:</td><td><?= htmlspecialchars($usulan['username']) ?></td></tr>
        <tr><td>Tahun Anggaran</td><td>:</td><td><?= date('Y') ?></td></tr>
    </table>

    <table>
        <tr><th width="5%">NO</th><th>URAIAN</th></tr>
        <tr>
            <td align="center">1</td>
            <td>
                <strong>Latar Belakang & Gambaran Umum</strong><br>
                <?= nl2br(htmlspecialchars($usulan['gambaran_umum'])) ?>
            </td>
        </tr>
        <tr>
            <td align="center">2</td>
            <td>
                <strong>Penerima Manfaat</strong><br>
                <?= htmlspecialchars($usulan['penerima_manfaat']) ?>
            </td>
        </tr>
        <tr>
            <td align="center">3</td>
            <td>
                <strong>Jadwal Pelaksanaan</strong><br>
                <?= htmlspecialchars($usulan['kurun_waktu_pelaksanaan'] ?? 'Menyesuaikan Jadwal Akademik') ?>
            </td>
        </tr>
        <tr>
            <td align="center">4</td>
            <td>
                <strong>Rencana Anggaran Biaya (Estimasi)</strong><br>
                Rp <?= number_format($usulan['nominal_pencairan'], 0, ',', '.') ?>
            </td>
        </tr>
    </table>

    <table class="signature">
        <tr>
            <td width="50%">
                Mengetahui,<br>Kepala Unit/Jurusan<br><br><br><br>
                _________________________<br>NIP. .................................
            </td>
            <td width="50%">
                Depok, <?= date('d F Y') ?><br>Pengusul Kegiatan<br><br><br><br>
                <strong><?= htmlspecialchars($usulan['username']) ?></strong><br>NIP. .................................
            </td>
        </tr>
    </table>
</body>
</html>