<?php
include 'db_connect.php';
session_start();
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}

$lesson_id = $_GET['lesson_id'] ?? 0;

// Fetch questions for this lesson
$stmt = $conn->prepare("SELECT * FROM questions WHERE lesson_id = ?");
$stmt->execute([$lesson_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    
    foreach ($questions as $q) {
        $q_id = $q['id'];
        $user_answer = $_POST["answer_$q_id"] ?? '';
        
        // Compare (case-sensitive – change to == if you want case-insensitive)
        $user_answer_normalized = strtoupper(trim($user_answer));
        $is_correct = ($user_answer_normalized === $q['correct_answer']) ? 1 : 0;

        $stmt = $conn->prepare("
            INSERT INTO user_answers 
            (user_id, question_id, answer, is_correct) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$user_id, $q_id, $user_answer, $is_correct]);
    }

    header("Location: results.php?lesson_id=$lesson_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz – Lesson <?= htmlspecialchars($lesson_id) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5 mb-5">
    <h2 class="mb-4">Quiz for Lesson <?= htmlspecialchars($lesson_id) ?></h2>

    <?php if (empty($questions)): ?>
        <div class="alert alert-warning">
            No questions available for this lesson yet.
        </div>
    <?php else: ?>
        <form method="POST">
            <?php $q_number = 1; ?>
            <?php foreach ($questions as $q): ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            Question <?= $q_number++ ?>: <?= htmlspecialchars($q['question']) ?>
                        </h5>

                        <?php
                        // Assume all are MCQ now – split options safely
                        $opts = array_map('trim', explode(',', $q['options'] ?? ''));
                        ?>

                        <?php foreach ($opts as $opt): ?>
                            <?php
                            $opt = trim($opt);
                            // Flexible parsing: A: Text, A.Text, A Text, A) Text, etc.
                            if (preg_match('/^([A-D])\s*[:.)]?\s*(.+)$/i', $opt, $matches)) {
                                $letter = strtoupper($matches[1]);
                                $text   = trim($matches[2]);
                            } else {
                                $letter = '';
                                $text   = $opt;
                            }
                            ?>

                            <div class="form-check mb-2">
                                <input type="radio"
                                       name="answer_<?= $q['id'] ?>"
                                       id="opt_<?= $q['id'] ?>_<?= htmlspecialchars($letter) ?>"
                                       value="<?= htmlspecialchars($letter) ?>"
                                       class="form-check-input"
                                       required>
                                <label class="form-check-label" for="opt_<?= $q['id'] ?>_<?= htmlspecialchars($letter) ?>">
                                    <?= htmlspecialchars($letter ? $letter . ': ' . $text : $text) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg px-5">
                    Submit Answers
                </button>
            </div>
        </form>
    <?php endif; ?>
</body>
</html>