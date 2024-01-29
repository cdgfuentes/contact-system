<?php
require_once __DIR__ . '/includes/bootstrap.php';

$userId = require_login();

if (isset($_GET['id'])) {
    $stmt = db()->prepare('DELETE FROM contacts WHERE user_id = ? AND contact_id = ?');
    $stmt->execute([$userId, $_GET['id']]);
}

redirect('contacts.php');
