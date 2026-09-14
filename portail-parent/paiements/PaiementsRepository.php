<?php
/**
 * PaiementsRepository — Couche d'accès aux données pour le module paiements
 *
 * Ecole Plus v1.2.0 — 2026-07-05
 */

declare(strict_types=1);

class PaiementsRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAnneeActive(): ?array
    {
        try {
            $stmt = $this->pdo->query("SELECT ID, LIBELLE FROM anneescolaire WHERE STATUT = 1 LIMIT 1");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (\PDOException $e) {
            error_log('[PaiementsRepo] getAnneeActive: ' . $e->getMessage());
            return null;
        }
    }

    public function getResumeFinancier(int $eleveId, int $idAnnee): ?array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    eas.ID AS IDELEVEANNEESCOLAIRE,
                    eas.IDCLASSE,
                    (SELECT COALESCE(SUM(MONTANT), 0) FROM paiementtypeclasse 
                     WHERE IDCLASSE = eas.IDCLASSE AND IDANNEESCOLAIRE = ?) AS montant_theorique,
                    (SELECT COALESCE(SUM(MONTANT), 0) FROM paiementfrais 
                     WHERE IDELEVEANNEESCOLAIRE = eas.ID AND IDANNEESCOLAIRE = ? AND STATUT = 1) AS montant_paye
                FROM eleveanneescolaire eas
                WHERE eas.IDELEVE = ? AND eas.IDANNEESCOLAIRE = ? AND eas.ETAT = 1
                LIMIT 1
            ");
            $stmt->execute([$idAnnee, $idAnnee, $eleveId, $idAnnee]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (\PDOException $e) {
            error_log('[PaiementsRepo] getResumeFinancier: ' . $e->getMessage());
            return null;
        }
    }

    public function getHistoriquePaiements(int $idEAS, int $idAnnee): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT ID, MONTANT, DATE, NOMPAYEUR
                FROM paiementfrais
                WHERE IDELEVEANNEESCOLAIRE = ? AND IDANNEESCOLAIRE = ? AND STATUT = 1
                ORDER BY DATE DESC
            ");
            $stmt->execute([$idEAS, $idAnnee]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('[PaiementsRepo] getHistoriquePaiements: ' . $e->getMessage());
            return [];
        }
    }

    public function verifyParentChild(int $parentId, int $eleveId): bool
    {
        try {
            $stmt = $this->pdo->prepare("SELECT 1 FROM parent_eleve WHERE ID_PARENT = ? AND ID_ELEVE = ? LIMIT 1");
            $stmt->execute([$parentId, $eleveId]);
            return (bool)$stmt->fetch();
        } catch (\PDOException $e) {
            error_log('[PaiementsRepo] verifyParentChild: ' . $e->getMessage());
            return false;
        }
    }

    public function getEleveInfo(int $eleveId): ?array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT e.PRENOM_ELEVE, e.NOM_ELEVE, e.MATRICULE, s.NOMSALLE, s.IDCLASSE
                FROM eleve e
                LEFT JOIN elevesalle es ON e.ID_ELEVE = es.IDELEVE AND es.STATUT = 1
                LEFT JOIN salle s ON es.IDSALLE = s.ID
                WHERE e.ID_ELEVE = ? LIMIT 1
            ");
            $stmt->execute([$eleveId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (\PDOException $e) {
            error_log('[PaiementsRepo] getEleveInfo: ' . $e->getMessage());
            return null;
        }
    }

    public function getParentInfo(int $parentId): ?array
    {
        try {
            $stmt = $this->pdo->prepare("SELECT NOM_PARENT, PRENOM_PARENT, TEL_PARENT, MAIL_PARENT FROM parents WHERE ID_PARENT = ? LIMIT 1");
            $stmt->execute([$parentId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (\PDOException $e) {
            error_log('[PaiementsRepo] getParentInfo: ' . $e->getMessage());
            return null;
        }
    }

    public function getFraisScolaire(int $idClasse, int $idAnnee): float
    {
        try {
            $stmt = $this->pdo->prepare("SELECT COALESCE(SUM(MONTANT), 0) FROM paiementtypeclasse WHERE IDCLASSE = ? AND IDANNEESCOLAIRE = ?");
            $stmt->execute([$idClasse, $idAnnee]);
            return (float)$stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log('[PaiementsRepo] getFraisScolaire: ' . $e->getMessage());
            return 0.0;
        }
    }

    public function getPaiementSingle(int $paiementId, int $eleveId, int $idAnnee): ?array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT pf.ID, pf.MONTANT, pf.DATE, pf.NOMPAYEUR
                FROM paiementfrais pf
                JOIN elevesalle es ON pf.IDELEVEANNEESCOLAIRE = es.ID
                WHERE pf.ID = ? AND es.IDELEVE = ? AND pf.IDANNEESCOLAIRE = ? AND pf.STATUT = 1
                LIMIT 1
            ");
            $stmt->execute([$paiementId, $eleveId, $idAnnee]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (\PDOException $e) {
            error_log('[PaiementsRepo] getPaiementSingle: ' . $e->getMessage());
            return null;
        }
    }

    public function getHistoriqueAnnuel(int $eleveId, int $idAnnee): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT pf.ID, pf.MONTANT, pf.DATE, pf.NOMPAYEUR
                FROM paiementfrais pf
                JOIN elevesalle es ON pf.IDELEVEANNEESCOLAIRE = es.ID
                WHERE es.IDELEVE = ? AND pf.IDANNEESCOLAIRE = ? AND pf.STATUT = 1
                ORDER BY pf.DATE ASC
            ");
            $stmt->execute([$eleveId, $idAnnee]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('[PaiementsRepo] getHistoriqueAnnuel: ' . $e->getMessage());
            return [];
        }
    }
}
