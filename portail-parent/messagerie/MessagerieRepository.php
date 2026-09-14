<?php
/**
 * MessagerieRepository — Couche d'accès aux données pour le module de messagerie
 *
 * Ecole Plus v1.2.0 — 2026-07-06
 */

declare(strict_types=1);

class MessagerieRepository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAnneeActive(): ?array
    {
        try {
            $stmt = $this->pdo->query("SELECT ID FROM anneescolaire WHERE STATUT = 1 LIMIT 1");
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (\PDOException $e) {
            error_log('[MessagerieRepo] getAnneeActive: ' . $e->getMessage());
            return null;
        }
    }

    public function getEnfantInfo(int $eleveId): ?array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT e.PRENOM_ELEVE, e.NOM_ELEVE, es.IDSALLE AS ID_SALLE
                FROM eleve e
                LEFT JOIN elevesalle es ON e.ID_ELEVE = es.IDELEVE AND es.STATUT = 1
                WHERE e.ID_ELEVE = ? LIMIT 1
            ");
            $stmt->execute([$eleveId]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (\PDOException $e) {
            error_log('[MessagerieRepo] getEnfantInfo: ' . $e->getMessage());
            return null;
        }
    }

    public function getProfesseursBySalle(int $salleId): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT DISTINCT p.ID as ID_PROFESSEUR, p.NOM as NOM_PROF, m.NOM_MATIERE as MATIERE
                FROM professeursallemat psm
                JOIN professeur p ON psm.IDPROF = p.ID
                JOIN matiere m ON psm.IDMAT = m.ID_MATIERE
                WHERE psm.IDSALLE = ? AND psm.STATUT = 1
            ");
            $stmt->execute([$salleId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            error_log('[MessagerieRepo] getProfesseursBySalle: ' . $e->getMessage());
            return [];
        }
    }

    public function findDepartementConversation(string $titre, int $parentId): ?int
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT c.ID FROM msg_conversations c
                JOIN msg_participants p ON c.ID = p.ID_CONVERSATION
                WHERE c.TYPE_CONV = 'DEPARTEMENT' AND c.TITRE = ? AND p.USER_TYPE = 'PARENT' AND p.ID_USER = ? LIMIT 1
            ");
            $stmt->execute([$titre, $parentId]);
            $val = $stmt->fetchColumn();
            return $val !== false ? (int)$val : null;
        } catch (\PDOException $e) {
            error_log('[MessagerieRepo] findDepartementConversation: ' . $e->getMessage());
            return null;
        }
    }

    public function findPriveeConversation(int $parentId, int $staffId): ?int
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT c.ID FROM msg_conversations c
                JOIN msg_participants p1 ON c.ID = p1.ID_CONVERSATION
                JOIN msg_participants p2 ON c.ID = p2.ID_CONVERSATION
                WHERE c.TYPE_CONV = 'PRIVEE' AND p1.USER_TYPE = 'PARENT' AND p1.ID_USER = ? AND p2.USER_TYPE = 'STAFF' AND p2.ID_USER = ? LIMIT 1
            ");
            $stmt->execute([$parentId, $staffId]);
            $val = $stmt->fetchColumn();
            return $val !== false ? (int)$val : null;
        } catch (\PDOException $e) {
            error_log('[MessagerieRepo] findPriveeConversation: ' . $e->getMessage());
            return null;
        }
    }

    public function marquerMessagesLus(int $convId, int $parentId): void
    {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE msg_statuts_lecture sl
                JOIN msg_messages m ON sl.ID_MESSAGE = m.ID
                SET sl.DATE_LECTURE = NOW()
                WHERE m.ID_CONVERSATION = ? AND sl.LECTEUR_TYPE = 'PARENT' AND sl.ID_LECTEUR = ? AND sl.DATE_LECTURE IS NULL
            ");
            $stmt->execute([$convId, $parentId]);
        } catch (\PDOException $e) {
            error_log('[MessagerieRepo] marquerMessagesLus: ' . $e->getMessage());
        }
    }

    public function getMessages(int $convId, int $lastId = 0): array
    {
        try {
            $sql = "SELECT m.ID as ID_MSG, m.CONTENU as MESSAGE, m.DATE_ENVOI, m.EXPEDITEUR_TYPE, m.ID_EXPEDITEUR,
                           pj.NOM_FICHIER as NOM_UNIQUE, pj.CHEMIN_URL as NOM_ORIGINAL, pj.TYPE_MIME,
                           (SELECT CASE WHEN DATE_LECTURE IS NOT NULL THEN 1 ELSE 0 END FROM msg_statuts_lecture WHERE ID_MESSAGE = m.ID LIMIT 1) as STATUT_LECTURE
                    FROM msg_messages m
                    LEFT JOIN msg_pieces_jointes pj ON m.ID = pj.ID_MESSAGE
                    WHERE m.ID_CONVERSATION = ?";
            $params = [$convId];

            if ($lastId > 0) {
                $sql .= " AND m.ID > ?";
                $params[] = $lastId;
            }

            $sql .= " ORDER BY m.DATE_ENVOI ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            error_log('[MessagerieRepo] getMessages: ' . $e->getMessage());
            return [];
        }
    }

    public function getMessageCount(int $convId): int
    {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM msg_messages WHERE ID_CONVERSATION = ?");
            $stmt->execute([$convId]);
            return (int)$stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log('[MessagerieRepo] getMessageCount: ' . $e->getMessage());
            return 0;
        }
    }

    public function createConversation(?string $titre, string $typeConv): int
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO msg_conversations (TITRE, TYPE_CONV) VALUES (?, ?)");
            $stmt->execute([$titre, $typeConv]);
            return (int)$this->pdo->lastInsertId();
        } catch (\PDOException $e) {
            error_log('[MessagerieRepo] createConversation: ' . $e->getMessage());
            throw $e;
        }
    }

    public function addParticipant(int $convId, string $userType, int $userId): void
    {
        try {
            $stmt = $this->pdo->prepare("INSERT IGNORE INTO msg_participants (ID_CONVERSATION, USER_TYPE, ID_USER) VALUES (?, ?, ?)");
            $stmt->execute([$convId, $userType, $userId]);
        } catch (\PDOException $e) {
            error_log('[MessagerieRepo] addParticipant: ' . $e->getMessage());
            throw $e;
        }
    }

    public function insertMessage(int $convId, string $expediteurType, int $expediteurId, string $contenu): int
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO msg_messages (ID_CONVERSATION, EXPEDITEUR_TYPE, ID_EXPEDITEUR, CONTENU) VALUES (?, ?, ?, ?)");
            $stmt->execute([$convId, $expediteurType, $expediteurId, $contenu]);
            return (int)$this->pdo->lastInsertId();
        } catch (\PDOException $e) {
            error_log('[MessagerieRepo] insertMessage: ' . $e->getMessage());
            throw $e;
        }
    }

    public function insertPieceJointe(int $messageId, string $nomFichier, string $cheminUrl, string $typeMime): void
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO msg_pieces_jointes (ID_MESSAGE, NOM_FICHIER, CHEMIN_URL, TYPE_MIME) VALUES (?, ?, ?, ?)");
            $stmt->execute([$messageId, $nomFichier, $cheminUrl, $typeMime]);
        } catch (\PDOException $e) {
            error_log('[MessagerieRepo] insertPieceJointe: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getStaffByProfile(string $profile): array
    {
        try {
            $stmt = $this->pdo->prepare("SELECT ID FROM utilisateur WHERE PROFIL = ? AND STATUT = 1");
            $stmt->execute([$profile]);
            return $stmt->fetchAll(\PDO::FETCH_COLUMN) ?: [];
        } catch (\PDOException $e) {
            error_log('[MessagerieRepo] getStaffByProfile: ' . $e->getMessage());
            return [];
        }
    }

    public function insertStatutLecture(int $messageId, string $lecteurType, int $lecteurId): void
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO msg_statuts_lecture (ID_MESSAGE, LECTEUR_TYPE, ID_LECTEUR, DATE_LECTURE) VALUES (?, ?, ?, NULL)");
            $stmt->execute([$messageId, $lecteurType, $lecteurId]);
        } catch (\PDOException $e) {
            error_log('[MessagerieRepo] insertStatutLecture: ' . $e->getMessage());
        }
    }

    public function getNonLus(int $parentId): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT c.TYPE_CONV, c.TITRE as CONV_TITRE, m.ID_EXPEDITEUR, COUNT(*) as nb_non_lus
                FROM msg_statuts_lecture sl
                JOIN msg_messages m ON sl.ID_MESSAGE = m.ID
                JOIN msg_conversations c ON m.ID_CONVERSATION = c.ID
                WHERE sl.LECTEUR_TYPE = 'PARENT' AND sl.ID_LECTEUR = ? AND sl.DATE_LECTURE IS NULL
                GROUP BY c.TYPE_CONV, c.TITRE, m.ID_EXPEDITEUR
            ");
            $stmt->execute([$parentId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
        } catch (\PDOException $e) {
            error_log('[MessagerieRepo] getNonLus: ' . $e->getMessage());
            return [];
        }
    }

    public function getParentInfo(int $parentId): ?array
    {
        try {
            $stmt = $this->pdo->prepare("SELECT NOM_PARENT, PRENOM_PARENT FROM parents WHERE ID_PARENT = ? LIMIT 1");
            $stmt->execute([$parentId]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (\PDOException $e) {
            error_log('[MessagerieRepo] getParentInfo: ' . $e->getMessage());
            return null;
        }
    }

    public function getLastMessageId(int $convId): ?int
    {
        try {
            $stmt = $this->pdo->prepare("SELECT ID FROM msg_messages WHERE ID_CONVERSATION = ? ORDER BY ID DESC LIMIT 1");
            $stmt->execute([$convId]);
            $val = $stmt->fetchColumn();
            return $val !== false ? (int)$val : null;
        } catch (\PDOException $e) {
            error_log('[MessagerieRepo] getLastMessageId: ' . $e->getMessage());
            return null;
        }
    }

    public function checkParticipant(int $convId, string $userType, int $userId): bool
    {
        try {
            $stmt = $this->pdo->prepare("SELECT 1 FROM msg_participants WHERE ID_CONVERSATION = ? AND USER_TYPE = ? AND ID_USER = ? LIMIT 1");
            $stmt->execute([$convId, $userType, $userId]);
            return (bool)$stmt->fetch();
        } catch (\PDOException $e) {
            error_log('[MessagerieRepo] checkParticipant: ' . $e->getMessage());
            return false;
        }
    }

    public function getStaffParticipants(int $convId): array
    {
        try {
            $stmt = $this->pdo->prepare("SELECT ID_USER FROM msg_participants WHERE ID_CONVERSATION = ? AND USER_TYPE = 'STAFF'");
            $stmt->execute([$convId]);
            return $stmt->fetchAll(\PDO::FETCH_COLUMN) ?: [];
        } catch (\PDOException $e) {
            error_log('[MessagerieRepo] getStaffParticipants: ' . $e->getMessage());
            return [];
        }
    }
}