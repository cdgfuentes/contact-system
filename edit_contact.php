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
    <title>Edit Contact</title>
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
    <?php $active = 'contacts'; require __DIR__ . '/partials/nav.php'; ?>

    <div class="container">
        <h2>Edit Contact</h2>
        <form action="<?php echo 'edit_contact.php?id=' . $contactId; ?>" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo e($name); ?>" required>

            <label for="company">Company:</label>
            <input type="text" id="company" name="company" value="<?php echo e($company); ?>" required>

            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" value="<?php echo e($phone); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo e($email); ?>" required>

            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
