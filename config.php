<?php
// Database settings. Override with environment variables (see docker-compose.yml)
// or drop a config.local.php next to this file that returns the same array.

if (is_file(__DIR__ . '/config.local.php')) {
    return require __DIR__ . '/config.local.php';
}

return [
    'db_host' => getenv('DB_HOST') ?: 'localhost',
    'db_name' => getenv('DB_NAME') ?: 'contact_system',
    'db_user' => getenv('DB_USER') ?: 'root',
    'db_pass' => getenv('DB_PASS') ?: '',
];
