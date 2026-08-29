<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/helpers.php';
require_login();
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>المسافر لتأجير السيارات — Al Musafir for Car Rental</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="home-page">
<?php require __DIR__ . '/header.php'; ?>

<main class="container home-hero">
    <h1>نظام عقود تأجير السيارات<br><span class="en">Car Rental Contract System</span></h1>
    <p class="hint">أنشئ عقد تأجير سيارة، احفظه في قاعدة البيانات، ثم اطبعه ووقّعه.<br>Create a car rental contract, save it to the database, then print and sign it.</p>

    <div class="home-actions">
        <a href="form.php" class="btn btn-primary btn-lg">➕ عقد جديد / New Contract</a>
        <a href="contracts.php" class="btn btn-secondary btn-lg">📄 العقود المحفوظة / Saved Contracts</a>
    </div>
</main>

<footer class="site-footer">
    <p>Al Musafir for Car Rental — commercial reg. no. 240168</p>
</footer>
</body>
</html>
