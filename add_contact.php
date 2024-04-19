<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/contacts.php';

$userId = require_login();
$username = current_username($userId);
$values = ['name' => '', 'company' => '', 'phone' => '', 'email' => ''];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    [$values, $errors] = validate_contact($_POST);

    if (!$errors) {
        $stmt = db()->prepare('INSERT INTO contacts (user_id, name, company, phone_number, email) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$userId, $values['name'], $values['company'], $values['phone'], $values['email']]);

        flash('Contact added.');
        redirect('contacts.php');
    }
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
        <?php
        $title = 'Add contact';
        $action = 'add_contact.php';
        require __DIR__ . '/partials/contact_form.php';
        ?>
    </main>
</body>
</html>
