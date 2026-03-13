<?php
/**
 * Daftar Buku
 *
 * @package Views\Books
 * @var array $books Daftar buku
 */

$pageTitle = 'Data Buku';
include ROOT_DIR . '/views/layout/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2>📖 Daftar Buku</h2>
        <div class="btn-group">
            <!-- Search (Req 2: input) -->
            <form method="GET" action="<?= BASE_URL ?>/index.php" class="search-box" id="bookSearchForm">
                <input type="hidden" name="page" value="books">
                <input type="text" name="search" class="form-control" 
                       placeholder="Cari judul, penulis, ISBN..." 
                       value="<?= htmlspecialchars($search ?? '') ?>">
                <button type="submit" class="btn btn-secondary btn-sm">🔍</button>
            </form>
            <a href="<?= BASE_URL ?>/index.php?page=books&action=create" class="btn btn-primary btn-sm">
                + Tambah Buku
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($books)): ?>
            <div class="empty-state">
                <div class="empty-icon">📖</div>
                <h3>Belum Ada Data Buku</h3>
                <p>Mulai dengan menambahkan buku pertama ke perpustakaan.</p>
                <a href="<?= BASE_URL ?>/index.php?page=books&action=create" class="btn btn-primary">
                    + Tambah Buku
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table" id="booksTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Cover</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Loop foreach dan counter (Req 3, 5)
                        $no = 1;
                        foreach ($books as $book):
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <?php if (!empty($book['cover_image'])): ?>
                                    <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($book['cover_image']) ?>" 
                                         alt="Cover" class="book-cover">
                                <?php else: ?>
                                    <div class="book-cover-placeholder">📖</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($book['title']) ?></strong>
                                <?php if (!empty($book['isbn'])): ?>
                                    <br><small style="color: var(--text-muted);">ISBN: <?= htmlspecialchars($book['isbn']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($book['author']) ?></td>
                            <td><span class="badge badge-info"><?= htmlspecialchars($book['category_name'] ?? '-') ?></span></td>
                            <td>
                                <?php if ($book['stock'] > 0): ?>
                                    <span class="badge badge-success"><?= $book['stock'] ?></span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Habis</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= BASE_URL ?>/index.php?page=books&action=show&id=<?= $book['id'] ?>" 
                                       class="btn btn-secondary btn-sm" title="Detail">👁️</a>
                                    <a href="<?= BASE_URL ?>/index.php?page=books&action=edit&id=<?= $book['id'] ?>" 
                                       class="btn btn-warning btn-sm" title="Edit">✏️</a>
                                    <a href="<?= BASE_URL ?>/index.php?page=books&action=delete&id=<?= $book['id'] ?>" 
                                       class="btn btn-danger btn-sm" title="Hapus"
                                       onclick="return confirm('Yakin ingin menghapus buku ini?')">🗑️</a>
                                </div>
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
