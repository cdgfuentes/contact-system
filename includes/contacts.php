<?php

/**
 * Trim + validate the add/edit contact form.
 * Returns [cleaned values, list of error messages].
 */
function validate_contact(array $input): array
{
    $values = [
        'name' => trim($input['name'] ?? ''),
        'company' => trim($input['company'] ?? ''),
        'phone' => trim($input['phone'] ?? ''),
        'email' => trim($input['email'] ?? ''),
    ];
    $errors = [];

    if ($values['name'] === '' || mb_strlen($values['name']) > 100) {
        $errors[] = 'Name is required (max 100 characters).';
    }
    if ($values['company'] === '' || mb_strlen($values['company']) > 32) {
        $errors[] = 'Company is required (max 32 characters).';
    }
    if (!preg_match('/^\+?[0-9 ()\-]{3,20}$/', $values['phone'])) {
        $errors[] = 'Phone can only have digits, spaces, + ( ) and - (max 20).';
    }
    if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($values['email']) > 100) {
        $errors[] = 'Please enter a valid email address.';
    }

    return [$values, $errors];
}
