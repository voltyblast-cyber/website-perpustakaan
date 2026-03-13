<?php

/**
 * ReportController - Mengelola laporan dan export data
 *
 * Mendemonstrasikan:
 * - File I/O: export CSV, baca log (Req 6)
 * - Penggunaan interface Exportable (Req 7)
 *
 * @package    Controllers
 * @author     Developer
 * @version    1.0.0
 */

require_once ROOT_DIR . '/vendor/autoload.php';

use App\Models\Book;
use App\Models\Borrowing;
use App\Helpers\FileManager;

/**
 * Menampilkan halaman laporan.
 *
 * @return void
 */
function reportIndex(): void
{
    $fileManager = new FileManager();
    $logs = $fileManager->readLog();

    $borrowingModel = new Borrowing();
    $stats = $borrowingModel->getStatistics();

    include ROOT_DIR . '/views/reports/index.php';
}

/**
 * Export data buku ke CSV.
 *
 * Menggunakan interface Exportable (Req 7).
 * Menggunakan file I/O (Req 6).
 *
 * @return void
 */
function exportBooks(): void
{
    $bookModel = new Book();
    $filename = 'laporan_buku_' . date('Y-m-d_His') . '.csv';
    $filePath = $bookModel->exportToCsv($filename);

    // Log aktivitas
    $fileManager = new FileManager();
    $fileManager->writeLog("Export data buku ke file '{$filename}'.", 'INFO');

    // Download file
    if (file_exists($filePath)) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }

    $_SESSION['flash_error'] = 'Gagal mengexport data.';
    header('Location: ' . BASE_URL . '/index.php?page=reports');
    exit;
}
