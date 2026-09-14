<?php
declare(strict_types=1);

require_once __DIR__ . '/ProfesseursRepository.php';

class ProfesseursController
{
    private ProfesseursRepository $repo;
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->repo = new ProfesseursRepository($pdo);
    }

    public function listProfesseurs(int $parentId, int $eleveId): array
    {
        $professeurs = $this->repo->getProfesseursByParent($parentId, $eleveId);

        $grouped = [];
        foreach ($professeurs as $row) {
            $pid = (int)$row['professeur_id'];
            if (!isset($grouped[$pid])) {
                $grouped[$pid] = [
                    'id' => $pid,
                    'nom' => $row['professeur_nom'],
                    'titre' => $row['professeur_titre'] ?? '',
                    'contact' => $row['professeur_contact'] ?? '',
                    'signature' => $row['professeur_signature'] ?? '',
                    'matieres' => [],
                    'classes' => [],
                ];
            }
            $matiere = $row['matiere_nom'];
            if (!in_array($matiere, $grouped[$pid]['matieres'], true)) {
                $grouped[$pid]['matieres'][] = $matiere;
            }
            $classe = $row['classe_nom'];
            if (!in_array($classe, $grouped[$pid]['classes'], true)) {
                $grouped[$pid]['classes'][] = $classe;
            }
        }

        return array_values($grouped);
    }

    public function getProfesseur(int $parentId, int $eleveId, int $professeurId): ?array
    {
        $prof = $this->repo->getProfesseurById($parentId, $eleveId, $professeurId);
        if (!$prof) return null;

        $prof['matieres'] = $this->repo->getMatieresByProfesseur($parentId, $eleveId, $professeurId);
        $prof['conversation_id'] = $this->repo->getConversationForProfesseur($parentId, $professeurId);

        return $prof;
    }

    public function startConversation(int $parentId, int $professeurId): ?int
    {
        $existing = $this->repo->getConversationForProfesseur($parentId, $professeurId);
        if ($existing) return $existing;

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare(
                'INSERT INTO msg_conversations (DEPARTEMENT, TITRE, TYPE_BULLETIN)
                 VALUES (?, ?, ?)'
            );
            $stmt->execute(['PRIVEE', 'Conversation parent-professeur', 'NON']);
            $convId = (int)$this->pdo->lastInsertId();

            $this->pdo->prepare(
                'INSERT INTO msg_participants (conversation_id, user_type, user_id, ROLE)
                 VALUES (?, ?, ?, ?)'
            )->execute([$convId, 'PARENT', $parentId, 'MEMBRE']);

            $this->pdo->prepare(
                'INSERT INTO msg_participants (conversation_id, user_type, user_id, ROLE)
                 VALUES (?, ?, ?, ?)'
            )->execute([$convId, 'STAFF', $professeurId, 'MEMBRE']);

            $this->pdo->commit();
            return $convId;
        } catch (\PDOException $e) {
            $this->pdo->rollBack();
            error_log('[Professeurs] Erreur création conversation: ' . $e->getMessage());
            return null;
        }
    }
}
