<?php
ob_start();
session_start();
require_once 'config/database.php';

if (isset($_SESSION['login_user'])) {
    header("Location: index.php");
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username)) { $errors[] = "Username tidak boleh kosong."; }
    if (empty($password)) { $errors[] = "Kata sandi tidak boleh kosong."; }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM pengguna WHERE username = ?");
            $stmt->execute([$username]);
            $userAccount = $stmt->fetch();

            if ($userAccount && password_verify($password, $userAccount['password'])) {
                $_SESSION['login_user'] = $userAccount['username'];
                $_SESSION['login_id']   = $userAccount['id'];
                
                header("Location: index.php");
                exit;
            } else {
                $errors[] = "Kombinasi Username atau Kata Sandi Anda salah.";
            }
        } catch (PDOException $e) {
            $errors[] = "Error sistem basis data: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TSD Inventaris</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Animasi pergerakan warna background lambat */
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

    <!-- Box Login dengan Efek Glassmorphism (Clear Berkaca-kaca) -->
    <div class="max-w-md w-full bg-white/10 backdrop-blur-md p-8 rounded-2xl shadow-2xl border border-white/20 mx-4 relative z-10 transition-all duration-500 hover:border-white/40">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto shadow-inner border border-white/10 animate-bounce duration-1000">
                <span class="text-3xl">📦</span>
            </div>
            <h1 class="text-2xl font-bold text-white mt-4 tracking-wide">Selamat Datang</h1>
            <p class="text-indigo-200/70 text-sm mt-1">Masuk untuk mengelola sistem inventaris</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-500/20 border-l-4 border-red-500 text-red-200 p-3 mb-5 rounded-xl text-sm backdrop-blur-sm">
                <ul class="list-disc pl-4">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php" class="space-y-5">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-indigo-200 mb-1.5">Username</label>
                <input type="text" name="username" value="<?= isset($username) ? htmlspecialchars($username) : '' ?>" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent text-sm text-white placeholder-indigo-300/30 outline-none transition" placeholder="Masukkan username">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-indigo-200 mb-1.5">Kata Sandi</label>
                <input type="password" name="password" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-transparent text-sm text-white placeholder-indigo-300/30 outline-none transition" placeholder="******">
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-medium py-2.5 rounded-xl transition duration-300 text-sm shadow-lg shadow-indigo-500/20 active:scale-95">Masuk Aplikasi</button>
        </form>

        <p class="text-center text-sm text-indigo-200/60 mt-8">Belum terdaftar? <a href="register.php" class="text-indigo-400 hover:text-indigo-300 hover:underline font-semibold transition">Buat akun baru</a></p>
    </div>
</body>
</html>