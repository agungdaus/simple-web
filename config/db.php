<?php
// =============================================
//  Konfigurasi koneksi database
// =============================================
define('BASE_URL', '/');  

$host = 'localhost';
$user = 'root';
$pass = 'root';
$db   = 'simple_web';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Koneksi database gagal: ' . htmlspecialchars($conn->connect_error));
}

$conn->set_charset('utf8mb4');
