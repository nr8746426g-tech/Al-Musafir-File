<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/db.php';
require __DIR__ . '/fields.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: form.php');
    exit;
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : null;
$fields = contract_fields();

$errors = [];
$clean = [];

foreach ($fields as $name => $spec) {
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

$columns = array_keys($fields);

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
    }
} catch (PDOException $e) {
    $message = str_contains($e->getMessage(), 'uq_contract_no')
        ? 'رقم العقد هذا مستخدم مسبقًا / This contract number is already in use.'
        : 'تعذر حفظ العقد، حاول مجددًا / Could not save the contract, please try again.';
    $_SESSION['form_errors'] = [$message];
    $_SESSION['form_values'] = $_POST;
    header('Location: form.php' . ($id ? '?id=' . $id : ''));
    exit;
}

header('Location: view.php?id=' . $id);
exit;
