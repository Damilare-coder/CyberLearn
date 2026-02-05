<?php
ob_start();  // Keep this – good practice

include 'db_connect.php';
session_start();

$error = null;  // Initialize so we can use it later

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Please fill in both fields.";
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: dashboard.php");
            exit;  // Always exit after redirect
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CyberLearn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0d1117; color: #c9d1d9; min-height: 100vh; }
        .login-card { background: #161b22; border: 1px solid #30363d; border-radius: 12px; max-width: 420px; margin: 100px auto; padding: 2.5rem; }
        .form-control { background: #0d1117; border: 1px solid #30363d; color: white; }
        .form-control:focus { border-color: #58a6ff; box-shadow: 0 0 0 0.25rem rgba(88,166,255,0.25); }
        .btn-primary { background: #238636; border: none; }
        .btn-primary:hover { background: #2ea44f; }
        h2 { color: #58a6ff; }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>  <!-- Now safe – moved after possible redirect -->

    <div class="login-card">
        <h2 class="text-center mb-4">Sign In</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required autofocus value="<?= htmlspecialchars($username ?? '') ?>">
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <p class="text-center mt-4 mb-0">
            Don't have an account? <a href="register.php" class="text-info">Sign up</a>
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php ob_end_flush(); ?>
</html>