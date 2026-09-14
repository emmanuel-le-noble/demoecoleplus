<?php

function getDemandesPermission($pdo)
{
    $stmt = $pdo->query("
        SELECT a.ID, a.DATE_DEMANDE, a.DATE_DEBUT, a.DATE_FIN, a.MOTIF_PERMISSION, 
               a.STATUT_PERMISSION, a.FICHIER_ABSENCE,
               e.NOM_ELEVE, e.PRENOM_ELEVE, e.MATRICULE,
               s.NOMSALLE,
               CONCAT(pt.NOM_PARENT, ' ', pt.PRENOM_PARENT) as NOM_PARENT,
               pt.MAIL_PARENT
        FROM absences a
        INNER JOIN elevesalle es ON a.IDELEVESALLE = es.ID
        INNER JOIN eleve e ON es.IDELEVE = e.ID_ELEVE
        INNER JOIN salle s ON es.IDSALLE = s.ID
        LEFT JOIN parent_eleve pe ON pe.ID_ELEVE = e.ID_ELEVE
        LEFT JOIN parents pt ON pe.ID_PARENT = pt.ID_PARENT
        WHERE a.MOTIF_PERMISSION IS NOT NULL AND a.MOTIF_PERMISSION != ''
        ORDER BY a.DATE_DEMANDE DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getDemandeById($id, $pdo)
{
    $stmt = $pdo->prepare("
        SELECT a.ID, a.DATE_DEMANDE, a.DATE_DEBUT, a.DATE_FIN, a.MOTIF_PERMISSION, 
               a.STATUT_PERMISSION, a.FICHIER_ABSENCE,
               e.NOM_ELEVE, e.PRENOM_ELEVE, e.MATRICULE,
               s.NOMSALLE,
               CONCAT(pt.NOM_PARENT, ' ', pt.PRENOM_PARENT) as NOM_PARENT,
               pt.MAIL_PARENT
        FROM absences a
        INNER JOIN elevesalle es ON a.IDELEVESALLE = es.ID
        INNER JOIN eleve e ON es.IDELEVE = e.ID_ELEVE
        INNER JOIN salle s ON es.IDSALLE = s.ID
        LEFT JOIN parent_eleve pe ON pe.ID_ELEVE = e.ID_ELEVE
        LEFT JOIN parents pt ON pe.ID_PARENT = pt.ID_PARENT
        WHERE a.ID = ?
    ");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function traiterDemande($id, $statut, $pdo)
{
    $stmt = $pdo->prepare("UPDATE absences SET STATUT_PERMISSION = ? WHERE ID = ?");
    $stmt->execute([$statut, $id]);
}
