<?php
/**
 * Daftar Anggota
 *
 * @package Views\Members
 * @var array $members Daftar anggota
 */

$pageTitle = 'Data Anggota';
include ROOT_DIR . '/views/layout/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2>👥 Daftar Anggota</h2>
        <div class="btn-group">
            <form method="GET" action="<?= BASE_URL ?>/index.php" class="search-box" id="memberSearchForm">
                <input type="hidden" name="page" value="members">
                <input type="text" name="search" class="form-control" placeholder="Cari anggota..."
                       value="<?= htmlspecialchars($search ?? '') ?>">
                <button type="submit" class="btn btn-secondary btn-sm">🔍</button>
            </form>
            <a href="<?= BASE_URL ?>/index.php?page=members&action=create" class="btn btn-primary btn-sm">
                + Tambah Anggota
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($members)): ?>
            <div class="empty-state">
                <div class="empty-icon">👥</div>
                <h3>Belum Ada Anggota</h3>
                <p>Mulai dengan mendaftarkan anggota perpustakaan.</p>
                <a href="<?= BASE_URL ?>/index.php?page=members&action=create" class="btn btn-primary">+ Tambah Anggota</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table" id="membersTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($members as $member): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><span class="badge badge-info"><?= htmlspecialchars($member['member_code']) ?></span></td>
                            <td><strong><?= htmlspecialchars($member['name']) ?></strong></td>
                            <td><?= htmlspecialchars($member['email'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($member['phone'] ?? '-') ?></td>
                            <td>
                                <?php if ($member['status'] === 'active'): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?= BASE_URL ?>/index.php?page=members&action=edit&id=<?= $member['id'] ?>" 
                                       class="btn btn-warning btn-sm">✏️</a>
                                    <a href="<?= BASE_URL ?>/index.php?page=members&action=delete&id=<?= $member['id'] ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin ingin menghapus anggota ini?')">🗑️</a>
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
