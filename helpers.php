<?php
/** Escape a string for safe HTML output. */
function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/**
 * Reads a field's current value out of the $values array in scope
 * (populated from $_POST on a failed submit, or from a DB row when
 * editing/viewing). Always returns a safely escaped string.
 */
function val(array $values, string $name): string
{
    return h($values[$name] ?? '');
}

/** Renders selected="selected" when $values[$name] === $option. */
function sel(array $values, string $name, string $option): string
{
    return (($values[$name] ?? '') === $option) ? ' selected' : '';
}

/**
 * Renders a saved value for the printed contract, or a blank underline
 * if the field was left empty — so the printed page still reads like
 * the original fillable template for anything not captured yet.
 */
function pv(array $row, string $name): string
{
    $v = $row[$name] ?? null;
    if ($v === null || $v === '') {
        return '<span class="blank">&nbsp;</span>';
    }
    return '<span class="filled">' . h($v) . '</span>';
}

/** Same as pv() but formats a Y-m-d date as d/m/Y. */
function pdate(array $row, string $name): string
{
    $v = $row[$name] ?? null;
    if (!$v) {
        return '<span class="blank">&nbsp;</span>';
    }
    $d = DateTime::createFromFormat('Y-m-d', $v);
    return '<span class="filled">' . h($d ? $d->format('d/m/Y') : $v) . '</span>';
}

/** Same as pv() but formats an H:i:s time as H:i. */
function ptime(array $row, string $name): string
{
    $v = $row[$name] ?? null;
    if (!$v) {
        return '<span class="blank">&nbsp;</span>';
    }
    $t = DateTime::createFromFormat('H:i:s', $v);
    return '<span class="filled">' . h($t ? $t->format('H:i') : $v) . '</span>';
}

/** Human-readable label for enum-ish values, used in the printed view. */
function label_map(string $name, string $value): string
{
    $maps = [
        'tax_status' => ['incl' => 'شاملة الضريبة / Tax incl.', 'excl' => 'غير شاملة الضريبة / Tax excl.'],
        'payment_method' => ['cash' => 'نقدًا / Cash', 'card' => 'بطاقة بنكية / Card', 'transfer' => 'تحويل بنكي / Bank transfer'],
        'insurance_type' => ['comprehensive' => 'شامل / Comprehensive', 'third_party' => 'ضد الغير / Third-party'],
        'late_penalty_unit' => ['hour' => 'ساعة / hour', 'day' => 'يوم / day'],
    ];

    return $maps[$name][$value] ?? $value;
}

/** Same as pv() but runs the value through label_map() first. */
function penum(array $row, string $name): string
{
    $v = $row[$name] ?? null;
    if (!$v) {
        return '<span class="blank">&nbsp;</span>';
    }
    return '<span class="filled">' . h(label_map($name, $v)) . '</span>';
}
