<?php
declare(strict_types=1);

class EmploiTempsRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getEmploiDuTemps(int $idSalle, int $idAnneeScolaire): array
    {
        $stmt = $this->pdo->prepare("
            SELECT set_.ID, set_.HEUREDEBUT, set_.HEUREFIN,
                   j.CODE as JOUR,
                   m.NOM_MATIERE,
                   CONCAT(p.NOM, ' ', p.TITRE) as PROFESSEUR,
                   s.NOMSALLE
            FROM salleemploitemps set_
            INNER JOIN jour j ON set_.IDJOUR = j.ID
            INNER JOIN matiere m ON set_.IDMATIERE = m.ID_MATIERE
            INNER JOIN professeur p ON set_.IDPROF = p.ID
            INNER JOIN salle s ON set_.IDSALLE = s.ID
            WHERE set_.IDSALLE = ? AND set_.IDANNEESCOLAIRE = ?
            ORDER BY j.ID, set_.HEUREDEBUT
        ");
        $stmt->execute([$idSalle, $idAnneeScolaire]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSalleByEleve(int $idEleve, int $idAnneeScolaire): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT s.ID, s.NOMSALLE, s.CODESALLE
            FROM elevesalle es
            INNER JOIN salle s ON es.IDSALLE = s.ID
            WHERE es.IDELEVE = ? AND es.STATUT != 0
            LIMIT 1
        ");
        $stmt->execute([$idEleve]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
