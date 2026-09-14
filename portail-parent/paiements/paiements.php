<?php
/**
 * paiements.php — Point d'entrée du module paiements (MVC)
 *
 * Ecole Plus v1.2.0 — 2026-07-05
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/PaiementsController.php';

$controller = new PaiementsController($pdo, (int)$parent_id, (int)$enfant_actif_id);
$controller->init();

$data = $controller->getDashboardData();
$data['enfant_actif'] = $enfant_actif;

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/slidebar.php';
include __DIR__ . '/views/paiements/index.php';
include __DIR__ . '/../includes/footer.php';
