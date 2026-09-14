-- ==========================================================================
-- MIGRATION COMPLÈTE — Ecole Plus Portail Parent v1.2.0
--
-- Fichier unique regroupant toutes les migrations de la base
-- goodh2642221_59bsri. À exécuter une seule fois.
--
-- Compatible : MySQL 8.0+ / MariaDB 10.11+
-- Date : 2026-07-06
--
-- Ordre d'exécution garanti :
--   1. Tables nouvelles (IF NOT EXISTS)
--   2. Colonnes manquantes (ALTER TABLE)
--   3. Restructuration parents
--   4. Index optimisés
-- ==========================================================================

-- ##########################################################################
-- SECTION 1 — TABLES NOUVELLES
-- ##########################################################################

-- -------------------------------------------------------------------------
-- 1.1 Table sessions_parents — Sessions sécurisées en base de données
-- -------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sessions_parents` (
    `id`            VARCHAR(128) NOT NULL COMMENT 'Identifiant de session (session_id)',
    `parent_id`     INT UNSIGNED DEFAULT NULL COMMENT 'ID du parent connecté (indexé pour Nettoyage)',
    `ip_address`    VARCHAR(45)  NOT NULL DEFAULT '' COMMENT 'IP du client (IPv4 ou IPv6)',
    `user_agent`    TEXT         NOT NULL COMMENT 'User-Agent du navigateur',
    `payload`       LONGTEXT     NOT NULL COMMENT 'Données sérialisées de la session ($_SESSION)',
    `last_activity` INT UNSIGNED NOT NULL COMMENT 'Timestamp UNIX de dernière activité',
    `created_at`    DATETIME     DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
    PRIMARY KEY (`id`),
    INDEX `idx_sessions_parent_id` (`parent_id`),
    INDEX `idx_sessions_last_activity` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Stockage des sessions parent en base (sécurité renforcée)';

-- -------------------------------------------------------------------------
-- 1.2 Table password_resets_parents — Jetons de réinitialisation mot de passe
-- -------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `password_resets_parents` (
    `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `parent_id`    INT UNSIGNED    NOT NULL,
    `token_hash`   CHAR(64)        NOT NULL,
    `expires_at`   DATETIME        NOT NULL,
    `used_at`      DATETIME        DEFAULT NULL,
    `requested_at` DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_password_resets_token_hash` (`token_hash`),
    KEY `idx_password_resets_parent_id` (`parent_id`),
    KEY `idx_password_resets_expires_at` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------------
-- 1.3 Table bulletin_calcule — Cache des moyennes calculées
-- -------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `bulletin_calcule` (
    `ID`                INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `IDELEVE`           INT          NOT NULL,
    `IDSALLE`           INT          NOT NULL,
    `IDANNEESCOLAIRE`   INT          NOT NULL,
    `IDPOSITION`        INT          NOT NULL,
    `MOYENNE_GENERALE`  DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `RANG_CLASSE`       INT UNSIGNED NOT NULL DEFAULT 0,
    `TOTAL_POINTS`      DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `TOTAL_COEFS`       DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `NOMBRE_MATIERES`   INT UNSIGNED NOT NULL DEFAULT 0,
    `DATE_CALCUL`       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `EST_GELE`          TINYINT(1)   NOT NULL DEFAULT 0 COMMENT '1 = gelé, 0 = recalculable',
    PRIMARY KEY (`ID`),
    UNIQUE KEY `uk_bulletin_calcule` (`IDELEVE`, `IDANNEESCOLAIRE`, `IDPOSITION`),
    KEY `idx_bulletin_calcule_salle` (`IDSALLE`),
    KEY `idx_bulletin_calcule_annee` (`IDANNEESCOLAIRE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------------
-- 1.4 Tables de messagerie
-- -------------------------------------------------------------------------

