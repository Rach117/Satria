<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Daftar Pengajuan Kegiatan</h1>
            <p class="text-slate-500 mt-1">Kegiatan yang memerlukan persetujuan PPK.</p>
        </div>
        <div class="bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm text-sm">
            <span class="font-bold text-slate-700">Menunggu:</span> 
            <span class="text-violet-600 font-mono ml-1"><?php echo count($pengajuan); ?></span>
        </div>
    </div>

    <?php if (empty($pengajuan)): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-16 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-50 mb-6">
                <span class="material-icons text-slate-300 text-4xl">check_circle</span>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Semua Selesai</h3>
            <p class="text-slate-500">Tidak ada pengajuan yang menunggu persetujuan.</p>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($pengajuan as $row): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all">
                <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-0.5 bg-violet-100 text-violet-700 text-[10px] font-bold uppercase rounded">
                                #<?php echo $row['id']; ?>
                            </span>
                            <span class="text-xs text-slate-500">•</span>
                            <span class="text-xs text-slate-500">Diajukan: <?php echo date('d M Y', strtotime($row['created_at'])); ?></span>
                        </div>
                        
                        <h3 class="text-lg font-bold text-slate-900 mb-2">
                            <?php echo htmlspecialchars($row['nama_kegiatan']); ?>
                        </h3>
                        
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-slate-400">Pengusul:</span>
                                <span class="font-bold text-slate-700 ml-2"><?php echo htmlspecialchars($row['username']); ?></span>
                            </div>
                            <div>
                                <span class="text-slate-400">PJ:</span>
                                <span class="font-bold text-slate-700 ml-2"><?php echo htmlspecialchars($row['penanggung_jawab']); ?></span>
                            </div>
                            <div>
                                <span class="text-slate-400">Anggaran:</span>
                                <span class="font-bold text-emerald-600 ml-2">Rp <?php echo number_format($row['nominal_rab'], 0, ',', '.'); ?></span>
                            </div>
                            <div>
                                <span class="text-slate-400">Periode:</span>
                                <span class="font-bold text-slate-700 ml-2">
                                    <?php echo date('d M', strtotime($row['waktu_pelaksanaan_mulai'])); ?> - 
                                    <?php echo date('d M Y', strtotime($row['waktu_pelaksanaan_selesai'])); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <a href="/usulan/detail?id=<?php echo $row['usulan_id']; ?>" target="_blank" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-lg hover:bg-slate-200 transition-all flex items-center">
                            <span class="material-icons text-sm mr-2">description</span> Lihat RAB
                        </a>
                        
                        <a href="/approval/proses?id=<?php echo $row['id']; ?>" class="px-6 py-2 bg-violet-600 text-white text-xs font-bold rounded-lg shadow-md hover:bg-violet-700 hover:-translate-y-0.5 transition-all flex items-center">
                            <span class="material-icons text-sm mr-2">verified_user</span> Proses
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__.'/../partials/footer.php'; ?>