<?php
// ajax/search_course.php
require_once '../config/db.php';

$query = '';
if (isset($_POST['query'])) {
    $query = $_POST['query'];
}

$sql = "SELECT * FROM courses WHERE course_code LIKE :query OR course_name LIKE :query ORDER BY course_code ASC";
$stmt = $pdo->prepare($sql);

$searchTerm = "%" . $query . "%";
$stmt->bindParam(':query', $searchTerm);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($stmt->rowCount() > 0) {
    $count = 1;
    foreach ($result as $row) {
        echo "<tr>
                <td>{$count}</td>
                <td>" . htmlspecialchars($row['course_code']) . "</td>
                <td>" . htmlspecialchars($row['course_name']) . "</td>
                <td>
                    <a href='manage_courses.php?delete={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Padam kursus ini?\")'>Padam</a>
                </td>
              </tr>";
        $count++;
    }
} else {
    // Tukar colspan dari 5 ke 4 kerana satu lajur (kredit) telah dibuang
    echo "<tr><td colspan='4' class='text-center text-muted'>Tiada kursus dijumpai.</td></tr>";
}
?>