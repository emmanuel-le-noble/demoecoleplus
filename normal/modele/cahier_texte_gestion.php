<?php

function getAllCahierTexte($idanneescolaire, $pdo)
{
    $stmt = $pdo->prepare("
        SELECT ct.ID, ct.DATE_COURS, ct.CONTENU_COURS, ct.DEVOIRS_A_FAIRE, ct.DATE_ECHEANCE, ct.FICHIER_DEVOIR,
               m.NOM_MATIERE,
               CONCAT(p.NOM, ' ', p.TITRE) as PROFESSEUR,
               s.NOMSALLE
        FROM cahier_texte ct
        LEFT JOIN matiere m ON ct.IDMATIERE = m.ID_MATIERE
        LEFT JOIN professeur p ON ct.IDPROF = p.ID
        LEFT JOIN salle s ON ct.IDSALLE = s.ID
        WHERE ct.IDANNEESCOLAIRE = ?
        ORDER BY ct.DATE_COURS DESC
    ");
    $stmt->execute([$idanneescolaire]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCahierTexteById($id, $pdo)
{
    $stmt = $pdo->prepare("
        SELECT ct.*, m.NOM_MATIERE, CONCAT(p.NOM, ' ', p.TITRE) as PROFESSEUR, s.NOMSALLE
        FROM cahier_texte ct
        LEFT JOIN matiere m ON ct.IDMATIERE = m.ID_MATIERE
        LEFT JOIN professeur p ON ct.IDPROF = p.ID
        LEFT JOIN elevesalle es ON ct.IDSALLE = es.ID
        LEFT JOIN salle s ON es.IDSALLE = s.ID
        WHERE ct.ID = ?
    ");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function createCahierTexte($idSalle, $idMatiere, $idProf, $idAnneeScolaire, $dateCours, $contenuCours, $devoirs, $dateEcheance, $pdo)
{
    $stmt = $pdo->prepare("
        INSERT INTO cahier_texte (IDSALLE, IDMATIERE, IDPROF, IDANNEESCOLAIRE, DATE_COURS, CONTENU_COURS, DEVOIRS_A_FAIRE, DATE_ECHEANCE)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$idSalle, $idMatiere, $idProf, $idAnneeScolaire, $dateCours, $contenuCours, $devoirs, $dateEcheance ?: null]);
    return (int)$pdo->lastInsertId();
}

function updateCahierTexte($id, $dateCours, $contenuCours, $devoirs, $dateEcheance, $pdo)
{
    $stmt = $pdo->prepare("UPDATE cahier_texte SET DATE_COURS = ?, CONTENU_COURS = ?, DEVOIRS_A_FAIRE = ?, DATE_ECHEANCE = ? WHERE ID = ?");
    $stmt->execute([$dateCours, $contenuCours, $devoirs, $dateEcheance ?: null, $id]);
}

function deleteCahierTexte($id, $pdo)
{
    $stmt = $pdo->prepare("DELETE FROM cahier_texte WHERE ID = ?");
    $stmt->execute([$id]);
}

function getMatieres($pdo)
{
    $stmt = $pdo->query("SELECT ID_MATIERE, NOM_MATIERE FROM matiere WHERE STATUT_MATIERE = 1 ORDER BY NOM_MATIERE");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProfesseurs($pdo)
{
    $stmt = $pdo->query("SELECT ID, CONCAT(NOM, ' ', TITRE) as NOM FROM professeur WHERE STATUT = 1 ORDER BY NOM");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getSalles($idanneescolaire, $pdo)
{
    $stmt = $pdo->prepare("
        SELECT s.ID, s.NOMSALLE 
        FROM salle s 
        INNER JOIN elevesalle es ON s.ID = es.IDSALLE 
        WHERE es.STATUT = 1
        GROUP BY s.ID, s.NOMSALLE
        ORDER BY s.NOMSALLE
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
