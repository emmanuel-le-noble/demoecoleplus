<?php
/**
 * EnfantsController — Gestion de la logique d'affichage des fiches enfants
 *
 * Ecole Plus v1.2.0 — 2026-07-06
 */

declare(strict_types=1);

require_once __DIR__ . '/EnfantsRepository.php';

class EnfantsController
{
    private EnfantsRepository $repo;
    private int $parentId;
    private array $mesEnfants;
    private int $idAnnee = 0;

    public function __construct(\PDO $pdo, int $parentId, array $mesEnfants)
    {
        $this->repo = new EnfantsRepository($pdo);
        $this->parentId = $parentId;
        $this->mesEnfants = $mesEnfants;
    }

    /**
     * Initialiser le contexte académique actif
     */
    public function init(): void
    {
        $annee = $this->repo->getAnneeActive();
        $this->idAnnee = $annee ? (int)($annee['ID'] ?? 1) : 1;
    }

    /**
     * Compiler les données statistiques, financières et scolaires de chaque enfant
     */
    public function getViewData(): array
    {
        // ANOMALIE CORRIGÉE : Utilisation d'un fallback sécurisé à 0 si le tableau mesEnfants est vide
        $defaultEnfantId = !empty($this->mesEnfants) ? (int)($this->mesEnfants[0]['ID_ELEVE'] ?? 0) : 0;
        $currentEnfantId = (int)($_SESSION['active_eleve_id'] ?? $defaultEnfantId);

        $enfantsData = [];
        foreach ($this->mesEnfants as $enfant) {
            $idEleve = (int)($enfant['ID_ELEVE'] ?? 0);
            if ($idEleve === 0) {
                continue;
            }
            
            $isSelected = ($idEleve === $currentEnfantId);

            $salleInfo = $this->repo->getEleveSalle($idEleve);
            $idEleveSalle = $salleInfo ? (int)($salleInfo['ID_ELEVE_SALLE'] ?? 0) : 0;
            $idClasse = $salleInfo ? (int)($salleInfo['IDCLASSE'] ?? 0) : 0;
            $nomClasse = $salleInfo ? (string)($salleInfo['NOMSALLE'] ?? 'Non définie') : (string)($enfant['NOMSALLE'] ?? 'Non assignée');

            $montantDu = 0.0;
            if ($idClasse > 0) {
                $montantDu = (float)$this->repo->getTotalFraisClasse($idClasse, $this->idAnnee);
            }

            $montantPaye = 0.0;
            if ($idEleveSalle > 0) {
                $montantPaye = (float)$this->repo->getTotalPaye($idEleveSalle, $this->idAnnee);
            }

            $solde = max(0.0, $montantDu - $montantPaye);
            $pct = $montantDu > 0 ? (int)min(100, round(($montantPaye / $montantDu) * 100)) : 0;

            $absences = 0;
            if ($idEleveSalle > 0) {
                $absences = $this->repo->getTotalAbsences($idEleveSalle, $this->idAnnee);
            }

            $notesCount = $this->repo->getNoteCount($idEleve, $this->idAnnee);

            $enfantsData[] = [
                'info'         => $enfant,
                'is_selected'  => $isSelected,
                'nom_classe'   => $nomClasse,
                'montant_du'   => $montantDu,
                'montant_paye' => $montantPaye,
                'solde'        => $solde,
                'pourcentage'  => $pct,
                'absences'     => $absences,
                'notes_count'  => $notesCount,
            ];
        }

        $notes = [];
        if ($currentEnfantId > 0) {
            $notes = $this->repo->getNotes($currentEnfantId, $this->idAnnee);
        }

        return [
            'current_enfant_id' => $currentEnfantId,
            'enfants'           => $enfantsData,
            'notes'             => $notes,
        ];
    }
}