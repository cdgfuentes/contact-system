<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$dbname = 'contact_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Fetch contacts associated with the logged-in user based on search term
    $searchTerm = $_GET['term'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM contacts WHERE user_id = ? AND (LOWER(name) LIKE ? OR LOWER(company) LIKE ? OR LOWER(phone_number) LIKE ? OR LOWER(email) LIKE ?)");
    $stmt->execute([$_SESSION['user_id'], "%$searchTerm%", "%$searchTerm%", "%$searchTerm%", "%$searchTerm%"]);
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output the filtered contacts as HTML
    foreach ($contacts as $contact) {
        echo '<tr class="action-row">';
        echo '<td>' . $contact['name'] . '</td>';
        echo '<td>' . $contact['company'] . '</td>';
        echo '<td>' . $contact['phone_number'] . '</td>';
        echo '<td>' . $contact['email'] . '</td>';
        echo '<td>';
        echo '<a href="edit_contact.php?id=' . $contact['contact_id'] . '" class="edit-action">Edit</a>';
        echo '<a href="delete_contact.php?id=' . $contact['contact_id'] . '" class="delete-action">Delete</a>';
        echo '</td>';
        echo '</tr>';
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
} finally {
    // Close the connection
    $pdo = null;
}
?>
