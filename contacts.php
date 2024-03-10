<?php
require_once __DIR__ . '/includes/bootstrap.php';

$userId = require_login();
$username = current_username($userId);

$stmt = db()->prepare('SELECT * FROM contacts WHERE user_id = ? ORDER BY name');
$stmt->execute([$userId]);
$contacts = $stmt->fetchAll();
$flash = take_flash();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts · Contact System</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
    <?php $active = 'contacts'; require __DIR__ . '/partials/nav.php'; ?>

    <main class="container">
        <?php if ($flash): ?>
            <p class="alert <?= e($flash['type']) ?>"><?= e($flash['message']) ?></p>
        <?php endif; ?>

        <div class="card">
            <div class="page-head">
                <h2>Contacts <span class="count"><?= count($contacts) ?></span></h2>
                <input type="search" id="searchInput" placeholder="Search contacts" autocomplete="off">
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $emptyText = 'No contacts yet. Add your first one!';
                        require __DIR__ . '/partials/contact_rows.php';
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div class="confirmation-popup" id="deleteConfirmation">
        <form action="delete_contact.php" method="post" class="card">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="deleteId">
            <p>Are you sure you want to delete this contact?</p>
            <div class="form-actions">
                <button type="submit" class="danger">Delete</button>
                <button type="button" id="cancelDelete" class="secondary">Cancel</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var popup = document.getElementById("deleteConfirmation");
            var tbody = document.querySelector("tbody");

            // event delegation so rows coming back from search still work
            tbody.addEventListener("click", function (event) {
                var link = event.target.closest(".delete-action");
                if (!link) return;

                event.preventDefault();
                document.getElementById("deleteId").value = link.dataset.id;
                popup.classList.add("open");
            });

            document.getElementById("cancelDelete").addEventListener("click", function () {
                popup.classList.remove("open");
            });

            // Search bar: wait until typing stops, ignore answers to older searches
            var searchInput = document.getElementById("searchInput");
            var timer = null;
            var latest = 0;

            searchInput.addEventListener("input", function () {
                clearTimeout(timer);
                timer = setTimeout(function () {
                    var id = ++latest;
                    fetch("search_contacts.php?term=" + encodeURIComponent(searchInput.value))
                        .then(function (res) { return res.text(); })
                        .then(function (html) {
                            if (id === latest) tbody.innerHTML = html;
                        });
                }, 250);
            });
        });
    </script>
</body>
</html>
