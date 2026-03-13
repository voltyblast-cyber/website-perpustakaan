<?php

/**
 * MemberController - Mengelola operasi CRUD anggota
 *
 * @package    Controllers
 * @author     Developer
 * @version    1.0.0
 */

require_once ROOT_DIR . '/vendor/autoload.php';

use App\Models\Member;
use App\Helpers\Validator;
use App\Helpers\FileManager;

/**
 * Menampilkan daftar anggota.
 *
 * @return void
 */
function memberIndex(): void
{
    $memberModel = new Member();
    $search = $_GET['search'] ?? '';

    if (!empty($search)) {
        $members = $memberModel->search($search);
    } else {
        $members = $memberModel->getAll();
    }

    include ROOT_DIR . '/views/members/index.php';
}

/**
 * Menampilkan form tambah anggota dan memproses penyimpanan.
 *
 * @return void
 */
function memberCreate(): void
{
    $memberModel = new Member();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $memberCode = $memberModel->generateMemberCode();
        include ROOT_DIR . '/views/members/create.php';
        return;
    }

    $data = [
        'member_code'     => trim($_POST['member_code'] ?? ''),
        'name'            => trim($_POST['name'] ?? ''),
        'email'           => trim($_POST['email'] ?? ''),
        'phone'           => trim($_POST['phone'] ?? ''),
        'address'         => trim($_POST['address'] ?? ''),
        'membership_date' => $_POST['membership_date'] ?? date('Y-m-d'),
        'status'          => 'active',
    ];

    $validator = new Validator();
    $isValid = $validator->validate($data, [
        'member_code' => 'required',
        'name'        => 'required|min:3|max:100',
        'email'       => 'email',
    ]);

    if (!$isValid) {
        $errors = $validator->getErrors();
        $memberCode = $data['member_code'];
        include ROOT_DIR . '/views/members/create.php';
        return;
    }

    if ($memberModel->create($data)) {
        $fileManager = new FileManager();
        $fileManager->writeLog("Anggota '{$data['name']}' berhasil ditambahkan.", 'INFO');

        $_SESSION['flash_success'] = 'Anggota berhasil ditambahkan.';
        header('Location: ' . BASE_URL . '/index.php?page=members');
        exit;
    } else {
        $errors = ['general' => ['Gagal menyimpan anggota.']];
        $memberCode = $data['member_code'];
        include ROOT_DIR . '/views/members/create.php';
    }
}

/**
 * Menampilkan form edit anggota dan memproses update.
 *
 * @return void
 */
function memberEdit(): void
{
    $id = (int) ($_GET['id'] ?? 0);
    $memberModel = new Member();
    $member = $memberModel->getById($id);

    if (!$member) {
        $_SESSION['flash_error'] = 'Anggota tidak ditemukan.';
        header('Location: ' . BASE_URL . '/index.php?page=members');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        include ROOT_DIR . '/views/members/edit.php';
        return;
    }

    $data = [
        'name'    => trim($_POST['name'] ?? ''),
        'email'   => trim($_POST['email'] ?? ''),
        'phone'   => trim($_POST['phone'] ?? ''),
        'address' => trim($_POST['address'] ?? ''),
        'status'  => $_POST['status'] ?? 'active',
    ];

    $validator = new Validator();
    $isValid = $validator->validate($data, [
        'name'  => 'required|min:3|max:100',
        'email' => 'email',
    ]);

    if (!$isValid) {
        $errors = $validator->getErrors();
        include ROOT_DIR . '/views/members/edit.php';
        return;
    }

    if ($memberModel->update($id, $data)) {
        $fileManager = new FileManager();
        $fileManager->writeLog("Anggota '{$data['name']}' (ID: {$id}) berhasil diperbarui.", 'INFO');

        $_SESSION['flash_success'] = 'Anggota berhasil diperbarui.';
        header('Location: ' . BASE_URL . '/index.php?page=members');
        exit;
    } else {
        $errors = ['general' => ['Gagal memperbarui anggota.']];
        include ROOT_DIR . '/views/members/edit.php';
    }
}

/**
 * Menghapus anggota.
 *
 * @return void
 */
function memberDelete(): void
{
    $id = (int) ($_GET['id'] ?? 0);
    $memberModel = new Member();
    $member = $memberModel->getById($id);

    if ($member) {
        if ($memberModel->delete($id)) {
            $fileManager = new FileManager();
            $fileManager->writeLog("Anggota '{$member['name']}' (ID: {$id}) dihapus.", 'WARNING');
            $_SESSION['flash_success'] = 'Anggota berhasil dihapus.';
        } else {
            $_SESSION['flash_error'] = 'Gagal menghapus anggota. Mungkin masih ada peminjaman aktif.';
        }
    }

    header('Location: ' . BASE_URL . '/index.php?page=members');
    exit;
}
