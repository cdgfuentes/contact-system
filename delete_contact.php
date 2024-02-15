<?php
require_once __DIR__ . '/includes/bootstrap.php';

$userId = require_login();

// deleting only happens through the confirm popup (POST + csrf), never a plain link
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $stmt = db()->prepare('DELETE FROM contacts WHERE user_id = ? AND contact_id = ?');
    $stmt->execute([$userId, (int) ($_POST['id'] ?? 0)]);

    if ($stmt->rowCount()) {
        flash('Contact deleted.');
    }
}

redirect('contacts.php');
