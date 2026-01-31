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

<!-- header.php -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #0d1117; border-bottom: 1px solid #30363d;">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php" style="color: #58a6ff;">
            CyberLearn
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item me-3">
                        <a href="profile.php" class="nav-link d-flex align-items-center text-white">
                            <img src="<?= htmlspecialchars($user['profile_image'] ?? 'uploads/default_avatar.jpg') ?>" 
                                 alt="Profile" class="rounded-circle me-2" 
                                 style="width: 32px; height: 32px; object-fit: cover; border: 2px solid #58a6ff;">
                            <?= htmlspecialchars($user['username'] ?? 'Profile') ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="logout.php" class="btn btn-sm btn-outline-danger">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="login.php" class="btn btn-sm btn-outline-primary me-2">Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="register.php" class="btn btn-sm btn-primary">Sign Up</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

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