<?php
include 'db_connect.php';
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $conn->prepare("SELECT username, profile_image FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC) ?: [
    'username' => 'User ' . $user_id,
    'profile_image' => 'uploads/default_avatar.jpg'
];

// Fetch lessons
$stmt = $conn->query("SELECT * FROM lessons");
$lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Real progress stats
$stmt = $conn->prepare("SELECT COUNT(*) FROM user_lesson_progress WHERE user_id = ?");
$stmt->execute([$user_id]);
$completed = $stmt->fetchColumn();

$total_lessons = count($lessons);

$stmt = $conn->prepare("SELECT AVG(percentage) FROM user_quiz_scores WHERE user_id = ?");
$stmt->execute([$user_id]);
$avg_score = round($stmt->fetchColumn() ?: 0, 1);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CyberLearn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #0d1117; color: #c9d1d9; }
        .navbar { background: #161b22 !important; border-bottom: 1px solid #30363d; }
        .navbar-brand { color: #58a6ff !important; }
        .card { background: #161b22; border: 1px solid #30363d; border-radius: 12px; }
        .btn-success { background: #238636; border: none; }
        .btn-success:hover { background: #2ea44f; }
        .progress-box { background: #161b22; border: 1px solid #58a6ff; border-radius: 12px; }
        .welcome-card { background: linear-gradient(135deg, #1e40af, #3b82f6); border-radius: 12px; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="dashboard.php">CyberLearn</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <img src="<?= htmlspecialchars($user['profile_image']) ?>" alt="Profile" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                            <?= htmlspecialchars($user['username']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end bg-dark border-secondary">
                            <li><a class="dropdown-item text-white" href="profile.php">Profile</a></li>
                            <li><hr class="dropdown-divider bg-secondary"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>





    <!-- Progress Section -->
    <div class="row mb-5 g-4 mt-4">
        <h3 class="mb-3">Welcome back, <?= htmlspecialchars($user['username']) ?>!</h3>
        <div class="col-lg-8">
            <div class="welcome-card p-4 text-white">
                
                <h3 class="text-light mt-3">CyberLearn teaches Cybersecurity</h3>

                <p class="lead mb-0">
                Cybersecurity is no longer optional — it’s essential. Every click, every password, every email matters. 
                You're taking the right step by learning the basics here. Stay sharp, stay secure.
                </p>           
                <h4 class="text-light mt-3">Why Cybersecurity Matters</h4>
                <p class="text-light lead">
                    In today's connected world, your data, privacy, and digital identity are constantly under threat. 
                    From phishing emails to weak passwords, small mistakes can lead to serious consequences.
                </p>
                <p class="text-light fs-5">
                    This platform teaches you the foundational knowledge to protect yourself and others. 
                    Start with any lesson below — every step makes you more secure.
                </p>
            </div>
        </div>

    <div class="col-lg-4">
        <div class="progress-box p-4 text-center h-100 d-flex flex-column justify-content-between">
            <div>
                <h5 class="text-info mb-2">Your Progress</h5>
                <h2 class="display-5 fw-bold text-white mb-1"><?= $avg_score ?>%</h2>
                <p class="text-muted small mb-3">
                    <?= $completed ?> of <?= $total_lessons ?> lessons completed
                </p>
            </div>

            <!-- Quick summary of completed lessons -->
            <?php if ($completed > 0): ?>
                <div class="mt-3">
                    <h6 class="text-white-75 mb-2">Completed:</h6>
                    <ul class="list-unstyled small text-start">
                        <?php
                        $stmt = $conn->prepare("
                            SELECT l.title 
                            FROM user_lesson_progress p
                            JOIN lessons l ON p.lesson_id = l.id
                            WHERE p.user_id = ?
                            ORDER BY p.completed_at DESC
                            LIMIT 3
                        ");
                        $stmt->execute([$user_id]);
                        $completed_lessons = $stmt->fetchAll(PDO::FETCH_COLUMN);
                        foreach ($completed_lessons as $title): ?>
                            <li>✓ <?= htmlspecialchars($title) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <a href="profile.php" class="btn btn-sm btn-outline-info mt-3">View Full Details</a>
        </div>
    </div>
</div>



    </div>
        <!-- Lessons Cards (your original card style restored + descriptions) -->
        <h3 class="mb-4 text-primary">Available Lessons</h3>
        <div class="row g-4">
            <?php foreach ($lessons as $lesson): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-white"><?= htmlspecialchars($lesson['title']) ?></h5>
                            <p class="card-text text-muted flex-grow-1">
                                <?= htmlspecialchars(substr($lesson['content'] ?? 'Learn the fundamentals of this important topic...', 0, 100)) ?>...
                            </p>
                            <div class="mt-auto">
                                <a href="lesson.php?id=<?= $lesson['id'] ?>" class="btn btn-success w-100">
                                    Start Lesson →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($lessons)): ?>
            <div class="alert alert-info text-center py-5">
                No lessons available yet. Add some in phpMyAdmin!
            </div>
        <?php endif; ?>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- 
<div class="card mt-3 mb-5">
    <div class="card-header">Quote</div>
    
    <div class="card-body">
        <figure>
            <blockquote class="blockquote">
                <p>A well-known quote, contained in a blockquote element.</p>
            </blockquote>
            <figcaption class="blockquote-footer"> Someone famous in <cite title="Source Title">Source Title</cite></figcaption>
        </figure>
    </div>
</div> -->

    <footer class="text-center py-5 mt-5 border-top">
        Copyright &copy; 2026 CyberLearn. All rights reserved.
        <p>Developed by Damilare Oyinloye Emmanuel (241629), SQI College of ICT. </p>
        
    </footer>
</body>
</html>