-- 1.4.1 msg_conversations
CREATE TABLE IF NOT EXISTS `msg_conversations` (
    `ID`            INT          NOT NULL AUTO_INCREMENT,
    `TITRE`         VARCHAR(255) DEFAULT NULL COMMENT 'Titre de la conversation',
    `TYPE_CONV`     VARCHAR(50)  NOT NULL COMMENT 'DEPARTEMENT ou PRIVEE',
    `DATE_CREATION` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`ID`),
    KEY `idx_msg_conv_type` (`TYPE_CONV`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Conversations de messagerie entre parents et personnel';

-- 1.4.2 msg_messages
CREATE TABLE IF NOT EXISTS `msg_messages` (
    `ID`              INT      NOT NULL AUTO_INCREMENT,
    `ID_CONVERSATION` INT      NOT NULL,
    `EXPEDITEUR_TYPE` VARCHAR(50) NOT NULL COMMENT 'PARENT ou STAFF',
    `ID_EXPEDITEUR`   INT      NOT NULL,
    `CONTENU`         TEXT     NOT NULL,
    `DATE_ENVOI`      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`ID`),
    KEY `idx_msg_messages_conversation` (`ID_CONVERSATION`, `DATE_ENVOI`),
    CONSTRAINT `fk_msg_messages_conv` FOREIGN KEY (`ID_CONVERSATION`) REFERENCES `msg_conversations` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Messages de la messagerie';

-- 1.4.3 msg_participants
CREATE TABLE IF NOT EXISTS `msg_participants` (
    `ID_CONVERSATION` INT         NOT NULL,
    `USER_TYPE`       VARCHAR(50) NOT NULL COMMENT 'PARENT ou STAFF',
    `ID_USER`         INT         NOT NULL,
    KEY `idx_msg_participants_user` (`USER_TYPE`, `ID_USER`),
    KEY `idx_msg_participants_conv` (`ID_CONVERSATION`),
    CONSTRAINT `fk_msg_participants_conv` FOREIGN KEY (`ID_CONVERSATION`) REFERENCES `msg_conversations` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Participants aux conversations de messagerie';

-- 1.4.4 msg_statuts_lecture
CREATE TABLE IF NOT EXISTS `msg_statuts_lecture` (
    `ID_MESSAGE`   INT          NOT NULL,
    `LECTEUR_TYPE` VARCHAR(50)  NOT NULL COMMENT 'PARENT ou STAFF',
    `ID_LECTEUR`   INT          NOT NULL,
    `DATE_LECTURE` DATETIME     DEFAULT NULL COMMENT 'NULL = non lu',
    KEY `idx_msg_statuts_lecture_composite` (`LECTEUR_TYPE`, `ID_LECTEUR`, `DATE_LECTURE`),
    KEY `idx_msg_statuts_message` (`ID_MESSAGE`),
    CONSTRAINT `fk_msg_statuts_message` FOREIGN KEY (`ID_MESSAGE`) REFERENCES `msg_messages` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Statut de lecture des messages par participant';

-- 1.4.5 msg_pieces_jointes
CREATE TABLE IF NOT EXISTS `msg_pieces_jointes` (
    `ID`          INT          NOT NULL AUTO_INCREMENT,
    `ID_MESSAGE`  INT          NOT NULL,
    `NOM_FICHIER` VARCHAR(255) NOT NULL COMMENT 'Nom unique du fichier sur le disque',
    `CHEMIN_URL`  VARCHAR(500) NOT NULL COMMENT 'Chemin relatif d''accès au fichier',
    `TYPE_MIME`   VARCHAR(100) DEFAULT NULL COMMENT 'Type MIME du fichier',
    PRIMARY KEY (`ID`),
    KEY `idx_msg_pj_message` (`ID_MESSAGE`),
    CONSTRAINT `fk_msg_pj_message` FOREIGN KEY (`ID_MESSAGE`) REFERENCES `msg_messages` (`ID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Pièces jointes aux messages de messagerie';


-- ##########################################################################
-- SECTION 2 — AJOUT DE COLONNES MANQUANTES
-- ##########################################################################

-- -------------------------------------------------------------------------
-- 2.1 Colonne EST_PUBLIE sur table note
-- -------------------------------------------------------------------------
SET @col_exists = (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'note'
      AND COLUMN_NAME = 'EST_PUBLIE'
);
SET @sql = IF(@col_exists = 0,
    'ALTER TABLE `note` ADD COLUMN `EST_PUBLIE` TINYINT(1) NOT NULL DEFAULT 0 AFTER `IDANNEESCOLAIRE`',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- -------------------------------------------------------------------------
-- 2.2 Colonne FICHIER_DEVOIR sur table cahier_texte
-- -------------------------------------------------------------------------
SET @col_exists = (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'cahier_texte'
      AND COLUMN_NAME = 'FICHIER_DEVOIR'
);
SET @sql = IF(@col_exists = 0,
    'ALTER TABLE `cahier_texte` ADD COLUMN `FICHIER_DEVOIR` VARCHAR(500) DEFAULT NULL AFTER `DATE_ECHEANCE`',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;


-- ##########################################################################
-- SECTION 3 — RESTRUCTURATION TABLE parents
-- ##########################################################################
--
-- L'ancien schéma (ID, NOM, PRENOM, TELEPHONE, EMAIL, MOT_DE_PASSE,
-- STATUT, DATE_CREATION) est remplacé par le nouveau schéma utilisé
-- par le code : ID_PARENT, NOM_PARENT, PRENOM_PARENT, SEXE_PARENT,
-- TEL_PARENT, MAIL_PARENT, LOGIN_PARENT, MTPASS_PARENT, STATUT_PARENT,
-- GOOGLE_SUB, DATE_CREATION.
--
-- Chaque étape est idempotente grâce à des vérifications INFORMATIOM_SCHEMA.
-- -------------------------------------------------------------------------

-- 3.1 Sauvegarder l'ancienne table si elle a l'ancien schéma
SET @has_new_schema = (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'parents'
      AND COLUMN_NAME = 'LOGIN_PARENT'
);

SET @sql = IF(@has_new_schema = 0,
    'CREATE TABLE IF NOT EXISTS `parents_backup_old_schema` LIKE `parents`',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = IF(@has_new_schema = 0,
    'INSERT IGNORE INTO `parents_backup_old_schema` SELECT * FROM `parents`',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 3.2 Supprimer la contrainte FK sur parent_eleve si elle existe
SET @fk_name = (
    SELECT CONSTRAINT_NAME
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'parent_eleve'
      AND REFERENCED_TABLE_NAME = 'parents'
    LIMIT 1
);
SET @sql = IF(@fk_name IS NOT NULL AND @has_new_schema = 0,
    CONCAT('ALTER TABLE `parent_eleve` DROP FOREIGN KEY `', @fk_name, '`'),
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 3.3 Supprimer les anciens index uniques TELEPHONE et EMAIL
SET @idx_tel = (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'parents' AND INDEX_NAME = 'TELEPHONE'
);
SET @sql = IF(@idx_tel > 0 AND @has_new_schema = 0,
    'ALTER TABLE `parents` DROP INDEX `TELEPHONE`',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @idx_email = (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'parents' AND INDEX_NAME = 'EMAIL'
);
SET @sql = IF(@idx_email > 0 AND @has_new_schema = 0,
    'ALTER TABLE `parents` DROP INDEX `EMAIL`',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 3.4 Recréer la table parents avec le nouveau schéma
SET @sql = IF(@has_new_schema = 0,
    'DROP TABLE IF EXISTS `parents`',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = IF(@has_new_schema = 0,
    'CREATE TABLE `parents` (
        `ID_PARENT`     INT          NOT NULL AUTO_INCREMENT,
        `NOM_PARENT`    VARCHAR(255) NOT NULL,
        `PRENOM_PARENT` VARCHAR(255) NOT NULL,
        `SEXE_PARENT`   VARCHAR(50)  DEFAULT NULL,
        `TEL_PARENT`    VARCHAR(50)  NOT NULL,
        `MAIL_PARENT`   VARCHAR(255) DEFAULT NULL,
        `LOGIN_PARENT`  VARCHAR(100) NOT NULL,
        `MTPASS_PARENT` VARCHAR(255) NOT NULL,
        `STATUT_PARENT` INT          NOT NULL DEFAULT 0,
        `GOOGLE_SUB`    VARCHAR(255) DEFAULT NULL,
        `DATE_CREATION` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`ID_PARENT`),
        UNIQUE KEY `uq_parents_login` (`LOGIN_PARENT`),
        UNIQUE KEY `uq_parents_google_sub` (`GOOGLE_SUB`),
        KEY `idx_parents_statut` (`STATUT_PARENT`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 3.5 Migrer les données de l'ancienne table
SET @sql = IF(@has_new_schema = 0,
    'INSERT INTO `parents`
        (`NOM_PARENT`, `PRENOM_PARENT`, `TEL_PARENT`, `MAIL_PARENT`,
         `LOGIN_PARENT`, `MTPASS_PARENT`, `STATUT_PARENT`, `DATE_CREATION`)
    SELECT
        `NOM`, `PRENOM`, `TELEPHONE`, `EMAIL`,
        LOWER(SUBSTRING_INDEX(`EMAIL`, "@", 1)),
        `MOT_DE_PASSE`,
        CASE WHEN `STATUT` = 1 THEN 1 ELSE 0 END,
        `DATE_CREATION`
    FROM `parents_backup_old_schema`',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- 3.6 Recréer la contrainte FK sur parent_eleve
SET @fk_still_missing = (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'parent_eleve'
      AND REFERENCED_TABLE_NAME = 'parents'
);
SET @sql = IF(@fk_still_missing = 0 AND @has_new_schema = 0,
    'ALTER TABLE `parent_eleve` ADD CONSTRAINT `FK_PARENTELEVE_PARENT` FOREIGN KEY (`ID_PARENT`) REFERENCES `parents` (`ID_PARENT`) ON DELETE CASCADE',
    'SELECT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;


-- ##########################################################################
-- SECTION 4 — INDEX OPTIMISÉS (compatible MariaDB 10.11+)
-- ##########################################################################

-- Procédure utilitaire pour créer un index s'il n'existe pas déjà
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS `create_index_if_not_exists`(
    IN p_table_name VARCHAR(64),
    IN p_index_name VARCHAR(64),
    IN p_columns VARCHAR(500)
)
BEGIN
    DECLARE v_count INT DEFAULT 0;
    SELECT COUNT(*) INTO v_count
    FROM INFORMATION_SCHEMA.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = p_table_name
      AND INDEX_NAME = p_index_name;
    IF v_count = 0 THEN
        SET @sql = CONCAT('CREATE INDEX `', p_index_name, '` ON `', p_table_name, '`(', p_columns, ')');
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END //
DELIMITER ;

-- 4.1 parent_eleve (Jointure parent <-> enfant)
CALL create_index_if_not_exists('parent_eleve', 'idx_parent_eleve_composite', 'ID_PARENT, ID_ELEVE');

-- 4.2 elevesalle (Affectation élève <-> salle/classe)
CALL create_index_if_not_exists('elevesalle', 'idx_elevesalle_statut', 'IDELEVE, STATUT');
CALL create_index_if_not_exists('elevesalle', 'idx_elevesalle_idsalle', 'IDSALLE');

-- 4.3 paiementfrais (Historique des paiements)
CALL create_index_if_not_exists('paiementfrais', 'idx_paiementfrais_eleve_annee', 'IDELEVEANNEESCOLAIRE, IDANNEESCOLAIRE');
CALL create_index_if_not_exists('paiementfrais', 'idx_paiementfrais_statut', 'STATUT');

-- 4.4 paiementtypeclasse (Tarification par classe)
CALL create_index_if_not_exists('paiementtypeclasse', 'idx_paiementtypeclasse_classe', 'IDCLASSE, IDANNEESCOLAIRE');

-- 4.5 note (Notes des élèves)
CALL create_index_if_not_exists('note', 'idx_note_eleve_annee_position', 'IDELEVE, IDANNEESCOLAIRE, IDPOSITION');
CALL create_index_if_not_exists('note', 'idx_note_publie', 'EST_PUBLIE');

-- 4.6 absences (Suivi d'assiduité)
CALL create_index_if_not_exists('absences', 'idx_absences_elevesalle', 'IDELEVESALLE, IDANNEESCOLAIRE');

-- 4.7 bulletin (Bulletins officiels)
CALL create_index_if_not_exists('bulletin', 'idx_bulletin_eleve_annee', 'IDELEVE, IDANNEESCOLAIRE, IDPOSITION');

-- 4.8 bulletincontenu (Détail du bulletin)
CALL create_index_if_not_exists('bulletincontenu', 'idx_bulletincontenu_bulletin', 'IDBULLETIN');

-- 4.9 cahier_texte (Cahier de textes et devoirs)
CALL create_index_if_not_exists('cahier_texte', 'idx_cahier_texte_salle', 'IDSALLE, IDANNEESCOLAIRE');
CALL create_index_if_not_exists('cahier_texte', 'idx_cahier_texte_echeance', 'DATE_ECHEANCE');

-- 4.10 professeursallemat (Affectation prof-matière-salle)
CALL create_index_if_not_exists('professeursallemat', 'idx_professeursallemat_salle', 'IDSALLE, STATUT');

-- 4.11 eleveanneescolaire (Inscription annuelle)
CALL create_index_if_not_exists('eleveanneescolaire', 'idx_eleveanneescolaire_eleve', 'IDELEVE, IDANNEESCOLAIRE');

-- 4.12 Tables de messagerie
CALL create_index_if_not_exists('msg_messages', 'idx_msg_messages_conversation', 'ID_CONVERSATION, DATE_ENVOI');
CALL create_index_if_not_exists('msg_statuts_lecture', 'idx_msg_statuts_lecture_composite', 'LECTEUR_TYPE, ID_LECTEUR, DATE_LECTURE');
CALL create_index_if_not_exists('msg_statuts_lecture', 'idx_msg_statuts_message', 'ID_MESSAGE');
CALL create_index_if_not_exists('msg_participants', 'idx_msg_participants_user', 'USER_TYPE, ID_USER');

-- Nettoyage : supprimer la procédure utilitaire
DROP PROCEDURE IF EXISTS `create_index_if_not_exists`;


-- ##########################################################################
-- FIN DE LA MIGRATION
-- ==========================================================================
-- Vérification :
--   SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES
--   WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME IN
--   ('sessions_parents','password_resets_parents','bulletin_calcule',
--    'msg_conversations','msg_messages','msg_participants',
--    'msg_statuts_lecture','msg_pieces_jointes','parents');
--
--   SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
--   WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'note'
--   AND COLUMN_NAME = 'EST_PUBLIE';
--
--   SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
--   WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'cahier_texte'
--   AND COLUMN_NAME = 'FICHIER_DEVOIR';
--
--   SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
--   WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'parents'
--   AND COLUMN_NAME = 'LOGIN_PARENT';
-- ##########################################################################
