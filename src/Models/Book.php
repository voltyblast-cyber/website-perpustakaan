<?php

/**
 * Book Model - Mengelola data buku perpustakaan
 *
 * Mendemonstrasikan:
 * - Inheritance (extends BaseModel)
 * - Polymorphism (override getAll)
 * - Interface implementation (Exportable)
 * - Overloading (__call magic method)
 * - Penggunaan external library (Carbon)
 *
 * @package    App\Models
 * @author     Developer
 * @version    1.0.0
 */

namespace App\Models;

use App\Interfaces\Exportable;
use Carbon\Carbon;

/**
 * Class Book
 *
 * Model untuk tabel books.
 * Mengimplementasikan Exportable untuk export data ke CSV.
 * Menggunakan __call() untuk method overloading.
 */
class Book extends BaseModel implements Exportable
{
    /** @var string Nama tabel */
    protected string $table = 'books';

    /** @var array Kolom yang boleh diisi */
    protected array $fillable = [
        'category_id', 'title', 'author', 'publisher',
        'year_published', 'isbn', 'stock', 'cover_image', 'description'
    ];

    /**
     * Mengambil semua buku dengan JOIN ke kategori.
     *
     * Override dari BaseModel::getAll() — Polymorphism.
     * Menambahkan nama kategori ke setiap record buku.
     *
     * @return array Daftar buku dengan nama kategori
     */
    public function getAll(): array
    {
        $sql = "SELECT b.*, c.name as category_name 
                FROM books b 
                LEFT JOIN categories c ON b.category_id = c.id 
                ORDER BY b.id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Mengambil detail buku berdasarkan ID dengan info kategori.
     *
     * Override dari BaseModel::getById() — Polymorphism.
     *
     * @param int $id ID buku
     * @return array|null Data buku atau null
     */
    public function getById(int $id): ?array
    {
        $sql = "SELECT b.*, c.name as category_name 
                FROM books b 
                LEFT JOIN categories c ON b.category_id = c.id 
                WHERE b.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();

        if ($result) {
            // Menggunakan Carbon (external library) untuk format tanggal
            $result['created_at_formatted'] = Carbon::parse($result['created_at'])
                ->locale('id')
                ->translatedFormat('d F Y H:i');
        }

        return $result ?: null;
    }

    /**
     * Magic method __call untuk method overloading.
     *
     * Memungkinkan pemanggilan method dinamis seperti:
     * - findByTitle($title)
     * - findByAuthor($author)
     * - findByIsbn($isbn)
     *
     * Req 7: Overloading
     *
     * @param string $name      Nama method yang dipanggil
     * @param array  $arguments Argumen yang diberikan
     * @return mixed Hasil query atau null
     * @throws \BadMethodCallException Jika method tidak dikenali
     */
    public function __call(string $name, array $arguments)
    {
        // Overloading: dynamic method call berdasarkan nama
        if (str_starts_with($name, 'findBy')) {
            $column = strtolower(substr($name, 6));
            $validColumns = ['title', 'author', 'isbn', 'publisher'];

            if (in_array($column, $validColumns) && isset($arguments[0])) {
                $stmt = $this->db->prepare(
                    "SELECT b.*, c.name as category_name 
                     FROM books b 
                     LEFT JOIN categories c ON b.category_id = c.id 
                     WHERE b.{$column} LIKE :value"
                );
                $stmt->execute(['value' => '%' . $arguments[0] . '%']);
                return $stmt->fetchAll();
            }
        }

        throw new \BadMethodCallException("Method {$name} tidak ditemukan pada class Book.");
    }

    /**
     * Export data buku ke file CSV.
     *
     * Implementasi dari interface Exportable.
     * Menggunakan file I/O untuk menulis data (Req 6).
     * Menggunakan foreach loop (Req 3).
     * Menggunakan array untuk header dan data (Req 5).
     *
     * @param string $filename Nama file output
     * @return string Path file CSV yang dihasilkan
     */
    public function exportToCsv(string $filename): string
    {
        $books = $this->getAll();
        $filePath = ROOT_DIR . '/storage/exports/' . $filename;

        // Pastikan direktori ada
        $dir = dirname($filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Menulis ke file (Req 6: menyimpan data ke media penyimpanan)
        $file = fopen($filePath, 'w');

        // Header CSV (menggunakan array - Req 5)
        $headers = ['ID', 'Judul', 'Penulis', 'Penerbit', 'Tahun', 'ISBN', 'Stok', 'Kategori'];
        fputcsv($file, $headers);

        // Data buku (foreach loop - Req 3)
        foreach ($books as $book) {
            $row = [
                $book['id'],
                $book['title'],
                $book['author'],
                $book['publisher'] ?? '-',
                $book['year_published'] ?? '-',
                $book['isbn'] ?? '-',
                $book['stock'],
                $book['category_name'] ?? '-',
            ];
            fputcsv($file, $row);
        }

        fclose($file);
        return $filePath;
    }

    /**
     * Mencari buku berdasarkan keyword.
     *
     * @param string $keyword Keyword pencarian
     * @return array Daftar buku yang cocok
     */
    public function search(string $keyword): array
    {
        $sql = "SELECT b.*, c.name as category_name 
                FROM books b 
                LEFT JOIN categories c ON b.category_id = c.id 
                WHERE b.title LIKE :keyword 
                   OR b.author LIKE :keyword2 
                   OR b.isbn LIKE :keyword3
                ORDER BY b.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'keyword'  => "%{$keyword}%",
            'keyword2' => "%{$keyword}%",
            'keyword3' => "%{$keyword}%",
        ]);
        return $stmt->fetchAll();
    }

    /**
     * Mengambil buku berdasarkan kategori.
     *
     * @param int $categoryId ID kategori
     * @return array Daftar buku dalam kategori tersebut
     */
    public function getByCategory(int $categoryId): array
    {
        $sql = "SELECT b.*, c.name as category_name 
                FROM books b 
                LEFT JOIN categories c ON b.category_id = c.id 
                WHERE b.category_id = :category_id 
                ORDER BY b.title ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['category_id' => $categoryId]);
        return $stmt->fetchAll();
    }

    /**
     * Mengurangi stok buku saat dipinjam.
     *
     * @param int $bookId ID buku
     * @return bool True jika berhasil
     */
    public function decrementStock(int $bookId): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE books SET stock = stock - 1 WHERE id = :id AND stock > 0"
        );
        return $stmt->execute(['id' => $bookId]);
    }

    /**
     * Menambah stok buku saat dikembalikan.
     *
     * @param int $bookId ID buku
     * @return bool True jika berhasil
     */
    public function incrementStock(int $bookId): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE books SET stock = stock + 1 WHERE id = :id"
        );
        return $stmt->execute(['id' => $bookId]);
    }
}
