<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

if ($_SESSION['user']['status'] !== 'Admin') {
    header('Location: ' . BASE_URL . 'dashboard.php?error=' . urlencode('Akses ditolak. Halaman ini hanya untuk Admin.'));
    exit;
}

$id    = (int)($_GET['id'] ?? 0);
$error = '';

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$stmt = $conn->prepare('SELECT id, username, status FROM users WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    header('Location: index.php?error=' . urlencode('User tidak ditemukan.'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $status   = $_POST['status']   ?? '';

    if ($username === '' || $status === '') {
        $error = 'Username dan status wajib diisi.';
    } elseif (!in_array($status, ['Admin', 'User'], true)) {
        $error = 'Status tidak valid.';
    } elseif (strlen($username) > 50) {
        $error = 'Username maksimal 50 karakter.';
    } else {
        if ($password !== '') {
            // Update termasuk password baru
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt   = $conn->prepare('UPDATE users SET username = ?, password = ?, status = ? WHERE id = ?');
            $stmt->bind_param('sssi', $username, $hashed, $status, $id);
        } else {
            // Update tanpa ubah password
            $stmt = $conn->prepare('UPDATE users SET username = ?, status = ? WHERE id = ?');
            $stmt->bind_param('ssi', $username, $status, $id);
        }

        if ($stmt->execute()) {
            header('Location: index.php?success=' . urlencode('User berhasil diperbarui!'));
            exit;
        } else {
            $error = 'Username sudah digunakan atau terjadi kesalahan.';
        }
        $stmt->close();
    }
    $user['username'] = $username;
    $user['status']   = $status;
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="mb-3">
    <a href="index.php" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<div class="card shadow-sm" style="max-width:500px">
    <div class="card-header fw-bold bg-warning">
        <i class="bi bi-pencil-square"></i> Edit User
    </div>
    <div class="card-body">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <input type="text" name="username" class="form-control" required maxlength="50"
                       value="<?= htmlspecialchars($user['username']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Password Baru
                    <small class="text-muted fw-normal">(kosongkan jika tidak diubah)</small>
                </label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select" required>
                    <option value="Admin" <?= $user['status'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="User"  <?= $user['status'] === 'User'  ? 'selected' : '' ?>>User</option>
                </select>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save"></i> Update
                </button>
                <a href="index.php" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
