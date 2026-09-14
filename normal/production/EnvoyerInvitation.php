<?php
session_name("ecoleplus");
session_start();

header('Content-Type: application/json');

include("../modele/connexion.php");
include("../modele/invitation_service.php");

if (!isset($_SESSION['iduser'])) {
    echo json_encode(['ok' => false, 'message' => 'Session expirée. Veuillez vous reconnecter.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'message' => 'Méthode non autorisée.']);
    exit;
}

$ideleve = isset($_POST['id_eleve']) ? (int)$_POST['id_eleve'] : 0;

if ($ideleve <= 0) {
    echo json_encode(['ok' => false, 'message' => 'Identifiant élève invalide.']);
    exit;
}

$stmt = $pdo->prepare('SELECT nom_eleve, prenom_eleve, mailtuteur, teltuteur FROM eleve WHERE id_eleve = ?');
$stmt->execute([$ideleve]);
$eleve = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$eleve) {
    echo json_encode(['ok' => false, 'message' => 'Élève introuvable.']);
    exit;
}

$result = envoyerInvitationParent(
    $pdo,
    $ideleve,
    $eleve['mailtuteur'],
    $eleve['teltuteur'],
    $eleve['prenom_eleve'],
    $eleve['nom_eleve']
);

echo json_encode($result);
