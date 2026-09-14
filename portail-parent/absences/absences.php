<?php
/**
 * absences.php — Point d'entrée du module absences (MVC)
 *
 * Ce fichier est un contrôleur mince : il instancie le contrôleur,
 * traite la requête, et affiche la vue. Aucune logique métier ici.
 *
 * Ecole Plus v1.2.0 — 2026-07-05
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/AbsencesController.php';

// Sécurité : s'assurer que la session est démarrée si session.php ne l'a pas fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Instancier le contrôleur
$controller = new AbsencesController(
    $pdo,
    (int)($parent_id ?? 0),
    (int)($enfant_actif_id ?? 0),
    $enfant_actif ?? []
);

// Initialiser le contexte (année scolaire, eleve_salle)
$controller->init();

// Traiter un éventuel POST (PRG)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->handlePost();
    $controller->redirect(
        $result['success'] ? 'success' : 'error',
        $result['message']
    );
}

// Préparer les données de la vue
$data = $controller->getViewData();

// Messages flash (depuis la redirect PRG)
$data['message_success'] = ($_GET['statut'] ?? '') === 'success' ? ($_GET['msg'] ?? '') : '';
$data['message_error'] = ($_GET['statut'] ?? '') === 'error' ? ($_GET['msg'] ?? '') : '';
$data['csrf_token'] = $_SESSION['csrf_token'] ?? '';

// Afficher le layout + la vue
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/slidebar.php';
include __DIR__ . '/views/absences/index.php';
include __DIR__ . '/../includes/footer.php';