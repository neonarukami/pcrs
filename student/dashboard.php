<?php
// student/dashboard.php
session_start();

// Semak jika sesi wujud dan peranan adalah pelajar
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelajar - PCRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="#">PCRS - Pelajar</a>
            <div class="d-flex">
                <span class="navbar-text me-3 text-white">
                    Hai, <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>
                </span>
                <a href="../auth/logout.php" class="btn btn-danger btn-sm">Log Keluar</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <h2 class="mb-4">Portal Pendaftaran Pelajar</h2>
                        <p class="lead">Sila daftar kursus anda untuk semester ini di bawah.</p>
                        
                        <a href="register_course.php" class="btn btn-primary btn-lg mt-3">
                            Daftar Kursus Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div> <?php include '../includes/footer.php'; ?>
</body>
</html>