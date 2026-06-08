<?php
/**
 * includes/auth.php
 * Session guard — redirect ke login jika belum login.
 * Wajib di-include SETELAH session_start() dan require config/db.php.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}
