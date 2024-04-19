<?php
/** @var string $title */
/** @var string $action */
/** @var array $values  name, company, phone, email */
/** @var array $errors */
?>
<form action="<?= e($action) ?>" method="post" class="card narrow">
    <?= csrf_field() ?>
    <h2><?= e($title) ?></h2>

    <?php foreach ($errors as $error): ?>
        <p class="alert error"><?= e($error) ?></p>
    <?php endforeach; ?>

    <label for="name">Name</label>
    <input type="text" id="name" name="name" maxlength="100" value="<?= e($values['name']) ?>" required>

    <label for="company">Company</label>
    <input type="text" id="company" name="company" maxlength="32" value="<?= e($values['company']) ?>" required>

    <label for="phone">Phone</label>
    <input type="tel" id="phone" name="phone" maxlength="20" value="<?= e($values['phone']) ?>" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" maxlength="100" value="<?= e($values['email']) ?>" required>

    <div class="form-actions">
        <button type="submit">Save</button>
        <a href="contacts.php" class="btn secondary">Cancel</a>
    </div>
</form>
