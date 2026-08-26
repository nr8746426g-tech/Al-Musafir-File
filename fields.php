<?php
/**
 * Single source of truth for every contract field: its SQL type and
 * whether it is required. Used by save.php to sanitize/bind values
 * generically, so the column list only has to be maintained here.
 */
function contract_fields(): array
{
    return [
        // Header
        'contract_no'   => ['type' => 'text', 'required' => true],
        'contract_date' => ['type' => 'date', 'required' => true],
        'place'         => ['type' => 'text'],
        'duration'      => ['type' => 'text'],

        // First party (Lessor)
        'lessor_represented_by' => ['type' => 'text'],
        'lessor_capacity'       => ['type' => 'text'],
        'lessor_address'        => ['type' => 'text'],
        'lessor_phone'          => ['type' => 'text'],

        // Second party (Renter)
        'renter_name'            => ['type' => 'text', 'required' => true],
        'renter_id_no'           => ['type' => 'text'],
        'renter_license_no'      => ['type' => 'text'],
        'renter_license_expiry'  => ['type' => 'date'],
        'renter_address'         => ['type' => 'text'],
        'renter_phone'           => ['type' => 'text'],

        // Vehicle
        'veh_make_model' => ['type' => 'text'],
        'veh_year'       => ['type' => 'text'],
        'veh_colour'     => ['type' => 'text'],
        'veh_plate_no'   => ['type' => 'text', 'required' => true],
        'veh_vin'        => ['type' => 'text'],
        'veh_odometer'   => ['type' => 'text'],

        // Clause 1: rental period
        'rental_start_date' => ['type' => 'date', 'required' => true],
        'rental_start_time' => ['type' => 'time'],
        'rental_end_date'   => ['type' => 'date', 'required' => true],
        'rental_end_time'   => ['type' => 'time'],

        // Clause 2: fee & payment
        'total_fee'         => ['type' => 'decimal', 'required' => true],
        'tax_status'        => ['type' => 'enum', 'options' => ['incl', 'excl']],
        'payment_method'    => ['type' => 'enum', 'options' => ['cash', 'card', 'transfer']],
        'first_instalment'  => ['type' => 'decimal'],
        'balance_due_note'  => ['type' => 'text'],

        // Clause 3: deposit
        'deposit_amount'      => ['type' => 'decimal'],
        'deposit_return_days' => ['type' => 'int'],

        // Clause 4: mileage
        'mileage_limit_km' => ['type' => 'int'],
        'extra_km_charge'  => ['type' => 'decimal'],

        // Clause 5: fuel
        'fuel_level'       => ['type' => 'text'],
        'fuel_service_fee' => ['type' => 'decimal'],

        // Clause 6: insurance
        'insurance_type'    => ['type' => 'enum', 'options' => ['comprehensive', 'third_party']],
        'insurance_company' => ['type' => 'text'],
        'deductible_amount' => ['type' => 'decimal'],

        // Clause 7: late return penalty
        'late_penalty_amount' => ['type' => 'decimal'],
        'late_penalty_unit'   => ['type' => 'enum', 'options' => ['hour', 'day']],

        // Clause 9: cancellation
        'cancellation_notice_period' => ['type' => 'text'],

        // Clause 8: vehicle condition inspection
        'insp_body_paint_handover'    => ['type' => 'text'],
        'insp_body_paint_return'      => ['type' => 'text'],
        'insp_glass_mirrors_handover' => ['type' => 'text'],
        'insp_glass_mirrors_return'   => ['type' => 'text'],
        'insp_tyres_rims_handover'    => ['type' => 'text'],
        'insp_tyres_rims_return'      => ['type' => 'text'],
        'insp_lights_handover'        => ['type' => 'text'],
        'insp_lights_return'          => ['type' => 'text'],
        'insp_interior_handover'      => ['type' => 'text'],
        'insp_interior_return'        => ['type' => 'text'],
        'insp_accessories_handover'   => ['type' => 'text'],
        'insp_accessories_return'     => ['type' => 'text'],

        // Signatures
        'lessor_sign_name' => ['type' => 'text'],
        'lessor_sign_date' => ['type' => 'date'],
        'renter_sign_name' => ['type' => 'text'],
        'renter_sign_date' => ['type' => 'date'],
    ];
}
