<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-8">Monitoring LPJ</h1>

    <?php if (isset($_SESSION['toast'])): ?>
        <div class="mb-4 p-4 rounded-lg bg-<?php echo $_SESSION['toast']['type'] == 'success' ? 'emerald' : 'rose'; ?>-100 text-<?php echo $_SESSION['toast']['type'] == 'success' ? 'emerald' : 'rose'; ?>-700 border border-<?php echo $_SESSION['toast']['type'] == 'success' ? 'emerald' : 'rose'; ?>-200 text-sm font-bold">
            <?php echo $_SESSION['toast']['msg']; unset($_SESSION['toast']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <?php if (empty($lpj_list)): ?>
            <div class="p-16 text-center">
                <span class="material-icons text-slate-300 text-5xl mb-4 block">receipt_long</span>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada LPJ</h3>
                <p class="text-slate-500">LPJ yang diupload pengusul akan muncul di sini.</p>
            </div>
        <?php else: ?>
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Kegiatan</th>
                        <th class="px-6 py-4">Pengusul</th>
                        <th class="px-6 py-4 text-center">Batas LPJ</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($lpj_list as $item): 
                        $deadline = new DateTime($item['batas_lpj']);
                        $now = new DateTime();
                        $isLate = $now > $deadline;
                    ?>
                    <tr class="hover:bg-slate-50 transition-colors <?php echo $isLate ? 'bg-rose-50/30' : ''; ?>">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800"><?php echo htmlspecialchars($item['nama_kegiatan']); ?></div>
                            <div class="text-xs text-slate-400 mt-1">ID: #<?php echo $item['id']; ?></div>
                        </td>
                        <td class="px-6 py-4 text-slate-600"><?php echo htmlspecialchars($item['username']); ?></td>
                        <td class="px-6 py-4 text-center">
                            <div class="font-mono text-xs <?php echo $isLate ? 'text-rose-600 font-bold' : 'text-slate-600'; ?>">
                                <?php echo date('d M Y', strtotime($item['batas_lpj'])); ?>
                            </div>
                            <?php if ($isLate): ?>
                                <span class="text-[10px] text-rose-600 font-bold">Terlambat!</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php
                            $allApproved = true;
                            foreach($item['lpj'] as $lpj) {
                                if($lpj['status_lpj'] !== 'Disetujui') {
                                    $allApproved = false;
                                    break;
                                }
                            }
                            ?>
                            <?php if($allApproved): ?>
                                <span class="inline-flex px-2 py-1 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 uppercase">Selesai</span>
                            <?php else: ?>
                                <span class="inline-flex px-2 py-1 rounded text-[10px] font-bold bg-amber-100 text-amber-700 border border-amber-200 uppercase">Proses</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="/bendahara/lpj-detail?id=<?php echo $item['id']; ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 shadow-md transition-all">
                                <span class="material-icons text-sm mr-2">visibility</span> Verifikasi
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