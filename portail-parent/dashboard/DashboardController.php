<?php
/**
 * DashboardController — Contrôleur pour le tableau de bord
 *
 * Ecole Plus v1.2.0 — 2026-07-06
 */

declare(strict_types=1);

require_once __DIR__ . '/DashboardRepository.php';

class DashboardController
{
    private DashboardRepository $repo;
    private int $parentId;
    private int $eleveId;
    private int $idAnnee = 0;

    public function __construct(\PDO $pdo, int $parentId, int $eleveId)
    {
        $this->repo = new DashboardRepository($pdo);
        $this->parentId = $parentId;
        $this->eleveId = $eleveId;
    }

    /**
     * Initialiser le contexte de l'année scolaire active
     */
    public function init(): void
    {
        $annee = $this->repo->getAnneeActive();
        $this->idAnnee = $annee ? (int)($annee['ID'] ?? $annee['id'] ?? 1) : 1;
    }

    /**
     * Préparer l'ensemble des données requises par le tableau de bord parent
     */
    public function getViewData(): array
    {
        $eleveInfos = $this->repo->getEleveInfos($this->eleveId, $this->idAnnee);

        if (!$eleveInfos) {
            $eleveInfos = [
                'PRENOM_ELEVE'          => 'Votre enfant',
                'NOM_ELEVE'             => '',
                'IDSALLE'               => 0,
                'IDCLASSE'              => 0,
                'NOMSALLE'              => 'Non assigné',
                'NOMCLASSE'             => 'Non assigné',
                'IDELEVEANNEESCOLAIRE'  => 0
            ];
        }

        // Résolution adaptative de la casse des index pour préserver l'intégrité de la plateforme
        $idSalle  = (int)($eleveInfos['IDSALLE'] ?? $eleveInfos['idsalle'] ?? 0);
        $idClasse = (int)($eleveInfos['IDCLASSE'] ?? $eleveInfos['idclasse'] ?? 0);
        $idEAS    = (int)($eleveInfos['IDELEVEANNEESCOLAIRE'] ?? $eleveInfos['ideleveanneescolaire'] ?? 0);

        $moyenne = $this->repo->getMoyenneGenerale($this->eleveId, $this->idAnnee);
        $moyenneGenerale = $moyenne !== null ? number_format($moyenne, 2, ',', ' ') : 'N/A';

        $absences = $this->repo->getTotalAbsences($this->eleveId, $this->idAnnee);

        $devoirsEnAttente = 0;
        if ($idSalle > 0) {
            $devoirsEnAttente = $this->repo->getDevoirsEnAttente($idSalle, $this->idAnnee);
        }

        $montantDu = 0.0;
        $montantPaye = 0.0;
        if ($idEAS > 0 && $idClasse > 0) {
            $montantDu = $this->repo->getTotalDu($idClasse, $this->idAnnee, $idEAS);
        }
        if ($idEAS > 0) {
            $montantPaye = $this->repo->getTotalPaye($idEAS, $this->idAnnee);
        }

        $solde = max(0.0, $montantDu - $montantPaye);
        $pct = $montantDu > 0 ? (int)round(($montantPaye / $montantDu) * 100) : 0;

        $notes = $this->repo->getRecentNotes($this->eleveId, $this->idAnnee);
        $devoirs = [];
        if ($idSalle > 0) {
            $devoirs = $this->repo->getProchainsDevoirs($idSalle, $this->idAnnee);
        }
        $annonces = $this->repo->getAnnonces();

        $messages = [];
        if ($this->parentId > 0) {
            $messages = $this->repo->getMessagesRecents($this->parentId);
        }

        return [
            'eleve'                   => $eleveInfos,
            'id_annee'                => $this->idAnnee,
            'moyenne_generale'        => $moyenneGenerale,
            'absences_non_justifiees' => $absences,
            'devoirs_en_attente'      => $devoirsEnAttente,
            'montant_total_theorique' => $montantDu,
            'montant_deja_paye'       => $montantPaye,
            'solde_financier'         => $solde,
            'pourcentage_paiement'    => $pct,
            'notes_recentes'          => $notes,
            'liste_devoirs'           => $devoirs,
            'annonces'                => $annonces,
            'messages_recents'        => $messages,
        ];
    }
}