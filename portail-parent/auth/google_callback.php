<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/session_bootstrap.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function googleAuthRedirect(string $status): never
{
    header('Location: login.php?oauth=' . rawurlencode($status));
    exit;
}

function googleApiRequest(string $url, array $headers = [], ?array $postData = null): array
{
    $curl = curl_init($url);
    if ($curl === false) {
        throw new RuntimeException('Impossible d’initialiser la connexion Google.');
    }

    $options = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_HTTPHEADER => $headers,
    ];
    if ($postData !== null) {
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = http_build_query($postData, '', '&', PHP_QUERY_RFC3986);
        $options[CURLOPT_HTTPHEADER][] = 'Content-Type: application/x-www-form-urlencoded';
    }
    curl_setopt_array($curl, $options);
    $response = curl_exec($curl);
    $status = (int)curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    $error = curl_error($curl);
    curl_close($curl);

    if (!is_string($response) || $status < 200 || $status >= 300) {
        throw new RuntimeException('Réponse Google invalide : ' . $error);
    }
    $data = json_decode($response, true);
    if (!is_array($data)) {
        throw new RuntimeException('Réponse Google illisible.');
    }
    return $data;
}

$state = (string)($_GET['state'] ?? '');
$expectedState = (string)($_SESSION['google_oauth_state'] ?? '');
$startedAt = (int)($_SESSION['google_oauth_started_at'] ?? 0);
unset($_SESSION['google_oauth_state'], $_SESSION['google_oauth_started_at']);

if ($state === '' || $expectedState === '' || !hash_equals($expectedState, $state) || $startedAt < time() - 600) {
    googleAuthRedirect('invalid_state');
}
if (isset($_GET['error']) || empty($_GET['code'])) {
    googleAuthRedirect('cancelled');
}

$clientId = (string)(getenv('GOOGLE_CLIENT_ID') ?: '');
$clientSecret = (string)(getenv('GOOGLE_CLIENT_SECRET') ?: '');
$appUrl = rtrim((string)(getenv('APP_URL') ?: ''), '/');
if ($clientId === '' || $clientSecret === '' || !filter_var($appUrl, FILTER_VALIDATE_URL)) {
    googleAuthRedirect('unavailable');
}

try {
    $tokens = googleApiRequest('https://oauth2.googleapis.com/token', [], [
        'code' => (string)$_GET['code'],
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'redirect_uri' => $appUrl . '/auth/google_callback.php',
        'grant_type' => 'authorization_code',
    ]);
    $accessToken = (string)($tokens['access_token'] ?? '');
    if ($accessToken === '') {
        throw new RuntimeException('Jeton d’accès absent.');
    }

    $googleUser = googleApiRequest('https://openidconnect.googleapis.com/v1/userinfo', ['Authorization: Bearer ' . $accessToken]);
    $googleSub = (string)($googleUser['sub'] ?? '');
    $googleEmail = filter_var((string)($googleUser['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $emailVerified = ($googleUser['email_verified'] ?? false) === true || ($googleUser['email_verified'] ?? '') === 'true';
    if ($googleSub === '' || $googleEmail === false || !$emailVerified) {
        throw new RuntimeException('Identité Google non vérifiée.');
    }

    // Une liaison existante est prioritaire. À la première connexion, seule
    // l’adresse vérifiée correspondant au dossier parent permet la liaison.
    $findBySub = $pdo->prepare('SELECT * FROM parents WHERE GOOGLE_SUB = ? AND STATUT_PARENT = 1 LIMIT 1');
    $findBySub->execute([$googleSub]);
    $parent = $findBySub->fetch(PDO::FETCH_ASSOC);

    if (!$parent) {
        $findByEmail = $pdo->prepare(
            "SELECT * FROM parents
             WHERE LOWER(MAIL_PARENT) = LOWER(?) AND STATUT_PARENT = 1
               AND (GOOGLE_SUB IS NULL OR GOOGLE_SUB = '')
             LIMIT 1"
        );
        $findByEmail->execute([$googleEmail]);
        $parent = $findByEmail->fetch(PDO::FETCH_ASSOC);
        if (!$parent) {
            googleAuthRedirect('not_linked');
        }

        $linkGoogle = $pdo->prepare("UPDATE parents SET GOOGLE_SUB = ? WHERE ID_PARENT = ? AND (GOOGLE_SUB IS NULL OR GOOGLE_SUB = '')");
        $linkGoogle->execute([$googleSub, (int)$parent['ID_PARENT']]);
        if ($linkGoogle->rowCount() !== 1) {
            googleAuthRedirect('not_linked');
        }
    }

    session_regenerate_id(true);
    $_SESSION['id_parent'] = (int)$parent['ID_PARENT'];
    $_SESSION['parent_id'] = (int)$parent['ID_PARENT'];
    $_SESSION['parent_nom'] = trim((string)$parent['NOM_PARENT'] . ' ' . (string)$parent['PRENOM_PARENT']);
    header('Location: ../dashboard/dashboard.php');
    exit;
} catch (\Throwable $e) {
    error_log('[GoogleAuth] Échec connexion : ' . $e->getMessage());
    googleAuthRedirect('error');
}
