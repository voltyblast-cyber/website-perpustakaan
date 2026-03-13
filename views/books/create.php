<?php
/**
 * Form Tambah Buku
 *
 * @package Views\Books
 * @var array $categories Kategori untuk select
 * @var array $errors Error validasi (opsional)
 */

$pageTitle = 'Tambah Buku';
$errors = $errors ?? [];
include ROOT_DIR . '/views/layout/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2>📖 Tambah Buku Baru</h2>
        <a href="<?= BASE_URL ?>/index.php?page=books" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <div class="card-body">
        <?php if (isset($errors['general'])): ?>
            <div class="alert alert-danger">❌ <?= htmlspecialchars($errors['general'][0]) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/index.php?page=books&action=create" 
              enctype="multipart/form-data" id="bookCreateForm">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="title">Judul Buku *</label>
                    <input type="text" id="title" name="title" class="form-control <?= isset($errors['title']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
                    <?php if (isset($errors['title'])): ?>
                        <div class="invalid-feedback"><?= $errors['title'][0] ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="author">Penulis *</label>
                    <input type="text" id="author" name="author" class="form-control <?= isset($errors['author']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($_POST['author'] ?? '') ?>" required>
                    <?php if (isset($errors['author'])): ?>
                        <div class="invalid-feedback"><?= $errors['author'][0] ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="category_id">Kategori *</label>
                    <select id="category_id" name="category_id" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($categories as $id => $name): ?>
                            <option value="<?= $id ?>" <?= (($_POST['category_id'] ?? '') == $id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="publisher">Penerbit</label>
                    <input type="text" id="publisher" name="publisher" class="form-control"
                           value="<?= htmlspecialchars($_POST['publisher'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="year_published">Tahun Terbit</label>
                    <input type="number" id="year_published" name="year_published" class="form-control"
                           min="1900" max="<?= date('Y') ?>"
                           value="<?= htmlspecialchars($_POST['year_published'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="isbn">ISBN</label>
                    <input type="text" id="isbn" name="isbn" class="form-control"
                           value="<?= htmlspecialchars($_POST['isbn'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="stock">Stok *</label>
                    <input type="number" id="stock" name="stock" class="form-control" min="0"
                           value="<?= htmlspecialchars($_POST['stock'] ?? '0') ?>" required>
                </div>
                <div class="form-group">
                    <label for="cover_image">Cover Buku</label>
                    <input type="file" id="cover_image" name="cover_image" class="form-control" accept="image/*">
                    <?php if (isset($errors['cover_image'])): ?>
                        <div class="invalid-feedback"><?= $errors['cover_image'][0] ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" class="form-control" rows="3"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
                <a href="<?= BASE_URL ?>/index.php?page=books" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php include ROOT_DIR . '/views/layout/footer.php'; ?>
