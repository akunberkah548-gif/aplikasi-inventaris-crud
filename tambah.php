<?php
// Mencegah error header saat pengalihan halaman (Location redirect)
ob_start(); 
session_start(); // Pastikan session aktif untuk mengecek data login

require_once 'config/database.php';

// Proteksi Sesi Login
if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitasi data input
    $nama_barang = trim($_POST['nama_barang']);
    $kategori = trim($_POST['kategori']);
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    // Validasi Server-Side
    if (empty($nama_barang)) $errors[] = "Nama barang wajib diisi.";
    if (empty($kategori)) $errors[] = "Kategori barang wajib dipilih.";
    if (!is_numeric($stok) || $stok < 0) $errors[] = "Stok harus berupa angka positif.";
    if (!is_numeric($harga) || $harga < 0) $errors[] = "Harga harus berupa angka positif.";

    // Jika lolos validasi, simpan ke database
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO barang (nama_barang, kategori, stok, harga) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nama_barang, $kategori, intval($stok), intval($harga)]);
            
            $_SESSION['success_msg'] = "✅ Barang '{$nama_barang}' berhasil ditambahkan!";
            header("Location: invetaris.php");
            exit;
        } catch (PDOException $e) {
            $errors[] = "Gagal menyimpan data ke database: " . $e->getMessage();
        }
    }
}

include 'includes/header.php';
?>

<div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">Tambah Barang Baru</h1>
    <p class="text-gray-500 text-sm mb-6">Pastikan seluruh data kolom terisi dengan benar.</p>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded text-sm shadow-sm">
            <ul class="list-disc pl-4">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
            <input type="text" name="nama_barang" value="<?= isset($nama_barang) ? htmlspecialchars($nama_barang) : '' ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
            <select name="kategori" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                <option value="">-- Pilih Kategori --</option>
                <option value="Elektronik" <?= (isset($kategori) && $kategori == 'Elektronik') ? 'selected' : '' ?>>Elektronik</option>
                <option value="Furnitur" <?= (isset($kategori) && $kategori == 'Furnitur') ? 'selected' : '' ?>>Furnitur</option>
                <option value="Alat Tulis" <?= (isset($kategori) && $kategori == 'Alat Tulis') ? 'selected' : '' ?>>Alat Tulis</option>
                <option value="Lainnya" <?= (isset($kategori) && $kategori == 'Lainnya') ? 'selected' : '' ?>>Lainnya</option>
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Stok</label>
                <input type="number" name="stok" value="<?= isset($stok) ? htmlspecialchars($stok) : '0' ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan (Rp)</label>
                <input type="number" name="harga" value="<?= isset($harga) ? htmlspecialchars($harga) : '0' ?>" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
            </div>
        </div>
        <div class="flex justify-end space-x-3 pt-4">
            <a href="index.php" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Batal</a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition shadow-sm">Simpan Data</button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>