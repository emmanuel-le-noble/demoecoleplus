<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/ProfesseursController.php';

$controller = new ProfesseursController($pdo);

$action = $_GET['action'] ?? 'list';
$professeurId = isset($_GET['id']) ? (int)$_GET['id'] : null;

if ($action === 'start_chat' && $professeurId) {
    $convId = $controller->startConversation((int)$parent_id, $professeurId);
    if ($convId) {
        header('Location: ../messagerie/messagerie.php?conv=' . $convId);
    } else {
        header('Location: professeurs.php?error=chat_failed');
    }
    exit;
}

$professeurs = $controller->listProfesseurs((int)$parent_id, (int)($enfant_actif_id ?? 0));
$profDetail = null;
if ($action === 'detail' && $professeurId) {
    $profDetail = $controller->getProfesseur((int)$parent_id, (int)($enfant_actif_id ?? 0), $professeurId);
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/slidebar.php';
include __DIR__ . '/views/professeurs/index.php';
include __DIR__ . '/../includes/footer.php';
