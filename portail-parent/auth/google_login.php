<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/session_bootstrap.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$clientId = (string)(getenv('GOOGLE_CLIENT_ID') ?: '');
$appUrl = rtrim((string)(getenv('APP_URL') ?: ''), '/');
if ($clientId === '' || !filter_var($appUrl, FILTER_VALIDATE_URL)) {
    header('Location: login.php?oauth=unavailable');
    exit;
}

$state = bin2hex(random_bytes(32));
$_SESSION['google_oauth_state'] = $state;
$_SESSION['google_oauth_started_at'] = time();

$params = [
    'client_id' => $clientId,
    'redirect_uri' => $appUrl . '/auth/google_callback.php',
    'response_type' => 'code',
    'scope' => 'openid email profile',
    'state' => $state,
    'prompt' => 'select_account',
];

header('Location: https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params, '', '&', PHP_QUERY_RFC3986));
exit;
