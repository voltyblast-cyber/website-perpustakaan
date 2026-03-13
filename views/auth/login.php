<?php
/**
 * Halaman Login
 *
 * Form input untuk autentikasi pengguna.
 * Req 2: Interface input ke pengguna.
 *
 * @package    Views\Auth
 * @var string $error Pesan error (opsional)
 */

$error = $error ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login - Sistem Manajemen Perpustakaan">
    <title>Login - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo">
                <div class="logo-icon">📚</div>
                <h1><?= APP_NAME ?></h1>
                <p class="subtitle">Silakan login untuk melanjutkan</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">❌ <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <!-- Form Login (Req 2: interface input) -->
            <form method="POST" action="<?= BASE_URL ?>/index.php?page=login" id="loginForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" 
                           placeholder="Masukkan username" required autofocus
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" 
                           placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 12px;">
                    🔐 Login
                </button>
            </form>

            <p style="text-align: center; margin-top: 24px; font-size: 13px; color: var(--text-muted);">
                Demo: admin / admin123
            </p>
        </div>
    </div>
</body>
</html>
