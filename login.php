<?php
session_start();

// Sudah login → langsung ke dashboard
if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

require_once __DIR__ . '/config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $status   = $_POST['status'] ?? '';

    if ($username === '' || $password === '' || $status === '') {
        $error = 'Semua field wajib diisi.';
    } elseif (!in_array($status, ['Admin', 'User'], true)) {
        $error = 'Status tidak valid.';
    } else {
        $stmt = $conn->prepare(
            'SELECT id, username, password, status FROM users WHERE username = ? AND status = ?'
        );
        $stmt->bind_param('ss', $username, $status);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($row && password_verify($password, $row['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id'       => $row['id'],
                'username' => $row['username'],
                'status'   => $row['status'],
            ];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Username, password, atau status tidak cocok.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height:100vh">

<div class="card shadow" style="width:100%;max-width:420px">
    <div class="card-body p-4">
        <h4 class="card-title text-center mb-4 fw-bold text-primary">
            Login
        </h4>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <input type="text" name="username" class="form-control" required
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
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Login</button>
            </div>
        </form>

        <hr>
        <p class="text-center mb-0 small">
            Belum punya akun? <a href="register.php">Register</a>
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
