<?php
// admin/manage_courses.php
session_start();
require_once '../config/db.php';

// Semak keselamatan
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$message = '';

// --- PROSES TAMBAH KURSUS (Ringkas) ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_course'])) {
    $code = strtoupper(trim($_POST['course_code']));
    $name = trim($_POST['course_name']);

    if (!empty($code) && !empty($name)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO courses (course_code, course_name) VALUES (:code, :name)");
            $stmt->bindParam(':code', $code);
            $stmt->bindParam(':name', $name);
            
            if ($stmt->execute()) {
                $message = "<div class='alert alert-success'>Kursus berjaya ditambah!</div>";
            }
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                $message = "<div class='alert alert-danger'>Ralat: Kod kursus '$code' sudah didaftarkan.</div>";
            } else {
                $message = "<div class='alert alert-danger'>Ralat: " . $e->getMessage() . "</div>";
            }
        }
    } else {
        $message = "<div class='alert alert-warning'>Sila isi semua maklumat.</div>";
    }
}

// --- PROSES PADAM KURSUS ---
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM courses WHERE id = :id");
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Kursus berjaya dipadam.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urus Kursus - PCRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">PCRS - Admin</a>
            <div class="d-flex">
                <a href="dashboard.php" class="btn btn-outline-light btn-sm me-2">Return</a>
                <a href="../auth/logout.php" class="btn btn-danger btn-sm">Log Out</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h3 class="mb-4">Course Registration</h3>
        <?= $message; ?>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">Add new course</div>
                    <div class="card-body">
                        <form action="manage_courses.php" method="POST">
                            <div class="mb-3">
                                <label>Course Code</label>
                                <input type="text" name="course_code" class="form-control" placeholder="Example: DFP40443" required>
                            </div>
                            <div class="mb-3">
                                <label>Course Name</label>
                                <input type="text" name="course_name" class="form-control" placeholder="Example: Full Stack Web Development" required>
                            </div>
                            <button type="submit" name="add_course" class="btn btn-primary w-100">Save Course</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <span>List of Courses</span>
                        <input type="text" id="search_box" class="form-control form-control-sm w-50" placeholder="Cari kod atau nama kursus...">
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover table-striped m-0">
                            <thead>
                                <tr>
                                    <th>Num</th>
                                    <th>Course Code</th>
                                    <th>Course Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="course_table_body">
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function loadCourses(query = '') {
                let formData = new FormData();
                formData.append('query', query);

                fetch('../ajax/search_course.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    document.getElementById('course_table_body').innerHTML = data;
                });
            }

            loadCourses();

            document.getElementById('search_box').addEventListener('keyup', function() {
                let searchValue = this.value;
                loadCourses(searchValue);
            });
        });
    </script>
    </div> <?php include '../includes/footer.php'; ?>
</body>
</html>