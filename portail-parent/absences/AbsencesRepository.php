<?php
/**
 * AbsencesRepository — Couche d'accès aux données pour le module absences
 *
 * Toutes les requêtes SQL liées aux absences/permissions sont centralisées ici.
 * Aucune logique métier ni HTML — uniquement les opérations CRUD.
 *
 * Ecole Plus v1.2.0 — 2026-07-05
 */

declare(strict_types=1);

class AbsencesRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Résoudre ID_ELEVESALLE et IDSALLE depuis IDELEVE
     */
    public function resolveEleveSalle(int $eleveId): ?array
    {
        try {
            $stmt = $this->pdo->prepare(
                "SELECT ID AS id, IDSALLE AS idsalle FROM elevesalle WHERE IDELEVE = ? AND STATUT = 1 LIMIT 1"
            );
            $stmt->execute([$eleveId]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (\PDOException $e) {
            error_log('[AbsencesRepo] resolveEleveSalle: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupérer l'année scolaire active
     */
    public function getAnneeActive(): ?array
    {
        try {
            $stmt = $this->pdo->query(
                "SELECT ID AS id, LIBELLE AS libelle FROM anneescolaire WHERE STATUT = 1 LIMIT 1"
            );
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (\PDOException $e) {
            error_log('[AbsencesRepo] getAnneeActive: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Inscrire une demande de permission / absence
     */
    public function insertAbsence(array $data): bool
    {
        try {
            // Utilisation de NOW() à la place de la date brute pour DATEENREG si c'est un datetime, sinon CURDATE()
            $stmt = $this->pdo->prepare("
                INSERT INTO absences 
                    (IDELEVESALLE, IDSALLE, IDANNEESCOLAIRE, IDPOSITION, NBREABSENCE, 
                     DATEENREG, DATE_DEMANDE, DATE_DEBUT, TYPEABSENCE, 
                     MOTIF_PERMISSION, STATUT_PERMISSION, IDUSERCREATE) 
                VALUES (?, ?, ?, 0, ?, NOW(), CURDATE(), ?, ?, ?, 0, ?)
            ");

            return $stmt->execute([
                (int)$data['id_eleve_salle'],
                (int)$data['id_salle'],
                (int)$data['id_annee'],
                (int)$data['duree'],
                $data['date_absence'],
                $data['type_absence'],
                $data['motif'],
                (int)$data['id_user'],
            ]);
        } catch (\PDOException $e) {
            error_log('[AbsencesRepo] insertAbsence: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Statistiques d'absences (justifiées / non justifiées)
     */
    public function getStats(int $idEleveSalle, int $idAnnee): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    SUM(CASE WHEN ab.STATUT_PERMISSION = 1 THEN ab.NBREABSENCE ELSE 0 END) AS abs_justifiees,
                    SUM(CASE WHEN ab.STATUT_PERMISSION != 1 OR ab.STATUT_PERMISSION IS NULL THEN ab.NBREABSENCE ELSE 0 END) AS abs_non_justifiees
                FROM absences ab
                WHERE ab.IDELEVESALLE = ? AND ab.IDANNEESCOLAIRE = ?
            ");
            $stmt->execute([$idEleveSalle, $idAnnee]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            $justifiees = (int)($row['abs_justifiees'] ?? 0);
            $nonJustifiees = (int)($row['abs_non_justifiees'] ?? 0);

            return [
                'justifiees'     => $justifiees,
                'non_justifiees' => $nonJustifiees,
                'total'          => $justifiees + $nonJustifiees,
            ];
        } catch (\PDOException $e) {
            error_log('[AbsencesRepo] getStats: ' . $e->getMessage());
            return ['justifiees' => 0, 'non_justifiees' => 0, 'total' => 0];
        }
    }

    /**
     * Historique chronologique des absences
     */
    public function getHistorique(int $idEleveSalle, int $idAnnee): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT ab.DATEENREG AS date_enreg, ab.NBREABSENCE AS nbre_absence, ab.TYPEABSENCE AS type_absence, 
                       ab.MOTIF_PERMISSION AS motif_permission, ab.STATUT_PERMISSION AS statut_permission
                FROM absences ab
                WHERE ab.IDELEVESALLE = ? AND ab.IDANNEESCOLAIRE = ?
                ORDER BY ab.DATEENREG DESC
            ");
            $stmt->execute([$idEleveSalle, $idAnnee]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            error_log('[AbsencesRepo] getHistorique: ' . $e->getMessage());
            return [];
        }
    }
}