<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Verifikasi Dokumen</h1>
            <p class="text-slate-500 mt-1">Pemeriksaan kelengkapan administrasi usulan kegiatan.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-4 py-2 bg-emerald-50 text-emerald-700 rounded-lg text-sm font-bold border border-emerald-100 shadow-sm">
                <span class="material-icons text-sm mr-2 align-middle">pending_actions</span>
                Antrian: <?php echo isset($total) ? $total : count($usulan); ?>
            </span>
        </div>
    </div>

    <?php if (empty($usulan)): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-16 text-center">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-slate-50 mb-6">
                <span class="material-icons text-slate-300 text-5xl">fact_check</span>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Semua Bersih!</h3>
            <p class="text-slate-500">Tidak ada dokumen yang perlu diverifikasi saat ini.</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 tracking-wider">Detail Usulan</th>
                        <th class="px-6 py-4 tracking-wider">Pengusul</th>
                        <th class="px-6 py-4 tracking-wider">Tanggal Masuk</th>
                        <th class="px-6 py-4 tracking-wider text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($usulan as $row): ?>
                    <tr class="hover:bg-emerald-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800 text-base mb-1 group-hover:text-emerald-700 transition-colors">
                                <?php echo htmlspecialchars($row['nama_kegiatan']); ?>
                            </div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded border border-slate-200 font-mono">
                                    ID: #<?php echo $row['id']; ?>
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold mr-3 border border-emerald-200">
                                    <?php echo strtoupper(substr($row['username'], 0, 1)); ?>
                                </div>
                                <span class="text-slate-700 font-medium"><?php echo htmlspecialchars($row['username']); ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-500">
                            <?php echo date('d M Y', strtotime($row['created_at'] ?? date('Y-m-d'))); ?>
                            <div class="text-xs text-slate-400">Menunggu Review</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="/verifikasi/proses?id=<?php echo $row['id']; ?>" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 text-white text-xs font-bold rounded-lg shadow-md hover:bg-emerald-700 hover:-translate-y-0.5 transition-all">
                                <span class="material-icons text-sm mr-2">search</span> Periksa
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php include __DIR__.'/../partials/footer.php'; ?>