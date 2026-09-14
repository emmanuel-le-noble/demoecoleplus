<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/session_bootstrap.php';
require_once __DIR__ . '/../services/OtpService.php';
require_once __DIR__ . '/../services/MailService.php';
require_once __DIR__ . '/../services/SmsService.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (isset($_SESSION['id_parent']) || isset($_SESSION['parent_id'])) { header('Location: ../dashboard/dashboard.php'); exit; }

$erreur = null;
$oauthMessages = [
    'unavailable' => 'La connexion Google n\'est pas encore configurée.',
    'cancelled' => 'La connexion Google a été annulée.',
    'invalid_state' => 'La demande de connexion Google a expiré. Veuillez réessayer.',
    'not_linked' => 'Aucun compte parent actif ne correspond à cette adresse Google.',
    'error' => 'La connexion Google a échoué. Veuillez réessayer.',
];
$oauthStatus = (string)($_GET['oauth'] ?? '');
if (isset($oauthMessages[$oauthStatus])) { $erreur = $oauthMessages[$oauthStatus]; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim((string)($_POST['login'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($login === '' || $password === '') {
        $erreur = 'Veuillez remplir tous les champs.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM parents WHERE LOGIN_PARENT = ? AND STATUT_PARENT = 1 LIMIT 1');
        $stmt->execute([$login]);
        $parent = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$parent || !password_verify($password, (string)$parent['MTPASS_PARENT'])) {
            $erreur = 'Identifiant ou mot de passe incorrect.';
        } else {
            $parentId = (int)$parent['ID_PARENT'];

            $otpEnabled = filter_var(getenv('OTP_ENABLED') ?: 'true', FILTER_VALIDATE_BOOLEAN);

            if (!$otpEnabled) {
                session_regenerate_id(true);
                $_SESSION['id_parent'] = $parentId;
                $_SESSION['parent_id'] = $parentId;
                $_SESSION['parent_nom'] = trim((string)$parent['NOM_PARENT'] . ' ' . (string)$parent['PRENOM_PARENT']);
                header('Location: ../dashboard/dashboard.php');
                exit;
            }

            $otpService = new OtpService($pdo);
            $mailService = new MailService();
            $smsService = new SmsService();

            $otpResult = $otpService->generate($parentId, 'login', $parent['MAIL_PARENT'] ?? null, $parent['TEL_PARENT'] ?? null);

            $mailService->sendOtpEmail($parent['MAIL_PARENT'], $otpResult['code'], 'login');

            $smsSent = false;
            if ($smsService->isEnabled() && !empty($parent['TEL_PARENT'])) {
                $smsResult = $smsService->sendOtpSms($parent['TEL_PARENT'], $otpResult['code'], 'login');
                $smsSent = $smsResult['sent'] ?? false;
            }

            $_SESSION['otp_parent_id'] = $parentId;
            $_SESSION['otp_type'] = 'login';
            $_SESSION['otp_email'] = $parent['MAIL_PARENT'] ?? '';
            $_SESSION['otp_telephone'] = $parent['TEL_PARENT'] ?? '';
            $_SESSION['otp_sms_sent'] = $smsSent;

            header('Location: otp.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ecole Plus — Espace Parents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        :root { --login-purple:#0f766e; --login-ink:#17313d; }
        body { min-height:100vh; margin:0; color:var(--login-ink); background:radial-gradient(circle at 10% 0%,rgba(255,255,255,.18),transparent 27rem),linear-gradient(115deg,#164e63 0%,#0f766e 46%,#d9efeb 72%,#edf3f7 100%); }
        .login-page { min-height:100vh; display:grid; grid-template-columns:minmax(0,1fr) 430px; align-items:center; gap:clamp(3rem,10vw,12rem); max-width:1340px; margin:auto; padding:2.5rem; }
        .login-showcase { color:#fff; max-width:520px; padding-left:clamp(0rem,6vw,5rem); }
        .brand-mark { width:76px; height:76px; display:grid; place-items:center; border:1px solid rgba(255,255,255,.35); border-radius:23px; background:rgba(255,255,255,.16); box-shadow:0 16px 35px rgba(34,55,143,.16); font-size:1.55rem; font-weight:700; letter-spacing:-.08em; }
        .login-showcase h1 { margin:2rem 0 .7rem; color:#fff; font-size:clamp(2.1rem,4vw,3.65rem); font-weight:700; letter-spacing:-.06em; }
        .login-showcase > p { margin:0; max-width:440px; color:rgba(255,255,255,.88); font-size:1.06rem; line-height:1.7; }
        .feature-list { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:.85rem; margin-top:2.4rem; }
        .feature-item { display:flex; align-items:center; gap:.7rem; padding:.85rem; border:1px solid rgba(255,255,255,.18); border-radius:14px; background:rgba(255,255,255,.10); color:#fff; font-size:.86rem; font-weight:500; backdrop-filter:blur(8px); }
        .feature-item i { width:30px; height:30px; display:grid; place-items:center; border-radius:9px; background:rgba(255,255,255,.18); }
        .login-panel { width:100%; padding:2.1rem 1.6rem 1.4rem; border:1px solid rgba(255,255,255,.82); border-radius:20px; background:rgba(255,255,255,.72); box-shadow:0 24px 60px rgba(41,58,129,.16); backdrop-filter:blur(22px) saturate(135%); }
        .panel-logo { width:54px; height:54px; display:grid; place-items:center; margin:0 auto 1rem; border-radius:16px; background:linear-gradient(135deg,var(--login-purple),#0e7490); color:#fff; font-weight:700; }
        .login-panel h2 { color:var(--login-ink); font-weight:700; font-size:1.5rem; letter-spacing:-.04em; }
        .login-panel .lead { color:#64748b; font-size:.9rem; }
        .login-panel .form-label { color:#475569; font-size:.8rem; font-weight:600; }
        .login-panel .input-group-text,.login-panel .form-control { height:45px; border-color:#e4e8f1; background:rgba(255,255,255,.83); color:var(--login-ink); }
        .login-panel .input-group-text { border-right:0; color:#94a3b8; }
        .login-panel .form-control { border-left:0; box-shadow:none; }
        .login-panel .form-control:focus { border-color:#a5b4fc; background:#fff; }
        .login-panel .input-group:focus-within { outline:3px solid rgba(99,102,241,.15); border-radius:10px; }
        .btn-login { height:47px; border:0; border-radius:10px; background:linear-gradient(100deg,var(--login-purple),#0e9388); color:#fff; font-size:.88rem; font-weight:600; box-shadow:0 10px 22px rgba(15,118,110,.22); }
        .btn-login:hover { color:#fff; background:linear-gradient(100deg,#0b5f59,#0f766e); transform:translateY(-1px); }
        .or-divider { display:flex; align-items:center; gap:.75rem; margin:1rem 0; color:#94a3b8; font-size:.75rem; }
        .or-divider::before,.or-divider::after { content:''; flex:1; border-top:1px solid #dce1ea; }
        .btn-google { height:43px; border-color:#dce1ea; border-radius:10px; background:rgba(255,255,255,.72); color:#273246; font-size:.84rem; font-weight:500; }
        .btn-google:hover { border-color:#bfc7d8; background:#fff; color:#273246; }
        .login-link { color:#0f766e; font-weight:600; text-decoration:none; }
        .login-link:hover { color:#0b5f59; text-decoration:underline; }
        .login-footer { color:rgba(71,85,105,.75); font-size:.72rem; text-align:center; }
        @media (max-width:991.98px) { .login-page { grid-template-columns:minmax(0,430px); justify-content:center; gap:0; padding:1.5rem; } .login-showcase { display:none; } }
        @media (max-width:460px) { .login-page { padding:1rem; } .login-panel { padding:1.8rem 1.2rem 1.25rem; } }
    </style>
</head>
<body>
<main class="login-page">
    <section class="login-showcase" aria-label="Présentation Ecole Plus">
        <div class="brand-mark">EP</div>
        <h1>Ecole Plus<br>Portail Parent</h1>
        <p>Suivez la scolarité de vos enfants simplement, avec toutes les informations importantes réunies dans un espace sécurisé.</p>
        <div class="feature-list">
            <div class="feature-item"><i class="fa-solid fa-chart-line"></i><span>Résultats scolaires</span></div>
            <div class="feature-item"><i class="fa-solid fa-calendar-check"></i><span>Absences &amp; retards</span></div>
            <div class="feature-item"><i class="fa-solid fa-book-open"></i><span>Devoirs &amp; cours</span></div>
            <div class="feature-item"><i class="fa-solid fa-wallet"></i><span>Suivi des paiements</span></div>
            <div class="feature-item"><i class="fa-regular fa-comment-dots"></i><span>Messagerie école</span></div>
            <div class="feature-item"><i class="fa-solid fa-bell"></i><span>Informations utiles</span></div>
        </div>
    </section>
    <section class="login-panel text-center">
        <div class="panel-logo">EP</div>
        <h2 class="mb-1">Bienvenue</h2>
        <p class="lead mb-4">Connectez-vous à votre espace parent</p>
        <?php if ($erreur): ?>
            <div class="alert alert-danger text-start small" role="alert"><i class="fa-solid fa-triangle-exclamation me-2"></i><?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <form action="" method="post" autocomplete="on" class="text-start">
            <div class="mb-3"><label for="login" class="form-label">Nom d'utilisateur</label><div class="input-group"><span class="input-group-text"><i class="fa-solid fa-user"></i></span><input type="text" name="login" id="login" class="form-control" placeholder="Entrez votre identifiant" autocomplete="username" required></div></div>
            <div class="mb-2"><label for="password" class="form-label">Mot de passe</label><div class="input-group"><span class="input-group-text"><i class="fa-solid fa-lock"></i></span><input type="password" name="password" id="password" class="form-control" placeholder="Entrez votre mot de passe" autocomplete="current-password" required></div></div>
            <div class="text-end mb-3"><a href="forgot_password.php" class="small login-link">Mot de passe oublié ?</a></div>
            <button type="submit" class="btn btn-login w-100">Se connecter</button>
        </form>
        <div class="or-divider">ou</div>
        <a href="google_login.php" class="btn btn-google w-100"><i class="fa-brands fa-google me-2 text-danger"></i>Continuer avec Google</a>
        <p class="login-footer mb-0">&copy; <?= date('Y') ?> Ecole Plus. Tous droits réservés.</p>
    </section>
</main>
</body>
</html>
