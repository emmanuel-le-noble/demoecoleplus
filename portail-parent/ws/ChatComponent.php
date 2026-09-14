<?php
/**
 * ChatComponent — Composant WebSocket pour la messagerie temps réel
 * 
 * Gère les connexions, les salles de discussion (conversations),
 * et le broadcast des messages entre participants.
 * 
 * Ecole Plus v1.2.0 — 2026-07-05
 */

declare(strict_types=1);

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db.php';

class ChatComponent implements MessageComponentInterface
{
    protected \SplObjectStorage $clients;
    protected array $conversations = []; // conversation_id => [connection_id => ConnectionInterface]
    protected \PDO $pdo;
    protected string $broadcastDir;

    public function __construct(\PDO $pdo)
    {
        $this->clients = new \SplObjectStorage();
        $this->pdo = $pdo;
        $this->broadcastDir = __DIR__;
        echo "[WS] ChatComponent initialisé.\n";
    }

    /**
     * Démarrer le polling des fichiers broadcast (appelé depuis bin/server.php)
     */
    public function startBroadcastPolling(\React\EventLoop\LoopInterface $loop): void
    {
        $loop->addPeriodicTimer(1.0, function () {
            $this->pollBroadcastQueue();
        });
    }

    /**
     * Vérifier les fichiers broadcast_queue_*.json et relayer les messages
     */
    private function pollBroadcastQueue(): void
    {
        $pattern = $this->broadcastDir . '/broadcast_queue_*.json';
        $files = glob($pattern);
        if (!$files) return;

        foreach ($files as $file) {
            $payload = @json_decode(file_get_contents($file), true);
            if ($payload && isset($payload['conversation'], $payload['message'])) {
                $convId = (int)$payload['conversation'];
                $this->broadcastToConversation($convId, $payload);
            }
            @unlink($file);
        }
    }

    /**
     * Nouvelle connexion WebSocket
     */
    public function onOpen(ConnectionInterface $conn): void
    {
        $this->clients->attach($conn);
        $query = $conn->httpRequest->getUri()->getQuery();
        parse_str($query, $params);

        $conn->conversationId = isset($params['conversation']) ? (int)$params['conversation'] : 0;
        $conn->parent_id = isset($params['parent_id']) ? (int)$params['parent_id'] : 0;
        $conn->user_type = $params['user_type'] ?? 'PARENT';

        if (!$this->isAuthorisedConnection($conn->parent_id, $conn->conversationId, (string)($params['auth'] ?? ''))) {
            echo "[WS] Connexion refusée #{$conn->resourceId} (authentification invalide)\n";
            $conn->close();
            return;
        }

        if ($conn->conversationId > 0) {
            if (!isset($this->conversations[$conn->conversationId])) {
                $this->conversations[$conn->conversationId] = [];
            }
            $this->conversations[$conn->conversationId][$conn->resourceId] = $conn;
            echo "[WS] Connexion #{$conn->resourceId} → Conversation {$conn->conversationId} (Parent #{$conn->parent_id})\n";
        } else {
            echo "[WS] Connexion #{$conn->resourceId} (sans conversation)\n";
        }
    }

    /**
     * Réception d'un message
     */
    public function onMessage(ConnectionInterface $from, $msg): void
    {
        $data = json_decode($msg, true);

        if (!$data || !isset($data['type'])) {
            return;
        }

        switch ($data['type']) {
            case 'join':
                $this->handleJoin($from, $data);
                break;
            case 'message':
                $this->handleMessage($from, $data);
                break;
            case 'read':
                $this->handleRead($from, $data);
                break;
            case 'ping':
                $from->send(json_encode(['type' => 'pong']));
                break;
        }
    }

