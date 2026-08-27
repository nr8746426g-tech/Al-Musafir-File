<?php
/**
 * Single source of truth for every contract field: its SQL type and
 * whether it is required. Used by save.php to sanitize/bind values
 * generically, so the column list only has to be maintained here.
 *
 * This matches the "Car Rental Agreement" template supplied by the
 * business (Second Party / Lessor = Al Musafir, fixed; First Party /
 * Lessee = the customer). Articles 4-10 are fixed boilerplate text
 * with no blanks, so they have no fields here — see view.php.
 */
function contract_fields(): array
{
    return [
        // Header. contract_no is server-generated (see save.php) — not user input.
        // It is not printed on this template; it's kept for internal search/records.
        'contract_no' => ['type' => 'text'],

        // First Party (Lessee) — the customer
        'lessee_name'        => ['type' => 'text', 'required' => true],
        'lessee_nationality' => ['type' => 'text'],
        'lessee_id_no'       => ['type' => 'text'],
        'lessee_phone'       => ['type' => 'text'],

        // Article 1: Vehicle
        'veh_type'            => ['type' => 'text'],
        'veh_plate_no'        => ['type' => 'text', 'required' => true],
        'veh_colour'          => ['type' => 'text'],
        'veh_odometer'        => ['type' => 'text'],
        'mileage_restricted'  => ['type' => 'enum', 'options' => ['yes', 'no']],
        'allowed_mileage_km'  => ['type' => 'int'],

        // Article 2: Rental period
        'rental_days'       => ['type' => 'int'],
        'rent_paid'         => ['type' => 'decimal'],
        'rental_start_date' => ['type' => 'date', 'required' => true],
        'rental_start_time' => ['type' => 'time'],
        'rental_end_date'   => ['type' => 'date', 'required' => true],
        'rental_end_time'   => ['type' => 'time'],

        // Article 3: Security deposit
        'security_deposit' => ['type' => 'decimal'],
    ];
}
