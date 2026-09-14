<?php
/**
 * app.php — Constantes centralisées de l'application (v1.2.0)
 *
 * À inclure UNE SEULE FOIS au démarrage (via session_bootstrap.php ou env.php).
 * Définit les constantes utilisées dans tout le projet.
 *
 * Ecole Plus v1.2.0 — 2026-09-12
 */

declare(strict_types=1);

// Éviter la redéfinition
if (defined('APP_VERSION')) {
    return;
}

// ─── Version ────────────────────────────────────────────────────────────
define('APP_VERSION',   '1.3.0');
define('APP_NAME',      getenv('APP_NAME')  ?: 'Ecole Plus');
define('APP_URL',       rtrim((string)(getenv('APP_URL') ?: ''), '/'));
define('APP_ENV',       getenv('APP_ENV')   ?: 'production');
define('APP_DEBUG',     filter_var(getenv('APP_DEBUG') ?: 'false', FILTER_VALIDATE_BOOLEAN));

// ─── Paths ──────────────────────────────────────────────────────────────
define('ROOT_PATH',     dirname(__DIR__));
define('CONFIG_PATH',   ROOT_PATH . '/config');
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('UPLOADS_PATH',  ROOT_PATH . '/uploads');

// ─── Sécurité ───────────────────────────────────────────────────────────
define('CSRF_TOKEN_LENGTH', 32);
define('SESSION_MAX_LIFETIME', 86400); // 24 heures

// ─── Helpers ────────────────────────────────────────────────────────────
/**
 * Retourne true si on est en environnement de développement.
 */
function is_dev(): bool
{
    return APP_ENV === 'development';
}

/**
 * Retourne true si on est en environnement de production.
 */
function is_prod(): bool
{
    return APP_ENV === 'production';
}
