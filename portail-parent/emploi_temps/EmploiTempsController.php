<?php
declare(strict_types=1);

require_once __DIR__ . '/EmploiTempsRepository.php';

class EmploiTempsController
{
    private EmploiTempsRepository $repo;
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->repo = new EmploiTempsRepository($pdo);
    }

    public function handle(): array
    {
        $idEleve = (int)($_SESSION['active_eleve_id'] ?? 0);
        if ($idEleve <= 0) {
            return ['error' => 'Aucun enfant sélectionné.'];
        }

        $idAnnee = $this->getAnneeActive();
        if ($idAnnee <= 0) {
            return ['error' => 'Aucune année scolaire active.'];
        }

        $salle = $this->repo->getSalleByEleve($idEleve, $idAnnee);
        if (!$salle) {
            return ['error' => 'Aucune classe assignée à cet enfant.'];
        }

        $emploi = $this->repo->getEmploiDuTemps((int)$salle['ID'], $idAnnee);

        return [
            'emploi' => $emploi,
            'salle' => $salle,
        ];
    }

    private function getAnneeActive(): int
    {
        $stmt = $this->pdo->query("SELECT ID FROM anneescolaire WHERE STATUT = 1 LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['ID'] : 0;
    }
}
