<?php
// auth/login.php
session_start();
require_once '../config/db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if (!empty($user) && !empty($pass)) {
        // Cari pengguna dalam pangkalan data
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->bindParam(':username', $user);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Sahkan pengguna dan kata laluan menggunakan password_verify()
        if ($userData && password_verify($pass, $userData['password'])) {
            // Tetapkan pembolehubah sesi
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['username'] = $userData['username'];
            $_SESSION['role'] = $userData['role'];

            // Halakan (redirect) berdasarkan peranan
            if ($userData['role'] === 'admin') {
                header("Location: ../admin/dashboard.php");
                exit;
            } else {
                header("Location: ../student/dashboard.php");
                exit;
            }
        } else {
            $message = "<div class='alert alert-danger'>Nama pengguna atau kata laluan salah.</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Sila isi semua ruangan.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Masuk - PCRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white text-center">
                        <h4>Sistem Pendaftaran Kursus Politeknik (PCRS)</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-center mb-4">Log Masuk Pengguna</h5>
                        
                        <?= $message; ?>

                        <form action="login.php" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nama Pengguna</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Kata Laluan</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Log Masuk</button>
                        </form>
                        <div class="mt-3 text-center">
                            Belum mendaftar? <a href="register.php">Daftar akaun baru</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>