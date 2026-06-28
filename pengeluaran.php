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
$error = '';
$sukses = '';

// 1. Ambil semua daftar barang untuk pilihan di Form (Dropdown)
try {
    $stmtBarang = $pdo->query("SELECT id, nama_barang, stok FROM barang WHERE stok > 0 ORDER BY nama_barang ASC");
    $daftar_barang = $stmtBarang->fetchAll();
} catch (PDOException $e) {
    die("Gagal mengambil data barang: " . $e->getMessage());
}

// 2. Proses jika Form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $barang_id = $_POST['barang_id'] ?? '';
    $jumlah_keluar = intval($_POST['jumlah_keluar'] ?? 0);
    $keterangan = trim($_POST['keterangan'] ?? '');

    if (empty($barang_id) || $jumlah_keluar <= 0 || empty($keterangan)) {
        $error = "Semua kolom wajib diisi dengan benar!";
    } else {
        try {
            // Cek stok barang saat ini terlebih dahulu
            $stmtCek = $pdo->prepare("SELECT nama_barang, stok FROM barang WHERE id = :id");
            $stmtCek->execute(['id' => $barang_id]);
            $barang = $stmtCek->fetch();

            if (!$barang) {
                $error = "Barang tidak ditemukan.";
            } elseif ($barang['stok'] < $jumlah_keluar) {
                $error = "Stok tidak mencukupi! Stok saat ini: " . $barang['stok'] . " unit.";
            } else {
                // Mulai Database Transaction agar aman
                $pdo->beginTransaction();

                try {
                    // Kurangi stok di tabel 'barang'
                    $stmtUpdate = $pdo->prepare("UPDATE barang SET stok = stok - :jumlah WHERE id = :id");
                    $stmtUpdate->execute([
                        'jumlah' => $jumlah_keluar,
                        'id' => $barang_id
                    ]);

                    // Log pengeluaran ke tabel pengeluaran_barang
                    $stmtLog = $pdo->prepare("INSERT INTO pengeluaran_barang (barang_id, jumlah_keluar, keterangan, petugas) VALUES (?, ?, ?, ?)");
                    $stmtLog->execute([$barang_id, $jumlah_keluar, $keterangan, $nama_user]);

                    $pdo->commit();
                    $sukses = "✅ Berhasil mengeluarkan {$jumlah_keluar} unit {$barang['nama_barang']}!";
                    
                    // Refresh data barang setelah stok berkurang
                    $stmtBarang = $pdo->query("SELECT id, nama_barang, stok FROM barang WHERE stok > 0 ORDER BY nama_barang ASC");
                    $daftar_barang = $stmtBarang->fetchAll();
                } catch (PDOException $e) {
                    $pdo->rollBack();
                    $error = "Gagal memproses pengeluaran: " . $e->getMessage();
                }
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Gagal memproses pengeluaran: " . $e->getMessage();
        }
    }
}

include 'includes/header.php';
?>

<div class="max-w-2xl mx-auto px-4 lg:px-8 py-4">
    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 space-y-6">
        <div>
            <h2 class="font-bold text-gray-800 text-2xl">📦 Form Pengeluaran Barang</h2>
            <p class="text-gray-500 text-sm mt-1">Kurangi stok inventaris untuk keperluan internal atau penjualan.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="p-4 bg-red-50 text-red-700 rounded-lg text-sm font-medium border border-red-100 flex items-center gap-2">
                <span>⚠️</span>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($sukses)): ?>
            <div class="p-4 bg-emerald-50 text-emerald-700 rounded-lg text-sm font-medium border border-emerald-100 flex items-center gap-2">
                <span>✅</span>
                <span><?= htmlspecialchars($sukses) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Barang yang Keluar</label>
                <select name="barang_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                    <option value="">-- Pilih Barang (Sisa Stok) --</option>
                    <?php foreach ($daftar_barang as $b): ?>
                        <option value="<?= $b['id'] ?>">
                            <?= htmlspecialchars($b['nama_barang']) ?> (Tersedia: <?= $b['stok'] ?> unit)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Unit Keluar</label>
                <input type="number" name="jumlah_keluar" min="1" placeholder="Contoh: 5" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan / Tujuan Pengeluaran</label>
                <textarea name="keterangan" rows="4" placeholder="Contoh: Dipakai untuk divisi marketing atau Barang rusak" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none" required></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="riwayat.php" class="px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 border border-gray-300 rounded-lg transition">
                    📋 Lihat Riwayat
                </a>
                <a href="index.php" class="px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 border border-gray-300 rounded-lg transition">
                    ← Kembali
                </a>
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white rounded-lg text-sm font-medium transition shadow-sm">
                    ✓ Proses Keluar
                </button>
            </div>
        </form>

        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg text-xs text-blue-700">
            <strong>💡 Tips:</strong> Stok barang akan otomatis berkurang setelah form diproses. Semua transaksi tercatat di riwayat pengeluaran.
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>