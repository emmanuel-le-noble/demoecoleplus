<?php
/**
 * CahierTexteController — Contrôleur pour le module Cahier de textes
 *
 * Ecole Plus v1.2.0 — 2026-07-06
 */

declare(strict_types=1);

require_once __DIR__ . '/CahierTexteRepository.php';

class CahierTexteController
{
    private CahierTexteRepository $repo;
    private int $parentId;
    private int $eleveId;
    private array $enfantActif;
    private int $idSalle = 0;
    private int $idAnnee = 0;

    public function __construct(\PDO $pdo, int $parentId, int $eleveId, array $enfantActif)
    {
        $this->repo = new CahierTexteRepository($pdo);
        $this->parentId = $parentId;
        $this->eleveId = $eleveId;
        $this->enfantActif = $enfantActif;
        
        // Résolution de l'ID de la salle en vérifiant la casse des clés
        $this->idSalle = (int)($enfantActif['ID_SALLE'] ?? $enfantActif['id_salle'] ?? 0);
    }

    /**
     * Initialiser le contexte de l'année scolaire active
     */
    public function init(): void
    {
        $annee = $this->repo->getAnneeActive();
        $this->idAnnee = $annee ? (int)($annee['id'] ?? 1) : 1;
    }

    /**
     * Préparer et retourner les données pour la vue index.php
     */
    public function getViewData(): array
    {
        $matiereFiltre = isset($_GET['matiere']) ? (int)$_GET['matiere'] : 0;

        $matieres = [];
        $devoirs = [];

        if ($this->idSalle > 0) {
            $matieres = $this->repo->getMatieresBySalle($this->idSalle, $this->idAnnee);
            $devoirs = $this->repo->getCahierTextes($this->idSalle, $this->idAnnee, $matiereFiltre > 0 ? $matiereFiltre : null);
        }

        return [
            'enfant_actif'   => $this->enfantActif,
            'matieres'       => $matieres,
            'matiere_filtre' => $matiereFiltre,
            'devoirs'        => $devoirs,
        ];
    }
}