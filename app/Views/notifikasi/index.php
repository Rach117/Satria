<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Notifikasi</h1>
            <p class="text-slate-500 mt-1">Pemberitahuan terbaru mengenai aktivitas sistem Anda.</p>
        </div>
        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider bg-slate-100 px-3 py-1 rounded-full">
            Total: <?php echo $total; ?> Pesan
        </div>
    </div>

    <?php if (empty($notifikasi)): ?>
        <div class="text-center py-20 bg-white rounded-2xl shadow-sm border border-slate-200">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4">
                <span class="material-icons text-slate-300 text-3xl">notifications_off</span>
            </div>
            <p class="text-slate-500 font-medium">Tidak ada notifikasi baru.</p>
        </div>
    <?php else: ?>
        <div class="space-y-3 relative before:absolute before:inset-0 before:ml-8 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
            <?php foreach ($notifikasi as $n): ?>
                <div class="relative flex items-start group">
                    
                    <div class="absolute left-8 md:left-0 md:relative flex items-center justify-center w-6 h-6 rounded-full -ml-3 md:ml-0 bg-white border-2 <?php echo !$n['is_read'] ? 'border-blue-500' : 'border-slate-200'; ?> z-10 mt-4 mr-6 shadow-sm">
                        <?php if(!$n['is_read']): ?><div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div><?php endif; ?>
                    </div>

                    <div class="flex-1 p-5 rounded-2xl border transition-all duration-200 <?php echo !$n['is_read'] ? 'bg-white border-blue-200 shadow-md shadow-blue-100' : 'bg-slate-50/50 border-slate-200 hover:bg-white'; ?>">
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="text-sm font-bold <?php echo !$n['is_read'] ? 'text-blue-800' : 'text-slate-700'; ?>">
                                <?php echo htmlspecialchars($n['judul']); ?>
                            </h4>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide whitespace-nowrap ml-2">
                                <?php echo date('d M H:i', strtotime($n['created_at'])); ?>
                            </span>
                        </div>
                        
                        <p class="text-sm text-slate-600 leading-relaxed mb-3"><?php echo htmlspecialchars($n['pesan']); ?></p>
                        
                        <div class="flex items-center justify-between mt-2 border-t border-slate-100 pt-3">
                            <?php if ($n['link']): ?>
                                <a href="<?php echo $n['link']; ?>" class="text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline flex items-center">
                                    Lihat Detail <span class="material-icons text-[10px] ml-1">arrow_forward</span>
                                </a>
                            <?php else: ?>
                                <span></span>
                            <?php endif; ?>

                            <?php if (!$n['is_read']): ?>
                                <form method="post" action="/notifikasi/read?id=<?php echo $n['id']; ?>">
                                    <button class="text-[10px] font-bold text-slate-400 hover:text-blue-600 flex items-center transition-colors" title="Tandai sudah dibaca">
                                        <span class="material-icons text-sm mr-1">check</span> Tandai Dibaca
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-8 flex justify-center gap-2">
            <?php $totalPages = ceil($total / $perPage); ?>
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                <a href="/notifikasi?page=<?php echo $p; ?>" class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-colors <?php echo ($p == $page) ? 'bg-blue-600 text-white shadow-lg' : 'bg-white border border-slate-200 text-slate-500 hover:bg-slate-50'; ?>">
                    <?php echo $p; ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>
<?php include __DIR__.'/../partials/footer.php'; ?>