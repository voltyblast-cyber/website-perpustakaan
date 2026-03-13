<?php
/**
 * Form Tambah Anggota
 *
 * @package Views\Members
 * @var string $memberCode Kode anggota yang di-generate
 * @var array $errors Error validasi (opsional)
 */

$pageTitle = 'Tambah Anggota';
$errors = $errors ?? [];
include ROOT_DIR . '/views/layout/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2>👥 Tambah Anggota Baru</h2>
        <a href="<?= BASE_URL ?>/index.php?page=members" class="btn btn-secondary btn-sm">← Kembali</a>
    </div>
    <div class="card-body">
        <form method="POST" action="<?= BASE_URL ?>/index.php?page=members&action=create" id="memberCreateForm">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label for="member_code">Kode Anggota</label>
                    <input type="text" id="member_code" name="member_code" class="form-control"
                           value="<?= htmlspecialchars($memberCode ?? '') ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="name">Nama Lengkap *</label>
                    <input type="text" id="name" name="name" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                    <?php if (isset($errors['name'])): ?>
                        <div class="invalid-feedback"><?= $errors['name'][0] ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    <?php if (isset($errors['email'])): ?>
                        <div class="invalid-feedback"><?= $errors['email'][0] ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="phone">Telepon</label>
                    <input type="text" id="phone" name="phone" class="form-control"
                           value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="membership_date">Tanggal Keanggotaan</label>
                    <input type="date" id="membership_date" name="membership_date" class="form-control"
                           value="<?= htmlspecialchars($_POST['membership_date'] ?? date('Y-m-d')) ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="address">Alamat</label>
                <textarea id="address" name="address" class="form-control" rows="2"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
                <a href="<?= BASE_URL ?>/index.php?page=members" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php include ROOT_DIR . '/views/layout/footer.php'; ?>
