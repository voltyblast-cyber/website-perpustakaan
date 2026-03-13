<?php
/**
 * Form Peminjaman Baru
 *
 * @package Views\Borrowings
 * @var array $books Daftar buku
 * @var array $members Daftar anggota [id => name]
 * @var array $errors Error validasi (opsional)
 */

$pageTitle = 'Peminjaman Baru';
$errors = $errors ?? [];
include ROOT_DIR . '/views/layout/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2>📋 Formulir Peminjaman Baru</h2>
        <a href="<?= BASE_URL ?>/index.php?page=borrowings" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <div class="card-body">
        <?php if (isset($errors['general'])): ?>
            <div class="alert alert-danger">❌ <?= htmlspecialchars($errors['general'][0]) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/index.php?page=borrowings&action=create" id="borrowingCreateForm">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="book_id">Pilih Buku *</label>
                    <select id="book_id" name="book_id" class="form-control <?= isset($errors['book_id']) ? 'is-invalid' : '' ?>" required>
                        <option value="">-- Pilih Buku --</option>
                        <?php foreach ($books as $book): ?>
                            <?php if ($book['stock'] > 0): ?>
                                <option value="<?= $book['id'] ?>" <?= (($_POST['book_id'] ?? '') == $book['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($book['title']) ?> (Stok: <?= $book['stock'] ?>)
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['book_id'])): ?>
                        <div class="invalid-feedback"><?= $errors['book_id'][0] ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="member_id">Pilih Anggota *</label>
                    <select id="member_id" name="member_id" class="form-control <?= isset($errors['member_id']) ? 'is-invalid' : '' ?>" required>
                        <option value="">-- Pilih Anggota --</option>
                        <?php foreach ($members as $id => $name): ?>
                            <option value="<?= $id ?>" <?= (($_POST['member_id'] ?? '') == $id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="borrow_date">Tanggal Pinjam *</label>
                    <input type="date" id="borrow_date" name="borrow_date" class="form-control"
                           value="<?= htmlspecialchars($_POST['borrow_date'] ?? date('Y-m-d')) ?>" required>
                </div>
                <div class="form-group">
                    <label for="due_date">Tanggal Tenggat *</label>
                    <input type="date" id="due_date" name="due_date" class="form-control"
                           value="<?= htmlspecialchars($_POST['due_date'] ?? date('Y-m-d', strtotime('+7 days'))) ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label for="notes">Catatan</label>
                <textarea id="notes" name="notes" class="form-control" rows="2"><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">💾 Simpan Peminjaman</button>
                <a href="<?= BASE_URL ?>/index.php?page=borrowings" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php include ROOT_DIR . '/views/layout/footer.php'; ?>
