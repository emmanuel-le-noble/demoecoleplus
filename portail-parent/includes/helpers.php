<?php
/**
 * includes/helpers.php
 * Fonctions utilitaires globales pour le projet
 * 
 * Ecole Plus v1.2.0 — 2026-07-06
 */

declare(strict_types=1);

// 1. Échappement XSS (sécurité de base)
if (!function_exists('e')) {
    function e(mixed $value): string {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

// 2. Formatage monétaire (FCFA) avec séparateur de milliers = espace
if (!function_exists('fcfa')) {
    function fcfa(mixed $montant): string {
        $montant = is_numeric($montant) ? (float)$montant : 0.0;
        return number_format($montant, 0, ',', ' ') . ' FCFA';
    }
}

// 3. Récupération de l'année scolaire active
if (!function_exists('getAnneeScolaire')) {
    function getAnneeScolaire(): string {
        $mois = (int)date('m');
        $annee = (int)date('Y');
        return ($mois >= 9) ? "$annee-" . ($annee + 1) : ($annee - 1) . "-$annee";
    }
}