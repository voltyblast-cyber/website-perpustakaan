<?php

/**
 * CrudInterface - Interface untuk operasi CRUD
 *
 * Interface ini mendefinisikan kontrak untuk operasi
 * Create, Read, Update, Delete pada semua model.
 *
 * @package    App\Interfaces
 * @author     Developer
 * @version    1.0.0
 */

namespace App\Interfaces;

/**
 * Interface CrudInterface
 *
 * Setiap model yang memerlukan operasi CRUD
 * harus mengimplementasikan interface ini.
 */
interface CrudInterface
{
    /**
     * Mengambil semua data dari tabel.
     *
     * @return array Daftar semua record
     */
    public function getAll(): array;

    /**
     * Mengambil satu data berdasarkan ID.
     *
     * @param int $id ID record yang dicari
     * @return array|null Data record atau null jika tidak ditemukan
     */
    public function getById(int $id): ?array;

    /**
     * Menyimpan data baru ke tabel.
     *
     * @param array $data Data yang akan disimpan
     * @return bool True jika berhasil, false jika gagal
     */
    public function create(array $data): bool;

    /**
     * Memperbarui data berdasarkan ID.
     *
     * @param int   $id   ID record yang akan diperbarui
     * @param array $data Data baru
     * @return bool True jika berhasil, false jika gagal
     */
    public function update(int $id, array $data): bool;

    /**
     * Menghapus data berdasarkan ID.
     *
     * @param int $id ID record yang akan dihapus
     * @return bool True jika berhasil, false jika gagal
     */
    public function delete(int $id): bool;
}
