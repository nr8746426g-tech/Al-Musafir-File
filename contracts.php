<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/helpers.php';
require __DIR__ . '/db.php';
require_login();

$q = trim((string) ($_GET['q'] ?? ''));

if ($q !== '') {
    $stmt = get_db()->prepare(
        'SELECT id, contract_no, lessee_name, veh_plate_no, rental_start_date, rent_paid, created_at
         FROM contracts
         WHERE contract_no LIKE :q OR lessee_name LIKE :q OR veh_plate_no LIKE :q
         ORDER BY id DESC'
    );
    $stmt->execute(['q' => '%' . $q . '%']);
} else {
    $stmt = get_db()->query(
        'SELECT id, contract_no, lessee_name, veh_plate_no, rental_start_date, rent_paid, created_at
         FROM contracts ORDER BY id DESC'
    );
}
$contracts = $stmt->fetchAll();
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>العقود المحفوظة / Saved Contracts — Al Musafir for Car Rental</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="list-page">
<?php require __DIR__ . '/header.php'; ?>

<main class="container">
    <h1>العقود المحفوظة / Saved Contracts</h1>

    <form action="contracts.php" method="get" class="search-form">
        <input type="text" name="q" value="<?= h($q) ?>" placeholder="ابحث برقم العقد، اسم المستأجر أو رقم اللوحة / Search by contract no, renter or plate">
        <button type="submit" class="btn btn-secondary">بحث / Search</button>
    </form>

    <?php if (!$contracts): ?>
        <p class="hint">لا توجد عقود محفوظة بعد. / No contracts saved yet.</p>
    <?php else: ?>
    <div class="table-scroll">
        <table class="list-table">
            <thead>
                <tr>
                    <th>رقم العقد<br>Contract No.</th>
                    <th>تاريخ البداية<br>Start Date</th>
                    <th>المستأجر<br>Lessee</th>
                    <th>رقم اللوحة<br>Plate</th>
                    <th>المبلغ<br>Rent Paid</th>
                    <th>إجراءات<br>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contracts as $row): ?>
                <tr>
                    <td><?= h($row['contract_no']) ?></td>
                    <td><?= h($row['rental_start_date']) ?></td>
                    <td><?= h($row['lessee_name']) ?></td>
                    <td><?= h($row['veh_plate_no']) ?></td>
                    <td><?= $row['rent_paid'] !== null ? h($row['rent_paid']) : '' ?></td>
                    <td class="actions">
                        <a href="view.php?id=<?= (int) $row['id'] ?>">عرض/طباعة · View/Print</a>
                        <a href="form.php?id=<?= (int) $row['id'] ?>">تعديل · Edit</a>
                        <?php if (is_admin()): ?>
                        <form action="delete.php" method="post" onsubmit="return confirm('هل أنت متأكد من الحذف؟ / Delete this contract?');" class="inline-form">
                            <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                            <button type="submit" class="link-danger">حذف · Delete</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</main>

<footer class="site-footer">
    <p>Al Musafir for Car Rental — commercial reg. no. 240168</p>
</footer>
</body>
</html>
