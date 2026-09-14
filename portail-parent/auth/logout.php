<?php
declare(strict_types=1);

// ==========================================================================
// 1. Initialiser le DatabaseSessionHandler AVANT session_start()
// ==========================================================================
require_once __DIR__ . '/../includes/session_bootstrap.php';

// ==========================================================================
// 2. Amorcer la session (utilise DatabaseSessionHandler)
// ==========================================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==========================================================================
// 3. Récupérer les paramètres du cookie AVANT de détruire la session
// ==========================================================================
$params = session_get_cookie_params();

// ==========================================================================
// 4. Détruire la session — try/catch + fallback sécurisé
// ==========================================================================
try {
    $_SESSION = [];
    session_destroy();
} catch (\Throwable $e) {
    error_log('[Logout] Erreur session_destroy: ' . $e->getMessage());
    $_SESSION = [];
    // Forcer l'invalidation du jeton côté serveur si la destruction BDD a échoué
    session_regenerate_id(true);
}

// ==========================================================================
// 5. Forcer la suppression du cookie côté client
// ==========================================================================
setcookie(
    session_name(),
    '',
    time() - 42000,
    $params['path'] ?? '/',
    $params['domain'] ?? '',
    (bool)($params['secure'] ?? false),
    (bool)($params['httponly'] ?? true)
);

// ==========================================================================
// 6. Redirection immédiate vers login
// ==========================================================================
header('Location: login.php');
exit;