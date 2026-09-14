<?php
declare(strict_types=1);

class ProfesseursRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getProfesseursByParent(int $parentId, int $eleveId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT DISTINCT
                p.ID AS professeur_id,
                p.NOM AS professeur_nom,
                p.TITRE AS professeur_titre,
                p.CONTACT AS professeur_contact,
                p.SIGNATURE AS professeur_signature,
                m.NOM_MATIERE AS matiere_nom,
                s.NOMSALLE AS classe_nom
             FROM professeur p
             INNER JOIN professeursallemat psm ON psm.IDPROF = p.ID
             INNER JOIN matiere m ON m.ID_MATIERE = psm.IDMAT
             INNER JOIN salle s ON s.ID = psm.IDSALLE
             INNER JOIN elevesalle es ON es.IDSALLE = s.ID
             INNER JOIN eleve e ON e.ID_ELEVE = es.IDELEVE
             INNER JOIN parent_eleve pe ON pe.ID_ELEVE = e.ID_ELEVE
             WHERE pe.ID_PARENT = ?
               AND e.ID_ELEVE = ?
               AND p.STATUT != 0
               AND psm.STATUT != 0
               AND es.STATUT != 0
             ORDER BY p.NOM ASC, m.NOM_MATIERE ASC'
        );
        $stmt->execute([$parentId, $eleveId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProfesseurById(int $parentId, int $eleveId, int $professeurId): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT DISTINCT
                p.ID AS professeur_id,
                p.NOM AS professeur_nom,
                p.TITRE AS professeur_titre,
                p.CONTACT AS professeur_contact,
                p.SIGNATURE AS professeur_signature,
                m.NOM_MATIERE AS matiere_nom,
                s.NOMSALLE AS classe_nom,
                s.ID AS salle_id
             FROM professeur p
             INNER JOIN professeursallemat psm ON psm.IDPROF = p.ID
             INNER JOIN matiere m ON m.ID_MATIERE = psm.IDMAT
             INNER JOIN salle s ON s.ID = psm.IDSALLE
             INNER JOIN elevesalle es ON es.IDSALLE = s.ID
             INNER JOIN eleve e ON e.ID_ELEVE = es.IDELEVE
             INNER JOIN parent_eleve pe ON pe.ID_ELEVE = e.ID_ELEVE
             WHERE pe.ID_PARENT = ?
               AND e.ID_ELEVE = ?
               AND p.ID = ?
               AND p.STATUT != 0
             LIMIT 1'
        );
        $stmt->execute([$parentId, $eleveId, $professeurId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getMatieresByProfesseur(int $parentId, int $eleveId, int $professeurId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT DISTINCT
                m.ID_MATIERE AS matiere_id,
                m.NOM_MATIERE AS matiere_nom,
                s.NOMSALLE AS classe_nom,
                s.ID AS salle_id
             FROM professeursallemat psm
             INNER JOIN matiere m ON m.ID_MATIERE = psm.IDMAT
             INNER JOIN salle s ON s.ID = psm.IDSALLE
             INNER JOIN elevesalle es ON es.IDSALLE = s.ID
             INNER JOIN eleve e ON e.ID_ELEVE = es.IDELEVE
             INNER JOIN parent_eleve pe ON pe.ID_ELEVE = e.ID_ELEVE
             WHERE pe.ID_PARENT = ?
               AND e.ID_ELEVE = ?
               AND psm.IDPROF = ?
               AND psm.STATUT != 0
             ORDER BY m.NOM_MATIERE ASC'
        );
        $stmt->execute([$parentId, $eleveId, $professeurId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getConversationForProfesseur(int $parentId, int $professeurId): ?int
    {
        $stmt = $this->pdo->prepare(
            'SELECT c.id
             FROM msg_conversations c
             INNER JOIN msg_participants p1 ON p1.conversation_id = c.id AND p1.user_type = ? AND p1.user_id = ?
             INNER JOIN msg_participants p2 ON p2.conversation_id = c.id AND p2.user_type = ? AND p2.user_id = ?
             WHERE c.DEPARTEMENT = ?
             LIMIT 1'
        );
        $stmt->execute(['PARENT', $parentId, 'STAFF', $professeurId, 'PRIVEE']);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['id'] : null;
    }
}
