-- ATK Inventory Database Setup
-- Run: mysql -u root -p < database/setup_mysql.sql

CREATE DATABASE IF NOT EXISTS atk_inventory CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE atk_inventory;

-- Users table
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    timestamps TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Barangs table
CREATE TABLE barangs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_barang VARCHAR(255) NOT NULL,
    kategori VARCHAR(255) NOT NULL,
    stok INT DEFAULT 0,
    batas_minimum INT DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Riwayat Stok table
CREATE TABLE riwayat_stoks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    barang_id BIGINT UNSIGNED NOT NULL,
    jenis ENUM('masuk', 'keluar') NOT NULL,
    jumlah INT NOT NULL,
    keterangan VARCHAR(255) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (barang_id) REFERENCES barangs(id) ON DELETE CASCADE
);

-- Insert sample user
INSERT INTO users (name, email, password) VALUES ('Admin', 'admin@example.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/X4.qO.1BoWBPfGKHe');

-- Insert sample barangs
INSERT INTO barangs (nama_barang, kategori, stok, batas_minimum) VALUES
('Kertas A4', 'Kertas', 50, 10),
('Tinta Ballpoint Biru', 'Tinta', 3, 5),
('Stapler', 'Alat Tulis', 0, 2),
('Klip Kertas', 'Alat Tulis', 200, 50),
('Penggaris Besi', 'Alat Tulis', 2, 3);

-- Insert sample riwayat
INSERT INTO riwayat_stoks (barang_id, jenis, jumlah, keterangan) VALUES
(1, 'masuk', 50, 'Pembelian awal'),
(1, 'keluar', 5, 'Penggunaan kantor');