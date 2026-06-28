<?php
ob_start();
session_start();

require_once 'config/database.php';

// Proteksi Sesi Login
if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit;
}

$nama_user = $_SESSION['login_user'];

// Pagination
$limit = 20;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Get total records
try {
    $stmtTotal = $pdo->query("SELECT COUNT(*) FROM pengeluaran_barang");
    $total = $stmtTotal->fetchColumn();
    $total_pages = ceil($total / $limit);

    // Fetch records with pagination (MariaDB doesn't support ? in LIMIT)
    $query = "
        SELECT pb.id, pb.jumlah_keluar, pb.keterangan, pb.petugas, pb.created_at, 
               b.id as barang_id, b.nama_barang, b.kategori, b.harga
        FROM pengeluaran_barang pb
        JOIN barang b ON pb.barang_id = b.id
        ORDER BY pb.created_at DESC
        LIMIT " . intval($limit) . " OFFSET " . intval($offset);
    
    $stmt = $pdo->query($query);
    $riwayat = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Gagal mengambil data: " . $e->getMessage());
}

include 'includes/header.php';
?>

<div class="max-w-7xl mx-auto px-4 lg:px-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h2 class="font-bold text-gray-800 text-2xl">📋 Riwayat Pengeluaran Barang</h2>
                <p class="text-gray-500 text-sm mt-1">Lihat semua histori pengeluaran barang dari inventaris.</p>
            </div>
            <a href="pengeluaran.php" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                ➕ Tambah Pengeluaran
            </a>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                <thead class="bg-gray-50 text-gray-700 font-semibold uppercase tracking-wider text-xs">
                    <tr>
                        <th class="px-6 py-4">Barang</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4 text-center">Jumlah Keluar</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4">Petugas</th>
                        <th class="px-6 py-4">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white text-gray-600">
                    <?php if (count($riwayat) > 0): ?>
                        <?php foreach ($riwayat as $row): ?>
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="px-6 py-4 font-semibold text-gray-800"><?= htmlspecialchars($row['nama_barang']) ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700">
                                        <?= htmlspecialchars($row['kategori']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-red-600">-<?= $row['jumlah_keluar'] ?> unit</td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($row['keterangan']) ?></td>
                                <td class="px-6 py-4 text-xs font-medium text-gray-700"><?= htmlspecialchars($row['petugas']) ?></td>
                                <td class="px-6 py-4 text-xs text-gray-500"><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                📭 Belum ada riwayat pengeluaran barang.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($total_pages > 1): ?>
            <div class="flex items-center justify-center gap-2 mt-6">
                <?php if ($page > 1): ?>
                    <a href="riwayat.php?page=1" class="px-3 py-2 border rounded-lg hover:bg-gray-100 text-sm font-medium">« Pertama</a>
                    <a href="riwayat.php?page=<?= $page - 1 ?>" class="px-3 py-2 border rounded-lg hover:bg-gray-100 text-sm font-medium">‹ Sebelumnya</a>
                <?php endif; ?>

                <div class="px-3 py-2 bg-indigo-50 text-indigo-600 rounded-lg text-sm font-medium">
                    Halaman <?= $page ?> dari <?= $total_pages ?>
                </div>

                <?php if ($page < $total_pages): ?>
                    <a href="riwayat.php?page=<?= $page + 1 ?>" class="px-3 py-2 border rounded-lg hover:bg-gray-100 text-sm font-medium">Berikutnya ›</a>
                    <a href="riwayat.php?page=<?= $total_pages ?>" class="px-3 py-2 border rounded-lg hover:bg-gray-100 text-sm font-medium">Terakhir »</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-700">
            <strong>💡 Info:</strong> Total transaksi pengeluaran barang: <strong><?= $total ?></strong> | Total halaman: <strong><?= $total_pages ?></strong>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>