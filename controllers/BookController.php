<?php

/**
 * BookController - Mengelola operasi CRUD buku
 *
 * Controller untuk menampilkan daftar, detail, tambah, edit, dan hapus buku.
 *
 * @package    Controllers
 * @author     Developer
 * @version    1.0.0
 */

require_once ROOT_DIR . '/vendor/autoload.php';

use App\Models\Book;
use App\Models\Category;
use App\Helpers\Validator;
use App\Helpers\FileManager;

/**
 * Menampilkan daftar buku.
 *
 * Menggunakan array dan loop untuk render data (Req 3, 5).
 *
 * @return void
 */
function bookIndex(): void
{
    $bookModel = new Book();
    $search = $_GET['search'] ?? '';

    // Control structure: if/else (Req 3)
    if (!empty($search)) {
        $books = $bookModel->search($search);
    } else {
        $books = $bookModel->getAll();
    }

    include ROOT_DIR . '/views/books/index.php';
}

/**
 * Menampilkan detail buku.
 *
 * @return void
 */
function bookShow(): void
{
    $id = (int) ($_GET['id'] ?? 0);
    $bookModel = new Book();
    $book = $bookModel->getById($id);

    if (!$book) {
        $_SESSION['flash_error'] = 'Buku tidak ditemukan.';
        header('Location: ' . BASE_URL . '/index.php?page=books');
        exit;
    }

    include ROOT_DIR . '/views/books/show.php';
}

/**
 * Menampilkan form tambah buku dan memproses penyimpanan.
 *
 * @return void
 */
function bookCreate(): void
{
    $categoryModel = new Category();
    $categories = $categoryModel->getForSelect();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        include ROOT_DIR . '/views/books/create.php';
        return;
    }

    // Ambil data dari form (Req 2: input)
    $data = [
        'category_id'   => $_POST['category_id'] ?? '',
        'title'         => trim($_POST['title'] ?? ''),
        'author'        => trim($_POST['author'] ?? ''),
        'publisher'     => trim($_POST['publisher'] ?? ''),
        'year_published' => $_POST['year_published'] ?? '',
        'isbn'          => trim($_POST['isbn'] ?? ''),
        'stock'         => (int) ($_POST['stock'] ?? 0),
        'description'   => trim($_POST['description'] ?? ''),
    ];

    // Validasi (Req 4: fungsi)
    $validator = new Validator();
    $isValid = $validator->validate($data, [
        'title'       => 'required|min:3|max:255',
        'author'      => 'required|min:3|max:150',
        'category_id' => 'required|numeric',
        'stock'       => 'required|numeric',
    ]);

    if (!$isValid) {
        $errors = $validator->getErrors();
        include ROOT_DIR . '/views/books/create.php';
        return;
    }

    // Upload cover jika ada (Req 6: file I/O)
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['size'] > 0) {
        $fileManager = new FileManager();
        $uploadResult = $fileManager->upload($_FILES['cover_image'], 'covers');

        if ($uploadResult['success']) {
            $data['cover_image'] = $uploadResult['filename'];
        } else {
            $errors = ['cover_image' => [$uploadResult['error']]];
            include ROOT_DIR . '/views/books/create.php';
            return;
        }
    }

    // Simpan ke database (Req 10)
    $bookModel = new Book();
    if ($bookModel->create($data)) {
        // Log aktivitas (Req 6)
        $fileManager = new FileManager();
        $fileManager->writeLog("Buku '{$data['title']}' berhasil ditambahkan.", 'INFO');

        $_SESSION['flash_success'] = 'Buku berhasil ditambahkan.';
        header('Location: ' . BASE_URL . '/index.php?page=books');
        exit;
    } else {
        $errors = ['general' => ['Gagal menyimpan buku.']];
        include ROOT_DIR . '/views/books/create.php';
    }
}

/**
 * Menampilkan form edit buku dan memproses update.
 *
 * @return void
 */
function bookEdit(): void
{
    $id = (int) ($_GET['id'] ?? 0);
    $bookModel = new Book();
    $book = $bookModel->getById($id);

    if (!$book) {
        $_SESSION['flash_error'] = 'Buku tidak ditemukan.';
        header('Location: ' . BASE_URL . '/index.php?page=books');
        exit;
    }

    $categoryModel = new Category();
    $categories = $categoryModel->getForSelect();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        include ROOT_DIR . '/views/books/edit.php';
        return;
    }

    $data = [
        'category_id'   => $_POST['category_id'] ?? '',
        'title'         => trim($_POST['title'] ?? ''),
        'author'        => trim($_POST['author'] ?? ''),
        'publisher'     => trim($_POST['publisher'] ?? ''),
        'year_published' => $_POST['year_published'] ?? '',
        'isbn'          => trim($_POST['isbn'] ?? ''),
        'stock'         => (int) ($_POST['stock'] ?? 0),
        'description'   => trim($_POST['description'] ?? ''),
    ];

    $validator = new Validator();
    $isValid = $validator->validate($data, [
        'title'       => 'required|min:3|max:255',
        'author'      => 'required|min:3|max:150',
        'category_id' => 'required|numeric',
        'stock'       => 'required|numeric',
    ]);

    if (!$isValid) {
        $errors = $validator->getErrors();
        include ROOT_DIR . '/views/books/edit.php';
        return;
    }

    // Upload cover baru jika ada
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['size'] > 0) {
        $fileManager = new FileManager();
        $uploadResult = $fileManager->upload($_FILES['cover_image'], 'covers');

        if ($uploadResult['success']) {
            // Hapus cover lama
            if (!empty($book['cover_image'])) {
                $fileManager->deleteFile($book['cover_image']);
            }
            $data['cover_image'] = $uploadResult['filename'];
        }
    }

    if ($bookModel->update($id, $data)) {
        $fileManager = new FileManager();
        $fileManager->writeLog("Buku '{$data['title']}' (ID: {$id}) berhasil diperbarui.", 'INFO');

        $_SESSION['flash_success'] = 'Buku berhasil diperbarui.';
        header('Location: ' . BASE_URL . '/index.php?page=books');
        exit;
    } else {
        $errors = ['general' => ['Gagal memperbarui buku.']];
        include ROOT_DIR . '/views/books/edit.php';
    }
}

/**
 * Menghapus buku.
 *
 * @return void
 */
function bookDelete(): void
{
    $id = (int) ($_GET['id'] ?? 0);
    $bookModel = new Book();
    $book = $bookModel->getById($id);

    if ($book) {
        // Hapus cover file
        if (!empty($book['cover_image'])) {
            $fileManager = new FileManager();
            $fileManager->deleteFile($book['cover_image']);
        }

        if ($bookModel->delete($id)) {
            $fileManager = new FileManager();
            $fileManager->writeLog("Buku '{$book['title']}' (ID: {$id}) dihapus.", 'WARNING');
            $_SESSION['flash_success'] = 'Buku berhasil dihapus.';
        } else {
            $_SESSION['flash_error'] = 'Gagal menghapus buku. Mungkin masih ada peminjaman aktif.';
        }
    }

    header('Location: ' . BASE_URL . '/index.php?page=books');
    exit;
}
