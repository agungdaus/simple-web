<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

$user_id = (int)$_SESSION['user']['id'];
$id      = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $conn->prepare('DELETE FROM notes WHERE id = ? AND user_id = ?');
    $stmt->bind_param('ii', $id, $user_id);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    if ($affected > 0) {
        header('Location: index.php?success=' . urlencode('Note berhasil dihapus!'));
    } else {
        header('Location: index.php?error=' . urlencode('Note tidak ditemukan.'));
    }
} else {
    header('Location: index.php?error=' . urlencode('ID tidak valid.'));
}
exit;
