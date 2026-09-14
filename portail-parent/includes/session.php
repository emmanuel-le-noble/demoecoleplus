<?php
/**
 * session.php — Gestion centralisée des sessions et contexte enfant (v1.2.0)
 * 
 * Ecole Plus v1.2.0 — 2026-07-06
 */

declare(strict_types=1);

require_once __DIR__ . '/session_bootstrap.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/helpers.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!isset($_SESSION['id_parent']) && isset($_SESSION['parent_id'])) {
    $_SESSION['id_parent'] = $_SESSION['parent_id'];
}

$parent_id = $_SESSION['id_parent'] ?? null;
$current_script = basename($_SERVER['PHP_SELF'] ?? '');

if (!$parent_id && $current_script !== 'login.php') {
    header('Location: ../auth/login.php');
    exit;
}

if (!$parent_id && $current_script === 'login.php') {
    return;
}

// ==========================================================================
// 1. INTERCEPTION ET TRAITEMENT DU CHANGEMENT D'ENFANT (PRG Pattern)
// ==========================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['switch_enfant_id'])) {
    if (isset($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
        $_SESSION['active_eleve_id'] = (int)$_POST['switch_enfant_id'];
    }
    
    $page_actuelle = parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH);
    if (!empty($_GET)) {
        $page_actuelle .= '?' . http_build_query($_GET);
    }
    if (!is_string($page_actuelle) || $page_actuelle === '' || !str_starts_with($page_actuelle, '/') || str_starts_with($page_actuelle, '//')) {
        $page_actuelle = '/';
    }
    
    header("Location: " . $page_actuelle);
    exit;
}

// ==========================================================================
// 2. RÉCUPÉRATION DES ENFANTS (Exécutée une seule fois)
// ==========================================================================
$stmtEnfants = $pdo->prepare("SELECT 
        e.ID_ELEVE, 
        e.NOM_ELEVE, 
        e.PRENOM_ELEVE, 
        e.PHOTO, 
        s.NOMSALLE, 
        s.ID as ID_SALLE,
        es.ID as ID_ELEVE_SALLE
    FROM eleve e 
    JOIN parent_eleve pe ON e.ID_ELEVE = pe.ID_ELEVE 
    LEFT JOIN elevesalle es ON e.ID_ELEVE = es.IDELEVE AND es.STATUT = 1
    LEFT JOIN salle s ON es.IDSALLE = s.ID 
    WHERE pe.ID_PARENT = ? 
      AND (e.ETAT_ELEVE = 'Actif' OR e.ETAT_ELEVE = '1')
");
$stmtEnfants->execute([$parent_id]);
$mes_enfants = $stmtEnfants->fetchAll(PDO::FETCH_ASSOC) ?: [];

if (empty($mes_enfants)) {
    die("Aucun enfant actif n'est associé à ce compte parent. Veuillez contacter l'administration.");
}

// ==========================================================================
// 3. DÉFINITION ET VÉRIFICATION DE L'ENFANT ACTIF
// ==========================================================================
if (!isset($_SESSION['active_eleve_id']) || empty($_SESSION['active_eleve_id'])) {
    $_SESSION['active_eleve_id'] = $mes_enfants[0]['ID_ELEVE'];
}

$enfant_actif_id = (int)$_SESSION['active_eleve_id'];

$enfant_actif = null;
foreach ($mes_enfants as $enf) {
    if ((int)$enf['ID_ELEVE'] === $enfant_actif_id) {
        $enfant_actif = $enf;
        break;
    }
}

if (!$enfant_actif) {
    $enfant_actif = $mes_enfants[0];
    $_SESSION['active_eleve_id'] = $enfant_actif['ID_ELEVE'];
    $enfant_actif_id = (int)$enfant_actif['ID_ELEVE'];
}
