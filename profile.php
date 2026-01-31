<?php
include 'db_connect.php';
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$user_id = $_SESSION['user_id'];

// Fetch current profile
$stmt = $conn->prepare("SELECT username, profile_image FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_image'])) {
    $file = $_FILES['profile_image'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array(strtolower($ext), $allowed)) {
            $new_filename = $user_id . '_profile.' . $ext;  // e.g. 1_profile.jpg
            $target = 'uploads/' . $new_filename;

            if (move_uploaded_file($file['tmp_name'], $target)) {
                // Update DB
                $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
                
                $stmt->execute(['uploads/' . $new_filename, $user_id]);  // ← add 'uploads/' prefix
                header("Location: profile.php?success=Image updated!");
                exit;
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Invalid file type. Allowed: jpg, jpeg, png, gif.";
        }
    } else {
        $error = "Upload error: " . $file['error'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2><?= htmlspecialchars($user['username']) ?></h2>
    <?php if (isset($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <?php if (isset($_GET['success'])): ?><div class="alert alert-success"><?= $_GET['success'] ?></div><?php endif; ?>

    <!-- View current image -->
    <div class="mb-4">
        <h4>Current Profile Image</h4>
        <img src="<?= htmlspecialchars($user['profile_image']) ?>" alt="Profile Image" class="img-fluid rounded-circle" style="max-width: 150px;">
    </div>

    <!-- Upload form -->
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Upload/Change Image</label>
            <input type="file" name="profile_image" class="form-control" required>
            <small class="form-text text-muted">Allowed: jpg, jpeg, png, gif. Max 2MB.</small>
        </div>
        <button type="submit" class="btn btn-primary">Update Image</button>
    </form>

    <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
</body>
</html>