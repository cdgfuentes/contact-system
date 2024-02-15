<?php
require_once __DIR__ . '/includes/bootstrap.php';

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

$name = $contact['name'];
$company = $contact['company'];
$phone = $contact['phone_number'];
$email = $contact['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $stmt = db()->prepare('UPDATE contacts SET name = ?, company = ?, phone_number = ?, email = ? WHERE contact_id = ? AND user_id = ?');
    $stmt->execute([$_POST['name'], $_POST['company'], $_POST['phone'], $_POST['email'], $contactId, $userId]);

    redirect('contacts.php');
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
        <form action="edit_contact.php?id=<?= $contactId ?>" method="post" class="card narrow">
            <?= csrf_field() ?>
            <h2>Edit contact</h2>

            <label for="name">Name</label>
            <input type="text" id="name" name="name" maxlength="100" value="<?= e($name) ?>" required>

            <label for="company">Company</label>
            <input type="text" id="company" name="company" maxlength="32" value="<?= e($company) ?>" required>

            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" maxlength="20" value="<?= e($phone) ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" maxlength="100" value="<?= e($email) ?>" required>

            <div class="form-actions">
                <button type="submit">Save</button>
                <a href="contacts.php" class="btn secondary">Cancel</a>
            </div>
        </form>
    </main>
</body>
</html>
