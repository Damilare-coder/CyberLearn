<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user data
try {
    $stmt = $conn->prepare("SELECT username, profile_image FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $user = [
            'username' => 'User ' . $user_id,
            'profile_image' => 'uploads/default_avatar.jpg'
        ];
    }
} catch (PDOException $e) {
    die("User fetch error: " . $e->getMessage());
}

// Fetch lessons
try {
    $stmt = $conn->query("SELECT * FROM lessons");
    $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $lessons = [];
    $error_message = "Could not load lessons: " . $e->getMessage();
}
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CyberLearn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #0d1117; color: #c9d1d9; font-family: 'Segoe UI', sans-serif; }
        .card { background-color: #161b22; border: 1px solid #30363d; }
        h2, h4 { color: #58a6ff; }
        .btn-primary { background-color: #238636; border-color: #238636; }
        .btn-primary:hover { background-color: #2ea44f; }
        .alert-warning { background-color: #2f3e4a; border-color: #444d56; color: #f0b849; }
    </style>
</head>
<body class="container mt-5">

    <!-- Profile & Welcome -->
    <div class="mb-4 d-flex align-items-center">
        <img src="<?= htmlspecialchars($user['profile_image'] ?? 'uploads/default_avatar.jpg') ?>" 
             alt="Profile" 
             class="rounded-circle me-3" 
             style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #58a6ff;">
        <h2 class="mb-0">Welcome back, <?= htmlspecialchars($user['username']) ?>!</h2>
    </div>

    <div class="card p-4 mb-4">
        <h3 class="mb-4 text-white">Choose a Lesson</h3>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-warning"><?= htmlspecialchars($error_message) ?></div>
        <?php elseif (empty($lessons)): ?>
            <div class="alert alert-info">No lessons available yet. Add some in phpMyAdmin!</div>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($lessons as $lesson): ?>
                    <li class="list-group-item bg-dark border-secondary">
                        <a href="lesson.php?id=<?= $lesson['id'] ?>" class="text-decoration-none text-white">
                            <?= htmlspecialchars($lesson['title']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>


</body>
</html>