<?php
/**
 * CahierTexteRepository — Gestion des requêtes SQL pour le cahier de textes
 *
 * Ecole Plus v1.2.0 — 2026-07-06
 */

declare(strict_types=1);

class CahierTexteRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Récupérer l'ID de l'année académique active
     */
    public function getAnneeActive(): ?array
    {
        try {
            $stmt = $this->pdo->query("SELECT ID AS id FROM anneescolaire WHERE STATUT = 1 LIMIT 1");
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (\PDOException $e) {
            error_log('[CahierTexteRepo] getAnneeActive: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtenir la liste des matières rattachées à une classe donnée
     */
    public function getMatieresBySalle(int $salleId, int $anneeId): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT DISTINCT m.ID_MATIERE AS id_matiere, m.NOM_MATIERE AS nom_matiere
                FROM professeursallemat psm
                JOIN matiere m ON psm.IDMAT = m.ID_MATIERE
                WHERE psm.IDSALLE = ? AND psm.IDANNEESCOLAIRE = ? AND psm.STATUT = 1
                ORDER BY m.NOM_MATIERE ASC
            ");
            $stmt->execute([$salleId, $anneeId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            error_log('[CahierTexteRepo] getMatieresBySalle: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer l'historique des cours et des devoirs avec filtrage optionnel
     */
    public function getCahierTextes(int $salleId, int $anneeId, ?int $matiereId): array
    {
        try {
            $sql = "
                SELECT c.ID AS id, 
                       c.CONTENU_COURS AS contenu_cours, 
                       c.DEVOIRS_A_FAIRE AS devoirs_a_faire, 
                       c.DATE_COURS AS date_cours, 
                       c.DATE_ECHEANCE AS date_echeance, 
                       c.FICHIER_DEVOIR AS fichier_devoir, 
                       m.NOM_MATIERE AS nom_matiere, 
                       p.NOM AS prof_nom
                FROM cahier_texte c
                JOIN matiere m ON c.IDMATIERE = m.ID_MATIERE
                JOIN professeur p ON c.IDPROF = p.ID
                WHERE c.IDSALLE = :id_salle
                  AND c.IDANNEESCOLAIRE = :id_annee
            ";
            $params = [':id_salle' => $salleId, ':id_annee' => $anneeId];

            if ($matiereId !== null && $matiereId > 0) {
                $sql .= " AND c.IDMATIERE = :id_matiere";
                $params[':id_matiere'] = $matiereId;
            }

            // Tri principal : Devoirs non dépassés d'abord, puis par date de cours décroissante
            $sql .= " ORDER BY CASE WHEN c.DATE_ECHEANCE >= CURDATE() THEN 0 ELSE 1 END, c.DATE_ECHEANCE ASC, c.DATE_COURS DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            error_log('[CahierTexteRepo] getCahierTextes: ' . $e->getMessage());
            return [];
        }
    }
}