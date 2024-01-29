<?php
require_once __DIR__ . '/includes/bootstrap.php';

$errors = [];
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || strlen($username) > 50) {
        $errors[] = 'Username is required (max 50 characters).';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if ($password !== ($_POST['confirm_password'] ?? '')) {
        $errors[] = 'Passwords do not match.';
    }

    if (!$errors) {
        // Check if username or email already exists
        $stmt = db()->prepare('SELECT 1 FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$username, $email]);

        if ($stmt->fetch()) {
            $errors[] = 'Username or email already exists. Please choose a different one.';
        }
    }

    if (!$errors) {
        $stmt = db()->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
        $stmt->execute([$username, $email, password_hash($password, PASSWORD_BCRYPT)]);
        $_SESSION['user_id'] = (int) db()->lastInsertId();

        redirect('thank_you.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
    <form action="register.php" method="post">
        <h2>User Registration</h2>

        <?php foreach ($errors as $error): ?>
            <p class="error"><?= e($error) ?></p>
        <?php endforeach; ?>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" maxlength="50" value="<?= e($username) ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" maxlength="100" value="<?= e($email) ?>" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" minlength="8" required>

        <label for="confirmPassword">Confirm Password:</label>
        <input type="password" id="confirmPassword" name="confirm_password" minlength="8" required>

        <input type="submit" value="Register">
        <a class="register-button" href="login.php">Back to login</a>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var password = document.getElementById("password");
            var confirmPassword = document.getElementById("confirmPassword");

            function check() {
                confirmPassword.setCustomValidity(
                    password.value === confirmPassword.value ? "" : "Passwords do not match"
                );
            }

            password.addEventListener("input", check);
            confirmPassword.addEventListener("input", check);
        });
    </script>
</body>
</html>
