<?php
/**
 * session_bootstrap.php — Enregistrement précoce du handler BDD (v1.2.0)
 * 
 * Ce fichier DOIT être inclus AVANT tout session_start().
 * Il configure le DatabaseSessionHandler pour que TOUTES les sessions
 * (y compris login.php) passent par la BDD.
 * 
 * Ecole Plus v1.2.0 — 2026-07-06
 */

declare(strict_types=1);

// 1. Inclure la connexion PDO en PREMIER pour garantir que $pdo soit toujours défini
//    (incline env.php → .env, puis db.php → $pdo)
require_once __DIR__ . '/../config/db.php';

// 1b. Charger les constantes de l'application (APP_VERSION, APP_URL, etc.)
require_once __DIR__ . '/../config/app.php';

// 2. Forcer le sérialiseur PHP serialize pour la compatibilité avec l'extraction du parent_id
ini_set('session.serialize_handler', 'php_serialize');

// 3. Ne rien faire si la session a déjà démarré de manière impromptue
if (session_status() !== PHP_SESSION_NONE) {
    error_log('[SessionBootstrap] ALERTE : session_start() déjà appelé avant l\'enregistrement du DatabaseSessionHandler — contournement du stockage BDD');
    return;
}

// 4. Inclure la classe DatabaseSessionHandler AVANT son instanciation
require_once __DIR__ . '/DatabaseSessionHandler.php';

// 5. Enregistrer le handler de session personnalisé lié à la base de données
$sessionHandler = new DatabaseSessionHandler($pdo);
$sessionHandler->setMaxLifetime(86400);
session_set_save_handler($sessionHandler, true);

// 6. Définir la durée de vie maximale du garbage collector natif de PHP (24 heures)
ini_set('session.gc_maxlifetime', '86400');