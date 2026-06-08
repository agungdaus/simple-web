<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

if ($_SESSION['user']['status'] !== 'Admin') {
    header('Location: ' . BASE_URL . 'dashboard.php?error=' . urlencode('Akses ditolak. Halaman ini hanya untuk Admin.'));
    exit;
}

$result  = $conn->query('SELECT id, username, status, created_at FROM users ORDER BY created_at DESC');
$success = htmlspecialchars($_GET['success'] ?? '');
$error   = htmlspecialchars($_GET['error']   ?? '');

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold"><i class="bi bi-people-fill"></i> Manajemen User</h4>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>dashboard.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-house"></i> Dashboard
        </a>
        <a href="create.php" class="btn btn-success btn-sm">
            <i class="bi bi-person-plus"></i> Tambah User
        </a>
    </div>
</div>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= $success ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= $error ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-success">
                <tr>
                    <th style="width:50px">#</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows === 0): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-person-x fs-3 d-block mb-2"></i>
                            Belum ada user.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($row['username']) ?></td>
                        <td>
                            <span class="badge <?= $row['status'] === 'Admin' ? 'bg-danger' : 'bg-secondary' ?>">
                                <?= htmlspecialchars($row['status']) ?>
                            </span>
                        </td>
                        <td class="text-muted small"><?= htmlspecialchars($row['created_at']) ?></td>
                        <td>
                            <a href="edit.php?id=<?= (int)$row['id'] ?>"
                               class="btn btn-warning btn-sm" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="delete.php?id=<?= (int)$row['id'] ?>"
                               class="btn btn-danger btn-sm" title="Hapus"
                               onclick="return confirm('Yakin hapus user ini?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
