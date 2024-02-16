<?php
require_once __DIR__ . '/includes/bootstrap.php';

$userId = require_login();

// Fetch contacts associated with the logged-in user based on search term
$like = '%' . ($_GET['term'] ?? '') . '%';
$stmt = db()->prepare('SELECT * FROM contacts WHERE user_id = ? AND (LOWER(name) LIKE ? OR LOWER(company) LIKE ? OR LOWER(phone_number) LIKE ? OR LOWER(email) LIKE ?)');
$stmt->execute([$userId, $like, $like, $like, $like]);
$contacts = $stmt->fetchAll();

foreach ($contacts as $contact) {
    require __DIR__ . '/partials/contact_row.php';
}
