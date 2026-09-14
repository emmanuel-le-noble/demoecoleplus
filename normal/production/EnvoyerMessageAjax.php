<?php
session_name("ecoleplus");
session_start();

header('Content-Type: application/json');

include("../modele/connexion.php");
include("../modele/messagerie.php");

if (!isset($_SESSION['iduser'])) {
    echo json_encode(['ok' => false, 'message' => 'Session expirée.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

$idConversation = isset($_POST['id_conversation']) ? (int)$_POST['id_conversation'] : 0;
$message = trim($_POST['message'] ?? '');

if ($idConversation <= 0 || $message === '') {
    echo json_encode(['ok' => false, 'message' => 'Paramètres invalides.']);
    exit;
}

$idUser = $_SESSION['iduser'];
$idMessage = envoyerMessage($idConversation, 'STAFF', $idUser, $message, $pdo);

echo json_encode([
    'ok' => true,
    'id' => $idMessage,
    'time' => date('d/m/Y H:i')
]);
