<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

if ($_SESSION['user']['status'] !== 'Admin') {
    header('Location: ' . BASE_URL . 'dashboard.php?error=' . urlencode('Akses ditolak. Halaman ini hanya untuk Admin.'));
    exit;
}

$id             = (int)($_GET['id'] ?? 0);
$current_user_id = (int)$_SESSION['user']['id'];

if ($id <= 0) {
    header('Location: index.php?error=' . urlencode('ID tidak valid.'));
    exit;
}

if ($id === $current_user_id) {
    // Cegah user menghapus akun dirinya sendiri
    header('Location: index.php?error=' . urlencode('Tidak dapat menghapus akun yang sedang digunakan.'));
    exit;
}

$stmt = $conn->prepare('DELETE FROM users WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$affected = $stmt->affected_rows;
$stmt->close();

if ($affected > 0) {
    header('Location: index.php?success=' . urlencode('User berhasil dihapus!'));
} else {
    header('Location: index.php?error=' . urlencode('User tidak ditemukan.'));
}
exit;
