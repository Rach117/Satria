<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    <div class="mb-8">
        <a href="/approval" class="text-slate-500 hover:text-indigo-600 text-sm flex items-center font-bold transition-colors mb-4">
            <span class="material-icons text-sm mr-1">arrow_back</span> Kembali
        </a>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Riwayat Persetujuan WD2</h1>
        <p class="text-slate-500 mt-1">Histori kegiatan yang telah disetujui.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <?php if (empty($riwayat)): ?>
            <div class="p-16 text-center">
                <span class="material-icons text-slate-300 text-5xl mb-4">history</span>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Riwayat</h3>
                <p class="text-slate-500">Riwayat persetujuan akan muncul di sini.</p>
            </div>
        <?php else: ?>
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Nama Kegiatan</th>
                        <th class="px-6 py-4">Pengusul</th>
                        <th class="px-6 py-4 text-right">Anggaran</th>
                        <th class="px-6 py-4 text-center">Tanggal Disetujui</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($riwayat as $row): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800"><?php echo htmlspecialchars($row['nama_kegiatan']); ?></div>
                            <div class="text-xs text-slate-500 mt-1">ID: #<?php echo $row['id']; ?></div>
                        </td>
                        <td class="px-6 py-4 text-slate-700"><?php echo htmlspecialchars($row['username']); ?></td>
                        <td class="px-6 py-4 text-right font-mono font-bold text-slate-700">
                            Rp <?php echo number_format($row['nominal_rab'], 0, ',', '.'); ?>
                        </td>
                        <td class="px-6 py-4 text-center text-xs text-slate-500">
                            <?php echo date('d M Y H:i', strtotime($row['updated_at'])); ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="/pengajuan/detail?id=<?php echo $row['id']; ?>" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-lg hover:bg-slate-200 transition-all">
                                <span class="material-icons text-sm mr-2">visibility</span> Detail
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__.'/../partials/footer.php'; ?>