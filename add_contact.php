<?php
require_once __DIR__ . '/includes/bootstrap.php';

$userId = require_login();
$username = current_username($userId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

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
    <title>Add contact · Contact System</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
    <?php $active = 'add'; require __DIR__ . '/partials/nav.php'; ?>

    <main class="container">
        <form action="add_contact.php" method="post" class="card narrow">
            <?= csrf_field() ?>
            <h2>Add contact</h2>

            <label for="name">Name</label>
            <input type="text" id="name" name="name" maxlength="100" required>

            <label for="company">Company</label>
            <input type="text" id="company" name="company" maxlength="32" required>

            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" maxlength="20" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" maxlength="100" required>

            <div class="form-actions">
                <button type="submit">Save</button>
                <a href="contacts.php" class="btn secondary">Cancel</a>
            </div>
        </form>
    </main>
</body>
</html>