    /**
     * Rejoindre une conversation
     */
    private function handleJoin(ConnectionInterface $conn, array $data): void
    {
        $convId = isset($data['conversation']) ? (int)$data['conversation'] : 0;
        // La conversation est authentifiée à l'ouverture et ne peut pas être
        // changée ensuite par un message client forgé.
        if ($convId <= 0 || $convId !== ($conn->conversationId ?? 0)) return;

        // Retirer de l'ancienne conversation
        if ($conn->conversationId > 0 && isset($this->conversations[$conn->conversationId][$conn->resourceId])) {
            unset($this->conversations[$conn->conversationId][$conn->resourceId]);
        }

        $conn->conversationId = $convId;

        if (!isset($this->conversations[$convId])) {
            $this->conversations[$convId] = [];
        }
        $this->conversations[$convId][$conn->resourceId] = $conn;

        $conn->send(json_encode([
            'type' => 'joined',
            'conversation' => $convId,
        ]));

        echo "[WS] #{$conn->resourceId} rejoint conversation {$convId}\n";
    }

    /**
     * Traitement d'un nouveau message
     */
    private function handleMessage(ConnectionInterface $from, array $data): void
    {
        $convId = $from->conversationId ?? 0;
        $content = trim($data['content'] ?? '');
        $parentId = $from->parent_id ?? 0;

        if ($convId <= 0 || empty($content) || $parentId <= 0) {
            return;
        }

        // Vérifier que le parent participe à cette conversation
        if (!$this->verifyParentInConversation($parentId, $convId)) {
            $from->send(json_encode(['type' => 'error', 'message' => 'Accès non autorisé.']));
            return;
        }

        // Sauvegarder en BDD
        $msgId = $this->saveMessage($convId, $parentId, $content);
        if ($msgId <= 0) {
            $from->send(json_encode(['type' => 'error', 'message' => 'Erreur envoi.']));
            return;
        }

        // Récupérer les infos du parent
        $parentInfo = $this->getParentInfo($parentId);

        // Construire le payload (htmlspecialchars pour output client)
        $payload = [
            'type' => 'new_message',
            'message' => [
                'ID_MSG' => $msgId,
                'MESSAGE' => $content,
                'DATE_ENVOI' => date('Y-m-d H:i:s'),
                'EXPEDITEUR_TYPE' => 'PARENT',
                'ID_EXPEDITEUR' => $parentId,
                'PRENOM' => htmlspecialchars($parentInfo['PRENOM_PARENT'] ?? '', ENT_QUOTES, 'UTF-8'),
                'NOM' => htmlspecialchars($parentInfo['NOM_PARENT'] ?? '', ENT_QUOTES, 'UTF-8'),
            ],
            'conversation' => $convId,
        ];

        // Broadcast à tous les participants de la conversation
        $this->broadcastToConversation($convId, $payload);

        // Notifier les staffs de la conversation
        $this->notifyStaff($convId, $msgId, $content, $parentId);
    }

