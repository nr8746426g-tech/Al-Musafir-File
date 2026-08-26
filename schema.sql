-- Al Musafir for Car Rental — Car Rental Agreement storage
-- Import with: mysql -u root -p < schema.sql

CREATE DATABASE IF NOT EXISTS al_musafir_contracts
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE al_musafir_contracts;

CREATE TABLE IF NOT EXISTS contracts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    -- Header
    contract_no VARCHAR(50) NOT NULL,
    contract_date DATE NOT NULL,
    place VARCHAR(255) NULL,
    duration VARCHAR(100) NULL,

    -- First party (Lessor)
    lessor_represented_by VARCHAR(255) NULL,
    lessor_capacity VARCHAR(255) NULL,
    lessor_address VARCHAR(255) NULL,
    lessor_phone VARCHAR(50) NULL,

    -- Second party (Renter)
    renter_name VARCHAR(255) NOT NULL,
    renter_id_no VARCHAR(100) NULL,
    renter_license_no VARCHAR(100) NULL,
    renter_license_expiry DATE NULL,
    renter_address VARCHAR(255) NULL,
    renter_phone VARCHAR(50) NULL,

    -- Vehicle
    veh_make_model VARCHAR(150) NULL,
    veh_year VARCHAR(20) NULL,
    veh_colour VARCHAR(50) NULL,
    veh_plate_no VARCHAR(50) NULL,
    veh_vin VARCHAR(100) NULL,
    veh_odometer VARCHAR(50) NULL,

    -- Clause 1: rental period
    rental_start_date DATE NULL,
    rental_start_time TIME NULL,
    rental_end_date DATE NULL,
    rental_end_time TIME NULL,

    -- Clause 2: fee & payment
    total_fee DECIMAL(10,2) NULL,
    tax_status ENUM('incl','excl') NULL,
    payment_method ENUM('cash','card','transfer') NULL,
    first_instalment DECIMAL(10,2) NULL,
    balance_due_note VARCHAR(255) NULL,

    -- Clause 3: deposit
    deposit_amount DECIMAL(10,2) NULL,
    deposit_return_days SMALLINT UNSIGNED NULL,

    -- Clause 4: mileage
    mileage_limit_km SMALLINT UNSIGNED NULL,
    extra_km_charge DECIMAL(10,2) NULL,

    -- Clause 5: fuel
    fuel_level VARCHAR(50) NULL,
    fuel_service_fee DECIMAL(10,2) NULL,

    -- Clause 6: insurance
    insurance_type ENUM('comprehensive','third_party') NULL,
    insurance_company VARCHAR(150) NULL,
    deductible_amount DECIMAL(10,2) NULL,

    -- Clause 7: late return penalty
    late_penalty_amount DECIMAL(10,2) NULL,
    late_penalty_unit ENUM('hour','day') NULL,

    -- Clause 9: cancellation
    cancellation_notice_period VARCHAR(100) NULL,

    -- Clause 8: vehicle condition inspection
    insp_body_paint_handover VARCHAR(255) NULL,
    insp_body_paint_return VARCHAR(255) NULL,
    insp_glass_mirrors_handover VARCHAR(255) NULL,
    insp_glass_mirrors_return VARCHAR(255) NULL,
    insp_tyres_rims_handover VARCHAR(255) NULL,
    insp_tyres_rims_return VARCHAR(255) NULL,
    insp_lights_handover VARCHAR(255) NULL,
    insp_lights_return VARCHAR(255) NULL,
    insp_interior_handover VARCHAR(255) NULL,
    insp_interior_return VARCHAR(255) NULL,
    insp_accessories_handover VARCHAR(255) NULL,
    insp_accessories_return VARCHAR(255) NULL,

    -- Signatures
    lessor_sign_name VARCHAR(255) NULL,
    lessor_sign_date DATE NULL,
    renter_sign_name VARCHAR(255) NULL,
    renter_sign_date DATE NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uq_contract_no (contract_no)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
