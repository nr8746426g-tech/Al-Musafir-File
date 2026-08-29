-- Adds login accounts to an EXISTING al_musafir_contracts database
-- without touching the contracts table or any saved data.
-- Import this file via phpMyAdmin (Import tab) — do NOT re-import
-- schema.sql, since that file drops and recreates the contracts table.

USE al_musafir_contracts;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','staff') NOT NULL DEFAULT 'staff',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- First admin account: username "Yazan Manager", password "Admin@112233".
-- Change this password after first login.
INSERT IGNORE INTO users (username, password_hash, role)
VALUES ('Yazan Manager', '$2y$12$qtjHXa0mKQQZ4VqlFjHH8.RXqoTAkNy6PRNPeasNwEL1EONCDNWWu', 'admin');
