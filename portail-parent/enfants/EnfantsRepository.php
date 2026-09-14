<?php
/**
 * EnfantsRepository — Gestion des requêtes de données pour la fratrie
 *
 * Ecole Plus v1.2.0 — 2026-07-06
 */

declare(strict_types=1);

class EnfantsRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAnneeActive(): ?array
    {
        try {
            $stmt = $this->pdo->query("SELECT ID FROM anneescolaire WHERE STATUT = 1 LIMIT 1");
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (\PDOException $e) {
            error_log('[EnfantsRepo] getAnneeActive: ' . $e->getMessage());
            return null;
        }
    }

    public function getEleveSalle(int $eleveId): ?array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT es.ID as ID_ELEVE_SALLE, s.IDCLASSE, s.NOMSALLE
                FROM elevesalle es
                JOIN salle s ON es.IDSALLE = s.ID
                WHERE es.IDELEVE = ? AND es.STATUT = 1
                LIMIT 1
            ");
            $stmt->execute([$eleveId]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (\PDOException $e) {
            error_log('[EnfantsRepo] getEleveSalle: ' . $e->getMessage());
            return null;
        }
    }

    public function getTotalFraisClasse(int $classeId, int $anneeId): float
    {
        try {
            $stmt = $this->pdo->prepare("SELECT COALESCE(SUM(MONTANT), 0) FROM paiementtypeclasse WHERE IDCLASSE = ? AND IDANNEESCOLAIRE = ?");
            $stmt->execute([$classeId, $anneeId]);
            return (float)$stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log('[EnfantsRepo] getTotalFraisClasse: ' . $e->getMessage());
            return 0.0;
        }
    }

    public function getTotalPaye(int $ideleveSalle, int $anneeId): float
    {
        try {
            $stmt = $this->pdo->prepare("SELECT COALESCE(SUM(MONTANT), 0) FROM paiementfrais WHERE IDELEVEANNEESCOLAIRE = ? AND IDANNEESCOLAIRE = ? AND STATUT = 1");
            $stmt->execute([$ideleveSalle, $anneeId]);
            return (float)$stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log('[EnfantsRepo] getTotalPaye: ' . $e->getMessage());
            return 0.0;
        }
    }

    public function getTotalAbsences(int $ideleveSalle, int $anneeId): int
    {
        try {
            $stmt = $this->pdo->prepare("SELECT COALESCE(SUM(NBREABSENCE), 0) FROM absences WHERE IDELEVESALLE = ? AND IDANNEESCOLAIRE = ?");
            $stmt->execute([$ideleveSalle, $anneeId]);
            return (int)$stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log('[EnfantsRepo] getTotalAbsences: ' . $e->getMessage());
            return 0;
        }
    }

    public function getNoteCount(int $eleveId, int $anneeId): int
    {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM note WHERE IDELEVE = ? AND IDANNEESCOLAIRE = ?");
            $stmt->execute([$eleveId, $anneeId]);
            return (int)$stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log('[EnfantsRepo] getNoteCount: ' . $e->getMessage());
            return 0;
        }
    }

    public function getNotes(int $eleveId, int $anneeId): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT n.*, m.NOM_MATIERE, p.libposition
                FROM note n
                JOIN matiere m ON n.IDMATIERE = m.ID_MATIERE
                LEFT JOIN position p ON n.IDPOSITION = p.idposition
                WHERE n.IDELEVE = ? AND n.IDANNEESCOLAIRE = ?
                ORDER BY p.idposition DESC, m.NOM_MATIERE ASC
            ");
            $stmt->execute([$eleveId, $anneeId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            error_log('[EnfantsRepo] getNotes: ' . $e->getMessage());
            return [];
        }
    }
}