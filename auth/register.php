<?php
// auth/register.php
session_start();
require_once '../config/db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $role = $_POST['role'];

    // Validasi pelayan (Server-side validation)
    if (!empty($user) && !empty($pass) && !empty($role)) {
        try {
            // Cincang kata laluan (Password hashing) untuk keselamatan
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

            // Gunakan prepared statements untuk mengelakkan SQL Injection
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
            $stmt->bindParam(':username', $user);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':role', $role);

            if ($stmt->execute()) {
                $message = "<div class='alert alert-success'>Pendaftaran berjaya! Sila <a href='login.php'>log masuk</a>.</div>";
            }
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                $message = "<div class='alert alert-danger'>Ralat: Nama pengguna telah wujud.</div>";
            } else {
                $message = "<div class='alert alert-danger'>Ralat pendaftaran: " . $e->getMessage() . "</div>";
            }
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
    <title>Daftar Pengguna - PCRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Sistem Pendaftaran Kursus Politeknik (PCRS)</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-center mb-4">Pendaftaran Pengguna Baru</h5>
                        
                        <?= $message; ?>

                        <form action="register.php" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Nama Pengguna</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Kata Laluan</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Peranan Pengguna</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="" selected disabled>Pilih Peranan</option>
                                    <option value="student">Pelajar</option>
                                    <option value="admin">Pentadbir (Admin)</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Daftar</button>
                        </form>
                        <div class="mt-3 text-center">
                            Sudah mempunyai akaun? <a href="login.php">Log Masuk di sini</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>