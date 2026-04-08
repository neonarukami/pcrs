<?php
// student/register_course.php
session_start();
require_once '../config/db.php';

// Semak keselamatan - Hanya pelajar (student) dibenarkan
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit;
}

$student_id = $_SESSION['user_id'];
$message = '';

// --- PROSES DAFTAR KURSUS ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_course'])) {
    $course_id = (int)$_POST['course_id'];

    if ($course_id > 0) {
        try {
            // Masukkan ke jadual registrations
            $stmt = $pdo->prepare("INSERT INTO registrations (student_id, course_id) VALUES (:student_id, :course_id)");
            $stmt->bindParam(':student_id', $student_id);
            $stmt->bindParam(':course_id', $course_id);
            
            if ($stmt->execute()) {
                $message = "<div class='alert alert-success'>Pendaftaran kursus berjaya!</div>";
            }
        } catch (PDOException $e) {
            // Kod 1062 bermaksud pelajar cuba daftar kursus yang sama dua kali
            if ($e->errorInfo[1] == 1062) {
                $message = "<div class='alert alert-warning'>Anda telah pun mendaftar untuk kursus ini.</div>";
            } else {
                $message = "<div class='alert alert-danger'>Ralat: " . $e->getMessage() . "</div>";
            }
        }
    } else {
        $message = "<div class='alert alert-warning'>Sila pilih kursus dari senarai.</div>";
    }
}

// --- PROSES UNENROLL KURSUS ---
if (isset($_GET['drop'])) {
    $reg_id = (int)$_GET['drop'];
    // Pastikan hanya kursus pelajar ini sahaja yang dipadam
    $stmt = $pdo->prepare("DELETE FROM registrations WHERE id = :id AND student_id = :student_id");
    $stmt->bindParam(':id', $reg_id);
    $stmt->bindParam(':student_id', $student_id);
    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Kursus telah digugurkan.</div>";
    }
}

// Ambil senarai SEMUA kursus untuk dimasukkan ke dalam Dropdown pilihan
$stmt_all_courses = $pdo->query("SELECT * FROM courses ORDER BY course_code ASC");
$all_courses = $stmt_all_courses->fetchAll(PDO::FETCH_ASSOC);

// Ambil senarai kursus yang TELAH DIDAFTARKAN oleh pelajar ini sahaja
$sql_my_courses = "SELECT r.id as reg_id, c.course_code, c.course_name, r.registration_date 
                   FROM registrations r 
                   JOIN courses c ON r.course_id = c.id 
                   WHERE r.student_id = :student_id 
                   ORDER BY r.registration_date DESC";
$stmt_my_courses = $pdo->prepare($sql_my_courses);
$stmt_my_courses->bindParam(':student_id', $student_id);
$stmt_my_courses->execute();
$my_courses = $stmt_my_courses->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kursus - PCRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">PCRS - Pelajar</a>
            <div class="d-flex">
                <a href="dashboard.php" class="btn btn-outline-light btn-sm me-2">Return</a>
                <a href="../auth/logout.php" class="btn btn-danger btn-sm">Log Out</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h3 class="mb-4">Subject Registration</h3>
        <?= $message; ?>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">Register new course</div>
                    <div class="card-body">
                        <form action="register_course.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Choose Course</label>
                                <select name="course_id" class="form-select" required>
                                    <option value="" selected disabled>-- Sila Pilih --</option>
                                    <?php foreach($all_courses as $course): ?>
                                        <option value="<?= $course['id'] ?>">
                                            <?= htmlspecialchars($course['course_code'] . ' - ' . $course['course_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" name="register_course" class="btn btn-primary w-100">Register</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">My Courses</div>
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
                            <tbody>
                                <?php if (count($my_courses) > 0): ?>
                                    <?php $count = 1; foreach($my_courses as $my_course): ?>
                                        <tr>
                                            <td><?= $count++; ?></td>
                                            <td><?= htmlspecialchars($my_course['course_code']); ?></td>
                                            <td><?= htmlspecialchars($my_course['course_name']); ?></td>
                                            <td>
                                                <a href="register_course.php?drop=<?= $my_course['reg_id']; ?>" class="btn btn-warning btn-sm" onclick="return confirm('Anda pasti mahu unenroll kursus ini?')">Unenroll</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Anda belum mendaftar mana-mana kursus.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div> <?php include '../includes/footer.php'; ?>
</body>
</html>