<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Daftar Usulan Saya</h1>
            <p class="text-slate-500 mt-1">Kelola draft dan usulan yang telah diajukan.</p>
        </div>
        <a href="/usulan/create" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-bold rounded-lg shadow-lg hover:bg-blue-700 hover:-translate-y-0.5 transition-all">
            <span class="material-icons text-sm mr-2">add_circle</span> Buat Usulan Baru
        </a>
    </div>

    <?php if (isset($_SESSION['toast'])): ?>
        <div class="mb-4 p-4 rounded-lg bg-<?php echo $_SESSION['toast']['type'] == 'success' ? 'emerald' : 'rose'; ?>-100 text-<?php echo $_SESSION['toast']['type'] == 'success' ? 'emerald' : 'rose'; ?>-700 border border-<?php echo $_SESSION['toast']['type'] == 'success' ? 'emerald' : 'rose'; ?>-200 text-sm font-bold animate-fade-in-down">
            <?php echo $_SESSION['toast']['msg']; unset($_SESSION['toast']); ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <?php if (empty($usulan)): ?>
            <div class="p-16 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-50 mb-6">
                    <span class="material-icons text-slate-300 text-4xl">assignment</span>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Usulan</h3>
                <p class="text-slate-500 mb-6">Buat usulan kegiatan pertama Anda untuk memulai.</p>
                <a href="/usulan/create" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition-colors">
                    <span class="material-icons text-sm mr-2">add</span> Mulai Buat Usulan
                </a>
            </div>
        <?php else: ?>
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Nama Kegiatan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Anggaran</th>
                        <th class="px-6 py-4 text-center">Tanggal</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($usulan as $row): 
                        $statusClass = match($row['status_usulan']) {
                            'Draft' => 'bg-slate-100 text-slate-600 border-slate-200',
                            'Diajukan' => 'bg-blue-100 text-blue-700 border-blue-200',
                            'Disetujui' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            'Revisi' => 'bg-amber-100 text-amber-700 border-amber-200',
                            'Ditolak' => 'bg-rose-100 text-rose-700 border-rose-200',
                            default => 'bg-slate-100 text-slate-600'
                        };
                    ?>
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800 group-hover:text-blue-700 transition-colors">
                                <?php echo htmlspecialchars($row['nama_kegiatan']); ?>
                            </div>
                            <div class="text-xs text-slate-400 mt-1">ID: #<?php echo $row['id']; ?></div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex px-2.5 py-1 rounded text-[10px] font-bold border <?php echo $statusClass; ?> uppercase tracking-wide">
                                <?php echo $row['status_usulan']; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-mono font-bold text-slate-700">
                            <?php echo $row['nominal_rab'] > 0 ? 'Rp ' . number_format($row['nominal_rab'], 0, ',', '.') : '-'; ?>
                        </td>
                        <td class="px-6 py-4 text-center text-xs text-slate-500">
                            <?php echo date('d M Y', strtotime($row['created_at'])); ?>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="/usulan/detail?id=<?php echo $row['id']; ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all" title="Detail">
                                    <span class="material-icons text-sm">visibility</span>
                                </a>

                                <?php if (in_array($row['status_usulan'], ['Draft', 'Revisi'])): ?>
                                    <a href="/usulan/edit?id=<?php echo $row['id']; ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-all" title="Edit">
                                        <span class="material-icons text-sm">edit</span>
                                    </a>

                                    <?php if ($row['status_usulan'] === 'Draft'): ?>
                                        <form method="POST" action="/usulan/submit" class="inline-block">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <input type="hidden" name="usulan_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-all" title="Ajukan" onclick="return confirm('Ajukan usulan ini?')">
                                                <span class="material-icons text-sm">send</span>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__.'/../partials/footer.php'; ?>