
CREATE DATABASE IF NOT EXISTS inventaris_db;
USE inventaris_db;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS produk;
DROP TABLE IF EXISTS kategori;
DROP TABLE IF EXISTS supplier;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE supplier (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL
);

CREATE TABLE produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    harga DECIMAL(12,2) NOT NULL DEFAULT 0,
    stok INT NOT NULL DEFAULT 0,
    kategori_id INT NOT NULL,
    supplier_id INT NOT NULL,
    CONSTRAINT fk_produk_kategori
        FOREIGN KEY (kategori_id) REFERENCES kategori(id),
    CONSTRAINT fk_produk_supplier
        FOREIGN KEY (supplier_id) REFERENCES supplier(id)
);

INSERT INTO kategori (nama) VALUES
('Serum'),
('Sunscreen'),
('Facial Wash'),
('Moisturizer'),
('Cleanser');

INSERT INTO supplier (nama) VALUES
('Beauty Care Indonesia'),
('PT Cantik Berseri'),
('CV Skincare Nusantara');

INSERT INTO produk
(nama, harga, stok, kategori_id, supplier_id)
VALUES
('Glow Serum Niacinamide', 85000, 20, 1, 1),
('Daily Sunscreen SPF 50', 95000, 25, 2, 2),
('Gentle Facial Wash', 55000, 30, 3, 3),
('Hydrating Moisturizer', 78000, 15, 4, 1),
('Micellar Cleansing Water', 45000, 22, 5, 2);