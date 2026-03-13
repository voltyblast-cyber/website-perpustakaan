<?php
/**
 * Halaman Laporan
 *
 * Menampilkan statistik peminjaman, log aktivitas, dan opsi export.
 * Req 6: Membaca data dari file (log).
 *
 * @package Views\Reports
 * @var array $logs Baris-baris log aktivitas
 * @var array $stats Statistik peminjaman
 */

$pageTitle = 'Laporan';
include ROOT_DIR . '/views/layout/header.php';
?>

<!-- Statistik Peminjaman -->
<div class="stats-grid" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-icon borrowed">📊</div>
        <div class="stat-info">
            <h3><?= $stats['total'] ?? 0 ?></h3>
            <p>Total Peminjaman</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">📖</div>
        <div class="stat-info">
            <h3><?= $stats['borrowed'] ?? 0 ?></h3>
            <p>Sedang Dipinjam</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">✅</div>
        <div class="stat-info">
            <h3><?= $stats['returned'] ?? 0 ?></h3>
            <p>Dikembalikan</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);">⚠️</div>
        <div class="stat-info">
            <h3><?= $stats['overdue'] ?? 0 ?></h3>
            <p>Terlambat</p>
        </div>
    </div>
</div>

<!-- Export -->
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h2>📥 Export Data</h2>
    </div>
    <div class="card-body">
        <p style="margin-bottom: 16px; color: var(--text-secondary);">
            Export data perpustakaan ke format CSV untuk keperluan arsip atau laporan.
        </p>
        <div class="btn-group">
            <a href="<?= BASE_URL ?>/index.php?page=reports&action=export-books" class="btn btn-success">
                📥 Export Data Buku (CSV)
            </a>
        </div>
    </div>
</div>

<!-- Log Aktivitas (Req 6: membaca data dari file) -->
<div class="card">
    <div class="card-header">
        <h2>📝 Log Aktivitas Hari Ini</h2>
    </div>
    <div class="card-body">
        <?php if (empty($logs)): ?>
            <div class="empty-state">
                <div class="empty-icon">📝</div>
                <h3>Belum Ada Log</h3>
                <p>Log aktivitas akan tercatat saat Anda melakukan operasi.</p>
            </div>
        <?php else: ?>
            <div class="log-viewer">
                <?php
                // Loop foreach pada log (Req 3, 5)
                foreach ($logs as $line):
                    if (!empty(trim($line))):
                ?>
                    <div class="log-line"><?= htmlspecialchars($line) ?></div>
                <?php
                    endif;
                endforeach;
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include ROOT_DIR . '/views/layout/footer.php'; ?>
