# 📦 TaskFlow Inventory System v1.1.0

**Sistem Manajemen Inventaris Modern dengan Interface Responsif & Fitur Lengkap**

---

## 🎯 Deskripsi Aplikasi

TaskFlow adalah sistem manajemen inventaris berbasis web yang dirancang untuk memudahkan pengelolaan stok barang, pencatatan pengeluaran, dan monitoring real-time. Aplikasi ini menggunakan teknologi modern dengan antarmuka yang user-friendly dan responsif.

**Dikembangkan untuk:** PT Teknologi Sunan Drajat Lamongan | Batch 2026

---

## ✨ Fitur Utama

### 1. **Dashboard Interaktif** 📊
- ✓ Statistik real-time: Total barang, Total stok global
- ✓ Grafik visual stok barang (Top 10) - Bar Chart
- ✓ Pie chart distribusi kategori barang
- ✓ Peringatan stok rendah (< 10 unit)
- ✓ Riwayat transaksi terbaru

### 2. **Manajemen Inventaris Barang** 📦
- ✓ Tambah, edit, dan hapus data barang
- ✓ Pencarian & filtering barang real-time
- ✓ Sorting berdasarkan: nama, kategori, stok, harga
- ✓ Tampilan tabel responsif dengan informasi lengkap
- ✓ Validasi server-side untuk keamanan data

### 3. **Sistem Pengeluaran Barang** 🚚
- ✓ Form pengeluaran barang terintegrasi
- ✓ Otomatis kurangi stok setelah proses
- ✓ Validasi stok tersedia sebelum pengeluaran
- ✓ Pencatatan keterangan/tujuan pengeluaran
- ✓ Database transaction untuk keamanan data

### 4. **Riwayat Transaksi** 📋
- ✓ Tampilkan semua pengeluaran barang dengan pagination
- ✓ Filter berdasarkan tanggal, barang, petugas
- ✓ Export-ready data format
- ✓ Informasi detail: barang, jumlah, waktu, petugas

### 5. **Autentikasi & Keamanan** 🔐
- ✓ Sistem login/register dengan password hashing
- ✓ Session protection di setiap halaman
- ✓ SQL injection prevention (prepared statements)
- ✓ Input validation & sanitization

---

## 🛠️ Teknologi Stack

| Layer | Teknologi |
|-------|-----------|
| **Frontend** | HTML5, Tailwind CSS 3.x, JavaScript, Chart.js |
| **Backend** | PHP 7.4+ / 8.x |
| **Database** | MySQL 8.x / MariaDB |
| **Server** | Apache (XAMPP) |
| **Security** | bcrypt, prepared statements, session management |

---

## 📋 Persyaratan Sistem

- **PHP** 7.4 atau lebih tinggi
- **MySQL** 5.7 atau MariaDB 10.2+
- **XAMPP** atau server lokal lainnya
- **Browser** modern (Chrome, Firefox, Safari, Edge)

---

## 🚀 Instalasi & Setup

### 1. Persiapan Database
```sql
-- Jalankan migration script dari file database/migration.sql
-- Copy-paste isi file ke MySQL console atau phpMyAdmin
```

### 2. Konfigurasi Koneksi Database
Edit file `config/database.php`:
```php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'db_inventaris';
```

### 3. Jalankan Aplikasi
```bash
# Tempat folder di root XAMPP
C:\xampp\htdocs\taskflow - fian

# Akses via browser
http://localhost/taskflow%20-%20fian/
```

### 4. Login Pertama Kali
**Demo User:**
- Username: `admin`
- Password: `123456`

---

## 📂 Struktur Folder

```
taskflow - fian/
├── index.php               # Dashboard utama
├── invetaris.php          # Halaman inventaris barang
├── pengeluaran.php        # Form pengeluaran barang
├── riwayat.php           # Riwayat transaksi pengeluaran
├── tambah.php            # Form tambah barang baru
├── edit.php              # Form edit barang
├── hapus.php             # Proses hapus barang
├── login.php             # Halaman login
├── register.php          # Halaman registrasi
├── logout.php            # Proses logout
├── tabel_barang.php      # Komponen tabel barang (reusable)
│
├── config/
│   └── database.php      # Konfigurasi koneksi database
│
├── database/
│   └── migration.sql     # Schema database + data awal
│
├── includes/
│   ├── header.php        # Header & navbar (reusable)
│   └── footer.php        # Footer (reusable)
│
└── css/
    └── style.css         # Custom styling CSS
```

---

## 🎨 Alur Kerja (Workflow)

