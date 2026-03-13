<?php

/**
 * BorrowingController - Mengelola operasi peminjaman buku
 *
 * @package    Controllers
 * @author     Developer
 * @version    1.0.0
 */

require_once ROOT_DIR . '/vendor/autoload.php';

use App\Models\Borrowing;
use App\Models\Book;
use App\Models\Member;
use App\Helpers\Validator;
use App\Helpers\FileManager;

/**
 * Menampilkan daftar peminjaman.
 *
 * @return void
 */
function borrowingIndex(): void
{
    $borrowingModel = new Borrowing();
    $borrowings = $borrowingModel->getAll();
    include ROOT_DIR . '/views/borrowings/index.php';
}

/**
 * Menampilkan form peminjaman baru dan memproses penyimpanan.
 *
 * @return void
 */
function borrowingCreate(): void
{
    $bookModel = new Book();
    $memberModel = new Member();

    // Array untuk dropdown (Req 5)
    $books = $bookModel->getAll();
    $members = $memberModel->getForSelect();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        include ROOT_DIR . '/views/borrowings/create.php';
        return;
    }

    $data = [
        'book_id'     => (int) ($_POST['book_id'] ?? 0),
        'member_id'   => (int) ($_POST['member_id'] ?? 0),
        'user_id'     => $_SESSION['user_id'],
        'borrow_date' => $_POST['borrow_date'] ?? date('Y-m-d'),
        'due_date'    => $_POST['due_date'] ?? '',
        'notes'       => trim($_POST['notes'] ?? ''),
    ];

    // Validasi
    $validator = new Validator();
    $isValid = $validator->validate($data, [
        'book_id'     => 'required|numeric',
        'member_id'   => 'required|numeric',
        'borrow_date' => 'required|date',
        'due_date'    => 'required|date',
    ]);

    if (!$isValid) {
        $errors = $validator->getErrors();
        include ROOT_DIR . '/views/borrowings/create.php';
        return;
    }

    // Cek stok buku
    $book = $bookModel->getById($data['book_id']);
    if (!$book || $book['stock'] <= 0) {
        $errors = ['book_id' => ['Stok buku habis atau buku tidak ditemukan.']];
        include ROOT_DIR . '/views/borrowings/create.php';
        return;
    }

    $borrowingModel = new Borrowing();
    if ($borrowingModel->create($data)) {
        $fileManager = new FileManager();
        $fileManager->writeLog(
            "Peminjaman buku '{$book['title']}' oleh member ID {$data['member_id']}.",
            'INFO'
        );

        $_SESSION['flash_success'] = 'Peminjaman berhasil dicatat.';
        header('Location: ' . BASE_URL . '/index.php?page=borrowings');
        exit;
    } else {
        $errors = ['general' => ['Gagal menyimpan peminjaman.']];
        include ROOT_DIR . '/views/borrowings/create.php';
    }
}

/**
 * Proses pengembalian buku.
 *
 * @return void
 */
function borrowingReturn(): void
{
    $id = (int) ($_GET['id'] ?? 0);
    $borrowingModel = new Borrowing();

    if ($borrowingModel->returnBook($id)) {
        $fileManager = new FileManager();
        $fileManager->writeLog("Pengembalian buku untuk peminjaman ID {$id}.", 'INFO');

        $_SESSION['flash_success'] = 'Buku berhasil dikembalikan.';
    } else {
        $_SESSION['flash_error'] = 'Gagal memproses pengembalian.';
    }

    header('Location: ' . BASE_URL . '/index.php?page=borrowings');
    exit;
}
