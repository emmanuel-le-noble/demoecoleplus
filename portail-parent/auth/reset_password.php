<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/session_bootstrap.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

header('Referrer-Policy: no-referrer');

$token = (string)($_GET['token'] ?? $_POST['token'] ?? '');
$tokenHash = ctype_xdigit($token) && strlen($token) === 64 ? hash('sha256', $token) : '';
$error = '';
$success = false;

if ($tokenHash === '') {
    $error = 'Ce lien de réinitialisation est invalide ou a expiré.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $error === '') {
    if (!hash_equals((string)$_SESSION['csrf_token'], (string)($_POST['csrf_token'] ?? ''))) {
        $error = 'Votre session a expiré. Veuillez réessayer.';
    } else {
        $password = (string)($_POST['password'] ?? '');
        $confirmation = (string)($_POST['password_confirmation'] ?? '');

        if (strlen($password) < 8) {
            $error = 'Le mot de passe doit contenir au moins 8 caractères.';
        } elseif (!hash_equals($password, $confirmation)) {
            $error = 'Les deux mots de passe ne correspondent pas.';
        } else {
            try {
                $pdo->beginTransaction();
                $find = $pdo->prepare(
                    'SELECT pr.id, pr.parent_id
                     FROM password_resets_parents pr
                     JOIN parents p ON p.ID_PARENT = pr.parent_id
                     WHERE pr.token_hash = ? AND pr.used_at IS NULL AND pr.expires_at > NOW() AND p.STATUT_PARENT = 1
                     LIMIT 1 FOR UPDATE'
                );
                $find->execute([$tokenHash]);
                $reset = $find->fetch(PDO::FETCH_ASSOC);

                if (!$reset) {
                    throw new RuntimeException('Jeton expiré ou déjà utilisé.');
                }

                $updatePassword = $pdo->prepare('UPDATE parents SET MTPASS_PARENT = ? WHERE ID_PARENT = ?');
                $updatePassword->execute([password_hash($password, PASSWORD_DEFAULT), (int)$reset['parent_id']]);
                $consume = $pdo->prepare('UPDATE password_resets_parents SET used_at = NOW() WHERE id = ? AND used_at IS NULL');
                $consume->execute([(int)$reset['id']]);
                $clearSessions = $pdo->prepare('DELETE FROM sessions_parents WHERE parent_id = ?');
                $clearSessions->execute([(int)$reset['parent_id']]);
                $pdo->commit();

                session_regenerate_id(true);
                $success = true;
            } catch (\Throwable $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                error_log('[PasswordReset] Réinitialisation impossible : ' . $e->getMessage());
                $error = 'Ce lien de réinitialisation est invalide ou a expiré.';
            }
        }
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nouveau mot de passe — Ecole Plus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light d-flex align-items-center min-vh-100">
<main class="container" style="max-width: 500px;">
    <section class="card border-0 shadow-sm p-4 p-md-5">
        <div class="text-center mb-4">
            <i class="fa-solid fa-lock text-success fs-1 mb-3"></i>
            <h1 class="h3">Choisissez un nouveau mot de passe</h1>
        </div>
        <?php if ($success): ?>
            <div class="alert alert-success">Votre mot de passe a été modifié. Vous pouvez maintenant vous connecter.</div>
            <a class="btn btn-success w-100" href="login.php">Se connecter</a>
        <?php elseif ($error !== ''): ?>
            <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <a class="btn btn-outline-secondary w-100" href="forgot_password.php">Demander un nouveau lien</a>
        <?php else: ?>
            <form method="post" novalidate>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)$_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">
                <label for="password" class="form-label">Nouveau mot de passe</label>
                <input type="password" class="form-control mb-3" id="password" name="password" autocomplete="new-password" minlength="8" required>
                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                <input type="password" class="form-control mb-4" id="password_confirmation" name="password_confirmation" autocomplete="new-password" minlength="8" required>
                <button class="btn btn-success w-100" type="submit">Enregistrer le nouveau mot de passe</button>
            </form>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
