<?php
/** @var string $username */
/** @var string $active  which nav link to highlight: contacts | add */
$links = [
    'add' => ['add_contact.php', 'Add Contact'],
    'contacts' => ['contacts.php', 'Contacts'],
];
?>
<header>
    <nav>
        <span class="logged-user">Hello, <?= e($username) ?>!</span>
        <div>
            <?php foreach ($links as $key => [$href, $label]): ?>
                <a href="<?= $href ?>"<?= $key === $active ? ' class="active"' : '' ?>><?= $label ?></a>
            <?php endforeach; ?>
            <a href="logout.php">Logout</a>
        </div>
    </nav>
</header>
