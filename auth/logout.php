<?php
// auth/logout.php
session_start();

// Buang semua pembolehubah sesi
$_SESSION = array();

// Musnahkan sesi sepenuhnya
session_destroy();

// Halakan kembali ke halaman log masuk
header("Location: login.php");
exit;
?>