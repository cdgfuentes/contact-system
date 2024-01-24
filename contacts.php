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

    // Fetch username based on user ID
    $stmt = $pdo->prepare("SELECT username FROM users WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user was found
    if ($user) {
        $username = $user['username'];
    } else {
        $username = "Unknown User";
    }

    // Get contacts associated with the logged-in user
    $stmt = $pdo->prepare("SELECT * FROM contacts WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
} finally {
    $pdo = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts</title>
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
        }
        ?>
    </header>

    <div class="container">
        <h2>Manage Contacts</h2>
        <input type="text" id="searchInput" placeholder="Search contacts">   
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
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
                ?>
            </tbody>
        </table>
    </div>
    <div class="confirmation-popup" id="deleteConfirmation">
        <p>Are you sure you want to delete this contact?</p>
        <button id="confirmDelete">Delete</button>
        <button id="cancelDelete">Cancel</button>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        var deleteLinks = document.getElementsByClassName("delete-action");
        var contactIds = <?php echo json_encode(array_column($contacts, 'contact_id')); ?>;

        Array.from(deleteLinks).forEach(function (link) {
            link.addEventListener("click", function (event) {
                event.preventDefault();
                var contactId = this.getAttribute("data-contact-id");
                document.getElementById("deleteConfirmation").style.display = "block";
                document.getElementById("confirmDelete").addEventListener("click", function () {                    
                    window.location.href = "delete_contact.php?id=" + contactId;
                });

                document.getElementById("cancelDelete").addEventListener("click", function () {                    
                    document.getElementById("deleteConfirmation").style.display = "none";
                });
            });
        });

        // Search bar
        var searchInput = document.getElementById("searchInput");        
        searchInput.addEventListener("input", function () {
            var searchTerm = searchInput.value.toLowerCase();
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.querySelector("tbody").innerHTML = xhr.responseText;
                }
            };
            xhr.open("GET", "search_contacts.php?term=" + searchTerm, true);
            xhr.send();
        });
    });
</script>
</body>
</html>
