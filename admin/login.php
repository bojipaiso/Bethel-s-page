<?php
// admin/login.php - FIXED VERSION
session_start();
require_once '../includes/db.php';

// Debug - Check if session works
$_SESSION['test'] = 'working';
if (!isset($_SESSION['test'])) {
    die("Session is not working! Check PHP session configuration.");
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Debug - See what's being submitted
    echo "<!-- Username: $username, Password: $password -->";

    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();

    // Debug - See if user found
    if ($user) {
        echo "<!-- User found in database -->";
        if (password_verify($password, $user['password'])) {
            echo "<!-- Password verified -->";
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $user['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Password incorrect!";
            echo "<!-- Password verification failed -->";
        }
    } else {
        $error = "Username not found!";
        echo "<!-- No user found with username: $username -->";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Bethel School</title>
    <link rel="stylesheet" href="../css/admin-style.css">
</head>
<body>
    <div class="admin-login-container">
        <div class="login-box">
            <div class="login-header">
                <h2>Bethel International School</h2>
                <h3>Admin Login</h3>
            </div>
            <?php if($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn-primary">Login</button>
            </form>
            <p class="login-note">Default: admin / admin123</p>
        </div>
    </div>
</body>
</html>