<?php

/**
 * Konfigurasi Database
 *
 * Menyediakan koneksi PDO ke database MySQL.
 * Menggunakan Singleton pattern untuk efisiensi koneksi.
 *
 * @package App\Config
 */

/**
 * Class Database
 *
 * Mengelola koneksi database menggunakan PDO.
 * Mengimplementasikan Singleton pattern.
 */
class Database
{
    /** @var string Host database */
    private const DB_HOST = 'sql105.infinityfree.com';

    /** @var string Nama database */
    private const DB_NAME = 'if0_41379132_perpustakaan';

    /** @var string Username database */
    private const DB_USER = 'if0_41379132';

    /** @var string Password database */
    private const DB_PASS = 'w6MIiX3gNF';

    /** @var PDO|null Instance koneksi PDO */
    private static ?PDO $instance = null;

    /**
     * Mendapatkan instance koneksi PDO (Singleton).
     *
     * @return PDO Instance koneksi database
     * @throws PDOException Jika koneksi gagal
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            try {
                $dsn = "mysql:host=" . self::DB_HOST . ";dbname=" . self::DB_NAME . ";charset=utf8mb4";
                self::$instance = new PDO($dsn, self::DB_USER, self::DB_PASS, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                die("Koneksi database gagal: " . $e->getMessage());
            }
        }
        return self::$instance;
    }

    /**
     * Mencegah instansiasi langsung (Singleton).
     */
    private function __construct()
    {
    }
}