### User Flow Diagram
```
┌─────────────────────────────────────────────────────────┐
│                      LOGIN PAGE                          │
│  Username: admin | Password: 123456                      │
└──────────────────────┬──────────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────────┐
│                   DASHBOARD (HOME)                       │
│  • Statistik & Summary                                   │
│  • Grafik Visual (Bar & Pie)                            │
│  • Low Stock Warnings                                    │
│  • Recent Transactions                                   │
└──┬──────────────────────┬──────────────┬──────────────┬──┘
   │                      │              │              │
   ▼                      ▼              ▼              ▼
┌──────────────┐  ┌──────────────┐  ┌─────────┐  ┌──────────┐
│ Inventaris   │  │ Pengeluaran  │  │ Riwayat │  │ Profile  │
│              │  │              │  │         │  │          │
│ • Lihat Data │  │ • Form Input │  │ • List  │  │ • Logout │
│ • Edit       │  │ • Auto Stok  │  │ • Paging│  │          │
│ • Hapus      │  │ • Validasi   │  │ • Detail│  │          │
│ • Cari       │  │ • Konfirmasi │  │ • Date  │  │          │
└──────────────┘  └──────────────┘  └─────────┘  └──────────┘
```

### Alur Teknis Database

#### 1. **Tambah Barang**
```
Form Input (nama, kategori, stok, harga)
        ↓
Validasi Server-Side (type check, empty check)
        ↓
INSERT INTO barang VALUES (...)
        ↓
Session Message: "✅ Barang 'X' berhasil ditambahkan!"
        ↓
Redirect → invetaris.php (dengan message)
```

#### 2. **Edit Barang**
```
Load Data (SELECT * WHERE id = ?)
        ↓
Form Pre-fill dengan data lama
        ↓
User Submit Perubahan
        ↓
Validasi Server-Side
        ↓
UPDATE barang SET nama=?, kategori=?, stok=?, harga=? WHERE id=?
        ↓
Session Message: "✅ Barang diperbarui!"
        ↓
Redirect → invetaris.php
```

#### 3. **Hapus Barang**
```
User Click Hapus Button
        ↓
JS Confirmation Dialog
        ↓
GET /hapus.php?id=X
        ↓
SELECT nama_barang FROM barang WHERE id=X (untuk message)
        ↓
DELETE FROM barang WHERE id=X
        ↓
Session Message: "✅ Barang 'Y' berhasil dihapus!"
        ↓
Redirect → invetaris.php
```

#### 4. **Pengeluaran Barang** (Main Feature)
```
SELECT barang → Dropdown List (barang dengan stok > 0)
        ↓
User Select: Barang, Jumlah, Keterangan
        ↓
Form Submit POST
        ↓
Server-Side Validasi
        ├─ if (stok_barang < jumlah_keluar)
        │   └─ Error: "Stok tidak mencukupi!"
        │
        └─ if (valid)
           ↓
           BEGIN TRANSACTION
           ├─ UPDATE barang SET stok = stok - jumlah WHERE id=?
           ├─ INSERT pengeluaran_barang (barang_id, jumlah_keluar, keterangan, petugas, created_at)
           └─ COMMIT
           ↓
           Session Message: "✅ Pengeluaran berhasil!"
           ↓
           Refresh Dropdown (barang dengan stok > 0)
```

---

## 📊 Fitur-Fitur Perbaikan v1.1.0

### Improvements yang Sudah Diimplementasikan:

✅ **UI/UX Enhancements**
- Modern design dengan Tailwind CSS
- Responsive layout untuk semua device
- Consistent styling di semua halaman
- Better visual hierarchy & spacing
- Gradient backgrounds & smooth transitions
- Icon usage untuk better UX

✅ **Fitur User Feedback**
- Success messages setelah setiap operasi
- Error alerts untuk validasi gagal
- Session-based message passing (tidak hilang saat reload)
- Dismissible notifications dengan close button
- Color-coded alerts (green=success, red=error, yellow=warning)

✅ **Workflow Improvements**
- **New: Transaction Logging** - Semua pengeluaran dicatat ke tabel `pengeluaran_barang`
- **New: Database Transactions** - Aman dengan BEGIN/COMMIT/ROLLBACK
- **New: Riwayat Page** - Lihat history pengeluaran lengkap
- Clear redirect flow (tambah→inventaris, edit→inventaris, etc)

✅ **Dashboard Enhancements**
- Low stock alerts/warnings (< 10 unit)
- Recent transactions display (5 terakhir)
- Interactive charts (Bar & Pie Chart)
- Real-time summary statistics

✅ **Security Fixes**
- Fixed session_start() timing in login.php
- Proper error handling (no raw DB errors ke user)
- Database transaction safety
- Input validation & sanitization

