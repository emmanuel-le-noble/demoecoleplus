<?php
session_name("ecoleplus");
session_start();

header('Content-Type: application/json');

include("../modele/connexion.php");
include("../modele/messagerie.php");

if (!isset($_SESSION['iduser'])) {
    echo json_encode(['ok' => false, 'messages' => []]);
    exit;
}

$idConversation = isset($_GET['conv']) ? (int)$_GET['conv'] : 0;
if ($idConversation <= 0) {
    echo json_encode(['ok' => false, 'messages' => []]);
    exit;
}

$messages = getMessages($idConversation, $_SESSION['iduser'], $pdo);

$lastId = 0;
$formatted = [];
foreach ($messages as $msg) {
    $formatted[] = [
        'id' => $msg['ID'],
        'expediteur_type' => $msg['EXPEDITEUR_TYPE'],
        'nom' => $msg['expediteur_nom'],
        'contenu' => $msg['CONTENU'],
        'date' => date('d/m/Y H:i', strtotime($msg['DATE_ENVOI'])),
        'is_staff' => $msg['EXPEDITEUR_TYPE'] === 'STAFF'
    ];
    $lastId = $msg['ID'];
}

echo json_encode(['ok' => true, 'messages' => $formatted, 'last_id' => $lastId]);
