<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/contacts.php';

$userId = require_login();
$username = current_username($userId);
$contactId = (int) ($_GET['id'] ?? 0);

// only the owner can open/edit a contact
$stmt = db()->prepare('SELECT * FROM contacts WHERE contact_id = ? AND user_id = ?');
$stmt->execute([$contactId, $userId]);
$contact = $stmt->fetch();

if (!$contact) {
    redirect('contacts.php');
}

$values = [
    'name' => $contact['name'],
    'company' => $contact['company'],
    'phone' => $contact['phone_number'],
    'email' => $contact['email'],
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    [$values, $errors] = validate_contact($_POST);

    if (!$errors) {
        $stmt = db()->prepare('UPDATE contacts SET name = ?, company = ?, phone_number = ?, email = ? WHERE contact_id = ? AND user_id = ?');
        $stmt->execute([$values['name'], $values['company'], $values['phone'], $values['email'], $contactId, $userId]);

        flash('Contact updated.');
        redirect('contacts.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit contact · Contact System</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
    <?php $active = 'contacts'; require __DIR__ . '/partials/nav.php'; ?>

    <main class="container">
        <?php
        $title = 'Edit contact';
        $action = 'edit_contact.php?id=' . $contactId;
        require __DIR__ . '/partials/contact_form.php';
        ?>
    </main>
</body>
</html>
