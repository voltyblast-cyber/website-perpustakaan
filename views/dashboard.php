<?php
/**
 * Halaman Dashboard
 *
 * Menampilkan statistik dan ringkasan data perpustakaan.
 * Req 2: Interface output ke pengguna.
 * Req 3: Control structures (if/else, foreach).
 * Req 5: Penggunaan array.
 *
 * @package    Views
 * @var array $stats Statistik dashboard
 * @var array $recentBorrowings Peminjaman aktif terbaru
 */

$pageTitle = 'Dashboard';
include ROOT_DIR . '/views/layout/header.php';
?>

<!-- Statistik Cards (Req 2: output, Req 5: array) -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon books">📖</div>
        <div class="stat-info">
            <h3><?= number_format($stats['total_books']) ?></h3>
            <p>Total Buku</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon members">👥</div>
        <div class="stat-info">
            <h3><?= number_format($stats['total_members']) ?></h3>
            <p>Total Anggota</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon borrowed">📋</div>
        <div class="stat-info">
            <h3><?= number_format($stats['borrowing_stats']['borrowed'] + $stats['borrowing_stats']['overdue']) ?></h3>
            <p>Sedang Dipinjam</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon categories">🏷️</div>
        <div class="stat-info">
            <h3><?= number_format($stats['total_categories']) ?></h3>
            <p>Kategori Buku</p>
        </div>
    </div>
</div>

<!-- Peminjaman Aktif -->
<div class="card">
    <div class="card-header">
        <h2>📋 Peminjaman Aktif</h2>
        <a href="<?= BASE_URL ?>/index.php?page=borrowings&action=create" class="btn btn-primary btn-sm">
            + Peminjaman Baru
        </a>
    </div>
    <div class="card-body">
        <?php if (empty($recentBorrowings)): ?>
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <h3>Tidak Ada Peminjaman Aktif</h3>
                <p>Semua buku telah dikembalikan.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table" id="activeBorrowingsTable">
                    <thead>
                        <tr>
                            <th>Buku</th>
                            <th>Anggota</th>
                            <th>Tenggat</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Loop foreach pada array (Req 3, 5)
                        foreach ($recentBorrowings as $borrowing):
                            // Control structure: if/else untuk status
                            $dueDate = new DateTime($borrowing['due_date']);
                            $now = new DateTime();
                            if ($dueDate < $now) {
                                $statusClass = 'badge-danger';
                                $statusLabel = 'Terlambat';
                            } else {
                                $statusClass = 'badge-warning';
                                $statusLabel = 'Dipinjam';
                            }
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($borrowing['book_title']) ?></td>
                            <td><?= htmlspecialchars($borrowing['member_name']) ?></td>
                            <td><?= date('d M Y', strtotime($borrowing['due_date'])) ?></td>
                            <td><span class="badge <?= $statusClass ?>"><?= $statusLabel ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include ROOT_DIR . '/views/layout/footer.php'; ?>
