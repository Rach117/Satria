<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Antrian Persetujuan</h1>
            <p class="text-slate-500 mt-1">Usulan kegiatan yang menunggu keputusan Anda.</p>
        </div>
        <div class="px-4 py-2 bg-white border border-slate-200 rounded-lg shadow-sm text-sm font-bold text-slate-600">
            Menunggu: <span class="text-indigo-600 ml-1"><?php echo count($usulan); ?></span>
        </div>
    </div>

    <?php if (empty($usulan)): ?>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-16 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-50 mb-6">
                <span class="material-icons text-slate-300 text-4xl">assignment_turned_in</span>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Tugas Selesai</h3>
            <p class="text-slate-500">Tidak ada usulan yang perlu diproses saat ini.</p>
        </div>
    <?php else: ?>
        <div class="grid gap-4">
            <?php foreach ($usulan as $row): ?>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 hover:shadow-md transition-all flex flex-col md:flex-row items-center justify-between gap-6 group">
                <div class="flex items-start gap-4 flex-1">
                    <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0 font-bold border border-indigo-100">
                        <?php echo strtoupper(substr($row['username'], 0, 2)); ?>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase rounded tracking-wider">
                                #<?php echo $row['id']; ?>
                            </span>
                            <span class="text-xs text-slate-500">Diajukan oleh <span class="font-bold text-slate-700"><?php echo htmlspecialchars($row['username']); ?></span></span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-700 transition-colors mb-1">
                            <?php echo htmlspecialchars($row['nama_kegiatan']); ?>
                        </h3>
                        <div class="text-sm text-emerald-600 font-bold">
                            Rp <?php echo number_format($row['nominal_pencairan'], 0, ',', '.'); ?>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <a href="/approval/proses?id=<?php echo $row['id']; ?>" class="flex-1 md:flex-none inline-flex justify-center items-center px-6 py-3 bg-indigo-600 text-white text-sm font-bold rounded-lg shadow-lg shadow-indigo-600/20 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all">
                        <span class="material-icons text-sm mr-2">gavel</span>
                        Proses Keputusan
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php include __DIR__.'/../partials/footer.php'; ?>