    /**
     * Marquer un message comme lu
     */
    private function handleRead(ConnectionInterface $from, array $data): void
    {
        $msgId = isset($data['message_id']) ? (int)$data['message_id'] : 0;
        $parentId = $from->parent_id ?? 0;

        if ($msgId <= 0 || $parentId <= 0) return;

        try {
            $stmt = $this->pdo->prepare("
                UPDATE msg_statuts_lecture 
                SET DATE_LECTURE = NOW() 
                WHERE ID_MESSAGE = ? AND LECTEUR_TYPE = 'PARENT' AND ID_LECTEUR = ? AND DATE_LECTURE IS NULL
            ");
            $stmt->execute([$msgId, $parentId]);
        } catch (\PDOException $e) {
            error_log('[WS] Erreur mark read: ' . $e->getMessage());
        }
    }

    /**
     * Vérifier qu'un parent participe à une conversation
     */
    private function verifyParentInConversation(int $parentId, int $convId): bool
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 1 FROM msg_participants 
                WHERE ID_CONVERSATION = ? AND USER_TYPE = 'PARENT' AND ID_USER = ? 
                LIMIT 1
            ");
            $stmt->execute([$convId, $parentId]);
            return $stmt->fetch() !== false;
        } catch (\PDOException $e) {
            return false;
        }
    }

    private function isAuthorisedConnection(int $parentId, int $convId, string $token): bool
    {
        $secret = getenv('ECOLEPLUS_WS_SECRET') ?: '';
        if ($secret === '' || $parentId <= 0 || $convId <= 0) {
            return false;
        }

        [$expiresAt, $signature] = array_pad(explode('.', $token, 2), 2, '');
        if (!ctype_digit($expiresAt) || (int)$expiresAt < time() || $signature === '') {
            return false;
        }

        $payload = $parentId . ':' . $convId . ':' . $expiresAt;
        $expected = hash_hmac('sha256', $payload, $secret);
        return hash_equals($expected, $signature) && $this->verifyParentInConversation($parentId, $convId);
    }

    /**
     * Sauvegarder un message en BDD
     */
    private function saveMessage(int $convId, int $parentId, string $content): int
    {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO msg_messages (ID_CONVERSATION, EXPEDITEUR_TYPE, ID_EXPEDITEUR, CONTENU) 
                VALUES (?, 'PARENT', ?, ?)
            ");
            $stmt->execute([$convId, $parentId, $content]);
            return (int)$this->pdo->lastInsertId();
        } catch (\PDOException $e) {
            error_log('[WS] Erreur save message: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Récupérer les infos d'un parent
     */
    private function getParentInfo(int $parentId): ?array
    {
        try {
            $stmt = $this->pdo->prepare("SELECT NOM_PARENT, PRENOM_PARENT FROM parents WHERE ID_PARENT = ? LIMIT 1");
            $stmt->execute([$parentId]);
            return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
        } catch (\PDOException $e) {
            return null;
        }
    }

    /**
     * Broadcaster un message à tous les membres d'une conversation
     */
    private function broadcastToConversation(int $convId, array $payload): void
    {
        if (!isset($this->conversations[$convId])) return;

        $json = json_encode($payload);
        foreach ($this->conversations[$convId] as $conn) {
            $conn->send($json);
        }
    }

    /**
     * Notifier les staffs d'une conversation (via BDD pour les hors-ligne)
     */
    private function notifyStaff(int $convId, int $msgId, string $content, int $parentId): void
    {
        try {
            // Récupérer les participants staff de cette conversation
            $stmt = $this->pdo->prepare("
                SELECT ID_USER FROM msg_participants 
                WHERE ID_CONVERSATION = ? AND USER_TYPE = 'STAFF'
            ");
            $stmt->execute([$convId]);
            $staffs = $stmt->fetchAll(\PDO::FETCH_COLUMN);

            // Créer les statuts de lecture pour les staffs (msgId passé directement)
            if ($msgId > 0 && !empty($staffs)) {
                $stmtInsert = $this->pdo->prepare("
                    INSERT IGNORE INTO msg_statuts_lecture (ID_MESSAGE, LECTEUR_TYPE, ID_LECTEUR, DATE_LECTURE) 
                    VALUES (?, 'STAFF', ?, NULL)
                ");
                foreach ($staffs as $staffId) {
                    $stmtInsert->execute([$msgId, $staffId]);
                }
            }
        } catch (\PDOException $e) {
            error_log('[WS] Erreur notify staff: ' . $e->getMessage());
        }
    }

    /**
     * Récupérer le dernier ID de message d'une conversation
     */
    private function getLastMessageId(int $convId): int
    {
        try {
            $stmt = $this->pdo->prepare("SELECT ID FROM msg_messages WHERE ID_CONVERSATION = ? ORDER BY ID DESC LIMIT 1");
            $stmt->execute([$convId]);
            return (int)($stmt->fetchColumn() ?: 0);
        } catch (\PDOException $e) {
            return 0;
        }
    }

    /**
     * Déconnexion
     */
    public function onClose(ConnectionInterface $conn): void
    {
        if ($conn->conversationId > 0 && isset($this->conversations[$conn->conversationId][$conn->resourceId])) {
            unset($this->conversations[$conn->conversationId][$conn->resourceId]);
            echo "[WS] #{$conn->resourceId} déconnecté de conversation {$conn->conversationId}\n";
        }
        $this->clients->detach($conn);
    }

    /**
     * Erreur
     */
    public function onError(ConnectionInterface $conn, \Exception $e): void
    {
        error_log("[WS] Erreur #{$conn->resourceId}: " . $e->getMessage());
        $conn->close();
    }
}
