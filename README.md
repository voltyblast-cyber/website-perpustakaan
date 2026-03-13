# website-perpustakaan

Website Perpustakaan adalah sistem berbasis web yang digunakan untuk mengelola data buku, anggota, dan peminjaman di perpustakaan. Sistem ini dibuat menggunakan PHP dan MySQL dengan menerapkan konsep Object-Oriented Programming (OOP) serta standar pengembangan modern.

Repository ini juga memenuhi berbagai aspek penilaian dalam implementasi pemrograman berbasis web.

---

## 1. Coding Guidelines (PSR-1 / PSR-12)

Screenshot dapat diambil pada file berikut:

src/Models/BaseModel.php

File ini menunjukkan:
- penggunaan namespace
- penggunaan use
- struktur class yang rapi
- indentasi sesuai standar PSR

---

## 2. Interface Input dan Output

### Input (Form)

Login form:

views/auth/login.php  
Baris 33-47

Form tambah buku:

views/books/create.php  
Baris 24-74

### Output (Menampilkan Data)

Dashboard statistik:

views/dashboard.php  
Baris 19-48

Daftar buku:

views/books/index.php  
Baris 48-61

Menampilkan data buku dalam tabel HTML.

---

## 3. Tipe Data, Percabangan, dan Pengulangan

### Percabangan (If / Else)

Autentikasi user:

controllers/AuthController.php  
Baris 54-70

Status keterlambatan peminjaman:

src/Models/Borrowing.php  
Baris 61-75

### Pengulangan

Do-While

src/Models/Member.php  
Baris 38-55

Digunakan untuk membuat kode member otomatis.

For Loop

src/Models/Borrowing.php  
Baris 58

Foreach

src/Helpers/Validator.php  
Baris 38-42

While

src/Models/Category.php  
Baris 45-47

Switch Case

public/index.php  
Baris 57-170

Digunakan untuk menentukan halaman berdasarkan parameter page.

---

## 4. Penggunaan Prosedur / Fungsi / Method

Controller methods:

controllers/BookController.php

Contoh fungsi:
- bookIndex()
- bookCreate()

Helper method:

src/Helpers/Validator.php  
Baris 55

Contoh:

private function applyRule()

---

## 5. Penggunaan Array

Array statistik:

src/Models/Borrowing.php  
Baris 160-165

$stats = ['total' => 0, 'borrowed' => 0, ...]

Array input form:

controllers/BookController.php  
Baris 59-68

Return array pada model:

src/Models/BaseModel.php  
Baris 50-51

---

## 6. File I/O (Menyimpan dan Membaca File)

Log File

src/Helpers/FileManager.php  
Baris 121-143

Menggunakan:
- file_put_contents()
- file_get_contents()

Export CSV

src/Models/Book.php  
Baris 128-148

Menggunakan:
- fopen()
- fputcsv()
- fclose()

---

## 7. Konsep OOP

Interface

src/Interfaces/CrudInterface.php

Abstract Class dan Hak Akses

src/Models/BaseModel.php

Contoh:
protected PDO $db;  
private int $perPage;

Inheritance dan Polymorphism

src/Models/User.php  
Baris 19-38

class User extends BaseModel

Override method:

parent::create()

Method Overloading (Magic Method)

src/Models/Book.php  
Baris 82-106

Menggunakan:

__call()

Untuk pencarian fleksibel seperti:

findByTitle()

---

## 8. Namespace / Package

Namespace utama:

src/Models/Book.php  
namespace App\Models

Namespace helper:

src/Helpers/FileManager.php  
namespace App\Helpers

Namespace interface:

src/Interfaces/CrudInterface.php  
namespace App\Interfaces

---

## 9. External Library

Library yang digunakan:

nesbot/carbon

Deklarasi dependency:

composer.json

Implementasi:

src/Models/Borrowing.php

Contoh penggunaan:

use Carbon\Carbon;  
$now = Carbon::now();

---

## 10. Penggunaan Database

Koneksi database:

config/database.php

Menggunakan:

new PDO(...)

Query database:

src/Models/Book.php  
Baris 45-51

---

## 11. Dokumentasi (PHPDoc)

Contoh dokumentasi:

src/Models/BaseModel.php

Menggunakan tag:
- @param
- @return
- @var
- @throws
- @package

---

## Catatan Tambahan

Untuk aspek keamanan web, sistem juga menggunakan:

- Prepared Statements untuk mencegah SQL Injection
- Password Hashing (bcrypt) untuk keamanan password

Contoh implementasi dapat ditemukan pada:

src/Models/BaseModel.php  
src/Models/User.php
