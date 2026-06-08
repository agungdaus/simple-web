<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

$user_id = (int)$_SESSION['user']['id'];
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul'] ?? '');
    $isi   = trim($_POST['isi']   ?? '');

    if ($judul === '' || $isi === '') {
        $error = 'Judul dan isi catatan wajib diisi.';
    } else {
        $stmt = $conn->prepare('INSERT INTO notes (judul, isi, user_id) VALUES (?, ?, ?)');
        $stmt->bind_param('ssi', $judul, $isi, $user_id);
        if ($stmt->execute()) {
            header('Location: index.php?success=' . urlencode('Note berhasil ditambahkan!'));
            exit;
        } else {
            $error = 'Gagal menyimpan note. Silakan coba lagi.';
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

<div class="card shadow-sm" style="max-width:640px">
    <div class="card-header fw-bold bg-primary text-white">
        <i class="bi bi-plus-circle"></i> Tambah Note Baru
    </div>
    <div class="card-body">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label fw-semibold">Judul</label>
                <input type="text" name="judul" class="form-control" required maxlength="150"
                       value="<?= htmlspecialchars($_POST['judul'] ?? '') ?>">
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Isi Catatan</label>
                <textarea name="isi" class="form-control" rows="7" required><?= htmlspecialchars($_POST['isi'] ?? '') ?></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan Note
                </button>
                <a href="index.php" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
