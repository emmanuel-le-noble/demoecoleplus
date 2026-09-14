<?php
/**
 * PaiementsController — Contrôleur pour le module paiements
 *
 * Ecole Plus v1.2.0 — 2026-07-05
 */

declare(strict_types=1);

require_once __DIR__ . '/PaiementsRepository.php';

class PaiementsController
{
    private PaiementsRepository $repo;
    private int $parentId;
    private int $eleveId;
    private int $idAnnee = 0;
    private string $libelleAnnee = '';

    public function __construct(\PDO $pdo, int $parentId, int $eleveId)
    {
        $this->repo = new PaiementsRepository($pdo);
        $this->parentId = $parentId;
        $this->eleveId = $eleveId;
    }

    public function init(): void
    {
        $annee = $this->repo->getAnneeActive();
        $this->idAnnee = $annee ? (int)$annee['ID'] : 1;
        $this->libelleAnnee = $annee ? ($annee['LIBELLE'] ?? '') : '';
    }

    public function getDashboardData(): array
    {
        $resume = $this->repo->getResumeFinancier($this->eleveId, $this->idAnnee);
        $historique = [];

        $montantTheorique = 0.0;
        $montantPaye = 0.0;
        $idEAS = 0;

        if ($resume) {
            $montantTheorique = floatval($resume['montant_theorique']);
            $montantPaye = floatval($resume['montant_paye']);
            $idEAS = (int)$resume['IDELEVEANNEESCOLAIRE'];
            $historique = $this->repo->getHistoriquePaiements($idEAS, $this->idAnnee);
        }

        $solde = max(0, $montantTheorique - $montantPaye);
        $pourcentage = $montantTheorique > 0 ? min(100, round(($montantPaye / $montantTheorique) * 100)) : 0;

        return [
            'libelle_annee' => $this->libelleAnnee,
            'montant_theorique' => $montantTheorique,
            'montant_paye' => $montantPaye,
            'solde' => $solde,
            'pourcentage' => $pourcentage,
            'historique' => $historique,
        ];
    }

    public function getRecuData(int $paiementId): ?array
    {
        if (!$this->repo->verifyParentChild($this->parentId, $this->eleveId)) {
            return null;
        }

        $paiement = $this->repo->getPaiementSingle($paiementId, $this->eleveId, $this->idAnnee);
        if (!$paiement) return null;

        $eleve = $this->repo->getEleveInfo($this->eleveId);
        $parent = $this->repo->getParentInfo($this->parentId);
        $annee = $this->repo->getAnneeActive();

        return [
            'paiement' => $paiement,
            'eleve' => $eleve,
            'parent' => $parent,
            'libelle_annee' => $annee ? ($annee['LIBELLE'] ?? '') : '',
            'reference' => 'REC-' . str_pad((string)$paiement['ID'], 5, '0', STR_PAD_LEFT),
        ];
    }

    public function getReleveData(): ?array
    {
        if (!$this->repo->verifyParentChild($this->parentId, $this->eleveId)) {
            return null;
        }

        $eleve = $this->repo->getEleveInfo($this->eleveId);
        $parent = $this->repo->getParentInfo($this->parentId);
        $annee = $this->repo->getAnneeActive();

        $idClasse = $eleve ? (int)$eleve['IDCLASSE'] : 0;
        $fraisScolaire = $this->repo->getFraisScolaire($idClasse, $this->idAnnee);
        $historique = $this->repo->getHistoriqueAnnuel($this->eleveId, $this->idAnnee);

        $totalPaye = 0.0;
        foreach ($historique as $p) {
            $totalPaye += floatval($p['MONTANT']);
        }

        $totalReste = max(0, $fraisScolaire - $totalPaye);
        $pct = $fraisScolaire > 0 ? min(100, round(($totalPaye / $fraisScolaire) * 100)) : 0;

        return [
            'eleve' => $eleve,
            'parent' => $parent,
            'libelle_annee' => $annee ? ($annee['LIBELLE'] ?? '') : '',
            'frais_scolaire' => $fraisScolaire,
            'total_paye' => $totalPaye,
            'total_reste' => $totalReste,
            'pourcentage' => $pct,
            'historique' => $historique,
        ];
    }

    /**
     * Get data for printing a single receipt
     */
    public function getRecuPrintData(int $paiementId): ?array
    {
        if (!$this->repo->verifyParentChild($this->parentId, $this->eleveId)) {
            return null;
        }

        $paiement = $this->repo->getPaiementSingle($paiementId, $this->eleveId, $this->idAnnee);
        if (!$paiement) return null;

        $eleve = $this->repo->getEleveInfo($this->eleveId);
        $parent = $this->repo->getParentInfo($this->parentId);

        return [
            'type' => 'recu',
            'paiement' => $paiement,
            'eleve' => $eleve,
            'parent' => $parent,
            'libelle_annee' => $this->libelleAnnee,
            'reference' => 'REC-' . str_pad((string)$paiement['ID'], 5, '0', STR_PAD_LEFT),
        ];
    }

    /**
     * Get data for printing a full yearly statement
     */
    public function getRelevePrintData(): ?array
    {
        if (!$this->repo->verifyParentChild($this->parentId, $this->eleveId)) {
            return null;
        }

        $eleve = $this->repo->getEleveInfo($this->eleveId);
        $parent = $this->repo->getParentInfo($this->parentId);

        $idClasse = $eleve ? (int)$eleve['IDCLASSE'] : 0;
        $montantTotal = $this->repo->getFraisScolaire($idClasse, $this->idAnnee);
        $historique = $this->repo->getHistoriqueAnnuel($this->eleveId, $this->idAnnee);

        $totalPaye = array_sum(array_column($historique, 'MONTANT'));
        $totalReste = max(0, $montantTotal - $totalPaye);
        $pct = $montantTotal > 0 ? min(100, round(($totalPaye / $montantTotal) * 100)) : 0;

        return [
            'type' => 'releve',
            'eleve' => $eleve,
            'parent' => $parent,
            'libelle_annee' => $this->libelleAnnee,
            'montant_total' => $montantTotal,
            'total_paye' => $totalPaye,
            'total_reste' => $totalReste,
            'pourcentage' => $pct,
            'historique' => $historique,
        ];
    }
}
