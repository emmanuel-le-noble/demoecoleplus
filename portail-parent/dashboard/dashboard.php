<?php
/**
 * dashboard.php — Point d'entrée du module tableau de bord (MVC)
 *
 * Ecole Plus v1.2.0 — 2026-07-06
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/DashboardController.php';

// Sécurité : s'assurer que la session est démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Instancier le contrôleur avec gestion des fallbacks sécurisés
$controller = new DashboardController(
    $pdo, 
    (int)($parent_id ?? 0), 
    (int)($enfant_actif_id ?? 0)
);

// Initialiser le contexte
$controller->init();

// Préparer les données de la vue
$data = $controller->getViewData();

// Alignement contextuel : s'assurer que la variable globale enfant_actif est disponible pour les inclusions
if (!isset($enfant_actif) && isset($data['eleve'])) {
    $enfant_actif = $data['eleve'];
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/slidebar.php';
include __DIR__ . '/views/dashboard/index.php';
include __DIR__ . '/../includes/footer.php';