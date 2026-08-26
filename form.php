<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/helpers.php';
require __DIR__ . '/db.php';

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
<header class="site-header">
    <div class="brand">
        <span class="brand-ar">المسافر لتأجير السيارات</span>
        <span class="brand-en">Al Musafir for Car Rental</span>
    </div>
    <nav>
        <a href="index.php">الرئيسية / Home</a>
        <a href="contracts.php">العقود المحفوظة / Saved Contracts</a>
    </nav>
</header>

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
            <legend>بيانات العقد / Contract Details</legend>
            <div class="grid grid-4">
                <label>رقم العقد / Contract No <span class="req">*</span>
                    <input type="text" name="contract_no" value="<?= val($values, 'contract_no') ?>" required>
                </label>
                <label>التاريخ / Date <span class="req">*</span>
                    <input type="date" name="contract_date" value="<?= val($values, 'contract_date') ?>" required>
                </label>
                <label>مكان التحرير / Place
                    <input type="text" name="place" value="<?= val($values, 'place') ?>">
                </label>
                <label>مدة العقد / Duration
                    <input type="text" name="duration" value="<?= val($values, 'duration') ?>" placeholder="e.g. 7 days">
                </label>
            </div>
        </fieldset>

        <fieldset>
            <legend>الطرف الأول (المؤجر) / First Party (Lessor)</legend>
            <p class="hint">المسافر لتأجير السيارات، سجل تجاري رقم 240168 — Al Musafir for Car Rental, commercial reg. no. 240168</p>
            <div class="grid grid-2">
                <label>يمثله (الاسم) / Represented by
                    <input type="text" name="lessor_represented_by" value="<?= val($values, 'lessor_represented_by') ?>">
                </label>
                <label>الصفة / Capacity
                    <input type="text" name="lessor_capacity" value="<?= val($values, 'lessor_capacity') ?>">
                </label>
                <label>العنوان / Address
                    <input type="text" name="lessor_address" value="<?= val($values, 'lessor_address') ?>">
                </label>
                <label>الهاتف / Phone
                    <input type="text" name="lessor_phone" value="<?= val($values, 'lessor_phone') ?>">
                </label>
            </div>
        </fieldset>

        <fieldset>
            <legend>الطرف الثاني (المستأجر) / Second Party (Renter)</legend>
            <div class="grid grid-2">
                <label>الاسم / Name <span class="req">*</span>
                    <input type="text" name="renter_name" value="<?= val($values, 'renter_name') ?>" required>
                </label>
                <label>رقم الهوية/الإقامة / ID or residency no.
                    <input type="text" name="renter_id_no" value="<?= val($values, 'renter_id_no') ?>">
                </label>
                <label>رقم رخصة القيادة / Licence no.
                    <input type="text" name="renter_license_no" value="<?= val($values, 'renter_license_no') ?>">
                </label>
                <label>تاريخ انتهاء الرخصة / Licence expiry
                    <input type="date" name="renter_license_expiry" value="<?= val($values, 'renter_license_expiry') ?>">
                </label>
                <label>العنوان / Address
                    <input type="text" name="renter_address" value="<?= val($values, 'renter_address') ?>">
                </label>
                <label>الهاتف / Phone
                    <input type="text" name="renter_phone" value="<?= val($values, 'renter_phone') ?>">
                </label>
            </div>
        </fieldset>

        <fieldset>
            <legend>بيانات السيارة / Vehicle Details</legend>
            <div class="grid grid-3">
                <label>الماركة والموديل / Make &amp; Model
                    <input type="text" name="veh_make_model" value="<?= val($values, 'veh_make_model') ?>">
                </label>
                <label>سنة الصنع / Year
                    <input type="text" name="veh_year" value="<?= val($values, 'veh_year') ?>">
                </label>
                <label>اللون / Colour
                    <input type="text" name="veh_colour" value="<?= val($values, 'veh_colour') ?>">
                </label>
                <label>رقم اللوحة / Plate No. <span class="req">*</span>
                    <input type="text" name="veh_plate_no" value="<?= val($values, 'veh_plate_no') ?>" required>
                </label>
                <label>رقم الهيكل / VIN
                    <input type="text" name="veh_vin" value="<?= val($values, 'veh_vin') ?>">
                </label>
                <label>العداد عند التسليم / Odometer
                    <input type="text" name="veh_odometer" value="<?= val($values, 'veh_odometer') ?>">
                </label>
            </div>
        </fieldset>

        <fieldset>
            <legend>البند 1: مدة عقد الإيجار / Clause 1: Rental Period</legend>
            <div class="grid grid-4">
                <label>تاريخ البدء / Start date <span class="req">*</span>
                    <input type="date" name="rental_start_date" value="<?= val($values, 'rental_start_date') ?>" required>
                </label>
                <label>الساعة / Start time
                    <input type="time" name="rental_start_time" value="<?= val($values, 'rental_start_time') ?>">
                </label>
                <label>تاريخ الانتهاء / End date <span class="req">*</span>
                    <input type="date" name="rental_end_date" value="<?= val($values, 'rental_end_date') ?>" required>
                </label>
                <label>الساعة / End time
                    <input type="time" name="rental_end_time" value="<?= val($values, 'rental_end_time') ?>">
                </label>
            </div>
        </fieldset>

        <fieldset>
            <legend>البند 2: قيمة الإيجار وطريقة الدفع / Clause 2: Rental Fee &amp; Payment</legend>
            <div class="grid grid-3">
                <label>القيمة الإجمالية / Total fee <span class="req">*</span>
                    <input type="number" step="0.01" name="total_fee" value="<?= val($values, 'total_fee') ?>" required>
                </label>
                <label>الضريبة / Tax
                    <select name="tax_status">
                        <option value="">—</option>
                        <option value="incl" <?= sel($values, 'tax_status', 'incl') ?>>شاملة / Incl.</option>
                        <option value="excl" <?= sel($values, 'tax_status', 'excl') ?>>غير شاملة / Excl.</option>
                    </select>
                </label>
                <label>طريقة الدفع / Payment method
                    <select name="payment_method">
                        <option value="">—</option>
                        <option value="cash" <?= sel($values, 'payment_method', 'cash') ?>>نقدًا / Cash</option>
                        <option value="card" <?= sel($values, 'payment_method', 'card') ?>>بطاقة بنكية / Card</option>
                        <option value="transfer" <?= sel($values, 'payment_method', 'transfer') ?>>تحويل / Transfer</option>
                    </select>
                </label>
                <label>الدفعة الأولى / First instalment
                    <input type="number" step="0.01" name="first_instalment" value="<?= val($values, 'first_instalment') ?>">
                </label>
                <label>باقي المبلغ يستحق / Balance due
                    <input type="text" name="balance_due_note" value="<?= val($values, 'balance_due_note') ?>" placeholder="e.g. on return">
                </label>
            </div>
        </fieldset>

        <fieldset>
            <legend>البند 3: مبلغ التأمين / Clause 3: Security Deposit</legend>
            <div class="grid grid-2">
                <label>مبلغ التأمين / Deposit amount
                    <input type="number" step="0.01" name="deposit_amount" value="<?= val($values, 'deposit_amount') ?>">
                </label>
                <label>يُرد خلال (أيام) / Returned within (days)
                    <input type="number" name="deposit_return_days" value="<?= val($values, 'deposit_return_days') ?>">
                </label>
            </div>
        </fieldset>

        <fieldset>
            <legend>البند 4: حد الكيلومترات / Clause 4: Mileage Limit</legend>
            <div class="grid grid-2">
                <label>الحد المسموح (كم/يوم) / Limit (km/day)
                    <input type="number" name="mileage_limit_km" value="<?= val($values, 'mileage_limit_km') ?>">
                </label>
                <label>رسم الكيلومتر الزائد / Extra km charge
                    <input type="number" step="0.01" name="extra_km_charge" value="<?= val($values, 'extra_km_charge') ?>">
                </label>
            </div>
        </fieldset>

        <fieldset>
            <legend>البند 5: سياسة الوقود / Clause 5: Fuel Policy</legend>
            <div class="grid grid-2">
                <label>مستوى الوقود عند التسليم / Fuel level at handover
                    <input type="text" name="fuel_level" value="<?= val($values, 'fuel_level') ?>" placeholder="e.g. Full / 3/4 / 1/2">
                </label>
                <label>رسم خدمة التعبئة / Refuelling service fee
                    <input type="number" step="0.01" name="fuel_service_fee" value="<?= val($values, 'fuel_service_fee') ?>">
                </label>
            </div>
        </fieldset>

        <fieldset>
            <legend>البند 6: التأمين على السيارة / Clause 6: Vehicle Insurance</legend>
            <div class="grid grid-3">
                <label>نوع التأمين / Insurance type
                    <select name="insurance_type">
                        <option value="">—</option>
                        <option value="comprehensive" <?= sel($values, 'insurance_type', 'comprehensive') ?>>شامل / Comprehensive</option>
                        <option value="third_party" <?= sel($values, 'insurance_type', 'third_party') ?>>ضد الغير / Third-party</option>
                    </select>
                </label>
                <label>شركة التأمين / Insurance company
                    <input type="text" name="insurance_company" value="<?= val($values, 'insurance_company') ?>">
                </label>
                <label>مبلغ التحمل / Deductible
                    <input type="number" step="0.01" name="deductible_amount" value="<?= val($values, 'deductible_amount') ?>">
                </label>
            </div>
        </fieldset>

        <fieldset>
            <legend>البند 7: غرامة التأخير / Clause 7: Late Return Penalty</legend>
            <div class="grid grid-2">
                <label>مبلغ الغرامة / Penalty amount
                    <input type="number" step="0.01" name="late_penalty_amount" value="<?= val($values, 'late_penalty_amount') ?>">
                </label>
                <label>لكل / Per
                    <select name="late_penalty_unit">
                        <option value="">—</option>
                        <option value="hour" <?= sel($values, 'late_penalty_unit', 'hour') ?>>ساعة / Hour</option>
                        <option value="day" <?= sel($values, 'late_penalty_unit', 'day') ?>>يوم / Day</option>
                    </select>
                </label>
            </div>
        </fieldset>

        <fieldset>
            <legend>البند 8: فحص حالة السيارة / Clause 8: Vehicle Condition Inspection</legend>
            <table class="inspection-input-table">
                <thead>
                    <tr>
                        <th>البند / Item</th>
                        <th>عند التسليم / At Handover</th>
                        <th>عند الاستلام / At Return</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $inspectionItems = [
                    'body_paint'    => 'الهيكل والطلاء / Body &amp; paint',
                    'glass_mirrors' => 'الزجاج والمرايا / Glass &amp; mirrors',
                    'tyres_rims'    => 'الإطارات والجنوط / Tyres &amp; rims',
                    'lights'        => 'الأضواء / Lights',
                    'interior'      => 'المقصورة الداخلية / Interior',
                    'accessories'   => 'الأدوات والملحقات / Accessories &amp; tools',
                ];
                foreach ($inspectionItems as $key => $label):
                ?>
                    <tr>
                        <td><?= $label ?></td>
                        <td><input type="text" name="insp_<?= $key ?>_handover" value="<?= val($values, 'insp_' . $key . '_handover') ?>"></td>
                        <td><input type="text" name="insp_<?= $key ?>_return" value="<?= val($values, 'insp_' . $key . '_return') ?>"></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </fieldset>

        <fieldset>
            <legend>البند 9: الإلغاء وإنهاء العقد / Clause 9: Cancellation &amp; Termination</legend>
            <div class="grid grid-2">
                <label>مدة الإشعار الكتابي المسبق / Prior written notice period
                    <input type="text" name="cancellation_notice_period" value="<?= val($values, 'cancellation_notice_period') ?>" placeholder="e.g. 3 days">
                </label>
            </div>
        </fieldset>

        <fieldset>
            <legend>التوقيعات / Signatures</legend>
            <div class="grid grid-4">
                <label>اسم المؤجر / Lessor name
                    <input type="text" name="lessor_sign_name" value="<?= val($values, 'lessor_sign_name') ?>">
                </label>
                <label>تاريخ توقيع المؤجر / Lessor sign date
                    <input type="date" name="lessor_sign_date" value="<?= val($values, 'lessor_sign_date') ?>">
                </label>
                <label>اسم المستأجر / Renter name
                    <input type="text" name="renter_sign_name" value="<?= val($values, 'renter_sign_name') ?>">
                </label>
                <label>تاريخ توقيع المستأجر / Renter sign date
                    <input type="date" name="renter_sign_date" value="<?= val($values, 'renter_sign_date') ?>">
                </label>
            </div>
            <p class="hint">التوقيع الفعلي والختم يُضافان يدويًا على النسخة المطبوعة. / The actual signature &amp; stamp are added by hand on the printed copy.</p>
        </fieldset>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?= $id ? 'حفظ التعديلات / Save Changes' : 'حفظ وإنشاء العقد / Save &amp; Create Contract' ?></button>
            <a href="<?= $id ? 'contracts.php' : 'index.php' ?>" class="btn btn-secondary">إلغاء / Cancel</a>
        </div>
    </form>
</main>

<footer class="site-footer">
    <p>Al Musafir for Car Rental — commercial reg. no. 240168</p>
</footer>
</body>
</html>
