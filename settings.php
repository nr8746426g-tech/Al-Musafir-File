<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/helpers.php';
require __DIR__ . '/db.php';
require_admin();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $next = trim((string) ($_POST['next_contract_number'] ?? ''));
    if (!ctype_digit($next) || (int) $next < 1) {
        $errors[] = 'الرقم لازم يكون رقم صحيح موجب (1 أو أكثر) / Must be a positive whole number (1 or more).';
    } else {
        set_setting('next_contract_number', (string) (int) $next);
        $success = 'تم التحديث / Updated.';
    }
}

$next = (int) get_setting('next_contract_number', '1');
$preview = 'AM-' . date('Y') . '-' . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>الإعدادات / Settings — Al Musafir for Car Rental</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="list-page">
<?php require __DIR__ . '/header.php'; ?>

<main class="container">
    <h1>الإعدادات / Settings</h1>

    <?php if ($errors): ?>
        <div class="alert alert-error">
            <ul><?php foreach ($errors as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert" style="background:#e6f4ea;border:1px solid #b7dfc0;color:#1e6b34;"><?= h($success) ?></div>
    <?php endif; ?>

    <fieldset class="contract-form" style="max-width:500px;">
        <legend>ترقيم العقود / Contract Numbering</legend>
        <p class="hint">
            رقم العقد الجاي رح يكون: <strong style="color:var(--navy-dark);"><?= h($preview) ?></strong><br>
            Next contract will be numbered: <strong style="color:var(--navy-dark);"><?= h($preview) ?></strong>
        </p>
        <form method="post">
            <label>الرقم التالي / Next Number
                <input type="number" name="next_contract_number" value="<?= (int) $next ?>" min="1" required>
            </label>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">حفظ / Save</button>
            </div>
        </form>
        <p class="hint">
            هاد الرقم منفصل تمامًا عن حذف العقود — حذف عقد ما بيرجّع رقمه للاستخدام
            تلقائيًا، تقدر تغيّره هون يدويًا وقت ما بدك.<br>
            This number is independent of deleting contracts — deleting one never
            silently reuses its number; change it here manually whenever you need to.
        </p>
    </fieldset>
</main>

<footer class="site-footer">
    <p>Al Musafir for Car Rental — commercial reg. no. 240168</p>
</footer>
</body>
</html>
