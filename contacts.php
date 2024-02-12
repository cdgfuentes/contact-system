<?php
require_once __DIR__ . '/includes/bootstrap.php';

$userId = require_login();
$username = current_username($userId);

$stmt = db()->prepare('SELECT * FROM contacts WHERE user_id = ?');
$stmt->execute([$userId]);
$contacts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts</title>
    <link rel="stylesheet" href="css/contacts.css">
</head>
<body>
    <?php $active = 'contacts'; require __DIR__ . '/partials/nav.php'; ?>

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
                <?php foreach ($contacts as $contact) {
                    require __DIR__ . '/partials/contact_row.php';
                } ?>
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
