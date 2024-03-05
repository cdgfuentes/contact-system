<?php
require_once __DIR__ . '/includes/bootstrap.php';

redirect_if_logged_in();

$error = '';
$email = '';
$flash = take_flash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $email = trim($_POST['email'] ?? '');

    $stmt = db()->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'] ?? '', $user['password'])) {
        login_user((int) $user['user_id']);
        redirect('contacts.php');
    } else {
        $error = 'Invalid email or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in · Contact System</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body class="auth">
    <form action="login.php" method="post" class="card">
        <?= csrf_field() ?>
        <h2>Log in</h2>

        <?php if ($flash): ?>
            <p class="alert <?= e($flash['type']) ?>"><?= e($flash['message']) ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="alert error"><?= e($error) ?></p>
        <?php endif; ?>

        <label for="email">Email address</label>
        <input type="email" id="email" name="email" value="<?= e($email) ?>" autocomplete="email" required autofocus>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" autocomplete="current-password" required>

        <input type="submit" value="Log in">
        <p class="switch">No account yet? <a href="register.php">Create one</a></p>
    </form>
</body>
</html>
