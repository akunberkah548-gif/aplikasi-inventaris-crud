<?php
ob_start(); 
session_start(); 

require_once 'config/database.php';

if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit;
}

$nama_user = $_SESSION['login_user'];

try {
    $stmtTotal = $pdo->query("SELECT COUNT(*) FROM barang");
    $totalBarang = $stmtTotal->fetchColumn();

    $stmtStok = $pdo->query("SELECT SUM(stok) FROM barang");
    $totalStok = $stmtStok->fetchColumn() ?? 0;

    // Get low stock items
    $stmtLowStock = $pdo->query("SELECT id, nama_barang, stok FROM barang WHERE stok < 10 ORDER BY stok ASC");
    $lowStockItems = $stmtLowStock->fetchAll();

    // Get recent transactions
    $query = "
        SELECT pb.id, pb.jumlah_keluar, pb.created_at, b.nama_barang 
        FROM pengeluaran_barang pb
        JOIN barang b ON pb.barang_id = b.id
        ORDER BY pb.created_at DESC
        LIMIT 5
    ";
    $stmtRecent = $pdo->query($query);

    $stmtChart = $pdo->query("SELECT nama_barang, kategori, stok FROM barang ORDER BY stok DESC LIMIT 10");
    $data_chart = $stmtChart->fetchAll();

    $kategori_counts = [];
    foreach ($data_chart as $row) {
        $kat = $row['kategori'];
        $kategori_counts[$kat] = ($kategori_counts[$kat] ?? 0) + 1;
    }

} catch (PDOException $e) {
    die("Gagal mengambil data dari database: " . $e->getMessage());
}

include 'includes/header.php'; 
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="max-w-7xl mx-auto space-y-6 px-4 lg:px-8">
    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Selamat Datang, <?= htmlspecialchars($nama_user) ?>! 👋</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola sistem inventaris, pantau stok barang, dan akses ruang komunikasi internal.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <a href="riwayat.php" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition shadow-sm flex items-center gap-2">
                📋 Riwayat Transaksi
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Total Ragam Barang</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?= $totalBarang ?> <span class="text-sm font-normal text-gray-400">Model</span></h3>
            </div>
            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-xl text-indigo-600">📦</div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Total Jumlah Stok Global</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?= number_format($totalStok, 0, ',', '.') ?> <span class="text-sm font-normal text-gray-400">Unit</span></h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-xl text-emerald-600">📊</div>
        </div>
    </div>

    <!-- Low Stock Alerts -->
    <?php if (count($lowStockItems) > 0): ?>
    <div class="bg-red-50 border-l-4 border-red-500 p-5 rounded-lg">
        <div class="flex items-start gap-3">
            <span class="text-2xl">⚠️</span>
            <div>
                <h3 class="font-bold text-red-900 mb-2">Peringatan: Stok Rendah</h3>
                <p class="text-sm text-red-700 mb-3">Beberapa item memiliki stok kurang dari 10 unit:</p>
                <ul class="space-y-1 text-sm text-red-700">
                    <?php foreach ($lowStockItems as $item): ?>
                        <li>• <strong><?= htmlspecialchars($item['nama_barang']) ?></strong> - Sisa: <span class="font-bold"><?= $item['stok'] ?></span> unit</li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm lg:col-span-2">
            <h3 class="font-bold text-gray-800 mb-4 text-sm uppercase tracking-wide text-gray-500">Grafik Stok Per Barang (Top 10)</h3>
            <div class="h-64">
                <canvas id="stokBarChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
            <h3 class="font-bold text-gray-800 mb-4 text-sm uppercase tracking-wide text-gray-500">Penyebaran Kategori</h3>
            <div class="h-64 flex justify-center">
                <canvas id="kategoriPieChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-800 text-lg">📝 Transaksi Terbaru</h3>
            <a href="riwayat.php" class="text-sm text-indigo-600 hover:text-indigo-700 font-semibold">Lihat Semua →</a>
        </div>
        
        <?php if (count($recentTransactions) > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Barang</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Jumlah</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($recentTransactions as $trans): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-800"><?= htmlspecialchars($trans['nama_barang']) ?></td>
                                <td class="px-4 py-3 text-center text-red-600 font-bold">-<?= $trans['jumlah_keluar'] ?> unit</td>
                                <td class="px-4 py-3 text-gray-500 text-xs"><?= date('d/m/Y H:i', strtotime($trans['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-400 py-8">📭 Belum ada transaksi pengeluaran barang</p>
        <?php endif; ?>
    </div>
</div>

<script>
    const dataBarang = <?= json_encode($data_chart) ?>;
    const namaBarang = dataBarang.map(item => item.nama_barang);
    const jumlahStok = dataBarang.map(item => item.stok);

    const ctxBar = document.getElementById('stokBarChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: namaBarang,
            datasets: [{
                label: 'Jumlah Stok (Unit)',
                data: jumlahStok,
                backgroundColor: 'rgba(79, 70, 229, 0.8)', 
                borderColor: 'rgba(79, 70, 229, 1)',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } }
        }
    });

    const kategoriData = <?= json_encode($kategori_counts) ?>;
    const labelKategori = Object.keys(kategoriData);
    const nilaiKategori = Object.values(kategoriData);

    const ctxPie = document.getElementById('kategoriPieChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: labelKategori,
            datasets: [{
                data: nilaiKategori,
                backgroundColor: ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#6b7280']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
</script>

<?php include 'includes/footer.php'; ?>