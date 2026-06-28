<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TSD Inventaris</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-gray-50 font-sans">

<nav class="bg-indigo-700 text-white shadow-md w-full mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            
            <div class="flex items-center gap-6">
                <span class="font-bold text-xl flex items-center gap-2">📦 TSD Inventaris</span>
                
                <div class="flex items-center gap-2">
                    <a href="index.php" class="text-white hover:bg-indigo-600 px-3 py-2 rounded-lg text-sm font-semibold transition">
                        Dashboard
                    </a>
                    <a href="invetaris.php" class="text-white hover:bg-indigo-600 px-3 py-2 rounded-lg text-sm font-semibold transition">
                        Inventaris Barang
                    </a>
                    <a href="pengeluaran.php" class="text-white hover:bg-indigo-600 px-3 py-2 rounded-lg text-sm font-semibold transition">
                        Pengeluaran Barang
                    </a>
                    <a href="riwayat.php" class="text-white hover:bg-indigo-600 px-3 py-2 rounded-lg text-sm font-semibold transition">
                        📋 Riwayat
                    </a>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <a href="tambah.php" class="bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-2 rounded-lg text-sm font-semibold transition shadow-sm border border-indigo-500 hidden sm:block">
                    + Tambah Barang
                </a>

                <div class="h-6 w-px bg-indigo-500 hidden sm:block"></div>

                <div class="hidden sm:flex items-center gap-2 bg-indigo-800/50 px-3 py-1.5 rounded-lg border border-indigo-600">
                    <span class="text-xs text-indigo-200">Logged in as: <strong class="text-white"><?= htmlspecialchars($_SESSION['login_user'] ?? 'User') ?></strong></span>
                </div>

                <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition shadow-sm">
                    Keluar
                </a>
            </div>

        </div>
    </div>
</nav>