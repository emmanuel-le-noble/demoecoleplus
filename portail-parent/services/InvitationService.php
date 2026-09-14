<?php
declare(strict_types=1);

class InvitationService
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(int $eleveId, string $email, string $telephone): array
    {
        $tokenPlain = bin2hex(random_bytes(48));
        $tokenHash = hash('sha256', $tokenPlain);
        $expiresAt = date('Y-m-d H:i:s', time() + 7 * 24 * 3600);

        $stmt = $this->pdo->prepare(
            'INSERT INTO parent_invitations (eleve_id, email, telephone, token_hash, token_plain, expires_at)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$eleveId, $email, $telephone, $tokenHash, $tokenPlain, $expiresAt]);

        return [
            'invitation_id' => (int)$this->pdo->lastInsertId(),
            'token' => $tokenPlain,
            'expires_at' => $expiresAt,
        ];
    }

    public function validate(string $tokenPlain): array
    {
        $tokenHash = hash('sha256', $tokenPlain);

        $stmt = $this->pdo->prepare(
            'SELECT pi.*, e.NOM_ELEVE, e.PRENOM_ELEVE
             FROM parent_invitations pi
             JOIN eleve e ON e.ID_ELEVE = pi.eleve_id
             WHERE pi.token_hash = ? AND pi.used_at IS NULL'
        );
        $stmt->execute([$tokenHash]);
        $invitation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$invitation) {
            return ['ok' => false, 'error' => 'Lien d\'invitation invalide.'];
        }

        if (strtotime($invitation['expires_at']) < time()) {
            return ['ok' => false, 'error' => 'Ce lien d\'invitation a expiré.'];
        }

        return [
            'ok' => true,
            'invitation_id' => (int)$invitation['id'],
            'eleve_id' => (int)$invitation['eleve_id'],
            'email' => $invitation['email'],
            'telephone' => $invitation['telephone'],
            'eleve_nom' => $invitation['NOM_ELEVE'],
            'eleve_prenom' => $invitation['PRENOM_ELEVE'],
        ];
    }

    public function markUsed(int $invitationId): void
    {
        $this->pdo->prepare('UPDATE parent_invitations SET used_at = NOW() WHERE id = ?')
            ->execute([$invitationId]);
    }

    public function getByEleveId(int $eleveId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, email, telephone, expires_at, used_at, created_at
             FROM parent_invitations WHERE eleve_id = ? ORDER BY created_at DESC'
        );
        $stmt->execute([$eleveId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
