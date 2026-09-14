<?php

function getAllParents($pdo)
{
    $stmt = $pdo->query("
        SELECT p.ID_PARENT, p.NOM_PARENT, p.PRENOM_PARENT, p.SEXE_PARENT, 
               p.TEL_PARENT, p.MAIL_PARENT, p.LOGIN_PARENT, p.STATUT_PARENT, p.DATE_CREATION,
               GROUP_CONCAT(CONCAT(e.PRENOM_ELEVE, ' ', e.NOM_ELEVE, ' (', s.NOMSALLE, ')') SEPARATOR ', ') as ENFANTS
        FROM parents p
        LEFT JOIN parent_eleve pe ON p.ID_PARENT = pe.ID_PARENT
        LEFT JOIN eleve e ON pe.ID_ELEVE = e.ID_ELEVE
        LEFT JOIN eleveanneescolaire eas ON e.ID_ELEVE = eas.IDELEVE
        LEFT JOIN elevesalle es ON eas.ID = es.IDELEVE
        LEFT JOIN salle s ON es.IDSALLE = s.ID
        GROUP BY p.ID_PARENT
        ORDER BY p.DATE_CREATION DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function toggleStatutParent($idParent, $nouveauStatut, $pdo)
{
    $stmt = $pdo->prepare("UPDATE parents SET STATUT_PARENT = ? WHERE ID_PARENT = ?");
    $stmt->execute([$nouveauStatut, $idParent]);
}

function resetPasswordParent($idParent, $nouveauMdp, $pdo)
{
    $hash = password_hash($nouveauMdp, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE parents SET MTPASS_PARENT = ? WHERE ID_PARENT = ?");
    $stmt->execute([$hash, $idParent]);
}

function getParentById($idParent, $pdo)
{
    $stmt = $pdo->prepare("
        SELECT p.*, 
               GROUP_CONCAT(CONCAT(e.PRENOM_ELEVE, ' ', e.NOM_ELEVE) SEPARATOR ', ') as ENFANTS
        FROM parents p
        LEFT JOIN parent_eleve pe ON p.ID_PARENT = pe.ID_PARENT
        LEFT JOIN eleve e ON pe.ID_ELEVE = e.ID_ELEVE
        WHERE p.ID_PARENT = ?
        GROUP BY p.ID_PARENT
    ");
    $stmt->execute([$idParent]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
