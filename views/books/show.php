<?php
/**
 * Detail Buku
 *
 * @package Views\Books
 * @var array $book Data buku
 */

$pageTitle = 'Detail Buku';
include ROOT_DIR . '/views/layout/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2>📖 Detail Buku</h2>
        <div class="btn-group">
            <a href="<?= BASE_URL ?>/index.php?page=books&action=edit&id=<?= $book['id'] ?>" class="btn btn-warning btn-sm">✏️ Edit</a>
            <a href="<?= BASE_URL ?>/index.php?page=books" class="btn btn-secondary btn-sm">← Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div>
                <?php if (!empty($book['cover_image'])): ?>
                    <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($book['cover_image']) ?>" 
                         alt="Cover" class="book-cover-large">
                <?php else: ?>
                    <div class="book-cover-large" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #e2e8f0, #cbd5e1); font-size: 48px;">
                        📖
                    </div>
                <?php endif; ?>
            </div>
            <div>
                <dl class="detail-info">
                    <dt>Judul</dt>
                    <dd><strong style="font-size: 20px;"><?= htmlspecialchars($book['title']) ?></strong></dd>

                    <dt>Penulis</dt>
                    <dd><?= htmlspecialchars($book['author']) ?></dd>

                    <dt>Kategori</dt>
                    <dd><span class="badge badge-info"><?= htmlspecialchars($book['category_name'] ?? '-') ?></span></dd>

                    <dt>Penerbit</dt>
                    <dd><?= htmlspecialchars($book['publisher'] ?? '-') ?></dd>

                    <dt>Tahun Terbit</dt>
                    <dd><?= htmlspecialchars($book['year_published'] ?? '-') ?></dd>

                    <dt>ISBN</dt>
                    <dd><?= htmlspecialchars($book['isbn'] ?? '-') ?></dd>

                    <dt>Stok</dt>
                    <dd>
                        <?php if ($book['stock'] > 0): ?>
                            <span class="badge badge-success"><?= $book['stock'] ?> tersedia</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Stok habis</span>
                        <?php endif; ?>
                    </dd>

                    <dt>Deskripsi</dt>
                    <dd><?= nl2br(htmlspecialchars($book['description'] ?? '-')) ?></dd>

                    <dt>Ditambahkan</dt>
                    <dd><?= htmlspecialchars($book['created_at_formatted'] ?? $book['created_at']) ?></dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<?php include ROOT_DIR . '/views/layout/footer.php'; ?>
