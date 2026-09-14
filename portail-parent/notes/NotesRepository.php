<?php
declare(strict_types=1);

class NotesRepository
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
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (\PDOException $e) {
            error_log('[NotesRepo] getAnneeActive: ' . $e->getMessage());
            return null;
        }
    }

    public function getPositions(): array
    {
        try {
            $stmt = $this->pdo->query("SELECT idposition, libposition FROM position ORDER BY idposition ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('[NotesRepo] getPositions: ' . $e->getMessage());
            return [];
        }
    }

    public function getBulletin(int $eleveId, int $anneeId, int $positionId, int $salleId): ?array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT ID, MOYENNE_GENE, RANG, MOYEN_ANN, RANG_ANN, observation
                FROM bulletin
                WHERE IDELEVE = ? AND IDANNEESCOLAIRE = ? AND IDPOSITION = ? AND IDSALLE = ?
                LIMIT 1
            ");
            $stmt->execute([$eleveId, $anneeId, $positionId, $salleId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (\PDOException $e) {
            error_log('[NotesRepo] getBulletin: ' . $e->getMessage());
            return null;
        }
    }

    public function getBulletinContenu(int $bulletinId): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT bc.INTE, bc.DS, bc.DN, bc.MOY_CLASSE, bc.NOTES_COMP, bc.MOY_TRIMES, bc.COEF, bc.APPRECIATION, m.NOM_MATIERE
                FROM bulletincontenu bc
                JOIN matiere m ON bc.IDMATIERE = m.ID_MATIERE
                WHERE bc.IDBULLETIN = ?
                ORDER BY m.NOM_MATIERE ASC
            ");
            $stmt->execute([$bulletinId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('[NotesRepo] getBulletinContenu: ' . $e->getMessage());
            return [];
        }
    }

    public function getNotes(int $eleveId, int $anneeId, ?int $positionId): array
    {
        try {
            $sql = "SELECT n.MOYENTRIMES, n.NOTEINT, n.NOTEDS, n.NOTEDN, n.NOTECOMP, n.MOYCLASS, n.COEF,
                           p.idposition, p.libposition, m.NOM_MATIERE, n.OBSERVATION
                    FROM note n
                    JOIN matiere m ON n.IDMATIERE = m.ID_MATIERE
                    JOIN position p ON n.IDPOSITION = p.idposition
                    WHERE n.IDELEVE = ? AND n.IDANNEESCOLAIRE = ?";
            $params = [$eleveId, $anneeId];

            if ($positionId !== null) {
                $sql .= " AND n.IDPOSITION = ?";
                $params[] = $positionId;
            }

            $sql .= " ORDER BY p.idposition ASC, m.NOM_MATIERE ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('[NotesRepo] getNotes: ' . $e->getMessage());
            return [];
        }
    }

    public function getBulletinCalcule(int $eleveId, int $anneeId, int $positionId): ?array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT MOYENNE_GENERALE, TOTAL_POINTS, TOTAL_COEFS, NOMBRE_MATIERES
                FROM bulletin_calcule
                WHERE IDELEVE = ? AND IDANNEESCOLAIRE = ? AND IDPOSITION = ? AND EST_GELE = 1
                LIMIT 1
            ");
            $stmt->execute([$eleveId, $anneeId, $positionId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (\PDOException $e) {
            error_log('[NotesRepo] getBulletinCalcule: ' . $e->getMessage());
            return null;
        }
    }

    public function getBulletinWithEleve(int $bulletinId, int $eleveId): ?array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT b.ID, b.MOYENNE_GENE, b.RANG, b.MOYEN_ANN, b.RANG_ANN, b.observation,
                       e.NOM_ELEVE, e.PRENOM_ELEVE, e.MATRICULE, e.SEXE_ELEVE, e.DATENAISSANCE_ELEVE,
                       s.NOMSALLE, a.LIBELLE as ANNEE_SCOLAIRE, p.libposition as PERIODE
                FROM bulletin b
                JOIN eleve e ON b.IDELEVE = e.ID_ELEVE
                JOIN salle s ON b.IDSALLE = s.ID
                JOIN anneescolaire a ON b.IDANNEESCOLAIRE = a.ID
                JOIN position p ON b.IDPOSITION = p.idposition
                WHERE b.ID = ? AND b.IDELEVE = ?
                LIMIT 1
            ");
            $stmt->execute([$bulletinId, $eleveId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (\PDOException $e) {
            error_log('[NotesRepo] getBulletinWithEleve: ' . $e->getMessage());
            return null;
        }
    }

    public function getFallbackBulletin(int $eleveId, int $anneeId): ?array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT bc.ID, bc.MOYENNE_GENERALE AS MOYENNE_GENE, bc.RANG_CLASSE AS RANG,
                       0 AS MOYEN_ANN, 0 AS RANG_ANN, '' AS observation,
                       e.NOM_ELEVE, e.PRENOM_ELEVE, e.MATRICULE, e.SEXE_ELEVE, e.DATENAISSANCE_ELEVE,
                       s.NOMSALLE, a.LIBELLE AS ANNEE_SCOLAIRE, p.libposition AS PERIODE,
                       bc.IDPOSITION
                FROM bulletin_calcule bc
                JOIN eleve e ON bc.IDELEVE = e.ID_ELEVE
                JOIN salle s ON bc.IDSALLE = s.ID
                JOIN anneescolaire a ON bc.IDANNEESCOLAIRE = a.ID
                JOIN position p ON bc.IDPOSITION = p.idposition
                WHERE bc.IDELEVE = ? AND bc.IDANNEESCOLAIRE = ? AND bc.EST_GELE = 1
                ORDER BY bc.IDPOSITION DESC
                LIMIT 1
            ");
            $stmt->execute([$eleveId, $anneeId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (\PDOException $e) {
            error_log('[NotesRepo] getFallbackBulletin: ' . $e->getMessage());
            return null;
        }
    }

    public function getNotesForBulletin(int $eleveId, int $anneeId, int $positionId): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT n.NOTEINT AS INTE, n.NOTEDS AS DS, n.NOTEDN AS DN,
                       n.MOYCLASS AS MOY_CLASSE, n.COEF, n.MOYENTRIMES AS MOY_TRIMES,
                       n.OBSERVATION AS APPRECIATION, m.NOM_MATIERE
                FROM note n
                JOIN matiere m ON n.IDMATIERE = m.ID_MATIERE
                WHERE n.IDELEVE = ? AND n.IDANNEESCOLAIRE = ? AND n.IDPOSITION = ? AND n.EST_PUBLIE = 1
                ORDER BY m.NOM_MATIERE ASC
            ");
            $stmt->execute([$eleveId, $anneeId, $positionId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('[NotesRepo] getNotesForBulletin: ' . $e->getMessage());
            return [];
        }
    }

    public function getBulletinContenuEnrichi(int $bulletinId): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT bc.INTE, bc.DS, bc.DN, bc.MOY_CLASSE, bc.NOTES_COMP,
                       bc.MOY_TRIMES, bc.COEF, bc.MOY_PONDERE, bc.RANG,
                       bc.PROFESSEUR, bc.APPRECIATION, m.NOM_MATIERE
                FROM bulletincontenu bc
                JOIN matiere m ON bc.IDMATIERE = m.ID_MATIERE
                WHERE bc.IDBULLETIN = ?
                ORDER BY m.NOM_MATIERE ASC
            ");
            $stmt->execute([$bulletinId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('[NotesRepo] getBulletinContenuEnrichi: ' . $e->getMessage());
            return [];
        }
    }

    public function getEffectifClasse(int $salleId, int $anneeId, int $positionId): int
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT COUNT(b.ID) AS total
                FROM bulletin b
                WHERE b.IDSALLE = ? AND b.IDANNEESCOLAIRE = ? AND b.IDPOSITION = ?
            ");
            $stmt->execute([$salleId, $anneeId, $positionId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? (int)$row['total'] : 0;
        } catch (\PDOException $e) {
            error_log('[NotesRepo] getEffectifClasse: ' . $e->getMessage());
            return 0;
        }
    }

    public function getStatistiquesClasse(int $salleId, int $anneeId, int $positionId): array
    {
        $stats = [
            'moy_max' => null,
            'moy_min' => null,
            'moy_classe' => null,
        ];
        try {
            $stmt = $this->pdo->prepare("
                SELECT
                    MAX(b.MOYENNE_GENE) AS moy_max,
                    MIN(b.MOYENNE_GENE) AS moy_min,
                    ROUND(AVG(b.MOYENNE_GENE), 2) AS moy_classe
                FROM bulletin b
                WHERE b.IDSALLE = ? AND b.IDANNEESCOLAIRE = ? AND b.IDPOSITION = ?
            ");
            $stmt->execute([$salleId, $anneeId, $positionId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $stats['moy_max'] = $row['moy_max'] !== null ? round((float)$row['moy_max'], 2) : null;
                $stats['moy_min'] = $row['moy_min'] !== null ? round((float)$row['moy_min'], 2) : null;
                $stats['moy_classe'] = $row['moy_classe'] !== null ? round((float)$row['moy_classe'], 2) : null;
            }
        } catch (\PDOException $e) {
            error_log('[NotesRepo] getStatistiquesClasse: ' . $e->getMessage());
        }
        return $stats;
    }

    public function getProfTitulaire(int $salleId): string
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT p.nom AS nomprof
                FROM professeur p
                JOIN professeursallemat psm ON p.id = psm.idprof
                WHERE psm.idsalle = ? AND psm.statut = 1 AND psm.idtitre = 2
                LIMIT 1
            ");
            $stmt->execute([$salleId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? (string)$row['nomprof'] : '';
        } catch (\PDOException $e) {
            error_log('[NotesRepo] getProfTitulaire: ' . $e->getMessage());
            return '';
        }
    }

    public function getDirecteur(): string
    {
        try {
            $stmt = $this->pdo->query("
                SELECT nom AS nomprof
                FROM professeur
                WHERE titre = 1 AND statut = 1
                LIMIT 1
            ");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? (string)$row['nomprof'] : '';
        } catch (\PDOException $e) {
            error_log('[NotesRepo] getDirecteur: ' . $e->getMessage());
            return '';
        }
    }

    public function getNombreAbsences(int $eleveSalleId, int $positionId, int $anneeId): ?int
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT nbreabsence
                FROM absences
                WHERE idposition = ? AND idanneescolaire = ? AND idelevesalle = ?
                LIMIT 1
            ");
            $stmt->execute([$positionId, $anneeId, $eleveSalleId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? (int)$row['nbreabsence'] : null;
        } catch (\PDOException $e) {
            error_log('[NotesRepo] getNombreAbsences: ' . $e->getMessage());
            return null;
        }
    }
}
