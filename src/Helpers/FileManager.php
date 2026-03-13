<?php

/**
 * FileManager - Mengelola operasi file
 *
 * Helper class untuk upload file, membaca/menulis log,
 * dan operasi file lainnya.
 *
 * Mendemonstrasikan:
 * - Namespace terpisah (App\Helpers) — Req 8
 * - File I/O (read/write) — Req 6
 * - Fungsi/prosedur — Req 4
 *
 * @package    App\Helpers
 * @author     Developer
 * @version    1.0.0
 */

namespace App\Helpers;

/**
 * Class FileManager
 *
 * Menyediakan utility untuk operasi file system.
 */
class FileManager
{
    /** @var string Direktori upload */
    private string $uploadDir;

    /** @var string Direktori log */
    private string $logDir;

    /** @var array Ekstensi file yang diizinkan */
    private array $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];

    /** @var int Ukuran maksimum file (5MB) */
    private int $maxFileSize = 5242880;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->uploadDir = UPLOAD_DIR;
        $this->logDir    = LOG_DIR;

        // Buat direktori jika belum ada
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0755, true);
        }
    }

    /**
     * Upload file ke server.
     *
     * Menggunakan if/else untuk validasi (Req 3).
     * Menggunakan array untuk file info (Req 5).
     *
     * @param array  $file        Data file dari $_FILES
     * @param string $subDir      Sub-direktori tujuan
     * @return array ['success' => bool, 'filename' => string, 'error' => string]
     */
    public function upload(array $file, string $subDir = 'covers'): array
    {
        // Array untuk menyimpan hasil (Req 5)
        $result = [
            'success'  => false,
            'filename' => '',
            'error'    => '',
        ];

        // Validasi: cek apakah file ada
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            $result['error'] = 'Tidak ada file yang diupload.';
            return $result;
        }

        // Validasi: cek ukuran file (if/else - Req 3)
        if ($file['size'] > $this->maxFileSize) {
            $result['error'] = 'Ukuran file terlalu besar. Maksimal 5MB.';
            return $result;
        }

        // Validasi: cek ekstensi file
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedExtensions)) {
            $result['error'] = 'Tipe file tidak diizinkan. Format: ' . implode(', ', $this->allowedExtensions);
            return $result;
        }

        // Generate nama file unik
        $filename = uniqid('file_') . '.' . $extension;
        $targetDir = $this->uploadDir . '/' . $subDir;

        // Buat sub-direktori jika belum ada
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $targetPath = $targetDir . '/' . $filename;

        // Upload file (Req 6: menyimpan data ke media penyimpanan)
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $result['success']  = true;
            $result['filename'] = $subDir . '/' . $filename;
        } else {
            $result['error'] = 'Gagal mengupload file.';
        }

        return $result;
    }

    /**
     * Menghapus file dari server.
     *
     * @param string $filename Path relatif file
     * @return bool True jika berhasil
     */
    public function deleteFile(string $filename): bool
    {
        $filePath = $this->uploadDir . '/' . $filename;
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    /**
     * Menulis log aktivitas ke file.
     *
     * Req 6: Menyimpan data ke media penyimpanan.
     *
     * @param string $message Pesan log
     * @param string $level   Level log (INFO, WARNING, ERROR)
     * @return void
     */
    public function writeLog(string $message, string $level = 'INFO'): void
    {
        $logFile = $this->logDir . '/activity_' . date('Y-m-d') . '.log';
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[{$timestamp}] [{$level}] {$message}" . PHP_EOL;

        // Menulis ke file (Req 6)
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }

    /**
     * Membaca log aktivitas dari file.
     *
     * Req 6: Membaca data dari media penyimpanan.
     *
     * @param string|null $date Tanggal log yang ingin dibaca (Y-m-d)
     * @return array Array berisi baris-baris log
     */
    public function readLog(?string $date = null): array
    {
        if ($date === null) {
            $date = date('Y-m-d');
        }

        $logFile = $this->logDir . '/activity_' . $date . '.log';

        // Membaca dari file (Req 6)
        if (file_exists($logFile)) {
            $content = file_get_contents($logFile);
            $lines = explode(PHP_EOL, trim($content));
            // Balik urutan agar terbaru di atas
            return array_reverse($lines);
        }

        return [];
    }
}
