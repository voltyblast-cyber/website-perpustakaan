<?php
/**
 * Form Edit Kategori
 *
 * @package Views\Categories
 * @var array $category Data kategori
 * @var array $errors Error validasi (opsional)
 */

$pageTitle = 'Edit Kategori';
$errors = $errors ?? [];
include ROOT_DIR . '/views/layout/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2>✏️ Edit Kategori</h2>
        <a href="<?= BASE_URL ?>/index.php?page=categories" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>/index.php?page=categories&action=edit&id=<?= $category['id'] ?>" id="categoryEditForm">
            <div class="form-group">
                <label for="name">Nama Kategori *</label>
                <input type="text" id="name" name="name" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                       value="<?= htmlspecialchars($_POST['name'] ?? $category['name']) ?>" required>
                <?php if (isset($errors['name'])): ?>
                    <div class="invalid-feedback"><?= $errors['name'][0] ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" class="form-control" rows="3"><?= htmlspecialchars($_POST['description'] ?? $category['description'] ?? '') ?></textarea>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">💾 Perbarui</button>
                <a href="<?= BASE_URL ?>/index.php?page=categories" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php include ROOT_DIR . '/views/layout/footer.php'; ?>
