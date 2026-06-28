<?php
ob_start();
require_once 'config/database.php';

if (isset($_SESSION['login_user'])) {
    header("Location: index.php");
    exit;
}

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($username)) { $errors[] = "Username wajib diisi."; }
    if (empty($email)) { $errors[] = "Email wajib diisi."; }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = "Format email tidak valid."; }
    if (strlen($password) < 6) { $errors[] = "Kata sandi minimal harus 6 karakter."; }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM pengguna WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                $errors[] = "Username atau Email sudah terdaftar di sistem.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                
                $insert = $pdo->prepare("INSERT INTO pengguna (username, email, password) VALUES (?, ?, ?)");
                $insert->execute([$username, $email, $hashed_password]);
                
                $success = "Registrasi sukses! Silakan dialihkan ke halaman login.";
                $username = $email = "";
            }
        } catch (PDOException $e) {
            $errors[] = "Terjadi kesalahan basis data: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - TSD Inventaris</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-gradient {
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }
    </style>
</head>
<body class="animate-gradient bg-gradient-to-tr from-slate-900 via-indigo-950 to-slate-900 font-sans flex items-center justify-center min-h-screen relative overflow-hidden">
    
    <!-- Dekorasi Ornamen Lingkaran Abstrak Dibelakang Box -->
    <div class="absolute w-72 h-72 bg-indigo-500/20 rounded-full blur-3xl -top-12 -left-12"></div>
    <div class="absolute w-96 h-96 bg-purple-500/10 rounded-full blur-3xl -bottom-20 -right-20"></div>

    <!-- Box Register dengan Efek Glassmorphism (Clear Berkaca-kaca) -->
    <div class="max-w-md w-full bg-white/10 backdrop-blur-md p-8 rounded-2xl shadow-2xl border border-white/20 mx-4 relative z-10 transition-all duration-500 hover:border-white/40">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto shadow-inner border border-white/10">
                <span class="text-3xl">👤</span>
            </div>
            <h1 class="text-2xl font-bold text-white mt-4 tracking-wide">Buat Akun Baru</h1>
            <p class="text-indigo-200/70 text-sm mt-1">Silakan daftarkan identitas Anda</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-500/20 border-l-4 border-red-500 text-red-200 p-3 mb-4 rounded-xl text-sm backdrop-blur-sm">
                <ul class="list-disc pl-4">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="bg-green-500/20 border-l-4 border-green-500 text-green-200 p-4 mb-4 rounded-xl text-sm text-center backdrop-blur-sm shadow-inner">
                <?= htmlspecialchars($success) ?>
                <div class="mt-3"><a href="login.php" class="inline-block bg-white text-indigo-950 font-bold px-4 py-1.5 rounded-lg text-xs shadow hover:bg-indigo-50 transition duration-200">Klik di sini untuk Login</a></div>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-indigo-200 mb-1.5">Username</label>
                <input type="text" name="username" value="<?= isset($username) ? htmlspecialchars($username) : '' ?>" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent text-sm text-white placeholder-indigo-300/30 outline-none transition">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-indigo-200 mb-1.5">Alamat Email</label>
                <input type="email" name="email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent text-sm text-white placeholder-indigo-300/30 outline-none transition">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-indigo-200 mb-1.5">Kata Sandi</label>
                <input type="password" name="password" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent text-sm text-white placeholder-indigo-300/30 outline-none transition" placeholder="******">
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-medium py-2.5 rounded-xl transition duration-300 text-sm shadow-lg shadow-indigo-500/20 active:scale-95">Daftar Akun</button>
        </form>

        <p class="text-center text-sm text-indigo-200/60 mt-8">Sudah punya akun? <a href="login.php" class="text-indigo-400 hover:text-indigo-300 hover:underline font-semibold transition">Login di sini</a></p>
    </div>
</body>
</html>