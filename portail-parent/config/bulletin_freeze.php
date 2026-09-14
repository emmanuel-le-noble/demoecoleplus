<?php
/**
 * bulletin_freeze.php — Script de gel des moyennes calculées
 *
 * Calcule et fige les moyennes pondérées pour chaque élève/période/année
 * dans la table `bulletin_calcule`. À exécuter après la publication des notes.
 *
 * Usage :
 *   php config/bulletin_freeze.php                    # Geler toutes les périodes
 *   php config/bulletin_freeze.php --position=3       # Geler uniquement le trimestre 3
 *   php config/bulletin_freeze.php --eleve=12         # Geler uniquement l'élève 12
 *   php config/bulletin_freeze.php --unfreeze         # Déverrouiller (rendre recalculable)
 *
 * Ecole Plus v1.2.0 — 2026-07-05
 */

declare(strict_types=1);

// Sécurité : CLI uniquement (pas d'accès web)
if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit('Accès interdit');
}

require_once __DIR__ . '/db.php';

// -------------------------------------------------------------------------
// Parsing des arguments en ligne de commande
// -------------------------------------------------------------------------
$options = getopt('', ['position:', 'eleve:', 'unfreeze', 'help']);

if (isset($options['help'])) {
    echo "Usage: php bulletin_freeze.php [--position=N] [--eleve=N] [--unfreeze]\n";
    echo "  --position=N   Geler uniquement le trimestre N\n";
    echo "  --eleve=N      Geler uniquement l'élève N\n";
    echo "  --unfreeze     Déverrouiller (rendre recalculable)\n";
    exit(0);
}

$positionFilter = isset($options['position']) ? max((int)$options['position'], 0) : 0;
$eleveFilter    = isset($options['eleve']) ? max((int)$options['eleve'], 0) : 0;
$unfreeze       = isset($options['unfreeze']);

echo "==================================================\n";
echo "  Ecole Plus — Gel des bulletins calculés v1.2.0\n";
echo "==================================================\n\n";

// -------------------------------------------------------------------------
// 1. Récupérer l'année scolaire active
// -------------------------------------------------------------------------
$stmt = $pdo->query("SELECT ID FROM anneescolaire WHERE STATUT = 1 LIMIT 1");
$annee = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$annee) {
    echo "[ERREUR] Aucune année scolaire active trouvée.\n";
    exit(1);
}
$idAnnee = (int)$annee['ID'];
echo "[INFO] Année scolaire active : #{$idAnnee}\n";

// -------------------------------------------------------------------------
// 2. Récupérer les périodes
// -------------------------------------------------------------------------
$sqlPos = "SELECT idposition, libposition FROM position";
$paramsPos = [];
if ($positionFilter > 0) {
    $sqlPos .= " WHERE idposition = ?";
    $paramsPos[] = $positionFilter;
}
$sqlPos .= " ORDER BY idposition ASC";

$stmt = $pdo->prepare($sqlPos);
$stmt->execute($paramsPos);
$positions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($positions)) {
    echo "[ERREUR] Aucune période trouvée.\n";
    exit(1);
}

echo "[INFO] Périodes à traiter : " . count($positions) . "\n\n";

// -------------------------------------------------------------------------
// 3. Récupérer les élèves concernés (avec notes publiées)
// -------------------------------------------------------------------------
$sqlEleves = "
    SELECT DISTINCT n.IDELEVE, n.IDSALLE
    FROM note n
    WHERE n.IDANNEESCOLAIRE = ? AND n.EST_PUBLIE = 1
";
$paramsEleves = [$idAnnee];

if ($eleveFilter > 0) {
    $sqlEleves .= " AND n.IDELEVE = ?";
    $paramsEleves[] = $eleveFilter;
}

$stmt = $pdo->prepare($sqlEleves);
$stmt->execute($paramsEleves);
$eleves = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($eleves)) {
    echo "[INFO] Aucun élève avec notes publiées trouvé.\n";
    exit(0);
}

echo "[INFO] Élèves à traiter : " . count($eleves) . "\n\n";

// -------------------------------------------------------------------------
// 4. Calculer les moyennes (sans ranking — sera fait en 2e passe)
// -------------------------------------------------------------------------
$estGele = $unfreeze ? 0 : 1;
$buffer = []; // [salle_id => [position_id => [moyenne => ..., eleve_id => ...]]]

