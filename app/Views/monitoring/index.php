<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-[90rem] mx-auto"> <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Monitoring Progres Kegiatan</h1>
            <p class="text-slate-500 mt-1">Pelacakan real-time status usulan berdasarkan tahapan birokrasi (Sesuai SOP).</p>
        </div>
        <div class="bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm text-sm">
            <span class="font-bold text-slate-700">Total Usulan:</span> 
            <span class="text-blue-600 font-mono ml-1"><?php echo isset($total) ? $total : count($usulan); ?></span>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <div class="md:col-span-5 relative">
                <span class="material-icons absolute left-3 top-2.5 text-slate-400 text-sm">search</span>
                <input type="text" name="q" placeholder="Cari Nama Kegiatan / Pengusul..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>" class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none text-sm transition-all">
            </div>
            <div class="md:col-span-3">
                <select name="status" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none text-sm text-slate-600">
                    <option value="">- Semua Status -</option>
                    <option value="Verifikasi">Verifikasi</option>
                    <option value="Disetujui">Disetujui</option>
                    <option value="Pencairan">Pencairan</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Ditolak" <?php echo ($_GET['status'] ?? '') === 'Ditolak' ? 'selected' : ''; ?>>Ditolak</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <input type="date" name="date" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none text-sm text-slate-600">
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="w-full py-2 bg-slate-800 text-white font-bold rounded-lg hover:bg-slate-900 transition-all text-sm shadow-md">Filter</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <?php if (empty($usulan)): ?>
            <div class="p-12 text-center">
                <span class="material-icons text-slate-300 text-5xl mb-2">toc</span>
                <h3 class="text-lg font-bold text-slate-700">Tidak ada data</h3>
                <p class="text-slate-500 text-sm">Belum ada usulan yang masuk kriteria pencarian.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="bg-slate-50 text-slate-600 uppercase font-bold text-[10px] border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-4 w-16 text-center">ID</th>
                            <th class="px-4 py-4 min-w-[250px]">Detail Kegiatan</th>
                            
                            <th class="px-2 py-4 text-center w-24 bg-slate-100/50 border-l border-slate-200">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="material-icons text-slate-400 text-lg">fact_check</span>
                                    <span>Validasi<br>Verifikator</span>
                                </div>
                            </th>
                            <th class="px-2 py-4 text-center w-24 border-l border-slate-200">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="material-icons text-slate-400 text-lg">supervisor_account</span>
                                    <span>Tahap 1<br>(ACC WD2)</span>
                                </div>
                            </th>
                            <th class="px-2 py-4 text-center w-24 border-l border-slate-200">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="material-icons text-slate-400 text-lg">approval</span>
                                    <span>Tahap 2<br>(ACC PPK)</span>
                                </div>
                            </th>
                            <th class="px-2 py-4 text-center w-24 border-l border-slate-200">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="material-icons text-slate-400 text-lg">payments</span>
                                    <span>Tahap 3<br>(Pencairan)</span>
                                </div>
                            </th>
                            <th class="px-2 py-4 text-center w-24 border-l border-slate-200">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="material-icons text-slate-400 text-lg">inventory</span>
                                    <span>Tahap 4<br>(LPJ)</span>
                                </div>
                            </th>
                            <th class="px-4 py-4 text-center w-28 bg-slate-100/50 border-l border-slate-200">Status Akhir</th>
                            <th class="px-4 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($usulan as $row): 
                            $s = $row['status_terkini'];
                            
                            // Helper Function untuk Icon Checklist Logic
                            $check = function($isActive, $isDone) {
                                if ($isDone) return '<span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-600"><span class="material-icons text-sm font-bold">check</span></span>';
                                if ($isActive) return '<span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 animate-pulse"><span class="material-icons text-sm">hourglass_empty</span></span>';
                                return '<span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 text-slate-300"><span class="material-icons text-sm">remove</span></span>';
                            };

                            // Logic Mapping Status ke Tahapan (State Machine)
                            // Urutan: Draft -> Verifikasi -> Menunggu WD2 -> Menunggu PPK -> Disetujui -> Pencairan -> LPJ -> Selesai
                            
                            // 1. Validasi Verifikator
                            $s1_done = !in_array($s, ['Draft', 'Revisi', 'Verifikasi', 'Ditolak']);
                            $s1_active = $s === 'Verifikasi';

                            // 2. ACC WD2
                            $s2_done = !in_array($s, ['Draft', 'Revisi', 'Verifikasi', 'Menunggu WD2', 'Ditolak']);
                            $s2_active = $s === 'Menunggu WD2';

                            // 3. ACC PPK
                            $s3_done = !in_array($s, ['Draft', 'Revisi', 'Verifikasi', 'Menunggu WD2', 'Menunggu PPK', 'Ditolak']);
                            $s3_active = $s === 'Menunggu PPK';

                            // 4. Pencairan (Uang Muka)
                            $s4_done = in_array($s, ['Pencairan', 'LPJ', 'Selesai']); // Disetujui = Belum cair
                            $s4_active = $s === 'Disetujui' || $s === 'Pencairan';

                            // 5. LPJ
                            $s5_done = $s === 'Selesai';
                            $s5_active = $s === 'LPJ' || $s === 'Pencairan'; // Pencairan selesai -> Masuk timer LPJ

                            // Logic Overdue
                            $isLate = false;
                            if (!empty($row['tgl_batas_lpj']) && $s !== 'Selesai') {
                                if (new DateTime() > new DateTime($row['tgl_batas_lpj'])) $isLate = true;
                            }
                        ?>
                        <tr class="hover:bg-slate-50 transition-colors group <?php echo $isLate ? 'bg-rose-50/40' : ''; ?>">
                            <td class="px-4 py-4 text-center font-mono text-xs text-slate-400">#<?php echo $row['id']; ?></td>
                            <td class="px-4 py-4">
                                <div class="font-bold text-slate-800 group-hover:text-blue-700 transition-colors mb-1">
                                    <?php echo htmlspecialchars($row['nama_kegiatan']); ?>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-slate-500">
                                    <span class="flex items-center text-slate-400"><span class="material-icons text-[10px] mr-1">person</span> <?php echo htmlspecialchars($row['username']); ?></span>
                                    <?php if($row['nominal_pencairan'] > 0): ?>
                                        <span class="px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 font-mono font-bold">Rp <?php echo number_format($row['nominal_pencairan'], 0, ',', '.'); ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <td class="px-2 py-4 text-center border-l border-slate-100 bg-slate-50/30">
                                <?php echo $check($s1_active, $s1_done); ?>
                            </td>
                            <td class="px-2 py-4 text-center border-l border-slate-100">
                                <?php echo $check($s2_active, $s2_done); ?>
                            </td>
                            <td class="px-2 py-4 text-center border-l border-slate-100">
                                <?php echo $check($s3_active, $s3_done); ?>
                            </td>
                            <td class="px-2 py-4 text-center border-l border-slate-100">
                                <?php echo $check($s4_active, $s4_done); ?>
                            </td>
                            <td class="px-2 py-4 text-center border-l border-slate-100">
                                <?php echo $check($s5_active, $s5_done); ?>
                            </td>

                            <td class="px-4 py-4 text-center border-l border-slate-100 bg-slate-50/30">
                                <?php if($s === 'Ditolak'): ?>
                                    <span class="inline-flex px-2 py-1 rounded text-[10px] font-bold bg-rose-100 text-rose-700 border border-rose-200">DITOLAK</span>
                                <?php elseif($s === 'Selesai'): ?>
                                    <span class="inline-flex px-2 py-1 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">SELESAI</span>
                                <?php elseif($isLate): ?>
                                    <span class="inline-flex px-2 py-1 rounded text-[10px] font-bold bg-rose-600 text-white animate-pulse">TERLAMBAT</span>
                                <?php else: ?>
                                    <span class="inline-flex px-2 py-1 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200"><?php echo $s; ?></span>
                                <?php endif; ?>
                            </td>

                            <td class="px-4 py-4 text-right">
                                <a href="/usulan/detail?id=<?php echo $row['id']; ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-slate-200 text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all" title="Lihat Detail">
                                    <span class="material-icons text-sm">visibility</span>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-between items-center">
                <div class="text-xs text-slate-500">Halaman <?php echo $page; ?></div>
                <div class="flex gap-1">
                    <?php $totalPages = ceil(($total ?? 0) / $perPage); ?>
                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <a href="/monitoring?page=<?php echo $p; ?>" class="w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-all <?php echo ($p == $page) ? 'bg-slate-800 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-200'; ?>">
                            <?php echo $p; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include __DIR__.'/../partials/footer.php'; ?>