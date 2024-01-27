<?php

session_start();

require_once __DIR__ . '/db.php';

/**
 * Escape a value for HTML output.
 */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect(string $to): void
{
    header("Location: $to");
    exit();
}

function require_login(): int
{
    if (!isset($_SESSION['user_id'])) {
        redirect('login.php');
    }

    return (int) $_SESSION['user_id'];
}

function current_username(int $userId): string
{
    $stmt = db()->prepare('SELECT username FROM users WHERE user_id = ?');
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    return $user ? $user['username'] : 'Unknown User';
}
