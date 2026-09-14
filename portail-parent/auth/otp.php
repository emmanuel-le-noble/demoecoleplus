<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/session_bootstrap.php';
require_once __DIR__ . '/../services/OtpService.php';
require_once __DIR__ . '/../services/MailService.php';
require_once __DIR__ . '/../services/SmsService.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }

$otpType = $_SESSION['otp_type'] ?? null;
$parentId = $_SESSION['otp_parent_id'] ?? null;
$otpEmail = $_SESSION['otp_email'] ?? '';
$otpTelephone = $_SESSION['otp_telephone'] ?? '';

if (!$otpType || !$parentId) {
    header('Location: login.php');
    exit;
}

$otpService = new OtpService($pdo);
$mailService = new MailService();
$smsService = new SmsService();

$erreur = null;
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'verify';

    if ($action === 'resend') {
        $canResend = $otpService->canResend($parentId, $otpType);
        if (!$canResend['allowed']) {
            $erreur = 'Veuillez patienter ' . $canResend['wait_seconds'] . ' secondes avant de redemander un code.';
        } else {
            $otpResult = $otpService->generate($parentId, $otpType, $otpEmail, $otpTelephone);
            $mailService->sendOtpEmail($otpEmail, $otpResult['code'], $otpType);
            $smsSent = false;
            if ($smsService->isEnabled() && $otpTelephone !== '') {
                $smsResult = $smsService->sendOtpSms($otpTelephone, $otpResult['code'], $otpType);
                $smsSent = $smsResult['sent'] ?? false;
            }
            $_SESSION['otp_sms_sent'] = $smsSent;
            header('Content-Type: application/json');
            echo json_encode(['ok' => true, 'sms_sent' => $smsSent]);
            exit;
        }
    } elseif ($action === 'verify') {
        $code = trim((string)($_POST['code'] ?? ''));
        if (strlen($code) < 4) {
            $erreur = 'Veuillez saisir le code complet.';
        } else {
            $result = $otpService->verify($parentId, $otpType, $code);
            if ($result['ok']) {
                if ($otpType === 'login') {
                    session_regenerate_id(true);
                    $_SESSION['id_parent'] = $parentId;
                    $_SESSION['parent_id'] = $parentId;

                    $stmt = $pdo->prepare('SELECT NOM_PARENT, PRENOM_PARENT FROM parents WHERE ID_PARENT = ?');
                    $stmt->execute([$parentId]);
                    $parent = $stmt->fetch(PDO::FETCH_ASSOC);
                    $_SESSION['parent_nom'] = trim((string)($parent['NOM_PARENT'] ?? '') . ' ' . (string)($parent['PRENOM_PARENT'] ?? ''));

                    unset($_SESSION['otp_parent_id'], $_SESSION['otp_type'], $_SESSION['otp_email'], $_SESSION['otp_telephone'], $_SESSION['otp_sms_sent']);
                    header('Location: ../dashboard/dashboard.php');
                    exit;
                } elseif ($otpType === 'registration') {
                    unset($_SESSION['otp_type']);
                    $_SESSION['otp_validated'] = true;
                    header('Location: inscription.php');
                    exit;
                }
            } else {
                $erreur = $result['error'] ?? 'Code incorrect.';
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
    <title>Vérification OTP — Ecole Plus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        :root { --otp-purple:#0f766e; --otp-ink:#17313d; }
        body { min-height:100vh; margin:0; color:var(--otp-ink); background:radial-gradient(circle at 10% 0%,rgba(255,255,255,.18),transparent 27rem),linear-gradient(115deg,#164e63 0%,#0f766e 46%,#d9efeb 72%,#edf3f7 100%); display:flex; align-items:center; justify-content:center; }
        .otp-card { max-width:440px; width:100%; padding:2.5rem 2rem; border:1px solid rgba(255,255,255,.82); border-radius:20px; background:rgba(255,255,255,.72); box-shadow:0 24px 60px rgba(41,58,129,.16); backdrop-filter:blur(22px) saturate(135%); }
        .otp-icon { width:72px; height:72px; display:grid; place-items:center; margin:0 auto 1.2rem; border-radius:20px; background:linear-gradient(135deg,#0f766e,#0e7490); color:#fff; font-size:1.8rem; }
        .otp-card h2 { color:var(--otp-ink); font-weight:700; font-size:1.4rem; text-align:center; }
        .otp-card .lead { color:#64748b; font-size:.88rem; text-align:center; }
        .otp-input { width:100%; height:60px; text-align:center; font-size:1.8rem; font-weight:700; letter-spacing:12px; border:2px solid #e2e8f0; border-radius:12px; background:rgba(255,255,255,.83); color:var(--otp-ink); }
        .otp-input:focus { border-color:#0d9488; box-shadow:0 0 0 3px rgba(13,148,136,.15); outline:none; }
        .btn-verify { height:48px; border:0; border-radius:10px; background:linear-gradient(100deg,var(--otp-purple),#0e9388); color:#fff; font-size:.9rem; font-weight:600; box-shadow:0 8px 20px rgba(15,118,110,.22); }
        .btn-verify:hover { background:linear-gradient(100deg,#0b5f59,#0f766e); color:#fff; }
        .resend-link { color:#0f766e; font-weight:600; text-decoration:none; cursor:pointer; }
        .resend-link:hover { color:#0b5f59; text-decoration:underline; }
        .timer { color:#94a3b8; font-size:.82rem; }
        .masked-info { color:#64748b; font-size:.85rem; text-align:center; margin-bottom:1.2rem; }
    </style>
</head>
<body>
<main class="otp-card">
    <div class="otp-icon"><i class="fa-solid fa-shield-halved"></i></div>
    <h2>Vérification de sécurité</h2>
    <p class="lead mb-3">Un code à usage unique vous a été envoyé.</p>
    <p class="masked-info" id="maskedInfo">
        <?php if ($otpType === 'login'): ?>
            Code envoyé sur <strong><?= htmlspecialchars(maskContact($otpTelephone), ENT_QUOTES, 'UTF-8') ?></strong>
            <?php if (!empty($otpEmail)): ?>
                et <strong><?= htmlspecialchars(maskEmail($otpEmail), ENT_QUOTES, 'UTF-8') ?></strong>
            <?php endif; ?>
        <?php else: ?>
            Code envoyé sur vos coordonnées enregistrées.
        <?php endif; ?>
    </p>

    <?php if ($erreur): ?>
        <div class="alert alert-danger text-center small" role="alert"><?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="post" id="otpForm" class="text-center">
        <input type="hidden" name="action" value="verify">
        <input type="text" name="code" id="otpCode" class="otp-input mb-3" maxlength="8" inputmode="numeric" pattern="[0-9]*" placeholder="_ _ _ _ _ _" autocomplete="one-time-code" autofocus required>
        <button type="submit" class="btn btn-verify w-100 mb-3" id="btnVerify">
            <i class="fa-solid fa-check me-1"></i> Vérifier le code
        </button>
    </form>

    <div class="text-center">
        <span class="timer" id="timerText">Renvoyer le code dans <strong id="countdown">60</strong>s</span>
        <a href="#" id="resendBtn" class="resend-link" style="display:none;" onclick="resendOtp(); return false;">
            <i class="fa-solid fa-rotate me-1"></i> Renvoyer le code
        </a>
    </div>

    <div class="text-center mt-4">
        <a href="login.php" class="text-muted small text-decoration-none"><i class="fa-solid fa-arrow-left me-1"></i> Retour à la connexion</a>
    </div>
</main>

<script>
(function() {
    let countdown = 60;
    const countdownEl = document.getElementById('countdown');
    const timerText = document.getElementById('timerText');
    const resendBtn = document.getElementById('resendBtn');
    const form = document.getElementById('otpForm');
    const codeInput = document.getElementById('otpCode');

    const interval = setInterval(function() {
        countdown--;
        countdownEl.textContent = countdown;
        if (countdown <= 0) {
            clearInterval(interval);
            timerText.style.display = 'none';
            resendBtn.style.display = 'inline';
        }
    }, 1000);

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const code = codeInput.value.replace(/\s/g, '');
        if (code.length < 4) { return; }
        codeInput.value = code;
        form.submit();
    });

    window.resendOtp = function() {
        const fd = new FormData();
        fd.append('action', 'resend');
        fetch('otp.php', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (data.ok) {
                    countdown = 60;
                    timerText.style.display = 'inline';
                    resendBtn.style.display = 'none';
                    countdownEl.textContent = countdown;
                    const newInterval = setInterval(function() {
                        countdown--;
                        countdownEl.textContent = countdown;
                        if (countdown <= 0) {
                            clearInterval(newInterval);
                            timerText.style.display = 'none';
                            resendBtn.style.display = 'inline';
                        }
                    }, 1000);
                }
            })
            .catch(() => {});
    };
})();
</script>
</body>
</html>
<?php

function maskContact(string $contact): string
{
    $contact = trim($contact);
    if ($contact === '') return '***';
    $len = mb_strlen($contact);
    if ($len <= 4) return str_repeat('*', $len);
    return mb_substr($contact, 0, 2) . str_repeat('*', $len - 4) . mb_substr($contact, -2);
}

function maskEmail(string $email): string
{
    $parts = explode('@', $email);
    if (count($parts) !== 2) return '***';
    $local = $parts[0];
    $domain = $parts[1];
    if (mb_strlen($local) <= 2) return $local . '@' . $domain;
    return mb_substr($local, 0, 2) . str_repeat('*', mb_strlen($local) - 2) . '@' . $domain;
}
