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

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT username FROM users WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $username = ($user) ? $user['username'] : "Unknown User";

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $name = $_POST['name'];
        $company = $_POST['company'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];

        $stmt = $pdo->prepare("INSERT INTO contacts (user_id, name, company, phone_number, email) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $name, $company, $phone, $email]);
    
        $inserted = true;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $pdo = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Contact</title>
    <link rel="stylesheet" href="css/contacts.css">
    <style>
        nav a.logged-user{
            color: #000;
        }
        nav a.add-contact{
            color: #000;
        }
        nav a.contacts,
        nav a.logout {
            color: #3498db;
        }
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
    <header>
        <?php
        if (isset($_SESSION['user_id'])) {
            echo '<nav>';
            echo '<a class="logged-user">Hello, ' . $username . '!</a>';
            echo '<div>';
            echo '<a href="add_contact.php" class="add-contact">Add Contact</a>';
            echo '<a href="contacts.php" class="contacts">Contacts</a>';
            echo '<a href="logout.php" class="logout">Logout</a>';
            echo '</div>';
            echo '</nav>';
        }else{
            echo 'NOT LOGGED IN';
        }
        ?>
    </header>

    <div class="container">
        <h2>Add Contact</h2>
        <form action="add_contact.php" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="company">Company:</label>
            <input type="text" id="company" name="company" required>

            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <button type="submit">Submit</button>
        </form>

        <?php
        if (isset($inserted) && $inserted) {
            header("Location: contacts.php");
            echo '<p>Contact successfully added!</p>';
        } elseif (isset($inserted) && !$inserted) {
            echo '<p>Failed to add contact. Please try again.</p>';
        }
        ?>
    </div>
</body>
</html>

