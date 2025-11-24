<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    <div class="mb-8">
        <a href="/monitoring" class="text-slate-500 hover:text-blue-600 text-sm flex items-center font-bold transition-colors mb-4">
            <span class="material-icons text-sm mr-1">arrow_back</span> Kembali ke Monitoring
        </a>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Detail Timeline Kegiatan</h1>
        <p class="text-slate-500 mt-1">Riwayat lengkap perjalanan usulan dari awal hingga selesai.</p>
    </div>

    <!-- Header Info -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 mb-8">
        <div class="flex items-start justify-between mb-6">
            <div class="flex-1">
                <h2 class="text-2xl font-extrabold text-slate-900 mb-2"><?php echo htmlspecialchars($usulan['nama_kegiatan']); ?></h2>
                <div class="flex items-center gap-4 text-sm text-slate-500">
                    <span class="flex items-center">
                        <span class="material-icons text-xs mr-1">person</span>
                        <?php echo htmlspecialchars($usulan['username']); ?>
                    </span>
                    <span class="flex items-center">
                        <span class="material-icons text-xs mr-1">account_balance</span>
                        <?php echo htmlspecialchars($usulan['nama_jurusan'] ?? 'Pusat'); ?>
                    </span>
                </div>
            </div>
            <div class="text-right">
                <?php
                    $statusClass = match($usulan['status_terkini']) {
                        'Selesai' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                        'Ditolak' => 'bg-rose-100 text-rose-700 border-rose-200',
                        default => 'bg-blue-100 text-blue-700 border-blue-200'
                    };
                ?>
                <span class="inline-flex px-4 py-2 rounded-lg text-xs font-bold border <?php echo $statusClass; ?> uppercase">
                    <?php echo $usulan['status_terkini']; ?>
                </span>
                <div class="text-sm text-slate-500 mt-2">
                    Nominal: <span class="font-bold text-emerald-600">Rp <?php echo number_format($usulan['nominal_pencairan'] ?? 0, 0, ',', '.'); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center">
            <span class="material-icons text-blue-600 mr-2">timeline</span>
            Kronologi Perjalanan Dokumen
        </h3>

        <div class="relative">
            <!-- Vertical Line -->
            <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-slate-200"></div>

            <div class="space-y-8">
                <?php if(!empty($timeline)): ?>
                    <?php foreach($timeline as $index => $log): 
                        $isLast = ($index === count($timeline) - 1);
                    ?>
                    <div class="relative pl-20">
                        <!-- Timeline Dot -->
                        <div class="absolute left-6 -translate-x-1/2 w-5 h-5 rounded-full border-4 border-white <?php echo $isLast ? 'bg-blue-600' : 'bg-slate-400'; ?> shadow-md"></div>

                        <div class="bg-slate-50 rounded-xl p-5 border border-slate-200 hover:shadow-md transition-all">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h4 class="font-bold text-slate-800 text-base"><?php echo htmlspecialchars($log['aksi']); ?></h4>
                                    <p class="text-xs text-slate-500 mt-1">
                                        oleh <span class="font-medium"><?php echo htmlspecialchars($log['username']); ?></span>
                                    </p>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs font-mono text-slate-500">
                                        <?php echo date('d M Y', strtotime($log['timestamp'])); ?>
                                    </div>
                                    <div class="text-xs text-slate-400">
                                        <?php echo date('H:i', strtotime($log['timestamp'])); ?> WIB
                                    </div>
                                </div>
                            </div>

                            <?php if($log['status_lama'] && $log['status_baru']): ?>
                            <div class="flex items-center gap-2 mb-3 text-sm">
                                <span class="px-2 py-1 bg-white rounded border border-slate-200 text-slate-600 font-medium">
                                    <?php echo $log['status_lama']; ?>
                                </span>
                                <span class="material-icons text-slate-400 text-sm">arrow_forward</span>
                                <span class="px-2 py-1 bg-blue-50 rounded border border-blue-200 text-blue-700 font-medium">
                                    <?php echo $log['status_baru']; ?>
                                </span>
                            </div>
                            <?php endif; ?>

                            <?php if($log['catatan']): ?>
                            <div class="mt-3 p-3 bg-amber-50 rounded-lg border border-amber-100">
                                <p class="text-xs font-bold text-amber-700 uppercase mb-1">Catatan:</p>
                                <p class="text-sm text-amber-800"><?php echo nl2br(htmlspecialchars($log['catatan'])); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-12 text-slate-400">
                        <span class="material-icons text-5xl mb-3 block">event_note</span>
                        <p>Belum ada riwayat aktivitas.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__.'/../partials/footer.php'; ?>