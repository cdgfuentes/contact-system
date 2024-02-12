<?php /** @var array $contact */ ?>
<tr class="action-row">
    <td><?= e($contact['name']) ?></td>
    <td><?= e($contact['company']) ?></td>
    <td><?= e($contact['phone_number']) ?></td>
    <td><?= e($contact['email']) ?></td>
    <td>
        <a href="edit_contact.php?id=<?= (int) $contact['contact_id'] ?>" class="edit-action">Edit</a>
        <a href="delete_contact.php?id=<?= (int) $contact['contact_id'] ?>" class="delete-action">Delete</a>
    </td>
</tr>
