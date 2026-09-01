<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/helpers.php';
require __DIR__ . '/db.php';
require_login();

$values = [];
$errors = [];
$id = null;

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = get_db()->prepare('SELECT * FROM contracts WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) {
        http_response_code(404);
        exit('Contract not found / العقد غير موجود');
    }
    $values = $row;
}

// Repopulate from a failed submission (see save.php).
if (!empty($_SESSION['form_errors'])) {
    $errors = $_SESSION['form_errors'];
    $values = $_SESSION['form_values'];
    unset($_SESSION['form_errors'], $_SESSION['form_values']);
}

$pageTitle = $id ? 'تعديل العقد / Edit Contract' : 'عقد جديد / New Contract';
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= h($pageTitle) ?> — Al Musafir for Car Rental</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="form-page">
<?php require __DIR__ . '/header.php'; ?>

<main class="container">
    <h1><?= h($pageTitle) ?></h1>

    <?php if ($errors): ?>
        <div class="alert alert-error">
            <strong>الرجاء تصحيح الأخطاء التالية / Please fix the following:</strong>
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= h($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="save.php" method="post" class="contract-form" novalidate>
        <?php if ($id): ?>
            <input type="hidden" name="id" value="<?= (int) $id ?>">
        <?php endif; ?>

        <fieldset>
            <legend>رقم العقد / Contract No.</legend>
            <div class="grid grid-2">
                <label>رقم العقد (للأرشيف الداخلي، لا يظهر على النموذج المطبوع) / Contract No. (internal record, not printed)
                    <?php if ($id): ?>
                        <input type="text" value="<?= val($values, 'contract_no') ?>" readonly>
                    <?php else: ?>
                        <input type="text" value="سيتولد تلقائيًا بعد الحفظ / auto-generated on save" readonly>
                    <?php endif; ?>
                </label>
            </div>
        </fieldset>

        <fieldset>
            <legend>الطرف الأول (المستأجر) / First Party (Lessee)</legend>
            <div class="grid grid-4">
                <label>الاسم / Name <span class="req">*</span>
                    <input type="text" name="lessee_name" value="<?= val($values, 'lessee_name') ?>" required>
                </label>
                <label>الجنسية / Nationality
                    <input type="text" name="lessee_nationality" value="<?= val($values, 'lessee_nationality') ?>">
                </label>
                <label>رقم البطاقة / الجواز / ID or Passport No.
                    <input type="text" name="lessee_id_no" value="<?= val($values, 'lessee_id_no') ?>">
                </label>
                <label>رقم الهاتف / Phone No.
                    <input type="text" name="lessee_phone" value="<?= val($values, 'lessee_phone') ?>">
                </label>
            </div>
            <p class="hint">الطرف الثاني (المؤجر) ثابت دائمًا: شركة المسافر لتأجير السيارات. / Second Party (Lessor) is always fixed: Al Musafir for Car Rental Company.</p>
        </fieldset>

        <fieldset>
            <legend>البند 1: السيارة / Article 1: Vehicle</legend>
            <div class="grid grid-3">
                <label>نوع المركبة / Vehicle Type
                    <input type="text" name="veh_type" value="<?= val($values, 'veh_type') ?>">
                </label>
                <label>رقم اللوحة / Plate Number <span class="req">*</span>
                    <input type="text" name="veh_plate_no" value="<?= val($values, 'veh_plate_no') ?>" required>
                </label>
                <label>اللون / Colour
                    <input type="text" name="veh_colour" value="<?= val($values, 'veh_colour') ?>">
                </label>
                <label>قراءة العداد / Odometer Reading
                    <input type="text" name="veh_odometer" value="<?= val($values, 'veh_odometer') ?>">
                </label>
                <label>المقيد / Restricted
                    <select name="mileage_restricted">
                        <option value="">—</option>
                        <option value="yes" <?= sel($values, 'mileage_restricted', 'yes') ?>>نعم / Yes</option>
                        <option value="no" <?= sel($values, 'mileage_restricted', 'no') ?>>لا / No</option>
                    </select>
                </label>
                <label>المسافة المسموحة (كم/يوم) / Allowed Mileage (km/day)
                    <input type="number" name="allowed_mileage_km" value="<?= val($values, 'allowed_mileage_km') ?>" placeholder="e.g. 300">
                </label>
            </div>
            <p class="hint">أي تجاوز يُحتسب 0.5 ريال قطري لكل كيلومتر (ثابت بالعقد). / Any excess is charged at a fixed QAR 0.5 per km.</p>
        </fieldset>

        <fieldset>
            <legend>البند 2: مدة الإيجار / Article 2: Rental Period</legend>
            <div class="grid grid-3">
                <label>عدد الأيام / Days
                    <input type="number" name="rental_days" value="<?= val($values, 'rental_days') ?>">
                </label>
                <label>المبلغ المدفوع (ر.ق) / Rent Paid (QAR)
                    <input type="number" step="0.01" name="rent_paid" value="<?= val($values, 'rent_paid') ?>">
                </label>
                <label></label>
                <label>تاريخ البداية / Start Date <span class="req">*</span>
                    <input type="date" name="rental_start_date" value="<?= val($values, 'rental_start_date') ?>" required>
                </label>
                <label>الساعة / Start Time
                    <input type="time" name="rental_start_time" value="<?= val($values, 'rental_start_time') ?>">
                </label>
                <label></label>
                <label>تاريخ الانتهاء / End Date <span class="req">*</span>
                    <input type="date" name="rental_end_date" value="<?= val($values, 'rental_end_date') ?>" required>
                </label>
                <label>الساعة / End Time
                    <input type="time" name="rental_end_time" value="<?= val($values, 'rental_end_time') ?>">
                </label>
            </div>
        </fieldset>

        <fieldset>
            <legend>البند 3: مبلغ التأمين / Article 3: Security Deposit</legend>
            <div class="grid grid-2">
                <label>مبلغ التأمين المدفوع (ر.ق) / Security Deposit Paid (QAR)
                    <input type="number" step="1" name="security_deposit" value="<?= val($values, 'security_deposit') ?>">
                </label>
            </div>
            <p class="hint">البنود 4 إلى 10 نصوص ثابتة بالعقد ولا تحتاج تعبئة. / Articles 4-10 are fixed contract text and need no input.</p>
        </fieldset>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $id ? 'حفظ التعديلات / Save Changes' : 'حفظ وإنشاء العقد / Save &amp; Create Contract' ?></button>
            <a href="<?= $id ? 'contracts.php' : 'index.php' ?>" class="btn btn-secondary">إلغاء / Cancel</a>
        </div>
    </form>
</main>

<footer class="site-footer">
    <p>Al Musafir for Car Rental — Tel: +974 3330 7747</p>
</footer>
</body>
</html>
