-- Adds the "open-ended rental" option to Article 2 and lets the end date be empty.
-- Safe for a live database: existing contracts keep their end dates untouched.
-- Run ONCE:  mysql -u root -p al_musafir_contracts < migration_add_open_ended.sql
-- (or paste the ALTER below in phpMyAdmin's SQL tab with the database selected)

USE al_musafir_contracts;

ALTER TABLE contracts
    ADD COLUMN open_ended ENUM('yes') NULL AFTER rental_start_time,
    MODIFY rental_end_date DATE NULL;
