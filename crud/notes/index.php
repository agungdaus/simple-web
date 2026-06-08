<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

$user_id = (int)$_SESSION['user']['id'];

$stmt = $conn->prepare(
    'SELECT id, judul, isi, created_at FROM notes WHERE user_id = ? ORDER BY created_at DESC'
);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$notes = $stmt->get_result();
$stmt->close();

$success = htmlspecialchars($_GET['success'] ?? '');
$error   = htmlspecialchars($_GET['error']   ?? '');

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold"><i class="bi bi-journal-text"></i> Notes Pribadi</h4>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>dashboard.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-house"></i> Dashboard
        </a>
        <a href="create.php" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Tambah Note
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
            <thead class="table-primary">
                <tr>
                    <th style="width:50px">#</th>
                    <th>Judul</th>
                    <th>Isi (Ringkasan)</th>
                    <th>Tanggal</th>
                    <th style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($notes->num_rows === 0): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-journal-x fs-3 d-block mb-2"></i>
                            Belum ada catatan. Silakan tambah note baru.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; while ($row = $notes->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($row['judul']) ?></td>
                        <td class="text-muted small">
                            <?= htmlspecialchars(mb_strimwidth($row['isi'], 0, 70, '...')) ?>
                        </td>
                        <td class="text-muted small"><?= htmlspecialchars($row['created_at']) ?></td>
                        <td>
                            <a href="edit.php?id=<?= (int)$row['id'] ?>"
                               class="btn btn-warning btn-sm" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="delete.php?id=<?= (int)$row['id'] ?>"
                               class="btn btn-danger btn-sm" title="Hapus"
                               onclick="return confirm('Yakin hapus note ini?')">
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
