<?php
/**
 * Daftar Kategori Buku
 *
 * @package Views\Categories
 * @var array $categories Daftar kategori
 */

$pageTitle = 'Data Kategori';
include ROOT_DIR . '/views/layout/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2>🏷️ Daftar Kategori</h2>
        <a href="<?= BASE_URL ?>/index.php?page=categories&action=create" class="btn btn-primary btn-sm">
            + Tambah Kategori
        </a>
    </div>
    <div class="card-body">
        <?php if (empty($categories)): ?>
            <div class="empty-state">
                <div class="empty-icon">🏷️</div>
                <h3>Belum Ada Kategori</h3>
                <p>Mulai dengan menambahkan kategori buku.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table" id="categoriesTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Jumlah Buku</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($categories as $cat): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><strong><?= htmlspecialchars($cat['name']) ?></strong></td>
                            <td><?= htmlspecialchars($cat['description'] ?? '-') ?></td>
                            <td><span class="badge badge-info"><?= $cat['book_count'] ?? 0 ?> buku</span></td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= BASE_URL ?>/index.php?page=categories&action=edit&id=<?= $cat['id'] ?>" 
                                       class="btn btn-warning btn-sm">✏️</a>
                                    <a href="<?= BASE_URL ?>/index.php?page=categories&action=delete&id=<?= $cat['id'] ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus kategori ini?')">🗑️</a>
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
