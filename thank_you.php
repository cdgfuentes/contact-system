<?php
require_once __DIR__ . '/includes/bootstrap.php';

require_login();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome · Contact System</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body class="auth">
    <div class="card">
        <h2>Thanks for registering!</h2>
        <p>Your account is ready. Start by adding your first contact.</p>
        <a href="contacts.php" class="btn">Go to contacts</a>
    </div>
</body>
</html>
