<?php

/**
 * Konfigurasi Umum Aplikasi
 *
 * File ini berisi konstanta dan pengaturan global
 * untuk Sistem Manajemen Perpustakaan.
 *
 * @package App\Config
 */

/** Nama Aplikasi */
define('APP_NAME', 'Sistem Manajemen Perpustakaan');

/** Base URL aplikasi */
define('BASE_URL', '/web_sertifikasi/public');

/** Direktori root project */
define('ROOT_DIR', dirname(__DIR__));

/** Direktori upload */
define('UPLOAD_DIR', ROOT_DIR . '/public/uploads');

/** Direktori log */
define('LOG_DIR', ROOT_DIR . '/storage/logs');

/** Durasi peminjaman default (hari) */
define('DEFAULT_BORROW_DAYS', 7);

/** Zona waktu */
date_default_timezone_set('Asia/Jakarta');

/** Mulai session jika belum ada */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
