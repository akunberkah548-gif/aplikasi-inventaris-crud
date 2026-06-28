<div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
    <?php if (!empty($success_msg)): ?>
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-6 rounded-lg text-sm shadow-sm flex items-center justify-between">
            <span><?= htmlspecialchars($success_msg) ?></span>
            <button onclick="this.parentElement.style.display='none'" class="text-emerald-700 hover:text-emerald-900 font-bold">✕</button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_msg)): ?>
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg text-sm shadow-sm flex items-center justify-between">
            <span><?= htmlspecialchars($error_msg) ?></span>
            <button onclick="this.parentElement.style.display='none'" class="text-red-700 hover:text-red-900 font-bold">✕</button>
        </div>
    <?php endif; ?>

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <h2 class="font-bold text-gray-800 text-lg">Daftar Inventaris Barang</h2>
            <p class="text-gray-400 text-xs mt-0.5">Cari dan lihat daftar semua barang di inventaris.</p>
        </div>
        
        <form method="GET" action="invetaris.php" class="flex items-center gap-2 w-full lg:w-auto">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama atau kategori..." class="px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 flex-1 lg:flex-none lg:w-72">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">Cari</button>
            <?php if (!empty($search)): ?>
                <a href="invetaris.php" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium transition">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
            <thead class="bg-gray-50 text-gray-700 font-semibold uppercase tracking-wider text-xs">
                <tr>
                    <th class="px-6 py-4">Nama Barang</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4 text-center">Stok</th>
                    <th class="px-6 py-4">Harga</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white text-gray-600">
                <?php if (count($items) > 0): ?>
                    <?php foreach ($items as $item): ?>
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="px-6 py-4 font-semibold text-gray-800"><?= htmlspecialchars($item['nama_barang']) ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700">
                                    <?= htmlspecialchars($item['kategori']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center <?= $item['stok'] < 10 ? 'text-red-500 font-bold' : '' ?>">
                                <?= number_format($item['stok'], 0, ',', '.') ?> unit
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-700">Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <a href="edit.php?id=<?= $item['id'] ?>" class="text-xs bg-amber-50 hover:bg-amber-100 text-amber-700 px-3 py-1.5 rounded-lg font-medium transition inline-block">Ubah</a>
                                <a href="hapus.php?id=<?= $item['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="text-xs bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 rounded-lg font-medium transition inline-block">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">Data tidak ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>