✅ **New Features**
- **Riwayat Pengeluaran (History)** - Tracking semua transaksi
- **Pagination** - Untuk banyak data (20 per halaman)
- **Navigation Menu** - Improved dengan riwayat link
- **Footer Enhancement** - Informasi aplikasi lebih detail
- **CSS File** - Custom styling untuk consistency

---

## 🔧 Panduan Penggunaan

### 1. Login
```
1. Akses http://localhost/taskflow%20-%20fian/
2. Masukkan: username=admin, password=123456
3. Klik "Masuk Aplikasi"
```

### 2. Tambah Barang
```
1. Klik "Tambah Barang" di navbar
2. Isi form:
   - Nama Barang: (text)
   - Kategori: (dropdown)
   - Jumlah Stok: (number)
   - Harga Satuan: (number)
3. Klik "Simpan Data"
4. ✅ Notifikasi sukses + redirect ke inventaris
```

### 3. Edit Barang
```
1. Di tabel inventaris, klik tombol "Ubah" (kuning)
2. Form otomatis ter-fill dengan data lama
3. Perbarui field yang perlu diubah
4. Klik "Simpan Perubahan"
5. ✅ Notifikasi sukses + redirect
```

### 4. Hapus Barang
```
1. Di tabel inventaris, klik tombol "Hapus" (merah)
2. Konfirmasi dialog muncul
3. Jika yakin, klik "OK"
4. ✅ Barang dihapus + notifikasi
```

### 5. Pengeluaran Barang
```
1. Klik menu "Pengeluaran Barang" di navbar
2. Pilih barang dari dropdown (hanya barang stok > 0)
3. Input jumlah unit yang keluar
4. Isi keterangan (contoh: "divisi marketing" atau "barang rusak")
5. Klik "Proses Keluar"
6. ✅ Stok otomatis berkurang, tercatat di riwayat
```

### 6. Lihat Riwayat Transaksi
```
1. Klik menu "Riwayat" di navbar
2. Lihat semua pengeluaran barang
3. Gunakan pagination (Next/Prev) untuk navigasi
4. Detail tersedia: barang, jumlah, waktu, petugas
```

---

## 🐛 Troubleshooting

| Masalah | Solusi |
|---------|--------|
| "Database connection failed" | Cek MySQL running, config/database.php benar |
| Session error saat login | Clear browser cache (Ctrl+Shift+Del) |
| Stok tidak berkurang | Pastikan tabel pengeluaran_barang exist (jalankan migration) |
| Chart tidak tampil | Cek koneksi internet (Chart.js load dari CDN) |
| Halaman blank/white | Lihat error: `tail -f /path/to/php-error.log` |
| Session messages hilang | Pastikan session.gc_probability > 0 di php.ini |

---

## 🔐 Security Features

1. **Prepared Statements** - Semua query pakai ? atau :param (anti SQL injection)
2. **Password Hashing** - bcrypt untuk hash password
3. **Session Protection** - Check $_SESSION di setiap halaman admin
4. **Input Sanitization** - htmlspecialchars() untuk output
5. **Error Handling** - Tidak tampilkan error DB ke user

---

## 📝 Catatan Developer

### Folder Permission
```bash
# Linux/Mac
chmod 755 database/
chmod 644 database/migration.sql
chmod 644 config/database.php
```

### Database Backup
```bash
# Export
mysqldump -u root -p db_inventaris > backup_inventaris.sql

# Import
mysql -u root -p db_inventaris < backup_inventaris.sql
```

### Debugging Mode
Edit `config/database.php`:
```php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/error.log');
```

---

## 🎯 Roadmap Fitur Masa Depan

- [ ] Export data ke Excel/PDF
- [ ] Multi-level user access (admin, staff, viewer)
- [ ] REST API untuk mobile app
- [ ] Email notification untuk low stock
- [ ] Analytics dashboard lebih detail
- [ ] Barcode/QR code scanner
- [ ] Dark mode theme

---

## 👥 Tim Pengembang

**PT Teknologi Sunan Drajat Lamongan**
- Batch: 2026
- All Rights Reserved © 2026

---

## 📄 Lisensi

Proprietary - Untuk penggunaan internal PT Teknologi Sunan Drajat Lamongan

---

## 📞 Support & Changelog

**Version History:**
- **v1.1.0** - Enhanced UI, Transaction Logging, Dashboard Improvements
- **v1.0.0** - Initial Release (CRUD Basic)

**Last Updated:** Desember 2026
**Status:** ✅ Stable & Production Ready