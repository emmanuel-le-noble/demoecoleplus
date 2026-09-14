<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/NotesController.php';

$controller = new NotesController($pdo, (int)$parent_id, (int)$enfant_actif_id, $enfant_actif);
$controller->init();

$data = $controller->getNotesData();

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/slidebar.php';
include __DIR__ . '/views/notes/index.php';
include __DIR__ . '/../includes/footer.php';
