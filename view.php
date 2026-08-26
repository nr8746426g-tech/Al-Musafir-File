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

    <header class="contract-header">
        <h1>عقد تأجير سيارات</h1>
        <h2>Car Rental Agreement</h2>
        <p class="note">نموذج عقد قابل للتعبئة — يُرجى إكمال الحقول المشار إليها بخط قبل التوقيع | Fillable template — complete the blank fields before signing</p>
    </header>

    <table class="info-table">
        <tr>
            <td class="lbl">رقم العقد / Contract No.</td>
            <td><?= pv($c, 'contract_no') ?></td>
            <td class="lbl">التاريخ / Date</td>
            <td><?= pdate($c, 'contract_date') ?></td>
        </tr>
        <tr>
            <td class="lbl">مكان التحرير / Place</td>
            <td><?= pv($c, 'place') ?></td>
            <td class="lbl">مدة العقد / Duration</td>
            <td><?= pv($c, 'duration') ?></td>
        </tr>
    </table>

    <p class="intro">
        The two parties identified below have agreed to enter into this car rental agreement, governed by the terms and conditions set out herein:
        <br><br>
        تم الاتفاق والتراضي بين الطرفين الآتي بيانهما على إبرام عقد تأجير سيارة، بموجب الشروط والأحكام الواردة في هذا العقد:
    </p>

    <section class="clause">
        <h3>المتعاقدان / The Parties</h3>
        <p>
            <strong>First Party (Lessor):</strong> Al Musafir for Car Rental (Limited Liability Company Owned by One Person), commercial reg. no. 240168, represented by <?= pv($c, 'lessor_represented_by') ?>, capacity <?= pv($c, 'lessor_capacity') ?>, hereinafter the "Lessor".
            Address: <?= pv($c, 'lessor_address') ?> — Phone: <?= pv($c, 'lessor_phone') ?>
        </p>
        <p dir="rtl">
            <strong>الطرف الأول (المؤجر):</strong> المسافر لتأجير السيارات (شركة ذات مسؤولية محدودة مالكها شخص واحد)، سجل تجاري رقم 240168، يمثله <?= pv($c, 'lessor_represented_by') ?> بصفته <?= pv($c, 'lessor_capacity') ?>، ويشار إليه بـ "المؤجر".
            العنوان: <?= pv($c, 'lessor_address') ?> — الهاتف: <?= pv($c, 'lessor_phone') ?>
        </p>
        <hr class="soft">
        <p>
            <strong>Second Party (Renter):</strong> Name <?= pv($c, 'renter_name') ?>, ID/residency no. <?= pv($c, 'renter_id_no') ?>, licence no. <?= pv($c, 'renter_license_no') ?>, expiring <?= pdate($c, 'renter_license_expiry') ?>.
            Address: <?= pv($c, 'renter_address') ?> — Phone: <?= pv($c, 'renter_phone') ?>, hereinafter the "Renter".
        </p>
        <p dir="rtl">
            <strong>الطرف الثاني (المستأجر):</strong> الاسم <?= pv($c, 'renter_name') ?>، رقم الهوية/الإقامة <?= pv($c, 'renter_id_no') ?>، رخصة القيادة <?= pv($c, 'renter_license_no') ?> وتاريخ انتهائها <?= pdate($c, 'renter_license_expiry') ?>.
            العنوان: <?= pv($c, 'renter_address') ?> — الهاتف: <?= pv($c, 'renter_phone') ?>، ويشار إليه بـ "المستأجر".
        </p>
    </section>

    <table class="vehicle-table">
        <thead>
            <tr>
                <th>الماركة والموديل<br>Make &amp; Model</th>
                <th>سنة الصنع<br>Year</th>
                <th>اللون<br>Colour</th>
                <th>رقم اللوحة<br>Plate No.</th>
                <th>رقم الهيكل<br>VIN</th>
                <th>العداد عند التسليم<br>Odometer</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= pv($c, 'veh_make_model') ?></td>
                <td><?= pv($c, 'veh_year') ?></td>
                <td><?= pv($c, 'veh_colour') ?></td>
                <td><?= pv($c, 'veh_plate_no') ?></td>
                <td><?= pv($c, 'veh_vin') ?></td>
                <td><?= pv($c, 'veh_odometer') ?></td>
            </tr>
        </tbody>
    </table>

    <section class="clause">
        <h3>البند 1: مدة عقد الإيجار — Clause 1: Rental Period</h3>
        <p>The rental period runs from <?= pdate($c, 'rental_start_date') ?> at <?= ptime($c, 'rental_start_time') ?> to <?= pdate($c, 'rental_end_date') ?> at <?= ptime($c, 'rental_end_time') ?>. May be extended by written agreement before it expires.</p>
        <p dir="rtl">تبدأ مدة الإيجار من تاريخ <?= pdate($c, 'rental_start_date') ?> الساعة <?= ptime($c, 'rental_start_time') ?>، وتنتهي في تاريخ <?= pdate($c, 'rental_end_date') ?> الساعة <?= ptime($c, 'rental_end_time') ?>. يجوز تمديدها بموافقة خطية قبل انتهاء المدة.</p>
    </section>

    <section class="clause">
        <h3>البند 2: قيمة الإيجار وطريقة الدفع — Clause 2: Rental Fee &amp; Payment</h3>
        <p>Total fee: <?= pv($c, 'total_fee') ?> (<?= penum($c, 'tax_status') ?>). Payment: <?= penum($c, 'payment_method') ?>. First instalment <?= pv($c, 'first_instalment') ?> due on signing; balance due <?= pv($c, 'balance_due_note') ?>.</p>
        <p dir="rtl">القيمة الإجمالية: <?= pv($c, 'total_fee') ?> (<?= penum($c, 'tax_status') ?>). طريقة الدفع: <?= penum($c, 'payment_method') ?>. دفعة أولى <?= pv($c, 'first_instalment') ?> عند التوقيع، والباقي <?= pv($c, 'balance_due_note') ?>.</p>
    </section>

    <section class="clause">
        <h3>البند 3: مبلغ التأمين (الوديعة) — Clause 3: Security Deposit</h3>
        <p>A refundable deposit of <?= pv($c, 'deposit_amount') ?> is payable, returned within <?= pv($c, 'deposit_return_days') ?> days of return, after deducting any amounts owed.</p>
        <p dir="rtl">يدفع المستأجر تأمينًا قابلًا للاسترداد قدره <?= pv($c, 'deposit_amount') ?>، يُرد خلال <?= pv($c, 'deposit_return_days') ?> أيام من الإرجاع، بعد خصم أي مستحقات.</p>
    </section>

    <section class="clause">
        <h3>البند 4: حد الكيلومترات المسموح به — Clause 4: Mileage Limit</h3>
        <p>Permitted limit <?= pv($c, 'mileage_limit_km') ?> km/day; each extra km charged at <?= pv($c, 'extra_km_charge') ?>.</p>
        <p dir="rtl">الحد المسموح <?= pv($c, 'mileage_limit_km') ?> كم/يوم، وتُحسب كل كيلومتر زائد بمبلغ <?= pv($c, 'extra_km_charge') ?>.</p>
    </section>

    <section class="clause">
        <h3>البند 5: سياسة الوقود — Clause 5: Fuel Policy</h3>
        <p>Vehicle handed over with fuel level <?= pv($c, 'fuel_level') ?>, to be returned at the same level. Shortfall charged at refuelling cost plus service fee <?= pv($c, 'fuel_service_fee') ?>.</p>
        <p dir="rtl">تُسلَّم السيارة بخزان وقود <?= pv($c, 'fuel_level') ?> وتُرجع بنفس المستوى. عند النقص يتحمل المستأجر تكلفة التعبئة إضافة لرسم خدمة <?= pv($c, 'fuel_service_fee') ?>.</p>
    </section>

    <section class="clause">
        <h3>البند 6: التأمين على السيارة — Clause 6: Vehicle Insurance</h3>
        <p>Vehicle insured (<?= penum($c, 'insurance_type') ?>) with <?= pv($c, 'insurance_company') ?>. Renter liable for a deductible of <?= pv($c, 'deductible_amount') ?> per incident. Insurance excludes damage from Renter negligence or breach of this agreement.</p>
        <p dir="rtl">السيارة مؤمَّنة (<?= penum($c, 'insurance_type') ?>) لدى <?= pv($c, 'insurance_company') ?>. يتحمل المستأجر مبلغ <?= pv($c, 'deductible_amount') ?> عن كل حادث. لا يشمل التأمين أضرار الإهمال أو مخالفة العقد.</p>
    </section>

    <section class="clause">
        <h3>البند 7: غرامة التأخير في الإرجاع — Clause 7: Late Return Penalty</h3>
        <p>Return after the agreed time without prior written notice incurs <?= pv($c, 'late_penalty_amount') ?> per <?= penum($c, 'late_penalty_unit') ?> of delay, and is deemed a breach of this agreement.</p>
        <p dir="rtl">عند التأخر عن الإرجاع دون إشعار كتابي مسبق، يُحتسب عن كل <?= penum($c, 'late_penalty_unit') ?> تأخير مبلغ <?= pv($c, 'late_penalty_amount') ?>، ويُعد ذلك خرقًا للعقد.</p>
    </section>

    <section class="clause">
        <h3>البند 8: فحص حالة السيارة — Clause 8: Vehicle Condition Inspection</h3>
        <table class="inspection-table">
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
                    <td><?= pv($c, 'insp_' . $key . '_handover') ?></td>
                    <td><?= pv($c, 'insp_' . $key . '_return') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section class="clause">
        <h3>البند 9: الإلغاء وإنهاء العقد — Clause 9: Cancellation &amp; Termination</h3>
        <p>Either party may terminate before the end date with <?= pv($c, 'cancellation_notice_period') ?> prior written notice. The Lessor may terminate immediately and repossess the vehicle without notice if the Renter breaches any term herein.</p>
        <p dir="rtl">يجوز لأي طرف إنهاء العقد قبل الموعد المحدد بإشعار كتابي مسبق مدته <?= pv($c, 'cancellation_notice_period') ?>. ويحق للمؤجر فسخ العقد فورًا واستعادة السيارة دون إشعار عند مخالفة المستأجر لأي بند.</p>
    </section>

    <section class="clause">
        <h3>البند 10: القانون الحاكم وتسوية النزاعات — Clause 10: Governing Law &amp; Disputes</h3>
        <p>This agreement is governed by and construed in accordance with the laws of the <strong>State of Qatar</strong>. Disputes shall first be resolved amicably; failing that, referred to the competent courts of the State of Qatar.</p>
        <p dir="rtl">يخضع هذا العقد ويُفسَّر وفقًا لأنظمة <strong>دولة قطر</strong>. وفي حال نشوء نزاع يسعى الطرفان لحله وديًا، وعند التعذر يُرفع النزاع إلى المحاكم المختصة في دولة قطر.</p>
    </section>

    <section class="clause">
        <h3>البند 11: أحكام عامة — Clause 11: General Provisions</h3>
        <ul class="bilingual-list">
            <li><span>This agreement and its annexes form one indivisible document.</span><span dir="rtl">يُعتبر هذا العقد وملحقاته وثيقة واحدة لا تتجزأ.</span></li>
            <li><span>No term may be amended except by written consent of both parties.</span><span dir="rtl">لا يجوز تعديل أي بند إلا بموافقة خطية من الطرفين.</span></li>
            <li><span>Executed in two original copies, one for each party.</span><span dir="rtl">حُرر من نسختين أصليتين، بيد كل طرف نسخة للعمل بموجبها.</span></li>
        </ul>
    </section>

    <section class="signatures">
        <h3>التوقيعات — Signatures</h3>
        <div class="sig-grid">
            <div class="sig-block">
                <h4>First Party (Lessor) — الطرف الأول (المؤجر)</h4>
                <p>Name / الاسم: <?= pv($c, 'lessor_sign_name') ?></p>
                <p>Signature / التوقيع: <span class="blank sig-line">&nbsp;</span></p>
                <p>Date / التاريخ: <?= pdate($c, 'lessor_sign_date') ?></p>
                <p>Company Stamp / الختم: <span class="blank sig-line">&nbsp;</span></p>
            </div>
            <div class="sig-block">
                <h4>Second Party (Renter) — الطرف الثاني (المستأجر)</h4>
                <p>Name / الاسم: <?= pv($c, 'renter_sign_name') ?></p>
                <p>Signature / التوقيع: <span class="blank sig-line">&nbsp;</span></p>
                <p>Date / التاريخ: <?= pdate($c, 'renter_sign_date') ?></p>
            </div>
        </div>
    </section>

</article>
</body>
</html>
