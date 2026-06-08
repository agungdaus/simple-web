<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

$user_id = (int)$_SESSION['user']['id'];
$id      = (int)($_GET['id'] ?? 0);
$error   = '';

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Ambil note — pastikan milik user yang sedang login
$stmt = $conn->prepare('SELECT id, judul, isi FROM notes WHERE id = ? AND user_id = ?');
$stmt->bind_param('ii', $id, $user_id);
$stmt->execute();
$note = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$note) {
    header('Location: index.php?error=' . urlencode('Note tidak ditemukan.'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $isi   = trim($_POST['isi']   ?? '');

    if ($judul === '' || $isi === '') {
        $error = 'Judul dan isi catatan wajib diisi.';
    } else {
        $stmt = $conn->prepare('UPDATE notes SET judul = ?, isi = ? WHERE id = ? AND user_id = ?');
        $stmt->bind_param('ssii', $judul, $isi, $id, $user_id);
        if ($stmt->execute()) {
            header('Location: index.php?success=' . urlencode('Note berhasil diperbarui!'));
            exit;
        } else {
            $error = 'Gagal memperbarui note.';
        }
        $stmt->close();
    }
    // Tampilkan ulang nilai yang diedit
    $note['judul'] = $judul;
    $note['isi']   = $isi;
}

require_once __DIR__ . '/../../includes/header.php';
?>

<div class="mb-3">
    <a href="index.php" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<div class="card shadow-sm" style="max-width:640px">
    <div class="card-header fw-bold bg-warning">
        <i class="bi bi-pencil-square"></i> Edit Note
    </div>
    <div class="card-body">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label fw-semibold">Judul</label>
                <input type="text" name="judul" class="form-control" required maxlength="150"
                       value="<?= htmlspecialchars($note['judul']) ?>">
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Isi Catatan</label>
                <textarea name="isi" class="form-control" rows="7" required><?= htmlspecialchars($note['isi']) ?></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save"></i> Update Note
                </button>
                <a href="index.php" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
