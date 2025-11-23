<?php include __DIR__.'/../partials/sidebar.php'; ?>
<div class="p-8 max-w-7xl mx-auto">
    <h1 class="text-3xl font-extrabold text-slate-900 mb-6">Verifikasi LPJ & Finalisasi</h1>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs">
                <tr>
                    <th class="px-6 py-3">Kegiatan</th>
                    <th class="px-6 py-3">Status LPJ</th>
                    <th class="px-6 py-3">Deadline</th>
                    <th class="px-6 py-3 text-right">Dokumen & Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($usulan as $u): 
                     $deadline = new DateTime($u['tgl_batas_lpj']);
                     $now = new DateTime();
                     $diff = $now->diff($deadline);
                     $isLate = $now > $deadline;
                ?>
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-800"><?php echo htmlspecialchars($u['nama_kegiatan']); ?></div>
                        <div class="text-xs text-slate-500 mt-1"><?php echo htmlspecialchars($u['username']); ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <?php if($u['status_terkini'] === 'LPJ'): ?>
                            <span class="text-blue-600 font-bold flex items-center"><span class="material-icons text-sm mr-1">upload_file</span> Proses Upload</span>
                        <?php else: ?>
                            <span class="text-emerald-600 font-bold flex items-center"><span class="material-icons text-sm mr-1">check_circle</span> Dana Cair</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-mono text-xs <?php echo $isLate ? 'text-rose-600 font-bold' : 'text-slate-600'; ?>">
                            <?php echo date('d M Y', strtotime($u['tgl_batas_lpj'])); ?>
                        </div>
                        <?php if(!$isLate): ?>
                            <span class="text-[10px] text-emerald-600 font-bold"><?php echo $diff->days; ?> hari lagi</span>
                        <?php else: ?>
                            <span class="text-[10px] text-rose-600 font-bold">Terlambat <?php echo $diff->days; ?> hari</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end items-center gap-2">
                            <a href="/pdf/berita_acara?id=<?php echo $u['id']; ?>" target="_blank" class="px-3 py-1 border border-slate-300 text-slate-600 rounded hover:bg-slate-50 text-xs font-bold flex items-center" title="Cetak Berita Acara Serah Terima">
                                <span class="material-icons text-[12px] mr-1">print</span> BA Serah Terima
                            </a>

                            <form method="POST" action="/keuangan/verifikasi_lpj/<?php echo $u['id']; ?>" onsubmit="return confirm('Pastikan dokumen fisik dan softcopy sudah sesuai. Selesaikan kegiatan?');">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-xs font-bold flex items-center shadow-sm">
                                    <span class="material-icons text-sm mr-1">verified</span> Finalisasi Selesai
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__.'/../partials/footer.php'; ?>