// ANOMALIE CORRIGÉE : Remplacement des valeurs statiques 0 par les marqueurs de paramètres adéquats
$stmtUpdate = $pdo->prepare("
    INSERT INTO bulletin_calcule 
        (IDELEVE, IDSALLE, IDANNEESCOLAIRE, IDPOSITION, 
         MOYENNE_GENERALE, RANG_CLASSE, TOTAL_POINTS, TOTAL_COEFS, 
         NOMBRE_MATIERES, DATE_CALCUL, EST_GELE)
    VALUES (?, ?, ?, ?, ?, 0, ?, ?, ?, NOW(), ?)
    ON DUPLICATE KEY UPDATE
        MOYENNE_GENERALE = VALUES(MOYENNE_GENERALE),
        TOTAL_POINTS = VALUES(TOTAL_POINTS),
        TOTAL_COEFS = VALUES(TOTAL_COEFS),
        NOMBRE_MATIERES = VALUES(NOMBRE_MATIERES),
        DATE_CALCUL = NOW(),
        EST_GELE = VALUES(EST_GELE)
");

$totalProcessed = 0;

foreach ($eleves as $eleve) {
    $idEleve = (int)$eleve['IDELEVE'];
    $idSalle = (int)$eleve['IDSALLE'];

    foreach ($positions as $position) {
        $idPosition = (int)$position['idposition'];
        $libPosition = $position['libposition'];

        // Récupérer les notes publiées (exclure NULL)
        $stmtNotes = $pdo->prepare("
            SELECT MOYENTRIMES, COEF
            FROM note
            WHERE IDELEVE = ? AND IDANNEESCOLAIRE = ? AND IDPOSITION = ? 
              AND EST_PUBLIE = 1 AND MOYENTRIMES IS NOT NULL
        ");
        $stmtNotes->execute([$idEleve, $idAnnee, $idPosition]);
        $notes = $stmtNotes->fetchAll(PDO::FETCH_ASSOC);

        if (empty($notes)) {
            continue;
        }

        // Calculer la moyenne pondérée
        $totalPoints = 0.0;
        $totalCoefs = 0.0;
        $nombreMatieres = count($notes);

        foreach ($notes as $n) {
            $moyenne = floatval($n['MOYENTRIMES']);
            $coef = max(floatval($n['COEF'] ?? 1), 1.0); // Minimum coef = 1
            $totalPoints += $moyenne * $coef;
            $totalCoefs += $coef;
        }

        $moyenneGenerale = $totalCoefs > 0 ? round($totalPoints / $totalCoefs, 2) : 0.0;

        // Insérer/mettre à jour avec injection de la variable moyenne générale calculée
        $stmtUpdate->execute([
            $idEleve, $idSalle, $idAnnee, $idPosition,
            $moyenneGenerale, $totalPoints, $totalCoefs, $nombreMatieres,
            $estGele,
        ]);

        // Buffer pour le calcul du ranking
        $buffer[$idSalle][$idPosition][] = [
            'eleve_id' => $idEleve,
            'moyenne' => $moyenneGenerale,
        ];

        $totalProcessed++;
        echo "  [OK] Élève #{$idEleve} | {$libPosition} | Moy: {$moyenneGenerale}\n";
    }
}

// -------------------------------------------------------------------------
// 5. Deuxième passe : calculer les rankings par classe/période
// -------------------------------------------------------------------------
echo "\n[INFO] Calcul des classements...\n";

$stmtRank = $pdo->prepare("
    UPDATE bulletin_calcule 
    SET RANG_CLASSE = ? 
    WHERE IDELEVE = ? AND IDANNEESCOLAIRE = ? AND IDSALLE = ? AND IDPOSITION = ?
");

$rangUpdates = 0;

foreach ($buffer as $idSalle => $periods) {
    foreach ($periods as $idPosition => $elevesData) {
        // Trier par moyenne décroissante
        usort($elevesData, fn($a, $b) => $b['moyenne'] <=> $a['moyenne']);

        $rang = 1;
        foreach ($elevesData as $idx => $data) {
            // Gestion des ex aequo : même moyenne = même rang
            if ($idx > 0 && $data['moyenne'] < $elevesData[$idx - 1]['moyenne']) {
                $rang = $idx + 1;
            }

            $stmtRank->execute([$rang, $data['eleve_id'], $idAnnee, $idSalle, $idPosition]);
            $rangUpdates++;
        }
    }
}

echo "\n==================================================\n";
echo "  Résultat : {$totalProcessed} bulletins calculés\n";
echo "  Rankings : {$rangUpdates} mis à jour\n";
echo "  Statut   : " . ($unfreeze ? "Déverrouillés (recalculables)" : "Gelés (figés)") . "\n";
echo "==================================================\n";