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

/**
 * Per-session token that every POST form has to send back.
 */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
}

function verify_csrf(): void
{
    if (!hash_equals(csrf_token(), $_POST['csrf'] ?? '')) {
        http_response_code(400);
        exit('Invalid form token, go back and try again.');
    }
}

/**
 * One-time message shown on the next page load (after a redirect).
 */
function flash(string $message, string $type = 'success'): void
{
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

function take_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);

    return $flash;
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
