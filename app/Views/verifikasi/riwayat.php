<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    <div class="mb-8">
        <a href="/verifikasi" class="text-slate-500 hover:text-emerald-600 text-sm flex items-center font-bold transition-colors mb-4">
            <span class="material-icons text-sm mr-1">arrow_back</span> Kembali ke Verifikasi
        </a>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Riwayat Verifikasi</h1>
        <p class="text-slate-500 mt-1">Histori usulan yang telah diproses oleh verifikator.</p>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" placeholder="Cari nama kegiatan..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 outline-none text-sm">
            
            <select name="status" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 outline-none text-sm bg-white">
                <option value="">Semua Status</option>
                <option value="Disetujui" <?php echo ($_GET['status'] ?? '') === 'Disetujui' ? 'selected' : ''; ?>>Disetujui</option>
                <option value="Revisi" <?php echo ($_GET['status'] ?? '') === 'Revisi' ? 'selected' : ''; ?>>Revisi</option>
                <option value="Ditolak" <?php echo ($_GET['status'] ?? '') === 'Ditolak' ? 'selected' : ''; ?>>Ditolak</option>
            </select>
            
            <input type="date" name="date" value="<?php echo htmlspecialchars($_GET['date'] ?? ''); ?>" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 outline-none text-sm">
            
            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 transition-colors text-sm">
                Filter
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-400 uppercase">Disetujui</span>
                <span class="material-icons text-emerald-500">check_circle</span>
            </div>
            <div class="text-3xl font-extrabold text-emerald-600"><?php echo $stats['disetujui'] ?? 0; ?></div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-400 uppercase">Revisi</span>
                <span class="material-icons text-amber-500">edit</span>
            </div>
            <div class="text-3xl font-extrabold text-amber-600"><?php echo $stats['revisi'] ?? 0; ?></div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-400 uppercase">Ditolak</span>
                <span class="material-icons text-rose-500">cancel</span>
            </div>
            <div class="text-3xl font-extrabold text-rose-600"><?php echo $stats['ditolak'] ?? 0; ?></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <?php if (empty($usulan)): ?>
            <div class="p-16 text-center">
                <span class="material-icons text-slate-300 text-5xl mb-4">history</span>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Riwayat</h3>
                <p class="text-slate-500">Riwayat verifikasi akan muncul di sini.</p>
            </div>
        <?php else: ?>
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Nama Kegiatan</th>
                        <th class="px-6 py-4">Pengusul</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Tanggal</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($usulan as $row): 
                        $statusClass = match($row['status_usulan']) {
                            'Disetujui' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            'Revisi' => 'bg-amber-100 text-amber-700 border-amber-200',
                            'Ditolak' => 'bg-rose-100 text-rose-700 border-rose-200',
                            default => 'bg-slate-100 text-slate-600'
                        };
                    ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800"><?php echo htmlspecialchars($row['nama_kegiatan']); ?></div>
                            <?php if ($row['kode_mak']): ?>
                                <div class="text-xs text-slate-500 mt-1">MAK: <?php echo htmlspecialchars($row['kode_mak']); ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-slate-700"><?php echo htmlspecialchars($row['username']); ?></td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex px-2.5 py-1 rounded text-[10px] font-bold border <?php echo $statusClass; ?> uppercase">
                                <?php echo $row['status_usulan']; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-xs text-slate-500">
                            <?php echo date('d M Y', strtotime($row['updated_at'])); ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="/usulan/detail?id=<?php echo $row['id']; ?>" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-lg hover:bg-slate-200 transition-all">
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