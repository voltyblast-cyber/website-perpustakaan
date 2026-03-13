<?php

/**
 * AuthController - Mengelola autentikasi pengguna
 *
 * Controller untuk handle login dan logout.
 *
 * @package    Controllers
 * @author     Developer
 * @version    1.0.0
 */

require_once ROOT_DIR . '/vendor/autoload.php';

use App\Models\User;
use App\Helpers\Validator;
use App\Helpers\FileManager;

/**
 * Proses login.
 *
 * Memvalidasi input, autentikasi user, dan membuat session.
 * Menggunakan control structure if/else (Req 3).
 *
 * @return void
 */
function handleLogin(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        include ROOT_DIR . '/views/auth/login.php';
        return;
    }

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validasi input (Req 4: penggunaan fungsi)
    $validator = new Validator();
    $isValid = $validator->validate(
        ['username' => $username, 'password' => $password],
        ['username' => 'required|min:3', 'password' => 'required|min:3']
    );

    if (!$isValid) {
        $error = $validator->getFirstError();
        include ROOT_DIR . '/views/auth/login.php';
        return;
    }

    // Autentikasi
    $userModel = new User();
    $user = $userModel->authenticate($username, $password);

    if ($user) {
        // Set session
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['username']  = $user['username'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role']      = $user['role'];

        // Catat log (Req 6: menyimpan data ke file)
        $fileManager = new FileManager();
        $fileManager->writeLog("User '{$username}' berhasil login.", 'INFO');

        header('Location: ' . BASE_URL . '/index.php?page=dashboard');
        exit;
    } else {
        $error = 'Username atau password salah.';
        include ROOT_DIR . '/views/auth/login.php';
    }
}

/**
 * Proses logout.
 *
 * Menghapus session dan redirect ke halaman login.
 *
 * @return void
 */
function handleLogout(): void
{
    $username = $_SESSION['username'] ?? 'Unknown';

    // Catat log
    $fileManager = new FileManager();
    $fileManager->writeLog("User '{$username}' logout.", 'INFO');

    session_destroy();
    header('Location: ' . BASE_URL . '/index.php?page=login');
    exit;
}

/**
 * Cek apakah user sudah login.
 *
 * @return bool True jika sudah login
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

/**
 * Cek apakah user adalah admin.
 *
 * @return bool True jika admin
 */
function isAdmin(): bool
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}
