<?php
/**
 * DashboardRepository — Couche d'accès aux données du tableau de bord
 *
 * Ecole Plus v1.2.0 — 2026-07-06
 */

declare(strict_types=1);

class DashboardRepository
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
            error_log('[DashboardRepo] getAnneeActive: ' . $e->getMessage());
            return null;
        }
    }

    public function getEleveInfos(int $eleveId, int $anneeId): ?array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT e.PRENOM_ELEVE, e.NOM_ELEVE, s.ID AS IDSALLE, s.NOMSALLE, cl.IDCLASSE, cl.NOMCLASSE, eas.ID AS IDELEVEANNEESCOLAIRE
                FROM eleve e
                LEFT JOIN eleveanneescolaire eas ON e.ID_ELEVE = eas.IDELEVE AND eas.IDANNEESCOLAIRE = ? AND eas.ETAT = 1
                LEFT JOIN elevesalle es ON e.ID_ELEVE = es.IDELEVE AND es.STATUT = 1
                LEFT JOIN salle s ON es.IDSALLE = s.ID
                LEFT JOIN classe cl ON s.IDCLASSE = cl.IDCLASSE
                WHERE e.ID_ELEVE = ?
                LIMIT 1
            ");
            $stmt->execute([$anneeId, $eleveId]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (\PDOException $e) {
            error_log('[DashboardRepo] getEleveInfos: ' . $e->getMessage());
            return null;
        }
    }

    public function getMoyenneGenerale(int $eleveId, int $anneeId): ?float
    {
        try {
            $stmt = $this->pdo->prepare("SELECT SUM(MOYENTRIMES * COALESCE(NULLIF(COEF, 0), 1)) / NULLIF(SUM(COALESCE(NULLIF(COEF, 0), 1)), 0) AS moyenne FROM note WHERE IDELEVE = ? AND IDANNEESCOLAIRE = ? AND EST_PUBLIE = 1");
            $stmt->execute([$eleveId, $anneeId]);
            $val = $stmt->fetchColumn();
            return $val !== false && $val !== null ? (float)$val : null;
        } catch (\PDOException $e) {
            error_log('[DashboardRepo] getMoyenneGenerale: ' . $e->getMessage());
            return null;
        }
    }

    public function getTotalAbsences(int $eleveId, int $anneeId): int
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT COALESCE(SUM(ab.NBREABSENCE), 0)
                FROM absences ab
                JOIN elevesalle es ON ab.IDELEVESALLE = es.ID
                WHERE es.IDELEVE = ? AND ab.IDANNEESCOLAIRE = ? AND (ab.STATUT_PERMISSION IS NULL OR ab.STATUT_PERMISSION != 1)
            ");
            $stmt->execute([$eleveId, $anneeId]);
            return (int)$stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log('[DashboardRepo] getTotalAbsences: ' . $e->getMessage());
            return 0;
        }
    }

    public function getDevoirsEnAttente(int $salleId, int $anneeId): int
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*)
                FROM cahier_texte c
                WHERE c.IDSALLE = ? AND c.IDANNEESCOLAIRE = ? AND c.DEVOIRS_A_FAIRE IS NOT NULL AND c.DATE_ECHEANCE >= CURDATE()
            ");
            $stmt->execute([$salleId, $anneeId]);
            return (int)$stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log('[DashboardRepo] getDevoirsEnAttente: ' . $e->getMessage());
            return 0;
        }
    }

    public function getTotalDu(int $classeId, int $anneeId, int $ideleveAnnee): float
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT COALESCE(SUM(ptc.MONTANT - IFNULL(ptc.REMISE, 0)), 0)
                FROM paiementtypeclasse ptc
                WHERE ptc.IDCLASSE = ? AND ptc.IDANNEESCOLAIRE = ? AND ptc.STATUT = 1
                  AND (ptc.IDELEVEANNEESCOLAIRE IS NULL OR ptc.IDELEVEANNEESCOLAIRE = ?)
            ");
            $stmt->execute([$classeId, $anneeId, $ideleveAnnee]);
            return (float)$stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log('[DashboardRepo] getTotalDu: ' . $e->getMessage());
            return 0.0;
        }
    }

    public function getTotalPaye(int $ideleveAnnee, int $anneeId): float
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT COALESCE(SUM(pf.MONTANT), 0)
                FROM paiementfrais pf
                WHERE pf.IDELEVEANNEESCOLAIRE = ? AND pf.IDANNEESCOLAIRE = ? AND pf.STATUT = 1
            ");
            $stmt->execute([$ideleveAnnee, $anneeId]);
            return (float)$stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log('[DashboardRepo] getTotalPaye: ' . $e->getMessage());
            return 0.0;
        }
    }

    public function getRecentNotes(int $eleveId, int $anneeId, int $limit = 4): array
    {
        try {
            // ANOMALIE CORRIGÉE : Utilisation impérative de bindValue pour le paramètre LIMIT numérique
            $stmt = $this->pdo->prepare("
                SELECT n.MOYENTRIMES, n.COEF, m.NOM_MATIERE, n.OBSERVATION
                FROM note n
                JOIN matiere m ON n.IDMATIERE = m.ID_MATIERE
                WHERE n.IDELEVE = :eleve_id AND n.IDANNEESCOLAIRE = :annee_id AND n.EST_PUBLIE = 1
                ORDER BY n.ID DESC LIMIT :limit
            ");
            $stmt->bindValue(':eleve_id', $eleveId, PDO::PARAM_INT);
            $stmt->bindValue(':annee_id', $anneeId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            error_log('[DashboardRepo] getRecentNotes: ' . $e->getMessage());
            return [];
        }
    }

    public function getProchainsDevoirs(int $salleId, int $anneeId, int $limit = 3): array
    {
        try {
            // ANOMALIE CORRIGÉE : Liaison explicite de l'entier limit pour se prémunir d'un crash PDO strict
            $stmt = $this->pdo->prepare("
                SELECT c.DEVOIRS_A_FAIRE, m.NOM_MATIERE, p.NOM as PROF_NOM, c.DATE_ECHEANCE
                FROM cahier_texte c
                JOIN matiere m ON c.IDMATIERE = m.ID_MATIERE
                JOIN professeur p ON c.IDPROF = p.ID
                WHERE c.IDSALLE = :salle_id AND c.IDANNEESCOLAIRE = :annee_id AND c.DEVOIRS_A_FAIRE IS NOT NULL
                ORDER BY c.DATE_ECHEANCE ASC LIMIT :limit
            ");
            $stmt->bindValue(':salle_id', $salleId, PDO::PARAM_INT);
            $stmt->bindValue(':annee_id', $anneeId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            error_log('[DashboardRepo] getProchainsDevoirs: ' . $e->getMessage());
            return [];
        }
    }

    public function getAnnonces(int $limit = 2): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT TITRE, CONTENU, DATE_PUBLICATION
                FROM annonces_ecole
                ORDER BY DATE_PUBLICATION DESC LIMIT ?
            ");
            $stmt->bindValue(1, $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            error_log('[DashboardRepo] getAnnonces: ' . $e->getMessage());
            return [];
        }
    }

    public function getMessagesRecents(int $parentId, int $limit = 5): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT m.CONTENU, m.DATE_ENVOI, m.EXPEDITEUR_TYPE
                FROM msg_messages m
                JOIN msg_participants p ON m.ID_CONVERSATION = p.ID_CONVERSATION
                WHERE p.USER_TYPE = 'PARENT' AND p.ID_USER = :parent_id
                ORDER BY m.DATE_ENVOI DESC LIMIT :limit
            ");
            $stmt->bindValue(':parent_id', $parentId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            error_log('[DashboardRepo] getMessagesRecents: ' . $e->getMessage());
            return [];
        }
    }
}
