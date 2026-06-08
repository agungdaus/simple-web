<?php
session_start();
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/auth.php';

$username = htmlspecialchars($_SESSION['user']['username']);
$status   = htmlspecialchars($_SESSION['user']['status']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Simple Web</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">
            <i class="bi bi-card-heading"></i> Simple Web
        </a>
        <div class="ms-auto d-flex align-items-center gap-3">
            <span class="text-white">
                <i class="bi bi-person-circle"></i> <?= $username ?>
                <span class="badge bg-warning text-dark ms-1"><?= $status ?></span>
            </span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>
</nav>

<!-- Content -->
<div class="container mt-5">
    <div class="mb-5">
        <h3 class="fw-bold">👋 Selamat Datang, <?= $username ?>!</h3>
        <p class="text-muted">
            Anda login sebagai
            <span class="badge bg-primary fs-6"><?= $status ?></span>
        </p>
    </div>

    <div class="row g-4">
        <!-- Card Notes -->
        <div class="col-md-6">
            <div class="card h-100 shadow-sm border-0 text-center">
                <div class="card-body py-5">
                    <i class="bi bi-journal-text text-primary" style="font-size:3.5rem"></i>
                    <h5 class="card-title mt-3 fw-bold">Notes Pribadi</h5>
                    <p class="text-muted small">Buat, edit, dan hapus catatan pribadi Anda</p>
                    <a href="crud/notes/index.php" class="btn btn-primary px-4">
                        <i class="bi bi-arrow-right-circle"></i> Kelola Notes
                    </a>
                </div>
            </div>
        </div>

        <!-- Card Users (Admin only) -->
        <?php if ($status === 'Admin'): ?>
        <div class="col-md-6">
            <div class="card h-100 shadow-sm border-0 text-center">
                <div class="card-body py-5">
                    <i class="bi bi-people-fill text-success" style="font-size:3.5rem"></i>
                    <h5 class="card-title mt-3 fw-bold">Manajemen User</h5>
                    <p class="text-muted small">Tambah, edit, dan hapus data pengguna</p>
                    <a href="crud/users/index.php" class="btn btn-success px-4">
                        <i class="bi bi-arrow-right-circle"></i> Kelola User
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<footer class="text-center text-muted py-4 mt-5 border-top">
    <small>&copy; <?= date('Y') ?> Simple Web — UTS Pemrograman Web II</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
