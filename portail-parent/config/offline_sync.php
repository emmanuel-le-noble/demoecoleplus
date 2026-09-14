<?php
/**
 * offline_sync.php — Endpoint pour synchroniser les données hors-ligne
 *
 * Renvoie les données de l'enfant actif au format JSON pour stockage IndexedDB.
 * Appelé par le JS quand l'utilisateur est en ligne.
 *
 * Usage GET : offline_sync.php?module=notes|absences|annonces|cahier_texte|all
 *
 * Ecole Plus v1.2.0 — 2026-07-05
 */

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('X-Content-Type-Options: nosniff');

// ANOMALIE CORRIGÉE : Inclusion explicite de db.php nécessaire pour hydrater $pdo avant l'exécution du contrôleur de session
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../includes/session.php';

$parentId = $_SESSION['id_parent'] ?? 0;
$eleveId = $_SESSION['active_eleve_id'] ?? 0;

if ($parentId <= 0 || $eleveId <= 0) {
    http_response_code(401);
    echo json_encode(['error' => 'Non autorisé']);
    exit;
}

// Vérifier que l'enfant appartient au parent
$stmt = $pdo->prepare("SELECT 1 FROM parent_eleve WHERE ID_PARENT = ? AND ID_ELEVE = ? LIMIT 1");
$stmt->execute([$parentId, $eleveId]);
if (!$stmt->fetch()) {
    http_response_code(403);
    echo json_encode(['error' => 'Accès interdit']);
    exit;
}

// Whitelist des modules autorisés
$allowedModules = ['all', 'notes', 'absences', 'annonces', 'cahier_texte'];
$module = $_GET['module'] ?? 'all';
if (!in_array($module, $allowedModules, true)) {
    http_response_code(400);
    echo json_encode(['error' => 'Module invalide']);
    exit;
}

$response = ['success' => true, 'eleve_id' => $eleveId, 'timestamp' => date('c')];

try {
    // Année scolaire active
    $stmtAnnee = $pdo->query("SELECT ID FROM anneescolaire WHERE STATUT = 1 LIMIT 1");
    $annee = $stmtAnnee->fetch(PDO::FETCH_ASSOC);
    $idAnnee = $annee ? (int)$annee['ID'] : 0;

    if ($module === 'all' || $module === 'notes') {
        try {
            $stmtNotes = $pdo->prepare("
                SELECT n.MOYENTRIMES AS moyentrimes, n.NOTEINT AS noteint, n.NOTEDS AS noteds, n.NOTEDN AS notedn, n.COEF AS coef,
                       n.OBSERVATION AS observation, m.NOM_MATIERE AS nom_matiere, p.libposition AS libposition
                FROM note n
                JOIN matiere m ON n.IDMATIERE = m.ID_MATIERE
                JOIN position p ON n.IDPOSITION = p.idposition
                WHERE n.IDELEVE = ? AND n.IDANNEESCOLAIRE = ? AND n.EST_PUBLIE = 1
                ORDER BY p.idposition ASC, m.NOM_MATIERE ASC
            ");
            $stmtNotes->execute([$eleveId, $idAnnee]);
            $response['notes'] = $stmtNotes->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('[OfflineSync] Erreur module notes: ' . $e->getMessage());
            $response['notes'] = [];
        }
    }

    if ($module === 'all' || $module === 'absences') {
        try {
            $stmtAbs = $pdo->prepare("
                SELECT a.ID AS id, a.DATEENREG AS dateenreg, a.NBREABSENCE AS nbreabsence, a.TYPEABSENCE AS typeabsence,
                       a.MOTIF_PERMISSION AS motif_permission, a.STATUT_PERMISSION AS statut_permission,
                       a.DATE_DEMANDE AS date_demande, a.DATE_DEBUT AS date_debut, a.DATE_FIN AS date_fin,
                       a.IDSALLE AS idsalle
                FROM absences a
                JOIN elevesalle es ON a.IDELEVESALLE = es.ID
                WHERE es.IDELEVE = ?
                ORDER BY a.DATEENREG DESC
                LIMIT 50
            ");
            $stmtAbs->execute([$eleveId]);
            $response['absences'] = $stmtAbs->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('[OfflineSync] Erreur module absences: ' . $e->getMessage());
            $response['absences'] = [];
        }
    }

    if ($module === 'all' || $module === 'annonces') {
        try {
            $stmtAnnonces = $pdo->query("
                SELECT ID, TITRE AS titre, CONTENU AS contenu, DATE_PUBLICATION AS date_publication
                FROM annonces_ecole
                ORDER BY DATE_PUBLICATION DESC
                LIMIT 20
            ");
            $response['annonces'] = $stmtAnnonces->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('[OfflineSync] Erreur module annonces: ' . $e->getMessage());
            $response['annonces'] = [];
        }
    }

    if ($module === 'all' || $module === 'cahier_texte') {
        try {
            $stmtCT = $pdo->prepare("
                SELECT ct.ID AS id, ct.DATE_COURS AS date_cours, ct.CONTENU_COURS AS contenu_cours,
                       ct.DEVOIRS_A_FAIRE AS devoirs_a_faire, ct.DATE_ECHEANCE AS date_echeance, ct.FICHIER_DEVOIR AS fichier_devoir,
                       m.NOM_MATIERE AS nom_matiere, p.NOM AS prof_nom,
                       s.NOMSALLE AS classe
                FROM cahier_texte ct
                JOIN matiere m ON ct.IDMATIERE = m.ID_MATIERE
                JOIN professeur p ON ct.IDPROF = p.ID
                JOIN salle s ON ct.IDSALLE = s.ID
                JOIN elevesalle es ON es.IDSALLE = s.ID AND es.STATUT = 1
                WHERE es.IDELEVE = ?
                ORDER BY ct.DATE_COURS DESC
                LIMIT 30
            ");
            $stmtCT->execute([$eleveId]);
            $response['cahier_texte'] = $stmtCT->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('[OfflineSync] Erreur module cahier_texte: ' . $e->getMessage());
            $response['cahier_texte'] = [];
        }
    }

    // Profil enfant
    try {
        $stmtProfil = $pdo->prepare("
            SELECT e.ID_ELEVE AS id_eleve, e.NOM_ELEVE AS nom_eleve, e.PRENOM_ELEVE AS prenom_eleve, e.MATRICULE AS matricule, e.SEXE_ELEVE AS sexe_eleve,
                   s.NOMSALLE AS classe
            FROM eleve e
            JOIN elevesalle es ON es.IDELEVE = e.ID_ELEVE AND es.STATUT = 1
            JOIN salle s ON es.IDSALLE = s.ID
            WHERE e.ID_ELEVE = ?
            LIMIT 1
        ");
        $stmtProfil->execute([$eleveId]);
        $response['profil'] = $stmtProfil->fetch(PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
        error_log('[OfflineSync] Erreur module profil: ' . $e->getMessage());
        $response['profil'] = null;
    }

} catch (\PDOException $e) {
    error_log('[OfflineSync] Erreur PDO globale: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur']);
    exit;
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);