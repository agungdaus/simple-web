<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_nav_username = htmlspecialchars($_SESSION['user']['username'] ?? '');
$_nav_status   = htmlspecialchars($_SESSION['user']['status'] ?? '');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Web — UTS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>dashboard.php">
            <i class="bi bi-card-heading"></i> Simple Web
        </a>
        <div class="ms-auto d-flex align-items-center gap-3">
            <span class="text-white">
                <i class="bi bi-person-circle"></i> <?= $_nav_username ?>
                <span class="badge bg-warning text-dark ms-1"><?= $_nav_status ?></span>
            </span>
            <a href="<?= BASE_URL ?>logout.php" class="btn btn-outline-light btn-sm">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="container mt-4">
