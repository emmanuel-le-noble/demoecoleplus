<?php
/**
 * cahier_texte.php — Point d'entrée du module Cahier de textes (MVC)
 *
 * Ecole Plus v1.2.0 — 2026-07-06
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/CahierTexteController.php';

// Sécurité : s'assurer que la session est démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Instancier le contrôleur avec gestion des fallbacks sécurisés
$controller = new CahierTexteController(
    $pdo, 
    (int)($parent_id ?? 0), 
    (int)($enfant_actif_id ?? 0), 
    $enfant_actif ?? []
);

// Initialiser le contexte
$controller->init();

// Préparer les données de la vue
$data = $controller->getViewData();

// Afficher le layout complet et injecter la vue
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/slidebar.php';
include __DIR__ . '/views/cahier_texte/index.php';
include __DIR__ . '/../includes/footer.php';