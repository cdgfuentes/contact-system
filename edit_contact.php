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

// Initialize variables for contact details
$contactId = $_GET['id']; // Get id from param (url)
$name = $company = $phone = $email = '';

try {    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM contacts WHERE contact_id = ?");
    $stmt->execute([$contactId]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($contact) {
        $name = $contact['name'];
        $company = $contact['company'];
        $phone = $contact['phone_number'];
        $email = $contact['email'];
    } else {
        echo "Contact not found.";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $company = $_POST['company'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];

        $stmt = $pdo->prepare("UPDATE contacts SET name = ?, company = ?, phone_number = ?, email = ? WHERE contact_id = ?");
        $stmt->execute([$name, $company, $phone, $email, $contactId]);

        header("Location: contacts.php");
        exit();        
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
    <title>Edit Contact</title>
    <link rel="stylesheet" href="css/contacts.css">
    <style>
        nav a.contacts {
            color: #000;
        }
        nav a.logged-user {
            color: #000;
        }
        nav a.add-contact,
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
        } else {
            echo 'NOT LOGGED IN';
        }
        ?>
    </header>

    <div class="container">
        <h2>Edit Contact</h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "?id=$contactId"); ?>" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

            <label for="company">Company:</label>
            <input type="text" id="company" name="company" value="<?php echo htmlspecialchars($company); ?>" required>

            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <button type="submit">Submit</button>
        </form>

        <?php
        if (isset($updated) && $updated) {
            echo '<p>Contact successfully updated!</p>';
        } elseif (isset($updated) && !$updated) {
            echo '<p>Failed to update contact. Please try again.</p>';
        }
        ?>
    </div>
</body>
</html>
