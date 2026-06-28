<?php
ob_start(); 
session_start(); 

require_once 'config/database.php';

if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit;
}

$nama_user = $_SESSION['login_user'];
$error = '';
$sukses = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $barang_id = isset($_POST['barang_id']) ? intval($_POST['barang_id']) : 0;
    $jumlah_keluar = isset($_POST['jumlah_keluar']) ? intval($_POST['jumlah_keluar']) : 0;
    $keterangan = isset($_POST['keterangan']) ? trim($_POST['keterangan']) : '';

    if ($barang_id <= 0 || $jumlah_keluar <= 0 || empty($keterangan)) {
        $error = "Semua kolom wajib diisi dengan benar!";
    } else {
        try {
            $stmtCek = $pdo->prepare("SELECT nama_barang, stok FROM barang WHERE id = :id");
            $stmtCek->execute(['id' => $barang_id]);
            $barang = $stmtCek->fetch();

            if (!$barang) {
                $error = "Barang tidak ditemukan di sistem.";
            } elseif ($barang['stok'] < $jumlah_keluar) {
                $error = "Stok tidak mencukupi! Sisa stok saat ini: " . $barang['stok'] . " unit.";
            } else {
                $pdo->beginTransaction();
                $stmtUpdate = $pdo->prepare("UPDATE barang SET stok = stok - :jumlah WHERE id = :id");
                $stmtUpdate->execute([
                    'jumlah' => $jumlah_keluar,
                    'id' => $barang_id
                ]);
                $pdo->commit();
                $sukses = "Berhasil memotong data! {$jumlah_keluar} unit '{$barang['nama_barang']}' telah dikeluarkan.";
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Gagal memproses pengeluaran data: " . $e->getMessage();
        }
    }
}

try {
    $stmtBarang = $pdo->query("SELECT id, nama_barang, stok FROM barang WHERE stok > 0 ORDER BY nama_barang ASC");
    $daftar_barang = $stmtBarang->fetchAll();
} catch (PDOException $e) {
    die("Gagal mengambil data dari database: " . $e->getMessage());
}

include 'includes/header.php'; 
?>

<div class="max-w-2xl mx-auto px-4 sm:px-0">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-6">
        <div>
            <h2 class="font-bold text-gray-800 text-xl">Form Pengeluaran Inventaris</h2>
            <p class="text-gray-400 text-xs mt-0.5">Pilih barang dan jumlah data yang ingin dikeluarkan dari sistem.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="p-4 bg-red-50 text-red-700 rounded-lg text-sm font-medium border border-red-100">⚠️ <?= $error ?></div>
        <?php endif; ?>

        <?php if (!empty($sukses)): ?>
            <div class="p-4 bg-emerald-50 text-emerald-700 rounded-lg text-sm font-medium border border-emerald-100">✅ <?= $sukses ?></div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Barang</label>
                <select name="barang_id" class="w-full px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    <option value="">-- Pilih data barang (Sisa stok) --</option>
                    <?php foreach ($daftar_barang as $b): ?>
                        <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['nama_barang']) ?> (Stok: <?= $b['stok'] ?> unit)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah Barang Keluar</label>
                <input type="number" name="jumlah_keluar" min="1" placeholder="Masukkan angka..." class="w-full px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Alasan Pengeluaran</label>
                <textarea name="keterangan" rows="3" placeholder="Contoh: Didistribusikan ke divisi IT..." class="w-full px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="index.php" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 transition">Kembali</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition shadow-sm">Kurangi & Simpan</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>