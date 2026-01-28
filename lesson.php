<?php
include 'db_connect.php';
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM lessons WHERE id = ?");
$stmt->execute([$id]);
$lesson = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$lesson) { die("Lesson not found."); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($lesson['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2><?= htmlspecialchars($lesson['title']) ?></h2>
    <p><?= nl2br(htmlspecialchars($lesson['content'])) ?></p>
    <a href="quiz.php?lesson_id=<?= $id ?>" class="btn btn-primary">Take Quiz</a>
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</body>
</html>