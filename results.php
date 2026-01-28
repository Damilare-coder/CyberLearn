<?php
include 'db_connect.php';
session_start();
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}

$lesson_id = $_GET['lesson_id'] ?? 0;
$user_id   = $_SESSION['user_id'];

// Fetch the most recent submission for this lesson
$stmt = $conn->prepare("
    SELECT ua.*, q.question, q.correct_answer 
    FROM user_answers ua 
    JOIN questions q ON ua.question_id = q.id 
    WHERE ua.user_id = ? 
      AND q.lesson_id = ? 
    ORDER BY ua.submitted_at DESC 
    LIMIT 999
");
$stmt->execute([$user_id, $lesson_id]);
$answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate score
$total     = count($answers);
$correct   = 0;
foreach ($answers as $a) {
    if ($a['is_correct']) $correct++;
}
$percentage = $total > 0 ? round(($correct / $total) * 100, 1) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h2 class="mb-4">Your Quiz Results</h2>

    <!-- Score summary – placed at the top -->
    <div class="alert alert-info mb-4">
        <strong>Score:</strong> <?= $correct ?> / <?= $total ?> 
        (<?= $percentage ?>%)
        <?php if ($percentage >= 80): ?>
            <span class="text-success ms-2">→ Excellent!</span>
        <?php elseif ($percentage >= 60): ?>
            <span class="text-warning ms-2">→ Good – keep practicing</span>
        <?php else: ?>
            <span class="text-danger ms-2">→ Review the lesson and try again</span>
        <?php endif; ?>
    </div>

    <?php if (empty($answers)): ?>
        <div class="alert alert-warning">
            No answers found for this quiz. Please take the quiz first.
        </div>
    <?php else: ?>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Question</th>
                    <th>Your Answer</th>
                    <th>Correct Answer</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($answers as $a): ?>
                    <tr>
                        <td><?= htmlspecialchars($a['question']) ?></td>
                        <td><?= htmlspecialchars($a['answer'] ?: '—') ?></td>
                        <td><?= htmlspecialchars($a['correct_answer']) ?></td>
                        <td>
                            <?php if ($a['is_correct']): ?>
                                <span class="badge bg-success">Correct</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Incorrect</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="mt-4">
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        <a href="lesson.php?id=<?= $lesson_id ?>" class="btn btn-primary ms-2">Retake Quiz</a>
    </div>

</body>
</html>