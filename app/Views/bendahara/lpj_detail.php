<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    <div class="mb-8">
        <a href="/bendahara/lpj-list" class="text-slate-500 hover:text-blue-600 text-sm flex items-center font-bold transition-colors mb-4">
            <span class="material-icons text-sm mr-1">arrow_back</span> Kembali ke Daftar LPJ
        </a>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Verifikasi LPJ</h1>
        <p class="text-slate-500 mt-1">Periksa kelengkapan dokumen pertanggungjawaban per kategori.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 mb-8">
        <h2 class="text-2xl font-bold text-slate-900 mb-2"><?php echo htmlspecialchars($pengajuan['nama_kegiatan']); ?></h2>
        <div class="flex items-center gap-4 text-sm text-slate-500">
            <span class="flex items-center">
                <span class="material-icons text-xs mr-1">person</span>
                <?php echo htmlspecialchars($pengajuan['username']); ?>
            </span>
            <span class="flex items-center">
                <span class="material-icons text-xs mr-1">account_balance_wallet</span>
                Total RAB: Rp <?php echo number_format($pengajuan['nominal_rab'], 0, ',', '.'); ?>
            </span>
        </div>
    </div>

    <div class="space-y-6">
        <?php foreach($rab_summary as $rab): 
            $lpj_kategori = array_filter($lpj, function($l) use ($rab) {
                return $l['kategori_id'] == $rab['kategori_id'];
            });
            $lpj_kategori = reset($lpj_kategori);
        ?>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-slate-800"><?php echo htmlspecialchars($rab['nama_kategori']); ?></h3>
                    <p class="text-xs text-slate-500 mt-1">Total RAB: Rp <?php echo number_format($rab['total_rab'], 0, ',', '.'); ?></p>
                </div>
                <?php if($lpj_kategori): ?>
                    <?php
                    $statusClass = match($lpj_kategori['status_lpj']) {
                        'Disetujui' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                        'Direvisi' => 'bg-amber-100 text-amber-700 border-amber-200',
                        default => 'bg-blue-100 text-blue-700 border-blue-200'
                    };
                    ?>
                    <span class="inline-flex px-3 py-1 rounded text-xs font-bold border <?php echo $statusClass; ?> uppercase">
                        <?php echo $lpj_kategori['status_lpj']; ?>
                    </span>
                <?php else: ?>
                    <span class="inline-flex px-3 py-1 rounded text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200 uppercase">
                        Belum Upload
                    </span>
                <?php endif; ?>
            </div>

            <?php if($lpj_kategori): ?>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <label class="text-xs font-bold text-slate-400 uppercase mb-1 block">Nominal LPJ</label>
                        <div class="font-bold text-slate-800">Rp <?php echo number_format($lpj_kategori['nominal_lpj'], 0, ',', '.'); ?></div>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <label class="text-xs font-bold text-slate-400 uppercase mb-1 block">Tanggal Upload</label>
                        <div class="font-bold text-slate-800"><?php echo date('d M Y', strtotime($lpj_kategori['tanggal_upload'])); ?></div>
                    </div>
                </div>

                <?php if($lpj_kategori['bukti_lpj_path']): ?>
                <div class="mb-6">
                    <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">Bukti LPJ</label>
                    <a href="<?php echo $lpj_kategori['bukti_lpj_path']; ?>" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 text-sm font-bold rounded-lg hover:bg-blue-100 transition-colors border border-blue-200">
                        <span class="material-icons text-sm mr-2">description</span> Lihat Dokumen
                    </a>
                </div>
                <?php endif; ?>

                <?php if($lpj_kategori['catatan_bendahara']): ?>
                <div class="p-4 bg-amber-50 rounded-xl border border-amber-100 mb-6">
                    <label class="text-xs font-bold text-amber-700 uppercase mb-2 block">Catatan Bendahara</label>
                    <p class="text-sm text-amber-800"><?php echo htmlspecialchars($lpj_kategori['catatan_bendahara']); ?></p>
                </div>
                <?php endif; ?>

                <?php if($lpj_kategori['status_lpj'] === 'Pending'): ?>
                <form method="POST" action="/bendahara/verifikasi-lpj" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="lpj_id" value="<?php echo $lpj_kategori['id']; ?>">
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Catatan (Opsional)</label>
                        <textarea name="catatan" rows="3" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all text-sm" placeholder="Berikan catatan jika perlu revisi..."></textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" name="status" value="Disetujui" class="flex-1 px-6 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 shadow-lg transition-all">
                            <span class="material-icons text-sm mr-2 align-middle">check_circle</span> Setujui LPJ
                        </button>
                        <button type="submit" name="status" value="Direvisi" class="flex-1 px-6 py-3 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 shadow-lg transition-all">
                            <span class="material-icons text-sm mr-2 align-middle">edit</span> Minta Revisi
                        </button>
                    </div>
                </form>
                <?php endif; ?>
            </div>
            <?php else: ?>
            <div class="p-6 text-center text-slate-400">
                <span class="material-icons text-4xl mb-2 block">hourglass_empty</span>
                Menunggu pengusul mengupload LPJ
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include __DIR__.'/../partials/footer.php'; ?>