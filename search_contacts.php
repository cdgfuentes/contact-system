<?php
require_once __DIR__ . '/includes/bootstrap.php';

$userId = require_login();

// Fetch contacts associated with the logged-in user based on search term
$like = '%' . ($_GET['term'] ?? '') . '%';
$stmt = db()->prepare('SELECT * FROM contacts WHERE user_id = ? AND (LOWER(name) LIKE ? OR LOWER(company) LIKE ? OR LOWER(phone_number) LIKE ? OR LOWER(email) LIKE ?)');
$stmt->execute([$userId, $like, $like, $like, $like]);
$contacts = $stmt->fetchAll();

// Output the filtered contacts as HTML
foreach ($contacts as $contact) {
    echo '<tr class="action-row">';
    echo '<td>' . e($contact['name']) . '</td>';
    echo '<td>' . e($contact['company']) . '</td>';
    echo '<td>' . e($contact['phone_number']) . '</td>';
    echo '<td>' . e($contact['email']) . '</td>';
    echo '<td>';
    echo '<a href="edit_contact.php?id=' . (int) $contact['contact_id'] . '" class="edit-action">Edit</a>';
    echo '<a href="delete_contact.php?id=' . (int) $contact['contact_id'] . '" class="delete-action">Delete</a>';
    echo '</td>';
    echo '</tr>';
}
