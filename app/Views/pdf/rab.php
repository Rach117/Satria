<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { text-align: center; font-weight: bold; font-size: 14px; margin-bottom: 20px; text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #ccc; text-align: center; font-weight: bold; padding: 8px; border: 1px solid #000; }
        td { border: 1px solid #000; padding: 5px; }
        .num { text-align: right; }
        .center { text-align: center; }
        .total-row { background-color: #eee; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">RENCANA ANGGARAN BIAYA (RAB)</div>
    
    <div style="margin-bottom: 15px;">
        <strong>Nama Kegiatan:</strong> <?= htmlspecialchars($usulan['nama_kegiatan']) ?><br>
        <strong>Kode MAK:</strong> <?= htmlspecialchars($usulan['kode_mak'] ?: '-') ?>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Uraian Belanja</th>
                <th width="10%">Vol</th>
                <th width="10%">Satuan</th>
                <th width="15%">Harga Satuan</th>
                <th width="15%">Total</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $grandTotal = 0;
        foreach ($rabRows as $i => $row): 
            $total = $row['volume'] * $row['harga_satuan'];
            $grandTotal += $total;
        ?>
            <tr>
                <td class="center"><?= $i+1 ?></td>
                <td><?= htmlspecialchars($row['uraian']) ?> <br> <em>(<?= htmlspecialchars($row['nama_kategori']) ?>)</em></td>
                <td class="center"><?= $row['volume'] ?></td>
                <td class="center"><?= htmlspecialchars($row['satuan']) ?></td>
                <td class="num">Rp <?= number_format($row['harga_satuan'], 0, ',', '.') ?></td>
                <td class="num">Rp <?= number_format($total, 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
            <tr class="total-row">
                <td colspan="5" style="text-align: right; padding-right: 10px;">GRAND TOTAL</td>
                <td class="num">Rp <?= number_format($grandTotal, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>
</body>
</html>