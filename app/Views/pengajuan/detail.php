<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-5xl mx-auto">
    <div class="mb-8">
        <a href="/pengajuan/list" class="text-slate-500 hover:text-blue-600 text-sm flex items-center font-bold transition-colors mb-4">
            <span class="material-icons text-sm mr-1">arrow_back</span> Kembali ke Daftar
        </a>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Detail Pengajuan Kegiatan</h1>
        <p class="text-slate-500 mt-1">Informasi lengkap pengajuan dan status pencairan dana.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Kolom Kiri: Detail Kegiatan -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
                <div class="border-b border-slate-100 pb-6 mb-6">
                    <h2 class="text-2xl font-extrabold text-slate-900 mb-2">
                        <?php echo htmlspecialchars($pengajuan['nama_kegiatan']); ?>
                    </h2>
                    <div class="flex items-center gap-4 text-sm text-slate-500">
                        <span class="flex items-center">
                            <span class="material-icons text-xs mr-1">person</span>
                            <?php echo htmlspecialchars($pengajuan['username']); ?>
                        </span>
                        <span class="flex items-center">
                            <span class="material-icons text-xs mr-1">calendar_today</span>
                            Diajukan: <?php echo date('d M Y', strtotime($pengajuan['created_at'])); ?>
                        </span>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <label class="text-xs font-bold text-slate-400 uppercase mb-1 block">Penanggung Jawab</label>
                            <div class="font-bold text-slate-800"><?php echo htmlspecialchars($pengajuan['penanggung_jawab']); ?></div>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <label class="text-xs font-bold text-slate-400 uppercase mb-1 block">Pelaksana</label>
                            <div class="font-bold text-slate-800"><?php echo htmlspecialchars($pengajuan['pelaksana']); ?></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                            <label class="text-xs font-bold text-blue-600 uppercase mb-1 block">Waktu Mulai</label>
                            <div class="font-bold text-slate-800"><?php echo date('d M Y', strtotime($pengajuan['waktu_pelaksanaan_mulai'])); ?></div>
                        </div>
                        <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                            <label class="text-xs font-bold text-blue-600 uppercase mb-1 block">Waktu Selesai</label>
                            <div class="font-bold text-slate-800"><?php echo date('d M Y', strtotime($pengajuan['waktu_pelaksanaan_selesai'])); ?></div>
                        </div>
                    </div>

                    <?php if($pengajuan['surat_pengantar_path']): ?>
                    <div class="p-4 bg-amber-50 rounded-xl border border-amber-100">
                        <label class="text-xs font-bold text-amber-700 uppercase mb-2 block">Surat Pengantar</label>
                        <a href="/uploads/<?php echo $pengajuan['surat_pengantar_path']; ?>" target="_blank" class="inline-flex items-center text-sm font-bold text-amber-600 hover:text-amber-700">
                            <span class="material-icons text-sm mr-2">description</span>
                            Lihat Dokumen
                        </a>
                    </div>
                    <?php endif; ?>

                    <?php if($pengajuan['rekomendasi_ppk']): ?>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <label class="text-xs font-bold text-slate-600 uppercase mb-2 block">Rekomendasi PPK</label>
                        <p class="text-sm text-slate-700 italic">"<?php echo htmlspecialchars($pengajuan['rekomendasi_ppk']); ?>"</p>
                    </div>
                    <?php endif; ?>

                    <?php if($pengajuan['rekomendasi_wd2']): ?>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <label class="text-xs font-bold text-slate-600 uppercase mb-2 block">Rekomendasi WD2</label>
                        <p class="text-sm text-slate-700 italic">"<?php echo htmlspecialchars($pengajuan['rekomendasi_wd2']); ?>"</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Riwayat Pencairan (jika ada) -->
            <?php if(!empty($pencairan)): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center">
                    <span class="material-icons text-emerald-600 mr-2">payments</span>
                    Riwayat Pencairan Dana
                </h3>
                <div class="space-y-4">
                    <?php foreach($pencairan as $p): ?>
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <div>
                            <div class="font-bold text-slate-800"><?php echo htmlspecialchars($p['nama_kategori']); ?></div>
                            <div class="text-xs text-slate-500 mt-1">
                                <?php echo date('d M Y', strtotime($p['tanggal_pencairan'])); ?>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-emerald-600">Rp <?php echo number_format($p['nominal_pencairan'], 0, ',', '.'); ?></div>
                            <?php if($p['bukti_transfer_path']): ?>
                            <a href="/uploads/<?php echo $p['bukti_transfer_path']; ?>" target="_blank" class="text-xs text-blue-600 hover:underline">Bukti Transfer</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Kolom Kanan: Status & Ringkasan -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl p-6 text-white shadow-xl">
                <label class="text-xs font-bold text-blue-200 uppercase tracking-wider mb-2 block">Total Anggaran</label>
                <div class="text-3xl font-extrabold mb-1">
                    Rp <?php echo number_format($pengajuan['nominal_rab'], 0, ',', '.'); ?>
                </div>
                <div class="text-xs text-blue-200">Anggaran yang disetujui</div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Status Pengajuan</h3>
                <?php
                    $status = $pengajuan['status_pengajuan'];
                    $statusClass = match($status) {
                        'Menunggu PPK' => 'bg-amber-100 text-amber-700 border-amber-200',
                        'Menunggu WD2' => 'bg-blue-100 text-blue-700 border-blue-200',
                        'Disetujui' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                        'Ditolak' => 'bg-rose-100 text-rose-700 border-rose-200',
                        default => 'bg-slate-100 text-slate-600 border-slate-200'
                    };
                ?>
                <div class="inline-flex px-4 py-2 rounded-lg border <?php echo $statusClass; ?> font-bold text-sm uppercase tracking-wide">
                    <?php echo $status; ?>
                </div>
            </div>

            <?php if($pengajuan['status_pengajuan'] === 'Disetujui'): ?>
            <div class="bg-emerald-50 rounded-2xl border border-emerald-200 p-6">
                <div class="flex items-center mb-3">
                    <span class="material-icons text-emerald-600 mr-2">check_circle</span>
                    <h4 class="font-bold text-emerald-800">Disetujui</h4>
                </div>
                <p class="text-sm text-emerald-700 mb-4">Pengajuan Anda telah disetujui. Menunggu proses pencairan dana oleh Bendahara.</p>
            </div>
            <?php endif; ?>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Aksi Cepat</h3>
                <div class="space-y-3">
                    <a href="/usulan/detail?id=<?php echo $pengajuan['usulan_id']; ?>" class="block w-full px-4 py-3 bg-slate-100 text-slate-700 text-sm font-bold rounded-lg hover:bg-slate-200 transition-all text-center">
                        <span class="material-icons text-sm mr-2 align-middle">description</span>
                        Lihat KAK & RAB
                    </a>
                    <a href="/monitoring" class="block w-full px-4 py-3 bg-blue-50 text-blue-700 text-sm font-bold rounded-lg hover:bg-blue-100 transition-all text-center border border-blue-200">
                        <span class="material-icons text-sm mr-2 align-middle">history</span>
                        Monitoring Status
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__.'/../partials/footer.php'; ?>