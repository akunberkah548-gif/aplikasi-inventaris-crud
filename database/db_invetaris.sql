-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Jun 2026 pada 02.27
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.5.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_inventaris`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id` int(11) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `stok` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id`, `nama_barang`, `kategori`, `stok`, `harga`, `created_at`, `updated_at`) VALUES
(1, 'Laptop Asus ROG', 'Elektronik', 5, 15000000, '2026-06-27 07:55:38', '2026-06-28 00:15:17'),
(5, 'radio', 'Elektronik', 5, 5000, '2026-06-27 11:55:27', '2026-06-28 00:15:17'),
(6, 'hp samsung', 'Elektronik', 1, 4444, '2026-06-27 12:09:09', '2026-06-28 00:17:07'),
(7, 'hp samsung', 'Elektronik', 5678, 5555555, '2026-06-27 17:02:31', '2026-06-28 00:15:17'),
(8, 'hp samsung', 'Elektronik', 4, 500000, '2026-06-28 00:16:34', '2026-06-28 00:16:34');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengeluaran_barang`
--

CREATE TABLE `pengeluaran_barang` (
  `id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL,
  `jumlah_keluar` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `petugas` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengeluaran_barang`
--

INSERT INTO `pengeluaran_barang` (`id`, `barang_id`, `jumlah_keluar`, `keterangan`, `petugas`, `created_at`) VALUES
(1, 6, 4, 'di beli', 'zid', '2026-06-28 00:17:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id`, `username`, `email`, `password`) VALUES
(1, 'admin', 'mohammadyazidur@gmail.com', '$2y$12$w4hNOPA0jlNha67UXrPoPe.KOtQj0KKj/z9NSvAk/czvxVGbHfEiy'),
(2, 'zid', 'zid@gmail.com', '$2y$12$OwD8ktfLkQuHxLuIN2ySSuALhnt2sMmBs6/TrgOea4uYVLIctTaQa'),
(3, 'admin', '', '$2y$10$YJr4DdN3r/VLM8WLhKRJzO0YJ8Yzml7wN9g0P5jR0K8Q1l5j.VyKe');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_barang_stok` (`stok`);

--
-- Indeks untuk tabel `pengeluaran_barang`
--
ALTER TABLE `pengeluaran_barang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pengeluaran_barang` (`barang_id`,`created_at`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `pengeluaran_barang`
--
ALTER TABLE `pengeluaran_barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `pengeluaran_barang`
--
ALTER TABLE `pengeluaran_barang`
  ADD CONSTRAINT `pengeluaran_barang_ibfk_1` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
