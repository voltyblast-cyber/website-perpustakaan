<?php

/**
 * CategoryController - Mengelola operasi CRUD kategori buku
 *
 * @package    Controllers
 * @author     Developer
 * @version    1.0.0
 */

require_once ROOT_DIR . '/vendor/autoload.php';

use App\Models\Category;
use App\Helpers\Validator;
use App\Helpers\FileManager;

/**
 * Menampilkan daftar kategori.
 *
 * @return void
 */
function categoryIndex(): void
{
    $categoryModel = new Category();
    $categories = $categoryModel->getAll();
    include ROOT_DIR . '/views/categories/index.php';
}

/**
 * Menampilkan form tambah kategori dan memproses penyimpanan.
 *
 * @return void
 */
function categoryCreate(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        include ROOT_DIR . '/views/categories/create.php';
        return;
    }

    $data = [
        'name'        => trim($_POST['name'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
    ];

    $validator = new Validator();
    $isValid = $validator->validate($data, [
        'name' => 'required|min:2|max:100',
    ]);

    if (!$isValid) {
        $errors = $validator->getErrors();
        include ROOT_DIR . '/views/categories/create.php';
        return;
    }

    $categoryModel = new Category();
    if ($categoryModel->create($data)) {
        $fileManager = new FileManager();
        $fileManager->writeLog("Kategori '{$data['name']}' berhasil ditambahkan.", 'INFO');

        $_SESSION['flash_success'] = 'Kategori berhasil ditambahkan.';
        header('Location: ' . BASE_URL . '/index.php?page=categories');
        exit;
    } else {
        $errors = ['general' => ['Gagal menyimpan kategori.']];
        include ROOT_DIR . '/views/categories/create.php';
    }
}

/**
 * Menampilkan form edit kategori dan memproses update.
 *
 * @return void
 */
function categoryEdit(): void
{
    $id = (int) ($_GET['id'] ?? 0);
    $categoryModel = new Category();
    $category = $categoryModel->getById($id);

    if (!$category) {
        $_SESSION['flash_error'] = 'Kategori tidak ditemukan.';
        header('Location: ' . BASE_URL . '/index.php?page=categories');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        include ROOT_DIR . '/views/categories/edit.php';
        return;
    }

    $data = [
        'name'        => trim($_POST['name'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
    ];

    $validator = new Validator();
    $isValid = $validator->validate($data, [
        'name' => 'required|min:2|max:100',
    ]);

    if (!$isValid) {
        $errors = $validator->getErrors();
        include ROOT_DIR . '/views/categories/edit.php';
        return;
    }

    if ($categoryModel->update($id, $data)) {
        $_SESSION['flash_success'] = 'Kategori berhasil diperbarui.';
        header('Location: ' . BASE_URL . '/index.php?page=categories');
        exit;
    } else {
        $errors = ['general' => ['Gagal memperbarui kategori.']];
        include ROOT_DIR . '/views/categories/edit.php';
    }
}

/**
 * Menghapus kategori.
 *
 * @return void
 */
function categoryDelete(): void
{
    $id = (int) ($_GET['id'] ?? 0);
    $categoryModel = new Category();

    if ($categoryModel->delete($id)) {
        $_SESSION['flash_success'] = 'Kategori berhasil dihapus.';
    } else {
        $_SESSION['flash_error'] = 'Gagal menghapus kategori. Mungkin masih ada buku terkait.';
    }

    header('Location: ' . BASE_URL . '/index.php?page=categories');
    exit;
}
