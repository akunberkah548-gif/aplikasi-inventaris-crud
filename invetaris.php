<?php
ob_start(); 
session_start(); 

require_once 'config/database.php';

if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit;
}

$nama_user = $_SESSION['login_user'];

// Handle display messages from session
$success_msg = $_SESSION['success_msg'] ?? '';
$error_msg = $_SESSION['error_msg'] ?? '';
unset($_SESSION['success_msg'], $_SESSION['error_msg']);

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    if (!empty($search)) {
        $query = "SELECT * FROM barang WHERE nama_barang LIKE :search OR kategori LIKE :search ORDER BY nama_barang ASC";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['search' => "%$search%"]);
    } else {
        $query = "SELECT * FROM barang ORDER BY nama_barang ASC";
        $stmt = $pdo->query($query);
    }
    $items = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Gagal mengambil data dari database: " . $e->getMessage());
}

include 'includes/header.php'; 
?>

<div class="max-w-7xl mx-auto px-4 lg:px-8">
    <?php include 'tabel_barang.php'; ?>
</div>

<?php include 'includes/footer.php'; ?>