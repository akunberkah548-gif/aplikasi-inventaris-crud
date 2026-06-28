CREATE DATABASE IF NOT EXISTS db_inventaris;
USE db_inventaris;

CREATE TABLE IF NOT EXISTS pengguna (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_barang VARCHAR(100) NOT NULL,
    kategori VARCHAR(50) NOT NULL,
    stok INT NOT NULL,
    harga INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS pengeluaran_barang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    barang_id INT NOT NULL,
    jumlah_keluar INT NOT NULL,
    keterangan TEXT,
    petugas VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (barang_id) REFERENCES barang(id)
);

CREATE INDEX idx_barang_stok ON barang(stok);
CREATE INDEX idx_pengeluaran_barang ON pengeluaran_barang(barang_id, created_at);

-- Insert demo user (password: 123456)
INSERT IGNORE INTO pengguna (username, password) VALUES 
('admin', '$2y$10$YJr4DdN3r/VLM8WLhKRJzO0YJ8Yzml7wN9g0P5jR0K8Q1l5j.VyKe');

-- Data Awal (Dummy)
INSERT INTO barang (nama_barang, kategori, stok, harga) VALUES
('Laptop Asus ROG', 'Elektronik', 5, 15000000),
('Meja Kerja Kayu', 'Furnitur', 12, 750000),
('Mouse Logi', 'Elektronik', 25, 300000);