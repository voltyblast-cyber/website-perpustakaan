<?php

/**
 * Entry Point / Router Utama
 *
 * File ini adalah titik masuk utama aplikasi.
 * Menangani routing berdasarkan parameter 'page' dan 'action'.
 *
 * Mendemonstrasikan:
 * - Control structure: switch/case (Req 3)
 * - Penggunaan Composer autoload (Req 9)
 *
 * @package    Public
 * @author     Developer
 * @version    1.0.0
 */

// Tampilkan error untuk debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load konfigurasi
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';

// Load Composer autoload (Req 9: external library)
if (file_exists(ROOT_DIR . '/vendor/autoload.php')) {
    require_once ROOT_DIR . '/vendor/autoload.php';
}

// Load controllers
require_once ROOT_DIR . '/controllers/AuthController.php';
require_once ROOT_DIR . '/controllers/BookController.php';
require_once ROOT_DIR . '/controllers/MemberController.php';
require_once ROOT_DIR . '/controllers/BorrowingController.php';
require_once ROOT_DIR . '/controllers/CategoryController.php';
require_once ROOT_DIR . '/controllers/ReportController.php';

// Ambil parameter routing
$page   = $_GET['page'] ?? 'login';
$action = $_GET['action'] ?? 'index';

// Cek autentikasi (kecuali halaman login)
if ($page !== 'login' && !isLoggedIn()) {
    header('Location: ' . BASE_URL . '/index.php?page=login');
    exit;
}

// Redirect ke dashboard jika sudah login tapi akses login page
if ($page === 'login' && isLoggedIn()) {
    header('Location: ' . BASE_URL . '/index.php?page=dashboard');
    exit;
}

/**
 * Routing menggunakan switch/case (Req 3: control structure).
 *
 * Setiap 'page' mengarah ke controller yang sesuai.
 * Setiap 'action' mengarah ke fungsi spesifik di controller.
 */
switch ($page) {
    case 'login':
        handleLogin();
        break;

    case 'logout':
        handleLogout();
        break;

    case 'dashboard':
        // Load dashboard dengan statistik
        require_once ROOT_DIR . '/vendor/autoload.php';
        $bookModel = new \App\Models\Book();
        $memberModel = new \App\Models\Member();
        $borrowingModel = new \App\Models\Borrowing();
        $categoryModel = new \App\Models\Category();

        // Array statistik dashboard (Req 5)
        $stats = [
            'total_books'      => $bookModel->count(),
            'total_members'    => $memberModel->count(),
            'total_categories' => $categoryModel->count(),
            'borrowing_stats'  => $borrowingModel->getStatistics(),
        ];

        $recentBorrowings = $borrowingModel->getActiveBorrowings();
        include ROOT_DIR . '/views/dashboard.php';
        break;

    case 'books':
        // Routing untuk buku
        switch ($action) {
            case 'create':
                bookCreate();
                break;
            case 'edit':
                bookEdit();
                break;
            case 'show':
                bookShow();
                break;
            case 'delete':
                bookDelete();
                break;
            default:
                bookIndex();
                break;
        }
        break;

    case 'members':
        switch ($action) {
            case 'create':
                memberCreate();
                break;
            case 'edit':
                memberEdit();
                break;
            case 'delete':
                memberDelete();
                break;
            default:
                memberIndex();
                break;
        }
        break;

    case 'borrowings':
        switch ($action) {
            case 'create':
                borrowingCreate();
                break;
            case 'return':
                borrowingReturn();
                break;
            default:
                borrowingIndex();
                break;
        }
        break;

    case 'categories':
        switch ($action) {
            case 'create':
                categoryCreate();
                break;
            case 'edit':
                categoryEdit();
                break;
            case 'delete':
                categoryDelete();
                break;
            default:
                categoryIndex();
                break;
        }
        break;

    case 'reports':
        switch ($action) {
            case 'export-books':
                exportBooks();
                break;
            default:
                reportIndex();
                break;
        }
        break;

    default:
        // Halaman tidak ditemukan
        include ROOT_DIR . '/views/dashboard.php';
        break;
}
