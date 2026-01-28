<?php
include 'db_connect.php';
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$stmt = $conn->query("SELECT * FROM lessons");
$lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['user_id']) ?>! Choose a Lesson</h2>  <!-- Replace with username fetch if needed -->
    <ul class="list-group">
        <?php foreach ($lessons as $lesson): ?>
            <li class="list-group-item">
                <a href="lesson.php?id=<?= $lesson['id'] ?>"><?= htmlspecialchars($lesson['title']) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="logout.php" class="btn btn-secondary mt-3">Logout</a>
</body>
</html>