<?php
require __DIR__ . '/helpers.php';
require __DIR__ . '/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$stmt = get_db()->prepare('SELECT * FROM contracts WHERE id = ?');
$stmt->execute([$id]);
$c = $stmt->fetch();

if (!$c) {
    http_response_code(404);
    exit('Contract not found / العقد غير موجود');
}
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>عقد رقم <?= h($c['contract_no']) ?> — Al Musafir for Car Rental</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="view-page">
<div class="toolbar no-print">
    <a href="index.php">الرئيسية / Home</a>
    <a href="contracts.php">العقود المحفوظة / Saved Contracts</a>
    <a href="form.php?id=<?= (int) $c['id'] ?>">تعديل / Edit</a>
    <button onclick="window.print()" class="btn btn-primary">🖨️ طباعة / Print</button>
</div>

<article class="contract-sheet">

    <img src="assets/img/logo-header.png" alt="Al Musafir for Car Rental" class="logo-header">

    <div class="title-bar" dir="ltr">
        <span class="en">Car Rental Agreement</span>
        <span class="ar">عقد إيجار سيارة</span>
    </div>

    <p class="intro" dir="ltr">
        <span class="en">This agreement is made between the following parties:</span>
        <span class="ar">تم تحرير هذا العقد بين كل من:</span>
    </p>

    <table class="party-table" dir="ltr">
        <tr>
            <th class="en">First Party (Lessee)</th>
            <th class="ar">الطرف الأول (المستأجر)</th>
        </tr>
        <tr>
            <td class="en">
                Name: <?= pv($c, 'lessee_name') ?><br>
                Nationality: <?= pv($c, 'lessee_nationality') ?><br>
                ID / Passport No.: <?= pv($c, 'lessee_id_no') ?><br>
                Phone No.: <?= pv($c, 'lessee_phone') ?>
            </td>
            <td class="ar">
                الاسم: <?= pv($c, 'lessee_name') ?><br>
                الجنسية: <?= pv($c, 'lessee_nationality') ?><br>
                رقم البطاقة / الجواز: <?= pv($c, 'lessee_id_no') ?><br>
                رقم الهاتف: <?= pv($c, 'lessee_phone') ?>
            </td>
        </tr>
        <tr>
            <th class="en">Second Party (Lessor)</th>
            <th class="ar">الطرف الثاني (المؤجر)</th>
        </tr>
        <tr>
            <td class="en"><strong>Al Musafir for Car Rental Company</strong></td>
            <td class="ar"><strong>شركة المسافر لتأجير السيارات</strong></td>
        </tr>
    </table>

    <p class="intro intro-bold" dir="ltr">
        <span class="en">Both parties, being of full legal capacity to contract, have agreed as follows:</span>
        <span class="ar">ويقرّ الطرفان بأهليتهما للتعاقد، وقد اتفقا على ما يلي:</span>
    </p>

    <table class="articles-table" dir="ltr">
        <thead>
            <tr><th class="en">English</th><th class="ar">العربية</th></tr>
        </thead>
        <tbody>
            <tr>
                <td class="en">
                    <strong class="art-title">Article 1</strong><br>
                    The First Party has rented from the Second Party the following vehicle:<br>
                    Vehicle type: <?= pv($c, 'veh_type') ?><br>
                    Plate number: <?= pv($c, 'veh_plate_no') ?><br>
                    Color: <?= pv($c, 'veh_colour') ?><br>
                    Odometer reading: <?= pv($c, 'veh_odometer') ?><br>
                    Restricted: <?= penum($c, 'mileage_restricted', 'en') ?><br>
                    Allowed mileage: <?= pv($c, 'allowed_mileage_km') ?> km per day; any excess is charged at QAR 0.5 per km.
                </td>
                <td class="ar">
                    <strong class="art-title">البند الأول</strong><br>
                    استأجر الطرف الأول من الطرف الثاني السيارة التالية:<br>
                    نوع المركبة: <?= pv($c, 'veh_type') ?><br>
                    رقم اللوحة: <?= pv($c, 'veh_plate_no') ?><br>
                    اللون: <?= pv($c, 'veh_colour') ?><br>
                    قراءة العداد: <?= pv($c, 'veh_odometer') ?><br>
                    المقيد: <?= penum($c, 'mileage_restricted', 'ar') ?><br>
                    المسافة المسموحة: <?= pv($c, 'allowed_mileage_km') ?> كيلومتر في اليوم، وإذا تجاوزها يُدفع 0.5 ريال قطري عن كل كيلومتر.
                </td>
            </tr>
            <tr>
                <td class="en">
                    <strong class="art-title">Article 2</strong><br>
                    Rental period: <?= pv($c, 'rental_days') ?> day(s). Rent paid: QAR <?= pv($c, 'rent_paid') ?><br>
                    From <?= ptime($c, 'rental_start_time') ?> until <?= ptime($c, 'rental_end_time') ?> of <?= pdate($c, 'rental_start_date') ?><br>
                    The agreement ends on <?= pdate($c, 'rental_end_date') ?> at the close of the final hour.
                </td>
                <td class="ar">
                    <strong class="art-title">البند الثاني</strong><br>
                    مدة الإيجار: <?= pv($c, 'rental_days') ?> يوم. المبلغ المدفوع: <?= pv($c, 'rent_paid') ?> ريال قطري.<br>
                    تبدأ من الساعة <?= ptime($c, 'rental_start_time') ?> حتى الساعة <?= ptime($c, 'rental_end_time') ?> من يوم <?= pdate($c, 'rental_start_date') ?><br>
                    وينتهي العقد بتاريخ <?= pdate($c, 'rental_end_date') ?> عند انتهاء الساعة الأخيرة.
                </td>
            </tr>
            <tr>
                <td class="en">
                    <strong class="art-title">Article 3</strong><br>
                    This agreement may only be renewed by a new agreement.<br>
                    Security deposit paid: QAR <?= pv($c, 'security_deposit') ?>
                </td>
                <td class="ar">
                    <strong class="art-title">البند الثالث</strong><br>
                    لا يُجدَّد هذا العقد إلا بعقد آخر.<br>
                    مبلغ التأمين المدفوع: <?= pv($c, 'security_deposit') ?> ريال قطري.
                </td>
            </tr>
            <tr>
                <td class="en"><strong class="art-title">Article 4</strong><br>The First Party acknowledges receipt of the rented vehicle at the agreed time and bears full responsibility for it from the start of the rental until its end (accidents, violations).</td>
                <td class="ar"><strong class="art-title">البند الرابع</strong><br>يقرّ الطرف الأول بأنه تسلّم السيارة المؤجَّرة في الوقت المحدد للإيجار، وهو مسؤول عنها اعتباراً من بداية الإيجار حتى نهايته (حوادث، مخالفات).</td>
            </tr>
            <tr>
                <td class="en"><strong class="art-title">Article 5</strong><br>The vehicle must be returned in the same condition it was received, on the date specified at the end of the rental term.</td>
                <td class="ar"><strong class="art-title">البند الخامس</strong><br>تُرجَع السيارة كما استُلمت، وفي اليوم المحدد لنهاية مدة الإيجار.</td>
            </tr>
            <tr>
                <td class="en"><strong class="art-title">Article 6</strong><br>Traffic violations must be settled by the day following their issuance; any delay in payment voids this agreement.</td>
                <td class="ar"><strong class="art-title">البند السادس</strong><br>تُسدَّد المخالفات في ثاني يوم من تاريخ المخالفة، وأي تأخّر في التسديد يُلغى العقد.</td>
            </tr>
            <tr>
                <td class="en"><strong class="art-title">Article 7</strong><br>Accidents are the driver's responsibility.</td>
                <td class="ar"><strong class="art-title">البند السابع</strong><br>الحوادث تحت مسؤولية السائق.</td>
            </tr>
            <tr>
                <td class="en"><strong class="art-title">Article 8</strong><br>A penalty of QAR 100 per day applies for any delay in returning the vehicle or renewing the agreement.</td>
                <td class="ar"><strong class="art-title">البند الثامن</strong><br>غرامة التأخير عن تسليم السيارة أو تجديد العقد 100 ريال قطري لليوم الواحد.</td>
            </tr>
            <tr>
                <td class="en"><strong class="art-title">Article 9</strong><br>The lessee is obligated to return the vehicle in the same condition in which it was received.</td>
                <td class="ar"><strong class="art-title">البند التاسع</strong><br>المستأجر مُلزَم بتسليم السيارة على نفس الحالة التي استلمها بها.</td>
            </tr>
            <tr>
                <td class="en"><strong class="art-title">Article 10</strong><br>The First Party shall pay QAR 500 plus the cost of the vehicle's downtime during repair.</td>
                <td class="ar"><strong class="art-title">البند العاشر</strong><br>يقوم الطرف الأول بدفع 500 ريال قطري + قيمة أيام توقّف السيارة أثناء التصليح.</td>
            </tr>
        </tbody>
    </table>

    <div class="sig-row" dir="ltr">
        <div class="sig-block">
            <span class="label">Second Party Signature<br>إمضاء الطرف الثاني</span>
            <span class="sig-line">&nbsp;</span>
        </div>
        <div class="sig-block">
            <span class="label">First Party Signature<br>إمضاء الطرف الأول</span>
            <span class="sig-line">&nbsp;</span>
        </div>
    </div>

    <img src="assets/img/footer-bar.png" alt="Al Musafir for Car Rental — Tel: +974 3330 7747" class="logo-footer">

</article>
</body>
</html>
