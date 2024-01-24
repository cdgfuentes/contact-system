<?php
session_start();
$host = 'localhost';
$dbname = 'contact_system';
$username = 'root';
$password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {        
    $email = $_POST['email'];
    $user_password = $_POST['password'];

    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($user_password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        header("Location: contacts.php"); // Redirect to a contacts.php
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
    <form action="login.php" method="post">
        <h2>User Login</h2>
        <label for="email">Email Address:</label>
        <input type="text" name="email" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <input type="submit" value="Login">
        <a class="register-button" href="register.php">Register</a>
    </form>
</body>
</html>