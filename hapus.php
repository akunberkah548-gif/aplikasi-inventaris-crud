<?php
// Wajib ditaruh di paling atas untuk menghindari error 'headers already sent' saat redirect
ob_start(); 
session_start(); // Pastikan session aktif untuk mengecek status login

require_once 'config/database.php';

// Proteksi Sesi Login
if (!isset($_SESSION['login_user'])) {
    header("Location: login.php");
    exit;
}

// Pastikan parameter ID ada di URL dan merupakan angka
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    try {
        // Ambil nama barang sebelum dihapus untuk pesan feedback
        $stmtGet = $pdo->prepare("SELECT nama_barang FROM barang WHERE id = ?");
        $stmtGet->execute([$id]);
        $barang = $stmtGet->fetch();
        
        // Gunakan Prepared Statement untuk keamanan
        $stmt = $pdo->prepare("DELETE FROM barang WHERE id = ?");
        $stmt->execute([$id]);
        
        if ($barang) {
            $_SESSION['success_msg'] = "✅ Barang '{$barang['nama_barang']}' berhasil dihapus!";
        }
    } catch (PDOException $e) {
        // Simpan error ke session jika query gagal
        $_SESSION['error_msg'] = "❌ Gagal menghapus data: " . $e->getMessage();
    }
}

// Redirect kembali ke halaman inventaris setelah berhasil
header("Location: invetaris.php");
exit;