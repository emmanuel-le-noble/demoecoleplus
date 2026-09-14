<?php
/**
 * env.php — Chargeur de variables d'environnement (.env) (v1.2.0)
 *
 * Parse le fichier .env et définit les variables via putenv() / $_ENV / $_SERVER.
 * Pas de dépendance externe. Compatible avec getenv() déjà utilisé partout.
 *
 * Usage : require_once __DIR__ . '/../config/env.php';
 *
 * Ecole Plus v1.2.0 — 2026-09-12
 */

declare(strict_types=1);

// Guard basé sur une constante (les constantes ne sont PAS hoistées comme les fonctions)
if (defined('ENV_LOADED')) {
    return;
}

/**
 * Charger un fichier .env et définir les variables d'environnement.
 */
function load_env(?string $path = null): void
{
    if ($path === null) {
        $path = dirname(__DIR__) . '/.env';
    }

    if (!is_file($path) || !is_readable($path)) {
        error_log("[Env] Fichier .env introuvable ou illisible : {$path}");
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || $line[0] === '#') {
            continue;
        }

        $eqPos = strpos($line, '=');
        if ($eqPos === false) {
            continue;
        }

        $key   = trim(substr($line, 0, $eqPos));
        $value = trim(substr($line, $eqPos + 1));

        // Retirer les guillemets
        if (strlen($value) >= 2) {
            $first = $value[0];
            $last  = $value[strlen($value) - 1];
            if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                $value = substr($value, 1, -1);
            }
        }

        // Commentaires en fin de ligne
        if (($quotePos = strpos($value, ' #')) !== false) {
            $value = trim(substr($value, 0, $quotePos));
        }

        // Ne pas écraser les variables système existantes
        if (getenv($key) !== false) {
            continue;
        }

        putenv("{$key}={$value}");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

// Auto-charger
load_env();
define('ENV_LOADED', true);
