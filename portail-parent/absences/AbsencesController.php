<?php
/**
 * AbsencesController — Contrôleur pour le module absences
 *
 * Gère la logique métier : traitement des formulaires, validation,
 * calcul du taux de présence, et préparation des données pour la vue.
 *
 * Ecole Plus v1.2.0 — 2026-07-05
 */

declare(strict_types=1);

require_once __DIR__ . '/AbsencesRepository.php';

class AbsencesController
{
    private AbsencesRepository $repo;
    private \PDO $pdo;
    private int $parentId;
    private int $eleveId;
    private array $enfantActif;
    private int $idEleveSalle = 0;
    private int $idSalle = 0;
    private int $idAnnee = 0;
    private string $libelleAnnee = '';
    private int $idUser = 0;

    // Constante métier (déplaçable en config si nécessaire)
    private const HEURES_THEORIQUES = 360;

    public function __construct(\PDO $pdo, int $parentId, int $eleveId, array $enfantActif)
    {
        $this->pdo = $pdo;
        $this->parentId = $parentId;
        $this->eleveId = $eleveId;
        $this->enfantActif = $enfantActif;
        $this->idUser = $parentId;
        $this->repo = new AbsencesRepository($pdo);
    }

    /**
     * Initialiser le contexte (année scolaire, eleve_salle)
     */
    public function init(): void
    {
        // Résoudre ID_ELEVESALLE
        $this->idEleveSalle = (int)($this->enfantActif['ID_ELEVE_SALLE'] ?? $this->enfantActif['ID'] ?? 0);
        $this->idSalle = (int)($this->enfantActif['ID_SALLE'] ?? $this->enfantActif['IDSALLE'] ?? 0);

        if ($this->idEleveSalle === 0 && $this->eleveId > 0) {
            $salleInfo = $this->repo->resolveEleveSalle($this->eleveId);
            if ($salleInfo) {
                $this->idEleveSalle = (int)$salleInfo['id'];
                $this->idSalle = (int)$salleInfo['idsalle'];
            }
        }

        // Année scolaire active
        $annee = $this->repo->getAnneeActive();
        $this->idAnnee = $annee ? (int)($annee['id'] ?? 1) : 1;
        $this->libelleAnnee = $annee ? ($annee['libelle'] ?? 'Année Académique') : 'Année Académique';
    }

    /**
     * Traiter une soumission de formulaire POST
     * @return array{success: bool, message: string}
     */
    public function handlePost(): array
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['soumettre_permission'])) {
            return ['success' => false, 'message' => ''];
        }

        // Vérification CSRF sécurisée avec fallback si session non active
        $sessionToken = $_SESSION['csrf_token'] ?? '';
        if (!isset($_POST['csrf_token']) || empty($sessionToken) || $_POST['csrf_token'] !== $sessionToken) {
            return ['success' => false, 'message' => 'Erreur de sécurité : Jeton CSRF invalide ou expiré.'];
        }

        $dateAbs = trim(strip_tags($_POST['date_absence'] ?? ''));
        $duree = max(1, intval($_POST['duree_absence'] ?? 1));
        $typeAbs = strip_tags($_POST['type_absence'] ?? 'Absence');
        $motif = trim(strip_tags($_POST['motif_permission'] ?? ''));

        // Validation
        if (empty($dateAbs) || empty($motif)) {
            return ['success' => false, 'message' => 'Veuillez remplir correctement tous les champs obligatoires.'];
        }

        if ($this->idEleveSalle <= 0 || $this->idSalle <= 0) {
            return ['success' => false, 'message' => 'Impossible de déterminer la classe de votre enfant.'];
        }

        // Insertion
        $ok = $this->repo->insertAbsence([
            'id_eleve_salle' => $this->idEleveSalle,
            'id_salle'       => $this->idSalle,
            'id_annee'       => $this->idAnnee,
            'duree'          => $duree,
            'date_absence'   => $dateAbs,
            'type_absence'   => $typeAbs,
            'motif'          => $motif,
            'id_user'        => $this->idUser,
        ]);

        if ($ok) {
            return ['success' => true, 'message' => 'Votre demande de permission / justification a bien été transmise à la vie scolaire.'];
        }

        return ['success' => false, 'message' => 'Une erreur technique est survenue. Veuillez réessayer.'];
    }

    /**
     * Préparer les données pour la vue
     */
    public function getViewData(): array
    {
        $stats = ['justifiees' => 0, 'non_justifiees' => 0, 'total' => 0];
        $historique = [];

        if ($this->idEleveSalle > 0) {
            $stats = $this->repo->getStats($this->idEleveSalle, $this->idAnnee);
            $historique = $this->repo->getHistorique($this->idEleveSalle, $this->idAnnee);
        }

        $tauxPresence = self::HEURES_THEORIQUES > 0
            ? max(0, round(((self::HEURES_THEORIQUES - $stats['total']) / self::HEURES_THEORIQUES) * 100, 1))
            : 100.0;

        // Pré-classifier les absences (Uniformisation en minuscules pour correspondre aux alias SQL)
        $historiqueFormate = array_map(function ($abs) {
            $typeLower = strtolower($abs['type_absence'] ?? '');
            $dateTimestamp = isset($abs['date_enreg']) ? strtotime($abs['date_enreg']) : false;
            
            return [
                'date'      => $dateTimestamp ? date('d/m/Y', $dateTimestamp) : 'N/A',
                'is_retard' => (strpos($typeLower, 'retard') !== false),
                'duree'     => (int)($abs['nbre_absence'] ?? 0),
                'motif'     => !empty($abs['motif_permission']) ? $abs['motif_permission'] : 'Aucune raison fournie',
                'statut'    => isset($abs['statut_permission']) ? (int)$abs['statut_permission'] : 0,
            ];
        }, $historique);

        return [
            'enfant_actif'        => $this->enfantActif,
            'libelle_annee'       => $this->libelleAnnee,
            'heures_theoriques'   => self::HEURES_THEORIQUES,
            'taux_presence'       => (float)$tauxPresence,
            'abs_justifiees'      => $stats['justifiees'],
            'abs_non_justifiees'  => $stats['non_justifiees'],
            'total_absences'      => $stats['total'],
            'historique'          => $historiqueFormate,
        ];
    }

    /**
     * Rediriger (PRG pattern)
     */
    public function redirect(string $status, string $message): void
    {
        header("Location: absences.php?statut=" . urlencode($status) . "&msg=" . urlencode($message));
        exit;
    }
}