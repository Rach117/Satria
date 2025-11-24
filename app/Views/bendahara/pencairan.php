<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pencairan Dana Kegiatan</h1>
            <p class="text-slate-500 mt-1">Proses pencairan dana secara bertahap per kategori anggaran.</p>
        </div>
    </div>

    <?php if (isset($_SESSION['toast'])): ?>
        <div class="mb-4 p-4 rounded-lg bg-<?php echo $_SESSION['toast']['type'] == 'success' ? 'emerald' : 'rose'; ?>-100 text-<?php echo $_SESSION['toast']['type'] == 'success' ? 'emerald' : 'rose'; ?>-700 border border-<?php echo $_SESSION['toast']['type'] == 'success' ? 'emerald' : 'rose'; ?>-200 text-sm font-bold">
            <?php echo $_SESSION['toast']['msg']; unset($_SESSION['toast']); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl p-6 text-white shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <span class="material-icons text-4xl">account_balance_wallet</span>
                <span class="text-xs font-bold text-blue-200 uppercase">Dana Tersedia</span>
            </div>
            <div class="text-3xl font-extrabold mb-1">Rp <?php echo number_format(($stats['dana_tersedia'] ?? 0) / 1000000, 1); ?> M</div>
            <div class="text-xs text-blue-200">Total anggaran</div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="material-icons text-4xl text-rose-600">trending_down</span>
                <span class="text-xs font-bold text-slate-400 uppercase">Dana Keluar</span>
            </div>
            <div class="text-3xl font-extrabold text-slate-800 mb-1">Rp <?php echo number_format(($stats['dana_keluar'] ?? 0) / 1000000, 1); ?> M</div>
            <div class="text-xs text-slate-500">Total tercairkan</div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="material-icons text-4xl text-amber-600">pending_actions</span>
                <span class="text-xs font-bold text-slate-400 uppercase">Antrian</span>
            </div>
            <div class="text-3xl font-extrabold text-slate-800 mb-1"><?php echo $stats['kegiatan_pending'] ?? 0; ?></div>
            <div class="text-xs text-slate-500">Kegiatan menunggu</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <?php if (empty($pengajuan_list)): ?>
            <div class="p-16 text-center">
                <span class="material-icons text-slate-300 text-5xl mb-4 block">event_available</span>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Tidak Ada Antrian</h3>
                <p class="text-slate-500">Semua pencairan telah diproses.</p>
            </div>
        <?php else: ?>
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Nama Kegiatan</th>
                        <th class="px-6 py-4">Pengusul</th>
                        <th class="px-6 py-4 text-right">Total RAB</th>
                        <th class="px-6 py-4 text-center">Status Pencairan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($pengajuan_list as $p): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800"><?php echo htmlspecialchars($p['nama_kegiatan']); ?></div>
                            <div class="text-xs text-slate-400 mt-1">ID: #<?php echo $p['id']; ?></div>
                        </td>
                        <td class="px-6 py-4 text-slate-600"><?php echo htmlspecialchars($p['username']); ?></td>
                        <td class="px-6 py-4 text-right font-mono font-bold text-slate-700">
                            Rp <?php echo number_format($p['nominal_rab'], 0, ',', '.'); ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php
                            $total_cair = 0;
                            foreach($p['rab_summary'] as $rab) {
                                $total_cair += $this->pengajuanModel->getTotalPencairanByKategori($p['id'], $rab['kategori_id']);
                            }
                            $progress = ($p['nominal_rab'] > 0) ? ($total_cair / $p['nominal_rab']) * 100 : 0;
                            ?>
                            <div class="text-xs font-bold text-slate-600 mb-1"><?php echo number_format($progress, 0); ?>% Tercairkan</div>
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo $progress; ?>%"></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="/bendahara/form-pencairan?pengajuan_id=<?php echo $p['id']; ?>" class="inline-flex items-center px-4 py-2 bg-amber-500 text-white text-xs font-bold rounded-lg hover:bg-amber-600 shadow-md transition-all">
                                <span class="material-icons text-sm mr-2">payments</span> Cairkan Dana
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