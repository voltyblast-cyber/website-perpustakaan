<?php

/**
 * User Model - Mengelola data pengguna
 *
 * Mendemonstrasikan:
 * - Inheritance (extends BaseModel)
 * - Polymorphism (override method create)
 * - Method khusus (authenticate, hashPassword)
 *
 * @package    App\Models
 * @author     Developer
 * @version    1.0.0
 */

namespace App\Models;

/**
 * Class User
 *
 * Model untuk tabel users.
 * Meng-extend BaseModel dan meng-override method create()
 * untuk menambahkan hashing password (polymorphism).
 */
class User extends BaseModel
{
    /** @var string Nama tabel */
    protected string $table = 'users';

    /** @var array Kolom yang boleh diisi */
    protected array $fillable = ['username', 'password', 'full_name', 'email', 'role'];

    /**
     * Menyimpan user baru dengan password yang di-hash.
     *
     * Override dari BaseModel::create() — Polymorphism.
     * Password di-hash sebelum disimpan ke database.
     *
     * @param array $data Data user baru
     * @return bool True jika berhasil
     */
    public function create(array $data): bool
    {
        // Polymorphism: menambahkan logic hashing sebelum save
        if (isset($data['password'])) {
            $data['password'] = $this->hashPassword($data['password']);
        }
        return parent::create($data);
    }

    /**
     * Autentikasi pengguna berdasarkan username dan password.
     *
     * Menggunakan control structure if/else (Req 3).
     *
     * @param string $username Username pengguna
     * @param string $password Password plain text
     * @return array|null Data user jika berhasil, null jika gagal
     */
    public function authenticate(string $username, string $password): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        // Control structure: percabangan if/else
        if ($user && password_verify($password, $user['password'])) {
            // Hapus password dari data yang dikembalikan
            unset($user['password']);
            return $user;
        } else {
            return null;
        }
    }

    /**
     * Hash password menggunakan bcrypt.
     *
     * @param string $password Password plain text
     * @return string Password yang sudah di-hash
     */
    private function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Mengambil user berdasarkan username.
     *
     * @param string $username Username yang dicari
     * @return array|null Data user atau null
     */
    public function getByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
