<?php
/**
 * Form Edit Buku
 *
 * @package Views\Books
 * @var array $book Data buku yang akan diedit
 * @var array $categories Kategori untuk select
 * @var array $errors Error validasi (opsional)
 */

$pageTitle = 'Edit Buku';
$errors = $errors ?? [];
include ROOT_DIR . '/views/layout/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2>✏️ Edit Buku</h2>
        <a href="<?= BASE_URL ?>/index.php?page=books" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <div class="card-body">
        <?php if (isset($errors['general'])): ?>
            <div class="alert alert-danger">❌ <?= htmlspecialchars($errors['general'][0]) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/index.php?page=books&action=edit&id=<?= $book['id'] ?>" 
              enctype="multipart/form-data" id="bookEditForm">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="title">Judul Buku *</label>
                    <input type="text" id="title" name="title" class="form-control <?= isset($errors['title']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($_POST['title'] ?? $book['title']) ?>" required>
                    <?php if (isset($errors['title'])): ?>
                        <div class="invalid-feedback"><?= $errors['title'][0] ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="author">Penulis *</label>
                    <input type="text" id="author" name="author" class="form-control <?= isset($errors['author']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($_POST['author'] ?? $book['author']) ?>" required>
                    <?php if (isset($errors['author'])): ?>
                        <div class="invalid-feedback"><?= $errors['author'][0] ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="category_id">Kategori *</label>
                    <select id="category_id" name="category_id" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($categories as $id => $name): ?>
                            <option value="<?= $id ?>" <?= (($_POST['category_id'] ?? $book['category_id']) == $id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="publisher">Penerbit</label>
                    <input type="text" id="publisher" name="publisher" class="form-control"
                           value="<?= htmlspecialchars($_POST['publisher'] ?? $book['publisher'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="year_published">Tahun Terbit</label>
                    <input type="number" id="year_published" name="year_published" class="form-control"
                           min="1900" max="<?= date('Y') ?>"
                           value="<?= htmlspecialchars($_POST['year_published'] ?? $book['year_published'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="isbn">ISBN</label>
                    <input type="text" id="isbn" name="isbn" class="form-control"
                           value="<?= htmlspecialchars($_POST['isbn'] ?? $book['isbn'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="stock">Stok *</label>
                    <input type="number" id="stock" name="stock" class="form-control" min="0"
                           value="<?= htmlspecialchars($_POST['stock'] ?? $book['stock']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="cover_image">Cover Buku (kosongkan jika tidak diubah)</label>
                    <input type="file" id="cover_image" name="cover_image" class="form-control" accept="image/*">
                    <?php if (!empty($book['cover_image'])): ?>
                        <small style="color: var(--text-secondary);">Cover saat ini: <?= htmlspecialchars($book['cover_image']) ?></small>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" class="form-control" rows="3"><?= htmlspecialchars($_POST['description'] ?? $book['description'] ?? '') ?></textarea>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">💾 Perbarui</button>
                <a href="<?= BASE_URL ?>/index.php?page=books" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php include ROOT_DIR . '/views/layout/footer.php'; ?>
