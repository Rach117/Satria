<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Dashboard Wakil Direktur 2</h1>
            <p class="text-slate-500 mt-1">Pusat persetujuan anggaran dan pengawasan kegiatan akademik.</p>
        </div>
        <div class="bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm text-sm flex items-center">
            <span class="material-icons text-blue-600 mr-2">assignment</span>
            <span class="font-bold text-slate-700 mr-2">Menunggu Approval:</span>
            <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded font-mono font-bold">
                <?php echo count(array_filter($usulan ?? [], function($u) { return $u['status_terkini'] === 'Menunggu WD2'; })); ?>
            </span>
        </div>
    </div>

    <?php 
        $lateItems = [];
        if (!empty($usulan)) {
            foreach($usulan as $u) {
                // Cek jika ada tanggal batas LPJ, status belum selesai, dan tanggal sekarang > batas
                if(!empty($u['tgl_batas_lpj']) && $u['status_terkini'] !== 'Selesai' && new DateTime() > new DateTime($u['tgl_batas_lpj'])) {
                    $lateItems[] = $u;
                }
            }
        }
    ?>

    <?php if(count($lateItems) > 0): ?>
        <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 mb-8 animate-fade-in-up">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <span class="material-icons text-rose-600 text-xl">warning</span>
                </div>
                <div class="ml-3 w-full">
                    <h3 class="text-sm font-bold text-rose-800">Peringatan: Keterlambatan Terdeteksi</h3>
                    <div class="mt-2 text-sm text-rose-700">
                        <p class="mb-2">Terdapat <strong><?php echo count($lateItems); ?> kegiatan</strong> yang belum menyelesaikan LPJ melewati batas waktu.</p>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach($lateItems as $late): ?>
                                <a href="/pdf/surat_teguran?id=<?php echo $late['id']; ?>" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-white border border-rose-300 rounded-lg text-xs font-bold text-rose-700 hover:bg-rose-50 transition-colors shadow-sm">
                                    <span class="material-icons text-[10px] mr-1.5">gavel</span>
                                    Tegur: <?php echo htmlspecialchars(substr($late['nama_kegiatan'], 0, 15)) . '...'; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h3 class="font-bold text-slate-700">Daftar Usulan Masuk</h3>
        </div>

        <?php if (empty($usulan)): ?>
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                    <span class="material-icons text-slate-300 text-3xl">inbox</span>
                </div>
                <h3 class="text-lg font-bold text-slate-700">Tidak ada usulan</h3>
                <p class="text-slate-500 text-sm mt-1">Saat ini belum ada usulan yang memerlukan persetujuan Anda.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4">Detail Kegiatan</th>
                            <th class="px-6 py-4">Pengusul & Unit</th>
                            <th class="px-6 py-4 text-center">Nominal (Rp)</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($usulan as $u): 
                            // Logic Status Color
                            $statusClass = match($u['status_terkini']) {
                                'Menunggu WD2' => 'bg-blue-100 text-blue-700 border-blue-200 animate-pulse',
                                'Menunggu PPK' => 'bg-amber-100 text-amber-700 border-amber-200',
                                'Disetujui' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'Ditolak' => 'bg-rose-100 text-rose-700 border-rose-200',
                                default => 'bg-slate-100 text-slate-600 border-slate-200'
                            };
                        ?>
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800 text-base mb-1 group-hover:text-blue-700 transition-colors">
                                    <?php echo htmlspecialchars($u['nama_kegiatan']); ?>
                                </div>
                                <div class="text-xs text-slate-500 line-clamp-1">
                                    <?php echo htmlspecialchars($u['gambaran_umum']); ?>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold text-xs mr-3">
                                        <?php echo strtoupper(substr($u['username'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-700 text-xs"><?php echo htmlspecialchars($u['username']); ?></div>
                                        <div class="text-[10px] text-slate-400">ID: #<?php echo $u['user_id']; ?></div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center font-mono text-slate-600">
                                <?php echo $u['nominal_pencairan'] ? 'Rp '.number_format($u['nominal_pencairan'], 0, ',', '.') : '-'; ?>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-2.5 py-1 rounded text-[10px] font-bold border <?php echo $statusClass; ?> uppercase tracking-wide">
                                    <?php echo $u['status_terkini']; ?>
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="/usulan/detail?id=<?php echo $u['id']; ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all" title="Lihat Detail">
                                        <span class="material-icons text-sm">visibility</span>
                                    </a>

                                    <?php if($u['status_terkini'] === 'Menunggu WD2'): ?>
                                        <a href="/approval/proses?id=<?php echo $u['id']; ?>" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-md hover:-translate-y-0.5 transition-all text-xs font-bold">
                                            <span class="material-icons text-[14px] mr-1">gavel</span> Putusan
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 text-xs text-slate-400 text-center">
                Menampilkan data terbaru untuk prioritas approval.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__.'/../partials/footer.php'; ?>