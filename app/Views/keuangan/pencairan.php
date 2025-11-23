<?php include __DIR__.'/../../Views/partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Pencairan Dana</h1>
            <p class="text-slate-500 mt-1">Daftar kegiatan siap bayar (Approved by PPK).</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">Kegiatan</th>
                    <th class="px-6 py-4">Pengusul</th>
                    <th class="px-6 py-4">Nominal</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if(empty($usulan)): ?>
                    <tr><td colspan="4" class="p-8 text-center text-slate-400">Tidak ada antrian pencairan.</td></tr>
                <?php else: ?>
                    <?php foreach ($usulan as $row): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-800"><?php echo htmlspecialchars($row['nama_kegiatan']); ?></td>
                        <td class="px-6 py-4 text-slate-600"><?php echo htmlspecialchars($row['username']); ?></td>
                        <td class="px-6 py-4 font-mono font-bold text-emerald-600">Rp <?php echo number_format($row['nominal_pencairan'], 0, ',', '.'); ?></td>
                        <td class="px-6 py-4 text-right">
                            <form method="post" action="/pencairan/proses?id=<?php echo $row['id']; ?>" onsubmit="return confirm('Konfirmasi pencairan dana ini? Timer LPJ akan dimulai.');">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-amber-500 text-white text-xs font-bold rounded-lg shadow hover:bg-amber-600 transition-all hover:-translate-y-0.5">
                                    <span class="material-icons text-sm mr-2">payments</span> Cairkan
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__.'/../../Views/partials/footer.php'; ?>