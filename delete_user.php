<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/db.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: users.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);
$me = current_user();

if ($id > 0 && $id !== $me['id']) {
    $pdo = get_db();

    $stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
    $stmt->execute([$id]);
    $target = $stmt->fetch();

    if ($target) {
        $isLastAdmin = $target['role'] === 'admin'
            && (int) $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn() <= 1;

        if (!$isLastAdmin) {
            $pdo->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
        }
    }
}

header('Location: users.php');
exit;
