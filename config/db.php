<?php
// config/db.php

$host = 'localhost';
$dbname = 'pcrs_db';
$username = 'root'; // Nama pengguna lalai untuk XAMPP/Laragon
$password = '';     // Kosongkan jika tiada kata laluan untuk XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Tetapkan mod ralat PDO kepada exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ralat sambungan pangkalan data: " . $e->getMessage());
}
?>