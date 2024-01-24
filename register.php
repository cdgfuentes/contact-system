<?php
session_start();

$host = 'localhost';
$dbname = 'contact_system';
$username = 'root';
$password = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            echo "<p class='error'>Username or email already exists. Please choose a different one.</p>";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $password]);
            $_SESSION['user_id'] = $pdo->lastInsertId();

            header("Location: thank_you.php");
            exit();
        }
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
} finally {
    // Close the connection
    $pdo = null;
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
        <label for="username">Username:</label>
        <input type="text" name="username" required>

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="confirmPassword">Confirm Password:</label>
        <input type="password" id="confirmPassword" required>

        <input type="submit" value="Register">
    </form>
</body>
</html>

<script>
        document.addEventListener("DOMContentLoaded", function () {
            var password = document.getElementById("password");
            var confirmPassword = document.getElementById("confirmPassword");
            var submitButton = document.getElementById("submitButton");

            confirmPassword.addEventListener("input", function () {
                if (password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity("Passwords do not match");
                } else {
                    confirmPassword.setCustomValidity("");
                }
            });

            submitButton.addEventListener("click", function () {
                if (password.value !== confirmPassword.value) {
                    alert("Passwords do not match");
                }
            });
        });
    </script>
