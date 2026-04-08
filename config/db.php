<?php
// config db.php

$host = '127.0.0.1'; // Use IP instead of localhost as a test
$dbname = 'pcrs_db';
$username = 'root';
$password = '';
$port = 3308;

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connected successfully!";
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>