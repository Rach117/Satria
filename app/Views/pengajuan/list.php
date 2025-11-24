<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pengajuan Kegiatan</h1>
        <p class="text-slate-500 mt-1">Ajukan kegiatan dari usulan yang telah disetujui verifikator.</p>
    </div>

    <?php if (isset($_SESSION['toast'])): ?>
        <div class="mb-4 p-4 rounded-lg bg-<?php echo $_SESSION['toast']['type'] == 'success' ? 'emerald' : 'rose'; ?>-100 text-<?php echo $_SESSION['toast']['type'] == 'success' ? 'emerald' : 'rose'; ?>-700 border border-<?php echo $_SESSION['toast']['type'] == 'success' ? 'emerald' : 'rose'; ?>-200 text-sm font-bold">
            <?php echo $_SESSION['toast']['msg']; unset($_SESSION['toast']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <?php if (empty($usulan_list)): ?>
            <div class="p-16 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-50 mb-6">
                    <span class="material-icons text-slate-300 text-4xl">event_available</span>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Usulan Disetujui</h3>
                <p class="text-slate-500">Usulan Anda yang disetujui akan muncul di sini.</p>
            </div>
        <?php else: ?>
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Nama Kegiatan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Anggaran</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($usulan_list as $row): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800"><?php echo htmlspecialchars($row['nama_kegiatan']); ?></div>
                            <div class="text-xs text-slate-400 mt-1">Kode MAK: <?php echo $row['kode_mak'] ?: '-'; ?></div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if ($row['sudah_diajukan']): ?>
                                <span class="inline-flex px-2.5 py-1 rounded text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200 uppercase">
                                    Sudah Diajukan
                                </span>
                            <?php else: ?>
                                <span class="inline-flex px-2.5 py-1 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 uppercase">
                                    Siap Diajukan
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right font-mono font-bold text-slate-700">
                            Rp <?php echo number_format($row['nominal_rab'], 0, ',', '.'); ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <?php if ($row['sudah_diajukan']): ?>
                                <a href="/pengajuan/detail?id=<?php echo $row['pengajuan_id']; ?>" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-lg hover:bg-slate-200 transition-all">
                                    <span class="material-icons text-sm mr-2">visibility</span> Detail
                                </a>
                            <?php else: ?>
                                <a href="/pengajuan/create?usulan_id=<?php echo $row['id']; ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 shadow-md transition-all">
                                    <span class="material-icons text-sm mr-2">send</span> Ajukan Kegiatan
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__.'/../partials/footer.php'; ?>