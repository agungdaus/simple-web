<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

if ($_SESSION['user']['status'] !== 'Admin') {
    header('Location: ' . BASE_URL . 'dashboard.php?error=' . urlencode('Akses ditolak. Halaman ini hanya untuk Admin.'));
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $status   = $_POST['status']   ?? '';

    if ($username === '' || $password === '' || $status === '') {
        $error = 'Semua field wajib diisi.';
    } elseif (!in_array($status, ['Admin', 'User'], true)) {
        $error = 'Status tidak valid.';
    } elseif (strlen($username) > 50) {
        $error = 'Username maksimal 50 karakter.';
    } else {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt   = $conn->prepare('INSERT INTO users (username, password, status) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $username, $hashed, $status);
        if ($stmt->execute()) {
            header('Location: index.php?success=' . urlencode('User berhasil ditambahkan!'));
            exit;
        } else {
            $error = 'Username sudah digunakan atau terjadi kesalahan.';
        }
        $stmt->close();
    }
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="mb-3">
    <a href="index.php" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<div class="card shadow-sm" style="max-width:500px">
    <div class="card-header fw-bold bg-success text-white">
        <i class="bi bi-person-plus"></i> Tambah User Baru
    </div>
    <div class="card-body">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <input type="text" name="username" class="form-control" required maxlength="50"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="Admin" <?= (($_POST['status'] ?? '') === 'Admin') ? 'selected' : '' ?>>Admin</option>
                    <option value="User"  <?= (($_POST['status'] ?? '') === 'User')  ? 'selected' : '' ?>>User</option>
                </select>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save"></i> Simpan
                </button>
                <a href="index.php" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
