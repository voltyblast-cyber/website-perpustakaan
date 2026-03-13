<?php
/**
 * Layout Header - Template bagian atas halaman
 *
 * Menyediakan sidebar navigasi dan header.
 *
 * @package    Views\Layout
 * @var string $pageTitle Judul halaman (opsional)
 */

$pageTitle = $pageTitle ?? 'Dashboard';
$currentPage = $_GET['page'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Manajemen Perpustakaan - Kelola buku, anggota, dan peminjaman">
    <title><?= htmlspecialchars($pageTitle) ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body>
    <div class="app-layout">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="brand-icon">📚</div>
                <h2><?= APP_NAME ?></h2>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Menu Utama</div>
                    <a href="<?= BASE_URL ?>/index.php?page=dashboard" 
                       class="nav-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                        <span class="nav-icon">🏠</span> Dashboard
                    </a>
                    <a href="<?= BASE_URL ?>/index.php?page=books" 
                       class="nav-link <?= $currentPage === 'books' ? 'active' : '' ?>">
                        <span class="nav-icon">📖</span> Data Buku
                    </a>
                    <a href="<?= BASE_URL ?>/index.php?page=members" 
                       class="nav-link <?= $currentPage === 'members' ? 'active' : '' ?>">
                        <span class="nav-icon">👥</span> Data Anggota
                    </a>
                    <a href="<?= BASE_URL ?>/index.php?page=borrowings" 
                       class="nav-link <?= $currentPage === 'borrowings' ? 'active' : '' ?>">
                        <span class="nav-icon">📋</span> Peminjaman
                    </a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Master Data</div>
                    <a href="<?= BASE_URL ?>/index.php?page=categories" 
                       class="nav-link <?= $currentPage === 'categories' ? 'active' : '' ?>">
                        <span class="nav-icon">🏷️</span> Kategori
                    </a>
                    <a href="<?= BASE_URL ?>/index.php?page=reports" 
                       class="nav-link <?= $currentPage === 'reports' ? 'active' : '' ?>">
                        <span class="nav-icon">📊</span> Laporan
                    </a>
                </div>
                <div class="nav-section">
                    <div class="nav-section-title">Akun</div>
                    <a href="<?= BASE_URL ?>/index.php?page=logout" class="nav-link">
                        <span class="nav-icon">🚪</span> Logout
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="header">
                <div class="header-left">
                    <h1><?= htmlspecialchars($pageTitle) ?></h1>
                </div>
                <div class="header-right">
                    <div class="user-info">
                        <div class="user-avatar">
                            <?= strtoupper(substr($_SESSION['full_name'] ?? 'U', 0, 1)) ?>
                        </div>
                        <div>
                            <div class="user-name"><?= htmlspecialchars($_SESSION['full_name'] ?? 'User') ?></div>
                            <div class="user-role"><?= ucfirst($_SESSION['role'] ?? 'user') ?></div>
                        </div>
                    </div>
                </div>
            </header>
            <div class="content-area">
                <?php
                // Flash messages
                if (isset($_SESSION['flash_success'])): ?>
                    <div class="alert alert-success">
                        ✅ <?= htmlspecialchars($_SESSION['flash_success']) ?>
                    </div>
                    <?php unset($_SESSION['flash_success']); ?>
                <?php endif; ?>
                <?php if (isset($_SESSION['flash_error'])): ?>
                    <div class="alert alert-danger">
                        ❌ <?= htmlspecialchars($_SESSION['flash_error']) ?>
                    </div>
                    <?php unset($_SESSION['flash_error']); ?>
                <?php endif; ?>
