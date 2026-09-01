<?php
/**
 * Returns a shared PDO connection to the MySQL database.
 */
function get_db(): PDO
{
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $config = require __DIR__ . '/config.php';

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        $config['host'],
        $config['port'],
        $config['name'],
        $config['charset']
    );

    $pdo = new PDO($dsn, $config['user'], $config['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}

function get_setting(string $key, ?string $default = null): ?string
{
    $stmt = get_db()->prepare('SELECT setting_value FROM settings WHERE setting_key = ?');
    $stmt->execute([$key]);
    $value = $stmt->fetchColumn();
    return $value !== false ? $value : $default;
}

function set_setting(string $key, string $value): void
{
    $stmt = get_db()->prepare(
        'INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
    );
    $stmt->execute([$key, $value]);
}

/**
 * Atomically reads and increments the contract sequence counter, so two
 * contracts saved at the same moment can never get the same number.
 * Independent of the contracts table's own auto-increment id, so a
 * deleted contract's number is never silently reused, and the admin can
 * set the starting point freely (see settings.php).
 */
function next_contract_number(): int
{
    $pdo = get_db();
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare('SELECT setting_value FROM settings WHERE setting_key = ? FOR UPDATE');
        $stmt->execute(['next_contract_number']);
        $current = (int) ($stmt->fetchColumn() ?: 1);

        $pdo->prepare(
            'INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
             ON DUPLICATE KEY UPDATE setting_value = ?'
        )->execute(['next_contract_number', (string) ($current + 1), (string) ($current + 1)]);

        $pdo->commit();
        return $current;
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}
