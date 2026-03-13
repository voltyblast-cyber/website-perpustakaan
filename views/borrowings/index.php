<?php
/**
 * Daftar Peminjaman
 *
 * @package Views\Borrowings
 * @var array $borrowings Daftar peminjaman
 */

$pageTitle = 'Data Peminjaman';
include ROOT_DIR . '/views/layout/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2>📋 Daftar Peminjaman</h2>
        <a href="<?= BASE_URL ?>/index.php?page=borrowings&action=create" class="btn btn-primary btn-sm">
            + Peminjaman Baru
        </a>
    </div>
    <div class="card-body">
        <?php if (empty($borrowings)): ?>
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <h3>Belum Ada Peminjaman</h3>
                <p>Belum ada data peminjaman buku.</p>
                <a href="<?= BASE_URL ?>/index.php?page=borrowings&action=create" class="btn btn-primary">+ Peminjaman Baru</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table" id="borrowingsTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Buku</th>
                            <th>Anggota</th>
                            <th>Tgl Pinjam</th>
                            <th>Tenggat</th>
                            <th>Tgl Kembali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($borrowings as $borrow): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><strong><?= htmlspecialchars($borrow['book_title']) ?></strong></td>
                            <td>
                                <?= htmlspecialchars($borrow['member_name']) ?>
                                <br><small style="color: var(--text-muted)"><?= htmlspecialchars($borrow['member_code']) ?></small>
                            </td>
                            <td><?= $borrow['borrow_date_formatted'] ?? date('d M Y', strtotime($borrow['borrow_date'])) ?></td>
                            <td><?= $borrow['due_date_formatted'] ?? date('d M Y', strtotime($borrow['due_date'])) ?></td>
                            <td><?= $borrow['return_date_formatted'] ?? ($borrow['return_date'] ? date('d M Y', strtotime($borrow['return_date'])) : '-') ?></td>
                            <td><span class="badge <?= $borrow['status_class'] ?? 'badge-warning' ?>"><?= $borrow['status_label'] ?? ucfirst($borrow['status']) ?></span></td>
                            <td>
                                <?php if ($borrow['status'] !== 'returned'): ?>
                                    <a href="<?= BASE_URL ?>/index.php?page=borrowings&action=return&id=<?= $borrow['id'] ?>" 
                                       class="btn btn-success btn-sm"
                                       onclick="return confirm('Konfirmasi pengembalian buku ini?')">
                                        ✅ Kembalikan
                                    </a>
                                <?php else: ?>
                                    <span style="color: var(--text-muted); font-size: 13px;">Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include ROOT_DIR . '/views/layout/footer.php'; ?>
