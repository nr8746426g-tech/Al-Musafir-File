<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/helpers.php';
require __DIR__ . '/db.php';

if (current_user()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    $stmt = get_db()->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'username' => $user['username'],
            'role' => $user['role'],
        ];
        header('Location: index.php');
        exit;
    }

    $error = 'اسم المستخدم أو كلمة المرور غير صحيحة / Invalid username or password.';
}
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>تسجيل الدخول / Login — Al Musafir for Car Rental</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
<main class="login-box">
    <div class="brand" style="text-align:center; margin-bottom: 20px;">
        <div class="brand-ar" style="color:var(--navy); font-size:1.3rem;">المسافر لتأجير السيارات</div>
        <div class="brand-en" style="color:#5c6b78;">Al Musafir for Car Rental</div>
    </div>

    <h1>تسجيل الدخول / Login</h1>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= h($error) ?></div>
    <?php endif; ?>

    <form method="post" class="contract-form">
        <label>اسم المستخدم / Username
            <input type="text" name="username" required autofocus>
        </label>
        <label>كلمة المرور / Password
            <input type="password" name="password" required>
        </label>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary" style="width:100%;">دخول / Login</button>
        </div>
    </form>
</main>
</body>
</html>
