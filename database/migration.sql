-- ============================================================
-- Sistem Manajemen Perpustakaan - Database Migration
-- Jalankan SQL ini di phpMyAdmin untuk membuat database & tabel
-- ============================================================

CREATE DATABASE IF NOT EXISTS db_perpustakaan
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE db_perpustakaan;

-- ------------------------------------------------------------
-- Tabel: users
-- Menyimpan data pengguna (admin/petugas)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) DEFAULT NULL,
    role ENUM('admin', 'petugas') NOT NULL DEFAULT 'petugas',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabel: categories
-- Kategori/genre buku
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabel: books
-- Data buku perpustakaan
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(150) NOT NULL,
    publisher VARCHAR(150) DEFAULT NULL,
    year_published YEAR DEFAULT NULL,
    isbn VARCHAR(20) DEFAULT NULL UNIQUE,
    stock INT NOT NULL DEFAULT 0,
    cover_image VARCHAR(255) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabel: members
-- Data anggota perpustakaan
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_code VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) DEFAULT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    address TEXT DEFAULT NULL,
    membership_date DATE NOT NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Tabel: borrowings
-- Data peminjaman buku
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS borrowings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    member_id INT NOT NULL,
    user_id INT NOT NULL,
    borrow_date DATE NOT NULL,
    due_date DATE NOT NULL,
    return_date DATE DEFAULT NULL,
    status ENUM('borrowed', 'returned', 'overdue') NOT NULL DEFAULT 'borrowed',
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Data Awal: Admin default & Kategori contoh
-- Username: admin | Password: admin123
-- ------------------------------------------------------------
INSERT INTO users (username, password, full_name, email, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@perpustakaan.com', 'admin'),
('petugas', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Petugas Perpustakaan', 'petugas@perpustakaan.com', 'petugas');

INSERT INTO categories (name, description) VALUES
('Fiksi', 'Novel, cerpen, dan karya fiksi lainnya'),
('Non-Fiksi', 'Buku ilmiah, biografi, dan karya non-fiksi'),
('Teknologi', 'Buku tentang teknologi dan komputer'),
('Sejarah', 'Buku tentang sejarah dunia dan Indonesia'),
('Sains', 'Buku tentang ilmu pengetahuan alam');

INSERT INTO books (category_id, title, author, publisher, year_published, isbn, stock, description) VALUES
(1, 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005, '9789793062792', 5, 'Novel tentang perjuangan anak-anak Belitung'),
(3, 'Clean Code', 'Robert C. Martin', 'Prentice Hall', 2008, '9780132350884', 3, 'A Handbook of Agile Software Craftsmanship'),
(2, 'Sapiens', 'Yuval Noah Harari', 'Harper', 2015, '9780062316097', 4, 'A Brief History of Humankind'),
(5, 'A Brief History of Time', 'Stephen Hawking', 'Bantam Books', 1988, '9780553380163', 2, 'Penjelasan tentang kosmologi'),
(4, 'Sejarah Indonesia Modern', 'M.C. Ricklefs', 'Gadjah Mada University Press', 2005, '9794202975', 3, 'Sejarah Indonesia dari abad ke-13');

INSERT INTO members (member_code, name, email, phone, address, membership_date, status) VALUES
('MBR-001', 'Budi Santoso', 'budi@email.com', '081234567890', 'Jl. Merdeka No. 10, Jakarta', '2025-01-15', 'active'),
('MBR-002', 'Siti Rahayu', 'siti@email.com', '081234567891', 'Jl. Sudirman No. 20, Bandung', '2025-02-20', 'active'),
('MBR-003', 'Andi Pratama', 'andi@email.com', '081234567892', 'Jl. Gatot Subroto No. 5, Surabaya', '2025-03-01', 'active');
