-- Adds the settings table (used for contract numbering) to an EXISTING
-- al_musafir_contracts database without touching contracts or users.
-- Import this file via phpMyAdmin (Import tab) — do NOT re-import
-- schema.sql, since that file drops and recreates the contracts table.

USE al_musafir_contracts;

CREATE TABLE IF NOT EXISTS settings (
    setting_key VARCHAR(100) NOT NULL PRIMARY KEY,
    setting_value VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- The next contract will be numbered AM-<year>-0002. Change '2' below
-- (or edit it later from the Settings page in the app) if you need a
-- different starting number.
INSERT IGNORE INTO settings (setting_key, setting_value)
VALUES ('next_contract_number', '2');
