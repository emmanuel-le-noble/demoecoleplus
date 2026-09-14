<?php
/**
 * enfants.php — Point d'entrée du module d'affichage de la fratrie (MVC)
 *
 * Ecole Plus v1.2.0 — 2026-07-06
 */

declare(strict_types=1);

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/EnfantsController.php';

// Sécurité : S'assurer de la présence des variables globales de session requises
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$parent_id_clean = (int)($parent_id ?? 0);
$enfants_list_clean = $mes_enfants ?? [];

// Instancier et initialiser le contrôleur d'abord pour valider le contexte global
$controller = new EnfantsController($pdo, $parent_id_clean, $enfants_list_clean);
$controller->init();

// Traitement sécurisé des requêtes POST (Changement d'enfant actif)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['select_active_id'])) {
    if (isset($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
        $idCible = (int)$_POST['select_active_id'];
        foreach ($enfants_list_clean as $enf) {
            if ((int)($enf['ID_ELEVE'] ?? 0) === $idCible) {
                $_SESSION['active_eleve_id'] = $idCible;
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            }
        }
    }
}

// Traitement sécurisé des requêtes POST (Redirection vers l'espace détaillé)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acceder_enfant_id'])) {
    if (isset($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
        $idCible = (int)$_POST['acceder_enfant_id'];
        foreach ($enfants_list_clean as $enf) {
            if ((int)($enf['ID_ELEVE'] ?? 0) === $idCible) {
                $_SESSION['active_eleve_id'] = $idCible;
                header("Location: ../notes/notes.php");
                exit;
            }
        }
    }
}

// Préparer les données finales consolidées de la fratrie
$data = $controller->getViewData();

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/slidebar.php';
include __DIR__ . '/views/enfants/index.php';
include __DIR__ . '/../includes/footer.php';