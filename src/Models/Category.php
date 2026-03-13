<?php

/**
 * Category Model - Mengelola data kategori buku
 *
 * Mendemonstrasikan:
 * - Inheritance (extends BaseModel)
 *
 * @package    App\Models
 * @author     Developer
 * @version    1.0.0
 */

namespace App\Models;

/**
 * Class Category
 *
 * Model untuk tabel categories.
 * Mewarisi semua method CRUD dari BaseModel (inheritance).
 */
class Category extends BaseModel
{
    /** @var string Nama tabel */
    protected string $table = 'categories';

    /** @var array Kolom yang boleh diisi */
    protected array $fillable = ['name', 'description'];

    /**
     * Mengambil semua kategori dengan jumlah buku.
     *
     * Override getAll() untuk menambahkan COUNT buku (polymorphism).
     *
     * @return array Kategori beserta jumlah buku
     */
    public function getAll(): array
    {
        $sql = "SELECT c.*, COUNT(b.id) as book_count 
                FROM categories c 
                LEFT JOIN books b ON c.id = b.category_id 
                GROUP BY c.id 
                ORDER BY c.name ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Mengambil daftar kategori untuk dropdown select.
     *
     * @return array Array [id => name] untuk form select
     */
    public function getForSelect(): array
    {
        $stmt = $this->db->query("SELECT id, name FROM categories ORDER BY name ASC");
        $categories = [];
        // Loop while untuk membangun array (Req 3: loop, Req 5: array)
        while ($row = $stmt->fetch()) {
            $categories[$row['id']] = $row['name'];
        }
        return $categories;
    }
}
