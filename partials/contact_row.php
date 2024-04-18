<?php /** @var array $contact */ ?>
<tr class="action-row">
    <td data-label="Name"><?= e($contact['name']) ?></td>
    <td data-label="Company"><?= e($contact['company']) ?></td>
    <td data-label="Phone"><?= e($contact['phone_number']) ?></td>
    <td data-label="Email"><?= e($contact['email']) ?></td>
    <td>
        <a href="edit_contact.php?id=<?= (int) $contact['contact_id'] ?>" class="edit-action">Edit</a>
        <a href="#" class="delete-action" data-id="<?= (int) $contact['contact_id'] ?>">Delete</a>
    </td>
</tr>
