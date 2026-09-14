<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/session_bootstrap.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['id_parent']) || isset($_SESSION['parent_id'])) {
    header('Location: ../dashboard/dashboard.php');
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = '';
$messageType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = (string)($_POST['csrf_token'] ?? '');
    if (!hash_equals((string)$_SESSION['csrf_token'], $csrfToken)) {
        $message = 'Votre session a expiré. Veuillez réessayer.';
        $messageType = 'danger';
    } elseif ((int)($_SESSION['password_reset_last_request'] ?? 0) > time() - 120) {
        // Réponse identique à une demande normale pour ne révéler aucune information.
        $message = 'Si cette adresse est associée à un compte actif, un lien de réinitialisation vient d’être envoyé.';
    } else {
        $_SESSION['password_reset_last_request'] = time();
        $email = filter_var(trim((string)($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);

        if ($email !== false) {
            try {
                $stmt = $pdo->prepare(
                    'SELECT ID_PARENT, NOM_PARENT, PRENOM_PARENT, MAIL_PARENT
                     FROM parents
                     WHERE LOWER(MAIL_PARENT) = LOWER(?) AND STATUT_PARENT = 1
                     LIMIT 1'
                );
                $stmt->execute([$email]);
                $parent = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($parent) {
                    $token = bin2hex(random_bytes(32));
                    $tokenHash = hash('sha256', $token);

                    $pdo->beginTransaction();
                    $invalidate = $pdo->prepare('UPDATE password_resets_parents SET used_at = NOW() WHERE parent_id = ? AND used_at IS NULL');
                    $invalidate->execute([(int)$parent['ID_PARENT']]);
                    $insert = $pdo->prepare(
                        'INSERT INTO password_resets_parents (parent_id, token_hash, expires_at)
                         VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 30 MINUTE))'
                    );
                    $insert->execute([(int)$parent['ID_PARENT'], $tokenHash]);
                    $pdo->commit();

                    $appUrl = rtrim((string)(getenv('APP_URL') ?: ''), '/');
                    $from = (string)(getenv('MAIL_FROM') ?: '');
                    if (filter_var($appUrl, FILTER_VALIDATE_URL) && filter_var($from, FILTER_VALIDATE_EMAIL)) {
                        $resetUrl = $appUrl . '/auth/reset_password.php?token=' . rawurlencode($token);
                        $subject = 'Réinitialisation de votre mot de passe Ecole Plus';
                        $body = "Bonjour " . trim((string)$parent['PRENOM_PARENT'] . ' ' . (string)$parent['NOM_PARENT']) . ",\n\n"
                            . "Une demande de réinitialisation de mot de passe a été reçue.\n"
                            . "Utilisez ce lien dans les 30 minutes :\n" . $resetUrl . "\n\n"
                            . "Si vous n’êtes pas à l’origine de cette demande, ignorez ce message.\n";
                        $headers = [
                            'From: ' . $from,
                            'Content-Type: text/plain; charset=UTF-8',
                        ];

                        if (!mail((string)$parent['MAIL_PARENT'], $subject, $body, implode("\r\n", $headers))) {
                            error_log('[PasswordReset] Échec de l’envoi de l’e-mail pour le parent #' . (int)$parent['ID_PARENT']);
                        }
                    } else {
                        error_log('[PasswordReset] APP_URL ou MAIL_FROM manquant : e-mail non envoyé.');
                    }
                }
            } catch (\Throwable $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                error_log('[PasswordReset] Demande impossible : ' . $e->getMessage());
            }
        }

        // Ne jamais indiquer si une adresse e-mail correspond à un compte.
        $message = 'Si cette adresse est associée à un compte actif, un lien de réinitialisation vient d’être envoyé.';
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mot de passe oublié — Ecole Plus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light d-flex align-items-center min-vh-100">
<main class="container" style="max-width: 500px;">
    <section class="card border-0 shadow-sm p-4 p-md-5">
        <div class="text-center mb-4">
            <i class="fa-solid fa-key text-success fs-1 mb-3"></i>
            <h1 class="h3">Mot de passe oublié ?</h1>
            <p class="text-muted mb-0">Saisissez l’adresse e-mail associée à votre compte parent.</p>
        </div>
        <?php if ($message !== ''): ?>
            <div class="alert alert-<?= $messageType === 'danger' ? 'danger' : 'success' ?>" role="alert"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <form method="post" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)$_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
            <label for="email" class="form-label">Adresse e-mail</label>
            <input type="email" class="form-control mb-3" id="email" name="email" autocomplete="email" required>
            <button class="btn btn-success w-100" type="submit">Envoyer le lien</button>
        </form>
        <a class="d-block text-center mt-3" href="login.php">Retour à la connexion</a>
    </section>
</main>
</body>
</html>
