<?php
/**
 * session_cleanup.php — Nettoyage des sessions expirées (v1.2.0)
 * 
 * Protégé par une clé secrète pour éviter l'accès public.
 * Usage CLI : php config/session_cleanup.php
 * Usage cron : 0 * * * * php /path/to/config/session_cleanup.php SECRET_KEY_ICI
 * 
 * Ecole Plus v1.2.0 — 2026-07-05
 */

declare(strict_types=1);

// Charger les variables d'environnement (.env)
require_once __DIR__ . '/env.php';

// Clé secrète pour autoriser l'exécution — via variable d'environnement
$CLE_SECRET = getenv('ECOLEPLUS_CLEANUP_KEY') ?: '';

// Vérification de la clé (CLI ou paramètre)
$isCli = (php_sapi_name() === 'cli');
$isAuthorized = false;

if ($isCli) {
    // En CLI : le script peut être exécuté librement (protégé par filesystem)
    $isAuthorized = true;
} elseif ($CLE_SECRET !== '' && isset($_GET['cle']) && hash_equals($CLE_SECRET, (string)$_GET['cle'])) {
    // En web : nécessite la clé secrète en paramètre
    $isAuthorized = true;
}

if (!$isAuthorized) {
    http_response_code(403);
    die("Accès interdit.");
}

// ANOMALIE CORRIGÉE : Inclusion impérative de db.php avant l'utilisation du wrapper PDO
require_once __DIR__ . '/db.php';

$maxLifetime = 86400;
$cutoff = time() - $maxLifetime;

try {
    $stmt = $pdo->prepare("DELETE FROM sessions_parents WHERE last_activity < ?");
    $stmt->execute([$cutoff]);
    $deleted = $stmt->rowCount();
} catch (\PDOException $e) {
    error_log('[EcolePlus] Erreur session_cleanup : ' . $e->getMessage());
    if (!$isCli) {
        http_response_code(500);
        header('Content-Type: text/plain; charset=utf-8');
    }
    die("Erreur lors du nettoyage des sessions. Consultez les logs serveur.");
}

error_log("[EcolePlus] Nettoyage sessions: $deleted session(s) expirée(s) supprimée(s).");

if (!$isCli) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "Nettoyage terminé : $deleted session(s) expirée(s) supprimée(s).";
}
