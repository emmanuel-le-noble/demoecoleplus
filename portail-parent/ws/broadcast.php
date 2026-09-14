<?php
/**
 * broadcast.php — Endpoint HTTP pour broadcaster un message via WebSocket
 * 
 * Appelé par messagerie.php après l'insertion en BDD.
 * Envoie le message à tous les participants connectés de la conversation.
 * 
 * Usage POST (JSON) :
 *   { "conversation_id": 123, "content": "Bonjour", "parent_id": 5 }
 * 
 * Headers requis :
 *   Authorization: Bearer <WS_SECRET>
 * 
 * Ecole Plus v1.2.0 — 2026-07-05
 */

declare(strict_types=1);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Sécurité : vérifier le secret partagé
$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$wsSecret = getenv('ECOLEPLUS_WS_SECRET') ?: '';
if ($wsSecret === '') {
    http_response_code(503);
    echo json_encode(['error' => 'WebSocket non configuré']);
    exit;
}

$expectedBearer = 'Bearer ' . $wsSecret;
if (!hash_equals($expectedBearer, $authHeader)) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !is_array($input)) {
    http_response_code(400);
    echo json_encode(['error' => 'Payload JSON invalide']);
    exit;
}

$convId = isset($input['conversation_id']) ? (int)$input['conversation_id'] : 0;
$content = trim($input['content'] ?? '');
$parentId = isset($input['parent_id']) ? (int)$input['parent_id'] : 0;

if ($convId <= 0 || empty($content) || $parentId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Paramètres invalides']);
    exit;
}

// Sécurité : vérifier que le parent participe à la conversation
require_once __DIR__ . '/../config/db.php';

$stmt = $pdo->prepare("
    SELECT 1 FROM msg_participants 
    WHERE ID_CONVERSATION = ? AND USER_TYPE = 'PARENT' AND ID_USER = ? 
    LIMIT 1
");
$stmt->execute([$convId, $parentId]);
if (!$stmt->fetch()) {
    http_response_code(403);
    echo json_encode(['error' => 'Accès non autorisé']);
    exit;
}

// Sauvegarder le message
$stmt = $pdo->prepare("
    INSERT INTO msg_messages (ID_CONVERSATION, EXPEDITEUR_TYPE, ID_EXPEDITEUR, CONTENU) 
    VALUES (?, 'PARENT', ?, ?)
");
$stmt->execute([$convId, $parentId, $content]);
$msgId = (int)$pdo->lastInsertId();

// Récupérer les infos du parent
$stmt = $pdo->prepare("SELECT NOM_PARENT, PRENOM_PARENT FROM parents WHERE ID_PARENT = ? LIMIT 1");
$stmt->execute([$parentId]);
$parent = $stmt->fetch(PDO::FETCH_ASSOC);

// Créer les statuts de lecture pour les participants hors-ligne
$stmt = $pdo->prepare("
    SELECT ID_USER FROM msg_participants 
    WHERE ID_CONVERSATION = ? AND USER_TYPE = 'STAFF'
");
$stmt->execute([$convId]);
$staffs = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (!empty($staffs)) {
    $stmtInsert = $pdo->prepare("
        INSERT IGNORE INTO msg_statuts_lecture (ID_MESSAGE, LECTEUR_TYPE, ID_LECTEUR, DATE_LECTURE) 
        VALUES (?, 'STAFF', ?, NULL)
    ");
    foreach ($staffs as $staffId) {
        $stmtInsert->execute([$msgId, $staffId]);
    }
}

// Notifier le serveur WebSocket via un script worker dédié
// Le script worker écoute sur un port interne et relaie le broadcast
$payload = json_encode([
    'type' => 'broadcast',
    'conversation' => $convId,
    'message' => [
        'ID_MSG' => $msgId,
        'MESSAGE' => $content,
        'DATE_ENVOI' => date('Y-m-d H:i:s'),
        'EXPEDITEUR_TYPE' => 'PARENT',
        'ID_EXPEDITEUR' => $parentId,
        'PRENOM' => htmlspecialchars($parent['PRENOM_PARENT'] ?? '', ENT_QUOTES, 'UTF-8'),
        'NOM' => htmlspecialchars($parent['NOM_PARENT'] ?? '', ENT_QUOTES, 'UTF-8'),
    ],
]);

// Écrire le payload dans un fichier que le worker WebSocket lit
// (pattern file-based IPC — fiable sur WampServer)
$broadcastFile = __DIR__ . '/broadcast_queue_' . $convId . '.json';
file_put_contents($broadcastFile, $payload, LOCK_EX);

echo json_encode([
    'success' => true,
    'message_id' => $msgId,
    'conversation' => $convId,
]);
