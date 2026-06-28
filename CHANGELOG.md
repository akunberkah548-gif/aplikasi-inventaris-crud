# 📝 CHANGELOG - TaskFlow Inventory System

All notable changes to this project will be documented in this file.

---

## [1.1.0] - 2026-12-28

### ✨ New Features
- ✅ **Transaction History Page** (`riwayat.php`)
  - View all item withdrawals with pagination
  - Display: item name, quantity, date, staff
  - Pagination support (20 records per page)
  
- ✅ **Transaction Logging** (pengeluaran_barang table)
  - Auto-log every withdrawal to database
  - Track who made the withdrawal & when
  - Queryable history for audits

- ✅ **Dashboard Enhancements**
  - Low stock alerts (< 10 units)
  - Recent transactions widget
  - Interactive charts (Bar & Pie)

- ✅ **CSS Stylesheet** (`css/style.css`)
  - Custom styling for consistency
  - Animations & transitions
  - Utility classes

- ✅ **Enhanced Navigation**
  - Added "Riwayat" menu link
  - Consistent navbar across all pages
  - Quick action buttons

### 🐛 Bug Fixes
- Fixed session_start() timing in login.php
  - Was checking $_SESSION before starting session
  - Now properly initializes session first
  
- Fixed navigation link typo
  - Header was linking to wrong file names
  - Updated to correct paths

- Fixed pengeluaran.php navigation
  - Typo in nav link ("pengrawan.php" → "pengeluaran.php")
  - Navigation now matches other pages

### 🎨 UI/UX Improvements
- Modern glassmorphism login page
- Gradient backgrounds & smooth transitions
- Better visual hierarchy & spacing
- Responsive design for mobile devices
- Color-coded alerts (success, error, warning)
- Improved table styling with hover effects
- Better form field styling

### 🔧 Technical Improvements
- **Database Transaction Safety**
  - Pengeluaran process uses BEGIN/COMMIT/ROLLBACK
  - Prevents partial updates if error occurs
  
- **Session-based Messaging**
  - Success/error messages persist across redirects
  - Automatically cleared after display
  - Better user feedback flow

- **Error Handling**
  - Graceful error messages (not raw DB errors)
  - Validation at both client & server side
  - Proper exception handling

- **Code Organization**
  - Separated concerns (header, footer, table component)
  - Reusable components
  - Cleaner code structure

### 📚 Documentation
- Comprehensive README.md
  - Features overview
  - Installation guide
  - Workflow diagrams
  - Troubleshooting guide
  
- CHANGELOG.md (this file)
  - Version history
  - All changes documented

### 🔐 Security
- No changes to core security (already good in v1.0)
- Maintained prepared statements
- Maintained password hashing
- Maintained session protection

---

## [1.0.0] - Initial Release

### Features
- ✅ User authentication (login/register)
- ✅ CRUD operations for items
  - Create: Add new items
  - Read: View items list with search
  - Update: Edit item details
  - Delete: Remove items with confirmation
  
- ✅ Item withdrawal functionality
  - Process item withdrawals
  - Auto-decrease stock
  
- ✅ Search & filter
  - Search by name & category
  - Sort by: name, category, stock, price
  - Ascending/Descending order

- ✅ Dashboard with statistics
  - Total items count
  - Total stock count
  
- ✅ Responsive design (Tailwind CSS)

---

## Summary of Changes (v1.0 → v1.1)

### What's New? 🎉
```
Before (v1.0):              After (v1.1):
- CRUD Only                 - CRUD + History
- No withdrawal logging     - Full transaction logging
- Basic dashboard           - Enhanced dashboard with alerts
- No feedback messages      - Session-based messaging
- Limited navigation        - Better navigation menu
```

### Files Modified
- ✏️ `includes/header.php` - Added riwayat link, improved nav
- ✏️ `includes/footer.php` - Enhanced styling & info
- ✏️ `config/database.php` - Session handling improved
- ✏️ `login.php` - Fixed session_start() timing
- ✏️ `tambah.php` - Added session success message
- ✏️ `edit.php` - Added session success message
- ✏️ `hapus.php` - Added session feedback
- ✏️ `invetaris.php` - Added message display
- ✏️ `pengeluaran.php` - Improved UI & added transaction logging
- ✏️ `index.php` - Added alerts & recent transactions
- ✏️ `tabel_barang.php` - Added alert display
- ✏️ `database/migration.sql` - Added pengeluaran_barang table & pengguna table

### Files Added
- ✨ `riwayat.php` - New transaction history page
- ✨ `css/style.css` - Custom styling (was empty)
- ✨ `README.md` - Comprehensive documentation (was minimal)
- ✨ `CHANGELOG.md` - This file

---

## Technical Details

### Database Schema Changes
```sql
-- NEW: pengeluaran_barang table
CREATE TABLE pengeluaran_barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    barang_id INT NOT NULL,
    jumlah_keluar INT NOT NULL,
    keterangan TEXT,
    petugas VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (barang_id) REFERENCES barang(id)
);

-- UPDATED: barang table (added updated_at)
ALTER TABLE barang ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- NEW: pengguna table (for user management)
CREATE TABLE pengguna (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Performance Notes
- Pagination on history page (20 records/page) for better performance
- Index on barang.stok for low-stock queries
- Index on pengeluaran_barang.barang_id for faster lookups

---

## Breaking Changes
⚠️ **IMPORTANT**: After updating from v1.0 to v1.1:

1. **Run Migration** - Execute new migration.sql to add tables
2. **Database Backup** - Backup existing data before migration
3. **Clear Sessions** - Clear browser cache after update

---

## Known Issues / Limitations
- No user role management (all users are admin)
- No bulk operations
- Chart.js requires internet for CDN (offline not supported)
- No export to Excel/PDF yet
- Single-language (Indonesian only)

---

## Future Roadmap (v1.2+)
- [ ] Multi-level user roles
- [ ] Export data (Excel/PDF)
- [ ] Email alerts for low stock
- [ ] Advanced analytics
- [ ] Barcode/QR scanning
- [ ] Mobile app
- [ ] API endpoint
- [ ] Dark mode

---

## Contributing
Internal development only. For changes, contact PT Teknologi Sunan Drajat.

---

## Version Info
- **Current**: v1.1.0 (Stable)
- **Previous**: v1.0.0
- **Release Date**: December 2026
- **Status**: ✅ Production Ready

---

**Last Updated**: December 28, 2026
**Maintained By**: PT Teknologi Sunan Drajat Lamongan