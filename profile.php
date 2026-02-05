<?php
include 'db_connect.php';
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $conn->prepare("SELECT username, email, profile_image FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC) ?: [
    'username' => 'Guest',
    'email' => 'N/A',
    'profile_image' => 'uploads/default_avatar.jpg'
];

// Handle image upload (your existing code – I kept it unchanged)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_image'])) {
    $file = $_FILES['profile_image'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allowed)) {
            $new_filename = $user_id . '_profile.' . $ext;
            $target = __DIR__ . '/uploads/' . $new_filename;
            $db_path = 'uploads/' . $new_filename;

            if (move_uploaded_file($file['tmp_name'], $target)) {
                chmod($target, 0644);
                $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
                $stmt->execute([$db_path, $user_id]);
                header("Location: profile.php?success=Image updated!");
                exit;
            } else {
                $error = "Failed to save image.";
            }
        } else {
            $error = "Invalid file type.";
        }
    } else {
        $error = "Upload error.";
    }
}

// Fetch completed lessons
$stmt = $conn->prepare("
    SELECT l.title, p.completed_at 
    FROM user_lesson_progress p
    JOIN lessons l ON p.lesson_id = l.id
    WHERE p.user_id = ?
    ORDER BY p.completed_at DESC
");
$stmt->execute([$user_id]);
$completed_lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch last quiz result
$stmt = $conn->prepare("
    SELECT l.title, s.score, s.total, s.percentage, s.attempted_at
    FROM user_quiz_scores s
    JOIN lessons l ON s.lesson_id = l.id
    WHERE s.user_id = ?
    ORDER BY s.attempted_at DESC
    LIMIT 1
");
$stmt->execute([$user_id]);
$last_quiz = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - CyberLearn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h2 class="mb-4 text-primary">Your Profile</h2>

    <!-- Current Profile Image -->
    <div class="card mb-4 bg-dark border-secondary">
        <div class="card-body text-center">
            <img src="<?= htmlspecialchars($user['profile_image']) ?>" 
                 alt="Profile" 
                 class="rounded-circle mb-3" 
                 style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #58a6ff;">
            <h4><?= htmlspecialchars($user['username']) ?></h4>
            <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
        </div>
    </div>

    <!-- Upload Form -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card mb-4 bg-dark border-secondary">
        <div class="card-body">
            <h5 class="card-title">Change Profile Image</h5>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <input type="file" name="profile_image" class="form-control" accept="image/*" required>
                    <small class="form-text text-muted">jpg, jpeg, png, gif only</small>
                </div>
                <button type="submit" class="btn btn-primary">Upload New Image</button>
            </form>
        </div>
    </div>

    <!-- Progress Details -->
    <div class="row g-4">
        <!-- Completed Lessons -->
        <div class="col-lg-6">
            <div class="card bg-dark border-success h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0 text-light">Completed Lessons</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($completed_lessons)): ?>
                        <p class="text-muted">No lessons completed yet.</p>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($completed_lessons as $prog): ?>
                                <li class="list-group-item bg-transparent border-secondary text-white">
                                    <strong><?= htmlspecialchars($prog['title']) ?></strong><br>
                                    <small class="text-muted">Completed on <?= date('M d, Y \a\t H:i', strtotime($prog['completed_at'])) ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Last Quiz Result -->
        <div class="col-lg-6">
            <div class="card bg-dark border-info h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0 text-light">Last Quiz Result</h5>
                </div>
                <div class="card-body text-center">
                    <?php if ($last_quiz): ?>
                        <h3 class="text-white"><?= $last_quiz['percentage'] ?>%</h3>
                        <p class="lead text-muted mb-1">
                            <?= htmlspecialchars($last_quiz['title']) ?>
                        </p>
                        <p class="text-white-75">
                            Score: <?= $last_quiz['score'] ?> / <?= $last_quiz['total'] ?><br>
                            <small>Completed on <?= date('M d, Y \a\t H:i', strtotime($last_quiz['attempted_at'])) ?></small>
                        </p>
                    <?php else: ?>
                        <p class="text-muted py-4">No quizzes taken yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 text-center">
        <a href="dashboard.php" class="btn btn-secondary btn-lg px-5">Back to Dashboard</a>
    </div>

</body>
</html>