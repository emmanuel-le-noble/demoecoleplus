<?php
/**
 * db.php — Connexion PDO sécurisée à MySQL (v1.2.0)
 * 
 * Ce fichier ne fournit QUE la connexion PDO.
 * La session est gérée par session.php avec DatabaseSessionHandler.
 * La protection auth est gérée par session.php.
 *
 * Ecole Plus v1.2.0 — 2026-07-06
 */

declare(strict_types=1);

// Charger les variables d'environnement (.env) si pas déjà fait
require_once __DIR__ . '/env.php';

if (!isset($pdo)) {
    
    $host     = getenv('DB_HOST') ?: 'localhost';
    $dbname   = getenv('DB_NAME') ?: 'ecole_plus';
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASS') ?: '';

    try {
        $pdo = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
            $username,
            $password,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                
                // CORRECTION : On repasse en CASE_NATURAL pour respecter la casse 
                // majuscule d'origine de votre base de données et éviter de casser l'existant.
                PDO::ATTR_CASE               => PDO::CASE_NATURAL,
            ]
        );
    } catch (PDOException $e) {
        error_log('[EcolePlus] Erreur connexion BD: ' . $e->getMessage());
        if (php_sapi_name() !== 'cli') {
            http_response_code(500);
        }
        die("Erreur critique de connexion. Veuillez contacter l'administrateur.");
    }
}