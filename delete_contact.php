<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: contacts.php");
    exit();
}

$host = 'localhost';
$dbname = 'contact_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $userId = $_SESSION['user_id'];
    $contactId = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM contacts WHERE user_id = ? AND contact_id = ?");
    $stmt->execute([$userId, $contactId]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$contact) {        
        header("Location: contacts.php");
        exit();
    }

    $stmt = $pdo->prepare("DELETE FROM contacts WHERE user_id = ? AND contact_id = ?");
    $stmt->execute([$userId, $contactId]);

    header("Location: contacts.php");
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $pdo = null;
}
?>
