<?php
declare(strict_types=1);

require_once __DIR__ . '/NotesRepository.php';

class NotesController
{
    private NotesRepository $repo;
    private \PDO $pdo;
    private int $parentId;
    private int $eleveId;
    private int $idSalle;
    private array $enfantActif;
    private int $idAnnee;

    public function __construct(\PDO $pdo, int $parentId, int $eleveId, array $enfantActif)
    {
        $this->pdo = $pdo;
        $this->parentId = $parentId;
        $this->eleveId = $eleveId;
        $this->enfantActif = $enfantActif;
        $this->idSalle = (int)($enfantActif['ID_SALLE'] ?? 0);
        $this->repo = new NotesRepository($pdo);
    }

    public function init(): void
    {
        $annee = $this->repo->getAnneeActive();
        $this->idAnnee = $annee ? (int)$annee['ID'] : 1;
    }

    public function getNotesData(): array
    {
        $rawTrimestre = $_GET['trimestre'] ?? 'Tous';
        $trimestreSelectionne = ($rawTrimestre !== 'Tous') ? (int)$rawTrimestre : null;

        $positions = $this->repo->getPositions();

        $bulletinOfficiel = null;
        $detailsBulletin = [];

        if ($trimestreSelectionne !== null && $this->idSalle > 0) {
            $bulletinOfficiel = $this->repo->getBulletin(
                $this->eleveId, $this->idAnnee, $trimestreSelectionne, $this->idSalle
            );

            if ($bulletinOfficiel) {
                $detailsBulletin = $this->repo->getBulletinContenu((int)$bulletinOfficiel['ID']);
            }
        }

        $notes = $this->repo->getNotes($this->eleveId, $this->idAnnee, $trimestreSelectionne);

        $noteMax = null;
        $noteMin = null;
        foreach ($notes as $n) {
            if (is_numeric($n['MOYENTRIMES'])) {
                $v = (float)$n['MOYENTRIMES'];
                if ($noteMax === null || $v > $noteMax) $noteMax = $v;
                if ($noteMin === null || $v < $noteMin) $noteMin = $v;
            }
        }

        $moyenneCalculee = null;
        $sourceMoyenne = 'calculee';

        if ($trimestreSelectionne !== null && $this->eleveId > 0) {
            $cache = $this->repo->getBulletinCalcule($this->eleveId, $this->idAnnee, $trimestreSelectionne);
            if ($cache && (float)$cache['MOYENNE_GENERALE'] >= 0) {
                $moyenneCalculee = round((float)$cache['MOYENNE_GENERALE'], 2);
                $sourceMoyenne = 'cache';
            }
        }

        if ($moyenneCalculee === null) {
            $totalPoints = 0;
            $totalCoefs = 0;
            foreach ($notes as $n) {
                if (is_numeric($n['MOYENTRIMES'])) {
                    $v = (float)$n['MOYENTRIMES'];
                    $c = (float)($n['COEF'] ?? 1);
                    $totalPoints += $v * $c;
                    $totalCoefs += $c;
                }
            }
            $moyenneCalculee = $totalCoefs > 0 ? round($totalPoints / $totalCoefs, 2) : null;
        }

        $chartLabels = [];
        $chartEleve = [];
        $chartClasse = [];

        if (!empty($detailsBulletin)) {
            foreach ($detailsBulletin as $d) {
                if (is_numeric($d['MOY_TRIMES'])) {
                    $chartLabels[] = $d['NOM_MATIERE'];
                    $chartEleve[] = round((float)$d['MOY_TRIMES'], 2);
                    $chartClasse[] = is_numeric($d['MOY_CLASSE']) ? round((float)$d['MOY_CLASSE'], 2) : null;
                }
            }
        } else {
            foreach ($notes as $n) {
                if (is_numeric($n['MOYENTRIMES'])) {
                    $chartLabels[] = $n['NOM_MATIERE'];
                    $chartEleve[] = round((float)$n['MOYENTRIMES'], 2);
                    $chartClasse[] = is_numeric($n['MOYCLASS']) ? round((float)$n['MOYCLASS'], 2) : null;
                }
            }
        }

        return [
            'enfant_actif' => $this->enfantActif,
            'positions' => $positions,
            'trimestre_selectionne' => $rawTrimestre,
            'bulletin_officiel' => $bulletinOfficiel,
            'details_bulletin' => $detailsBulletin,
            'notes' => $notes,
            'moyenne_calculee' => $moyenneCalculee,
            'source_moyenne' => $sourceMoyenne,
            'note_max' => $noteMax !== null ? round($noteMax, 2) : null,
            'note_min' => $noteMin !== null ? round($noteMin, 2) : null,
            'chart_labels' => $chartLabels,
            'chart_eleve' => $chartEleve,
            'chart_classe' => $chartClasse,
        ];
    }

    public function getBulletinPrintData(int $bulletinId): ?array
    {
        $bulletin = $this->repo->getBulletinWithEleve($bulletinId, $this->eleveId);

        $source = 'officiel';

        if (!$bulletin) {
            $annee = $this->repo->getAnneeActive();
            $idAnneeActive = $annee ? (int)$annee['ID'] : 0;

            if ($idAnneeActive > 0 && $this->eleveId > 0) {
                $bulletin = $this->repo->getFallbackBulletin($this->eleveId, $idAnneeActive);
                if ($bulletin) {
                    $source = 'cache';
                }
            }
        }

        if (!$bulletin) return null;
        $bulletin['_source'] = $source;

        $idPosition = (int)($bulletin['IDPOSITION'] ?? 0);
        $idAnnee = (int)($bulletin['IDANNEESCOLAIRE'] ?? $this->idAnnee);
        $idSalle = $this->idSalle;

        $details = [];
        if ($source === 'cache') {
            if ($idPosition > 0) {
                $details = $this->repo->getNotesForBulletin($this->eleveId, $idAnnee, $idPosition);
            }
        } else {
            $details = $this->repo->getBulletinContenuEnrichi($bulletinId);
        }

        $effectif = ($idSalle > 0 && $idAnnee > 0 && $idPosition > 0)
            ? $this->repo->getEffectifClasse($idSalle, $idAnnee, $idPosition)
            : 0;

        $stats = ($idSalle > 0 && $idAnnee > 0 && $idPosition > 0)
            ? $this->repo->getStatistiquesClasse($idSalle, $idAnnee, $idPosition)
            : ['moy_max' => null, 'moy_min' => null, 'moy_classe' => null];

        $profTitulaire = ($idSalle > 0) ? $this->repo->getProfTitulaire($idSalle) : '';
        $directeur = $this->repo->getDirecteur();

        $nbreAbsences = $this->repo->getNombreAbsences($this->eleveId, $idPosition, $idAnnee);

        $anneeInfo = $this->repo->getAnneeActive();

        return [
            'bulletin' => $bulletin,
            'details' => $details,
            'effectif' => $effectif,
            'stats' => $stats,
            'prof_titulaire' => $profTitulaire,
            'directeur' => $directeur,
            'nbre_absences' => $nbreAbsences,
            'annee_scolaire' => $anneeInfo ? (string)$anneeInfo['LIBELLE'] : '',
        ];
    }
}
