<?php
/**
 * recu_print.php — Point d'entrée pour l'impression (recu/relevé)
 *
 * Ecole Plus v1.2.0 — 2026-07-05
 */
declare(strict_types=1);

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/PaiementsController.php';

$type = $_GET['type'] ?? 'recu';
$paiementId = isset($_GET['id']) ? intval($_GET['id']) : 0;

$controller = new PaiementsController($pdo, (int)$parent_id, (int)$enfant_actif_id);
$controller->init();

if ($type === 'releve') {
    $data = $controller->getRelevePrintData();
} else {
    $data = $controller->getRecuPrintData($paiementId);
}

if (!$data) {
    http_response_code(404);
    die("Document introuvable ou accès non autorisé.");
}

include __DIR__ . '/views/paiements/print.php';
