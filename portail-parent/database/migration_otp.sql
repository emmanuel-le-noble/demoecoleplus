-- ==========================================================================
-- Ecole Plus v1.3 — Migration OTP + Invitations
-- À exécuter APRES migration.sql et test_data.sql
-- ==========================================================================

-- ── Table OTP Codes ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS otp_codes (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    parent_id INT(11) NOT NULL,
    type VARCHAR(20) NOT NULL COMMENT 'login|registration|password_reset',
    code_hash CHAR(64) NOT NULL COMMENT 'SHA-256 du code OTP',
    email VARCHAR(255) DEFAULT NULL,
    telephone VARCHAR(50) DEFAULT NULL,
    expires_at DATETIME NOT NULL,
    attempts INT(11) NOT NULL DEFAULT 0,
    used_at DATETIME DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45) NOT NULL DEFAULT '',
    PRIMARY KEY (id),
    KEY idx_otp_parent_id (parent_id),
    KEY idx_otp_expires_at (expires_at),
    KEY idx_otp_type (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Table Parent Invitations ────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS parent_invitations (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    eleve_id INT(11) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telephone VARCHAR(50) NOT NULL,
    token_hash CHAR(64) NOT NULL COMMENT 'SHA-256 du token',
    token_plain VARCHAR(128) NOT NULL COMMENT 'Token en clair (pour URL)',
    expires_at DATETIME NOT NULL,
    used_at DATETIME DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_invitation_token (token_hash),
    KEY idx_invitation_eleve (eleve_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SELECT 'Migration OTP + Invitations terminée avec succès' AS status;
