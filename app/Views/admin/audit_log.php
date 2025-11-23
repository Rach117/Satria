<?php include __DIR__.'/../partials/sidebar.php'; ?>

<div class="p-8 max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Sistem Audit Log</h1>
            <p class="text-slate-500 mt-1">Rekam jejak aktivitas dan keamanan sistem.</p>
        </div>
        <a href="/audit-log/export" class="bg-slate-800 text-white hover:bg-slate-900 px-4 py-2 rounded-lg text-sm font-bold shadow-md transition-all flex items-center">
            <span class="material-icons text-sm mr-2">download</span> Export CSV
        </a>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-6">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <input type="text" name="user" placeholder="Cari User..." value="<?php echo htmlspecialchars($_GET['user'] ?? ''); ?>" class="flex-1 px-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none">
            <input type="text" name="action" placeholder="Cari Aksi..." value="<?php echo htmlspecialchars($_GET['action'] ?? ''); ?>" class="flex-1 px-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none">
            <input type="date" name="date" value="<?php echo htmlspecialchars($_GET['date'] ?? ''); ?>" class="px-4 py-2 border border-slate-300 rounded-lg text-sm text-slate-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none">
            <button type="submit" class="bg-blue-600 text-white font-bold px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-xs border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3">Timestamp</th>
                    <th class="px-6 py-3">User</th>
                    <th class="px-6 py-3">Aktivitas</th>
                    <th class="px-6 py-3 text-right">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-mono text-xs">
                <?php foreach ($logs as $l): ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-3 text-slate-500 whitespace-nowrap">
                        <?php echo $l['timestamp']; ?>
                    </td>
                    <td class="px-6 py-3 font-bold text-slate-700">
                        <?php echo htmlspecialchars($l['username']); ?>
                    </td>
                    <td class="px-6 py-3">
                        <?php 
                            $aksi = htmlspecialchars($l['aksi']);
                            $cls = 'text-slate-600';
                            if(str_contains($aksi, 'Login')) $cls = 'text-blue-600 font-bold';
                            if(str_contains($aksi, 'Gagal')) $cls = 'text-rose-600 font-bold';
                        ?>
                        <span class="<?php echo $cls; ?>"><?php echo $aksi; ?></span>
                    </td>
                    <td class="px-6 py-3 text-right text-slate-400">
                        <?php echo htmlspecialchars($l['ip_address']); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__.'/../partials/footer.php'; ?>