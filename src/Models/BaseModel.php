<?php

/**
 * BaseModel - Abstract class dasar untuk semua model
 *
 * Class abstrak ini menyediakan implementasi dasar operasi CRUD
 * menggunakan PDO. Semua model harus meng-extend class ini.
 *
 * Mendemonstrasikan:
 * - Abstract class
 * - Hak akses (public, protected, private)
 * - Properties
 * - Interface implementation
 *
 * @package    App\Models
 * @author     Developer
 * @version    1.0.0
 */

namespace App\Models;

use App\Interfaces\CrudInterface;
use PDO;

/**
 * Abstract Class BaseModel
 *
 * Menyediakan operasi CRUD dasar yang dapat di-override
 * oleh child class untuk kebutuhan spesifik (polymorphism).
 */
abstract class BaseModel implements CrudInterface
{
    /**
     * Instance koneksi PDO.
     *
     * @var PDO Koneksi database (protected: hanya bisa diakses oleh child class)
     */
    protected PDO $db;

    /**
     * Nama tabel database.
     *
     * @var string Nama tabel (protected: di-set oleh child class)
     */
    protected string $table;

    /**
     * Nama primary key.
     *
     * @var string Primary key column (protected: bisa di-override)
     */
    protected string $primaryKey = 'id';

    /**
     * Kolom yang boleh diisi (fillable).
     *
     * @var array Daftar kolom yang diizinkan untuk insert/update
     */
    protected array $fillable = [];

    /**
     * Jumlah record per halaman untuk pagination.
     *
     * @var int Items per page (private: hanya di class ini)
     */
    private int $perPage = 10;

    /**
     * Constructor - Inisialisasi koneksi database.
     *
     * @return void
     */
    public function __construct()
    {
        $this->db = \Database::getConnection();
    }

    /**
     * Mengambil semua data dari tabel.
     *
     * Implementasi default dari CrudInterface::getAll().
     * Bisa di-override oleh child class (polymorphism).
     *
     * @return array Daftar semua record
     */
    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    /**
     * Mengambil satu data berdasarkan ID.
     *
     * @param int $id ID record
     * @return array|null Data record atau null
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Menyimpan data baru ke tabel.
     *
     * Menggunakan kolom fillable untuk keamanan.
     * Menggunakan array_intersect_key dan array_map (Req 5: Array).
     *
     * @param array $data Data yang akan disimpan
     * @return bool True jika berhasil
     */
    public function create(array $data): bool
    {
        // Filter hanya kolom yang diizinkan (menggunakan array)
        $filtered = array_intersect_key($data, array_flip($this->fillable));
        $columns  = implode(', ', array_keys($filtered));
        $placeholders = ':' . implode(', :', array_keys($filtered));

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($filtered);
    }

    /**
     * Memperbarui data berdasarkan ID.
     *
     * @param int   $id   ID record
     * @param array $data Data baru
     * @return bool True jika berhasil
     */
    public function update(int $id, array $data): bool
    {
        $filtered = array_intersect_key($data, array_flip($this->fillable));
        $setParts = array_map(function ($key) {
            return "{$key} = :{$key}";
        }, array_keys($filtered));
        $setClause = implode(', ', $setParts);

        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :id";
        $filtered['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($filtered);
    }

    /**
     * Menghapus data berdasarkan ID.
     *
     * @param int $id ID record
     * @return bool True jika berhasil
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Menghitung total record di tabel.
     *
     * @return int Jumlah total record
     */
    public function count(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM {$this->table}");
        $result = $stmt->fetch();
        return (int) $result['total'];
    }

    /**
     * Mengambil data dengan pagination.
     *
     * Menggunakan control structure: if/else, kalkulasi offset.
     *
     * @param int $page Nomor halaman (mulai dari 1)
     * @return array Array berisi 'data' dan 'pagination' info
     */
    public function paginate(int $page = 1): array
    {
        // Kontrol percabangan: validasi halaman
        if ($page < 1) {
            $page = 1;
        }

        $total  = $this->count();
        $offset = ($page - 1) * $this->perPage;
        $totalPages = (int) ceil($total / $this->perPage);

        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} ORDER BY id DESC LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit', $this->perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'data'       => $stmt->fetchAll(),
            'total'      => $total,
            'page'       => $page,
            'perPage'    => $this->perPage,
            'totalPages' => $totalPages,
        ];
    }

    /**
     * Getter untuk perPage (private property).
     *
     * @return int Items per page
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * Setter untuk perPage (private property).
     *
     * @param int $perPage Items per page
     * @return void
     */
    public function setPerPage(int $perPage): void
    {
        if ($perPage > 0 && $perPage <= 100) {
            $this->perPage = $perPage;
        }
    }

    /**
     * Mendapatkan ID terakhir yang di-insert.
     *
     * @return string ID terakhir
     */
    public function getLastInsertId(): string
    {
        return $this->db->lastInsertId();
    }
}
