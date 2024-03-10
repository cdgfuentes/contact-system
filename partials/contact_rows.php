<?php
/** @var array $contacts */
/** @var string $emptyText */
?>
<?php if (!$contacts): ?>
    <tr>
        <td colspan="5" class="empty"><?= e($emptyText) ?></td>
    </tr>
<?php endif; ?>
<?php foreach ($contacts as $contact) {
    require __DIR__ . '/contact_row.php';
} ?>
