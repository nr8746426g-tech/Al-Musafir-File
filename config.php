<?php
/**
 * Database configuration.
 * Values are read from environment variables so real credentials
 * never need to be committed to the repository. Sensible local
 * defaults are provided as a fallback for quick local testing.
 */

return [
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'port' => getenv('DB_PORT') ?: '3306',
    'name' => getenv('DB_NAME') ?: 'al_musafir_contracts',
    'user' => getenv('DB_USER') ?: 'root',
    'pass' => getenv('DB_PASS') ?: '',
    'charset' => 'utf8mb4',
];
