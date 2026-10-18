-- Adds payment method + cheque details to Article 2 of existing contracts.
-- Safe for a live database: only ADDS columns, existing contracts are untouched
-- (their payment method simply stays empty).
-- Run ONCE:  mysql -u root -p al_musafir_contracts < migration_add_payment_method.sql
-- (running it twice fails with "Duplicate column name" — harmless, nothing changes)

USE al_musafir_contracts;

ALTER TABLE contracts
    ADD COLUMN payment_method ENUM('cash','cheque','transfer','card') NULL AFTER rent_paid,
    ADD COLUMN cheque_no VARCHAR(50) NULL AFTER payment_method,
    ADD COLUMN cheque_bank VARCHAR(100) NULL AFTER cheque_no,
    ADD COLUMN cheque_date DATE NULL AFTER cheque_bank;
