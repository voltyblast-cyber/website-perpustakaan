<?php
/**
 * Form Edit Anggota
 *
 * @package Views\Members
 * @var array $member Data anggota
 * @var array $errors Error validasi (opsional)
 */

$pageTitle = 'Edit Anggota';
$errors = $errors ?? [];
include ROOT_DIR . '/views/layout/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2>✏️ Edit Anggota</h2>
        <a href="<?= BASE_URL ?>/index.php?page=members" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>/index.php?page=members&action=edit&id=<?= $member['id'] ?>" id="memberEditForm">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="member_code">Kode Anggota</label>
                    <input type="text" id="member_code" class="form-control"
                           value="<?= htmlspecialchars($member['member_code']) ?>" readonly disabled>
                </div>
                <div class="form-group">
                    <label for="name">Nama Lengkap *</label>
                    <input type="text" id="name" name="name" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($_POST['name'] ?? $member['name']) ?>" required>
                    <?php if (isset($errors['name'])): ?>
                        <div class="invalid-feedback"><?= $errors['name'][0] ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="<?= htmlspecialchars($_POST['email'] ?? $member['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Telepon</label>
                    <input type="text" id="phone" name="phone" class="form-control"
                           value="<?= htmlspecialchars($_POST['phone'] ?? $member['phone'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="active" <?= (($_POST['status'] ?? $member['status']) === 'active') ? 'selected' : '' ?>>Aktif</option>
                        <option value="inactive" <?= (($_POST['status'] ?? $member['status']) === 'inactive') ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="address">Alamat</label>
                <textarea id="address" name="address" class="form-control" rows="2"><?= htmlspecialchars($_POST['address'] ?? $member['address'] ?? '') ?></textarea>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">💾 Perbarui</button>
                <a href="<?= BASE_URL ?>/index.php?page=members" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php include ROOT_DIR . '/views/layout/footer.php'; ?>
