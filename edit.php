<?php
// Mencegah error header saat pengalihan halaman (Location redirect)
ob_start(); 
session_start(); // Pastikan session_start() aktif jika menggunakan $_SESSION

require_once 'config/database.php';

// Proteksi Sesi Login
if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit;
}

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

// Ambil data lama berdasarkan ID untuk ditampilkan di form
try {
    $stmt = $pdo->prepare("SELECT * FROM barang WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();

    if (!$item) {
        die("Data barang dengan ID tersebut tidak ditemukan di database.");
    }
} catch (PDOException $e) {
    die("Gagal mengambil data: " . $e->getMessage());
}

$errors = [];

// Proses eksekusi form ketika tombol "Simpan Perubahan" ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_barang = trim($_POST['nama_barang']);
    $kategori = trim($_POST['kategori']);
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    // Validasi Server-Side
    if (empty($nama_barang)) {
        $errors[] = "Nama barang wajib diisi.";
    }
    if (empty($kategori)) {
        $errors[] = "Kategori barang wajib dipilih.";
    }
    if (!is_numeric($stok) || $stok < 0) {
        $errors[] = "Stok harus berupa angka dan tidak boleh negatif.";
    }
    if (!is_numeric($harga) || $harga < 0) {
        $errors[] = "Harga harus berupa angka dan tidak boleh negatif.";
    }

    // Jika input valid dan tidak ada error, lakukan UPDATE ke database
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE barang SET nama_barang = ?, kategori = ?, stok = ?, harga = ? WHERE id = ?");
            $stmt->execute([$nama_barang, $kategori, intval($stok), intval($harga), $id]);
            
            // Simpan pesan sukses ke session
            $_SESSION['success_msg'] = "✅ Barang '{$nama_barang}' berhasil diperbarui!";
            
            // Redirect ke inventaris jika sukses
            header("Location: invetaris.php");
            exit;
        } catch (PDOException $e) {
            $errors[] = "Gagal memperbarui data ke database: " . $e->getMessage();
        }
    }
}

// Panggil layout header cetakan UI setelah semua logika PHP selesai
include 'includes/header.php';
?>

<div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">Ubah Data Barang</h1>
    <p class="text-gray-500 text-sm mb-6">Perbarui data aset dengan informasi terbaru.</p>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded text-sm shadow-sm">
            <ul class="list-disc pl-4">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="edit.php?id=<?= $id ?>" class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
            <input type="text" name="nama_barang" value="<?= htmlspecialchars($item['nama_barang'] ?? '') ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
            <select name="kategori" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                <option value="Elektronik" <?= ($item['kategori'] ?? '') == 'Elektronik' ? 'selected' : '' ?>>Elektronik</option>
                <option value="Furnitur" <?= ($item['kategori'] ?? '') == 'Furnitur' ? 'selected' : '' ?>>Furnitur</option>
                <option value="Alat Tulis" <?= ($item['kategori'] ?? '') == 'Alat Tulis' ? 'selected' : '' ?>>Alat Tulis</option>
                <option value="Lainnya" <?= ($item['kategori'] ?? '') == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Stok</label>
                <input type="number" name="stok" value="<?= htmlspecialchars($item['stok'] ?? 0) ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan (Rp)</label>
                <input type="number" name="harga" value="<?= htmlspecialchars($item['harga'] ?? 0) ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
            </div>
        </div>
        <div class="flex justify-end space-x-3 pt-4">
            <a href="index.php" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Batal</a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition shadow-sm">Simpan Perubahan</button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>