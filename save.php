<?php
require __DIR__ . '/auth.php';
require __DIR__ . '/db.php';
require __DIR__ . '/fields.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: form.php');
    exit;
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : null;
$fields = contract_fields();

$errors = [];
$clean = [];

foreach ($fields as $name => $spec) {
    // contract_no is server-generated (see below), never taken from the form.
    if ($name === 'contract_no') {
        continue;
    }

    $raw = isset($_POST[$name]) ? trim((string) $_POST[$name]) : '';

    if ($raw === '') {
        if (!empty($spec['required'])) {
            $errors[] = "الحقل \"$name\" مطلوب / \"$name\" is required.";
        }
        $clean[$name] = null;
        continue;
    }

    switch ($spec['type']) {
        case 'text':
            $clean[$name] = $raw;
            break;

        case 'date':
            $d = DateTime::createFromFormat('Y-m-d', $raw);
            if (!$d || $d->format('Y-m-d') !== $raw) {
                $errors[] = "تاريخ غير صالح في الحقل \"$name\" / Invalid date for \"$name\".";
                $clean[$name] = null;
            } else {
                $clean[$name] = $raw;
            }
            break;

        case 'time':
            $t = DateTime::createFromFormat('H:i', $raw) ?: DateTime::createFromFormat('H:i:s', $raw);
            if (!$t) {
                $errors[] = "وقت غير صالح في الحقل \"$name\" / Invalid time for \"$name\".";
                $clean[$name] = null;
            } else {
                $clean[$name] = $t->format('H:i:s');
            }
            break;

        case 'decimal':
            if (!is_numeric($raw)) {
                $errors[] = "قيمة رقمية غير صالحة في الحقل \"$name\" / Invalid number for \"$name\".";
                $clean[$name] = null;
            } else {
                $clean[$name] = (float) $raw;
            }
            break;

        case 'int':
            if (!ctype_digit($raw)) {
                $errors[] = "قيمة صحيحة غير صالحة في الحقل \"$name\" / Invalid whole number for \"$name\".";
                $clean[$name] = null;
            } else {
                $clean[$name] = (int) $raw;
            }
            break;

        case 'enum':
            if (!in_array($raw, $spec['options'], true)) {
                $errors[] = "قيمة غير مسموحة في الحقل \"$name\" / Invalid option for \"$name\".";
                $clean[$name] = null;
            } else {
                $clean[$name] = $raw;
            }
            break;

        default:
            $clean[$name] = $raw;
    }
}

if ($errors) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_values'] = $_POST;
    header('Location: form.php' . ($id ? '?id=' . $id : ''));
    exit;
}

// contract_no is never edited once assigned, so it's excluded from the
// UPDATE column list; for a new contract it doesn't exist yet (it needs
// the row's own id), so it's excluded from the INSERT too and filled in
// right after via the id MySQL just assigned.
$columns = array_diff(array_keys($fields), ['contract_no']);

try {
    $pdo = get_db();

    if ($id) {
        $setSql = implode(', ', array_map(fn ($c) => "$c = :$c", $columns));
        $stmt = $pdo->prepare("UPDATE contracts SET $setSql WHERE id = :id");
        $clean['id'] = $id;
        $stmt->execute($clean);
    } else {
        $colSql = implode(', ', $columns);
        $placeholders = implode(', ', array_map(fn ($c) => ":$c", $columns));
        $stmt = $pdo->prepare("INSERT INTO contracts ($colSql) VALUES ($placeholders)");
        $stmt->execute($clean);
        $id = (int) $pdo->lastInsertId();

        $contractNo = 'AM-' . date('Y') . '-' . str_pad((string) $id, 4, '0', STR_PAD_LEFT);
        $pdo->prepare('UPDATE contracts SET contract_no = ? WHERE id = ?')->execute([$contractNo, $id]);
    }
} catch (PDOException $e) {
    $_SESSION['form_errors'] = ['تعذر حفظ العقد، حاول مجددًا / Could not save the contract, please try again.'];
    $_SESSION['form_values'] = $_POST;
    header('Location: form.php' . ($id ? '?id=' . $id : ''));
    exit;
}

header('Location: view.php?id=' . $id);
exit;
