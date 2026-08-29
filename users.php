<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/helpers.php';
require __DIR__ . '/db.php';
require_admin();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $role = ($_POST['role'] ?? '') === 'admin' ? 'admin' : 'staff';

    if ($username === '') {
        $errors[] = 'اسم المستخدم مطلوب / Username is required.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'كلمة المرور لازم تكون 6 خانات على الأقل / Password must be at least 6 characters.';
    }

    if (!$errors) {
        try {
            $stmt = get_db()->prepare(
                'INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)'
            );
            $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $role]);
            $success = 'تمت إضافة المستخدم / User added.';
        } catch (PDOException $e) {
            $errors[] = 'تعذرت الإضافة — يمكن اسم المستخدم مستخدم مسبقًا / Could not add — username may already be taken.';
        }
    }
}

$users = get_db()->query('SELECT id, username, role, created_at FROM users ORDER BY id')->fetchAll();
$me = current_user();
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>المستخدمون / Users — Al Musafir for Car Rental</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="list-page">
<?php require __DIR__ . '/header.php'; ?>

<main class="container">
    <h1>المستخدمون / Users</h1>

    <?php if ($errors): ?>
        <div class="alert alert-error">
            <ul><?php foreach ($errors as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert" style="background:#e6f4ea;border:1px solid #b7dfc0;color:#1e6b34;"><?= h($success) ?></div>
    <?php endif; ?>

    <fieldset class="contract-form" style="max-width:600px;">
        <legend>إضافة مستخدم جديد / Add New User</legend>
        <form method="post">
            <div class="grid grid-3">
                <label>اسم المستخدم / Username
                    <input type="text" name="username" required>
                </label>
                <label>كلمة المرور / Password
                    <input type="password" name="password" required minlength="6">
                </label>
                <label>الصلاحية / Role
                    <select name="role">
                        <option value="staff">موظف / Staff</option>
                        <option value="admin">أدمن / Admin</option>
                    </select>
                </label>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">إضافة / Add</button>
            </div>
        </form>
    </fieldset>

    <div class="table-scroll" style="margin-top:24px;">
        <table class="list-table">
            <thead>
                <tr>
                    <th>اسم المستخدم / Username</th>
                    <th>الصلاحية / Role</th>
                    <th>تاريخ الإضافة / Added</th>
                    <th>إجراءات / Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= h($u['username']) ?><?= $u['id'] === $me['id'] ? ' (أنت / you)' : '' ?></td>
                    <td><?= $u['role'] === 'admin' ? 'أدمن / Admin' : 'موظف / Staff' ?></td>
                    <td><?= h($u['created_at']) ?></td>
                    <td class="actions">
                        <?php if ((int) $u['id'] !== (int) $me['id']): ?>
                        <form action="delete_user.php" method="post" onsubmit="return confirm('حذف هذا المستخدم؟ / Delete this user?');" class="inline-form">
                            <input type="hidden" name="id" value="<?= (int) $u['id'] ?>">
                            <button type="submit" class="link-danger">حذف · Delete</button>
                        </form>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<footer class="site-footer">
    <p>Al Musafir for Car Rental — commercial reg. no. 240168</p>
</footer>
</body>
</html>
