<?php

// Force errors on - put this as the VERY FIRST lines
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<pre>Debug: PHP is executing up to here!</pre>";


include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Secure hash

    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $pass, $email])) {
        header("Location: login.php?success=Registered!");
        exit;
    } else {
        $error = "Username or email taken.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
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
<body class="container mt-5">

<div class="login-card">
    <h2 class="text-center mb-4">Create Account</h2>

    <?php if (isset($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Sign Up</button>
    </form>

    <p class="text-center mt-4 mb-0">
        Already have an account? <a href="login.php" class="text-info">Login</a>
    </p>
</div>
</html>




<!-- ... same head and style as login ... -->

