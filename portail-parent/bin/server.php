<?php
/**
 * server.php — Point d'entrée du serveur WebSocket Ecole Plus
 * 
 * Usage : php bin/server.php
 * 
 * Le serveur écoute sur le port 8080 (configurable).
 * Les clients se connectent via : ws://localhost:8080?conversation=X&parent_id=Y
 * 
 * Ecole Plus v1.2.0 — 2026-07-05
 */

declare(strict_types=1);

// Masque les alertes de dépréciation (Deprecated) pour nettoyer la console
error_reporting(E_ALL & ~E_DEPRECATED);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../ws/ChatComponent.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$port = 8080;

echo "==================================================\n";
echo "  Ecole Plus — Serveur WebSocket v1.2.0\n";
echo "  Port : {$port}\n";
echo "  URL  : ws://localhost:{$port}\n";
echo "==================================================\n\n";

$chatComponent = new ChatComponent($pdo);

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            $chatComponent
        )
    ),
    $port
);

// Démarrer le polling des fichiers broadcast (PHP → WS via files)
$chatComponent->startBroadcastPolling($server->loop);

echo "[OK] Serveur démarré. En attente de connexions...\n";
echo "[INFO] Pour arrêter : Ctrl+C\n\n";

$server->run();
