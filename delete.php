<?php
require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contracts.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

if ($id > 0) {
    $stmt = get_db()->prepare('DELETE FROM contracts WHERE id = ?');
    $stmt->execute([$id]);
}

header('Location: contracts.php');
exit;
