<?php
require_once __DIR__ . '/includes/bootstrap.php';

$userId = require_login();

// escape LIKE wildcards so searching "50%" or "a_b" matches literally
$term = mb_strtolower(trim($_GET['term'] ?? ''));
$like = '%' . addcslashes($term, '%_\\') . '%';

$stmt = db()->prepare(
    'SELECT * FROM contacts
     WHERE user_id = ?
       AND (LOWER(name) LIKE ? OR LOWER(company) LIKE ? OR LOWER(phone_number) LIKE ? OR LOWER(email) LIKE ?)
     ORDER BY name'
);
$stmt->execute([$userId, $like, $like, $like, $like]);
$contacts = $stmt->fetchAll();

$emptyText = 'No contacts match "' . $term . '".';
require __DIR__ . '/partials/contact_rows.php';
