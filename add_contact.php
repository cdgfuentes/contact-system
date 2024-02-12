<?php
require_once __DIR__ . '/includes/bootstrap.php';

$userId = require_login();
$username = current_username($userId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = db()->prepare('INSERT INTO contacts (user_id, name, company, phone_number, email) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$userId, $_POST['name'], $_POST['company'], $_POST['phone'], $_POST['email']]);

    redirect('contacts.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Contact</title>
    <link rel="stylesheet" href="css/contacts.css">
    <style>
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 8px;
        }
        input {
            margin-bottom: 16px;
            padding: 8px;
            box-sizing: border-box;
        }
        button {
            background-color: #3498db;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php $active = 'add'; require __DIR__ . '/partials/nav.php'; ?>

    <div class="container">
        <h2>Add Contact</h2>
        <form action="add_contact.php" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="company">Company:</label>
            <input type="text" id="company" name="company" required>

            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>

