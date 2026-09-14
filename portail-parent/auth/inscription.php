<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/session_bootstrap.php';
require_once __DIR__ . '/../services/OtpService.php';
require_once __DIR__ . '/../services/MailService.php';
require_once __DIR__ . '/../services/SmsService.php';
require_once __DIR__ . '/../services/InvitationService.php';

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

$errors  = [];
$success = false;
$step    = 1;
$values  = [
    'prenom'    => '',
    'nom'       => '',
    'login'     => '',
    'telephone' => '',
    'email'     => '',
    'sexe'      => '',
];

$invService = new InvitationService($pdo);

// ─── Récupérer le token ─────────────────────────────────────────────────
$token = trim((string)($_GET['token'] ?? ''));
if ($token === '' && isset($_SESSION['invitation_token'])) {
    $token = $_SESSION['invitation_token'];
}

if ($token === '') {
    $errors[] = 'Lien d\'invitation invalide ou manquant.';
} else {
    $invResult = $invService->validate($token);
    if (!$invResult['ok']) {
        $errors[] = $invResult['error'] ?? 'Lien d\'invitation invalide.';
    } else {
        $_SESSION['invitation_token'] = $token;
        $_SESSION['invitation_id'] = $invResult['invitation_id'];
        $_SESSION['invitation_eleve_id'] = $invResult['eleve_id'];
        $values['email'] = $invResult['email'] ?? '';
    }
}

$invitationData = $invResult['ok'] ?? false ? $invResult : null;

// ─── ÉTAPE 1 : Vérification de l'email ──────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'check_email') {
    header('Content-Type: application/json');
    $email = trim((string)($_POST['email'] ?? ''));
    if ($email === '') {
        echo json_encode(['ok' => false, 'msg' => 'Veuillez saisir votre adresse e-mail.']);
        exit;
    }
    if ($invitationData && strtolower($email) === strtolower($invitationData['email'])) {
        $_SESSION['email_verified'] = true;
        echo json_encode(['ok' => true]);
    } else {
        echo json_encode(['ok' => false, 'msg' => 'Cette adresse e-mail ne correspond pas à celle de l\'invitation.']);
    }
    exit;
}

// ─── ÉTAPE 2 : Création du compte ───────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!isset($_POST['action']) || $_POST['action'] !== 'check_email')) {

    foreach (['prenom', 'nom', 'login', 'telephone', 'email', 'sexe'] as $field) {
        $values[$field] = trim((string)($_POST[$field] ?? ''));
    }
    $password             = (string)($_POST['password'] ?? '');
    $passwordConfirmation = (string)($_POST['password_confirmation'] ?? '');

    // CSRF
    if (!hash_equals((string)$_SESSION['csrf_token'], (string)($_POST['csrf_token'] ?? ''))) {
        $errors[] = 'Votre session a expiré. Veuillez réessayer.';
    }

    // Vérifier que l'email a bien été validé à l'étape 1
    if (empty($_SESSION['email_verified'])) {
        $errors[] = 'Veuillez d\'abord valider votre adresse e-mail.';
    }

    // Vérifier que l'email saisi correspond bien à l'invitation
    if ($invitationData && strtolower($values['email']) !== strtolower($invitationData['email'])) {
        $errors[] = 'L\'adresse e-mail ne correspond pas à celle de l\'invitation.';
    }

    // validations
    if (mb_strlen($values['prenom']) < 2 || mb_strlen($values['prenom']) > 255) {
        $errors[] = 'Le prénom doit contenir entre 2 et 255 caractères.';
    }
    if (mb_strlen($values['nom']) < 2 || mb_strlen($values['nom']) > 255) {
        $errors[] = 'Le nom doit contenir entre 2 et 255 caractères.';
    }
    if (!preg_match('/^[A-Za-z0-9._-]{4,100}$/', $values['login'])) {
        $errors[] = "L'identifiant doit contenir 4 à 100 caractères (lettres, chiffres, points, tirets ou underscores).";
    }
    if (mb_strlen($values['telephone']) < 6 || mb_strlen($values['telephone']) > 100) {
        $errors[] = 'Veuillez saisir un numéro de téléphone valide.';
    }
    if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($values['email']) > 255) {
        $errors[] = 'Veuillez saisir une adresse e-mail valide.';
    }
    if ($values['sexe'] !== '' && !in_array($values['sexe'], ['Masculin', 'Féminin', 'Autre'], true)) {
        $errors[] = 'La valeur de sexe sélectionnée est invalide.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
    } elseif (!hash_equals($password, $passwordConfirmation)) {
        $errors[] = 'Les deux mots de passe ne correspondent pas.';
    }
    if (!isset($_POST['accept_terms'])) {
        $errors[] = 'Vous devez confirmer que les informations fournies sont exactes.';
    }

    // Doublon
    if ($errors === []) {
        $existing = $pdo->prepare(
            'SELECT 1 FROM parents WHERE LOGIN_PARENT = ? OR LOWER(MAIL_PARENT) = LOWER(?) LIMIT 1'
        );
        $existing->execute([$values['login'], $values['email']]);
        if ($existing->fetch()) {
            $errors[] = 'Cet identifiant ou cette adresse e-mail est déjà utilisé.';
        }
    }

    // Création du compte
    if ($errors === []) {
        $eleveId = (int)($_SESSION['invitation_eleve_id'] ?? 0);

        try {
            $pdo->beginTransaction();

            $insertParent = $pdo->prepare(
                'INSERT INTO parents (NOM_PARENT, PRENOM_PARENT, SEXE_PARENT, TEL_PARENT, MAIL_PARENT, LOGIN_PARENT, MTPASS_PARENT, STATUT_PARENT, DATE_CREATION)
                 VALUES (?, ?, ?, ?, ?, ?, ?, 0, NOW())'
            );
            $insertParent->execute([
                $values['nom'],
                $values['prenom'],
                $values['sexe'] !== '' ? $values['sexe'] : null,
                $values['telephone'],
                $values['email'],
                $values['login'],
                password_hash($password, PASSWORD_DEFAULT),
            ]);
            $parentId = (int)$pdo->lastInsertId();

            $insertLink = $pdo->prepare(
                'INSERT INTO parent_eleve (ID_PARENT, ID_ELEVE) VALUES (?, ?)'
            );
            $insertLink->execute([$parentId, $eleveId]);

            $invService->markUsed((int)($_SESSION['invitation_id'] ?? 0));

            $pdo->commit();
            $success = true;

            unset($_SESSION['invitation_token'], $_SESSION['invitation_id'], $_SESSION['invitation_eleve_id'], $_SESSION['email_verified']);
        } catch (\PDOException $e) {
            $pdo->rollBack();
            error_log('[InscriptionParent] Erreur : ' . $e->getMessage());
            $errors[] = 'Impossible de créer le compte pour le moment. Veuillez réessayer.';
        }
    }
}

// ─── Déterminer l'étape courante ────────────────────────────────────────
if ($success) {
    $step = 3;
} elseif (!empty($_SESSION['email_verified'])) {
    $step = 2;
} else {
    $step = 1;
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription parent — Ecole Plus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { background: linear-gradient(135deg, #f8fafc, #eaf4f0); min-height: 100vh; }
        .register-card { max-width: 600px; border: 0; box-shadow: 0 18px 55px rgba(15, 23, 42, .12); }
        .step-indicator { display: flex; justify-content: center; gap: 0; margin-bottom: 2rem; }
        .step-indicator .step { display: flex; align-items: center; gap: .5rem; font-size: .85rem; color: #94a3b8; }
        .step-indicator .step.active { color: #0d9488; font-weight: 600; }
        .step-indicator .step.done { color: #16a34a; }
        .step-indicator .step-num { width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .85rem; border: 2px solid #cbd5e1; color: #94a3b8; }
        .step-indicator .step.active .step-num { border-color: #0d9488; color: #0d9488; background: #f0fdfa; }
        .step-indicator .step.done .step-num { border-color: #16a34a; color: #fff; background: #16a34a; }
        .step-connector { width: 40px; height: 2px; background: #cbd5e1; align-self: center; }
        .step-connector.done { background: #16a34a; }
        .eleve-badge { background: #f0fdfa; border: 1px solid #99f6e4; border-radius: .5rem; padding: .75rem 1rem; }
        .invitation-badge { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: .5rem; padding: .75rem 1rem; margin-bottom: 1.5rem; }
        .email-check { position: relative; }
        .email-check .spinner-border { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); display: none; }
        #step2 { display: none; }
    </style>
</head>
<body class="py-5">
<main class="container">
    <section class="card register-card mx-auto">
        <div class="card-body p-4 p-md-5">

            <div class="text-center mb-4">
                <i class="fa-solid fa-user-plus text-success fs-1 mb-3"></i>
                <h1 class="h3 mb-2">Inscription parent</h1>
                <p class="text-muted mb-0">Créez votre compte pour suivre la scolarité de votre enfant.</p>
            </div>

            <?php if ($invitationData): ?>
                <div class="invitation-badge">
                    <i class="fa-solid fa-envelope-open-text text-primary me-2"></i>
                    Votre enfant <strong><?= htmlspecialchars($invitationData['eleve_prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars($invitationData['eleve_nom'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong> est inscrit dans l'établissement.
                </div>
            <?php endif; ?>

            <!-- Indicateur d'étapes -->
            <div class="step-indicator" id="stepIndicator">
                <div class="step <?= $step >= 1 ? ($step > 1 ? 'done' : 'active') : '' ?>" id="ind1">
                    <div class="step-num"><?= $step > 1 ? '<i class="fa-solid fa-check"></i>' : '1' ?></div>
                    <span>Email</span>
                </div>
                <div class="step-connector <?= $step > 1 ? 'done' : '' ?>" id="conn1"></div>
                <div class="step <?= $step >= 2 ? ($step > 2 ? 'done' : 'active') : '' ?>" id="ind2">
                    <div class="step-num"><?= $step > 2 ? '<i class="fa-solid fa-check"></i>' : '2' ?></div>
                    <span>Vos informations</span>
                </div>
            </div>

            <?php if ($success): ?>
                <!-- ══════ SUCCÈS ══════ -->
                <div class="text-center py-4">
                    <div class="mb-3">
                        <i class="fa-solid fa-circle-check text-success" style="font-size: 3.5rem;"></i>
                    </div>
                    <h4 class="text-success mb-2">Compte créé avec succès !</h4>
                    <p class="text-muted mb-1">Votre compte parent a été créé et lié à votre enfant.</p>
                    <p class="text-muted mb-4">L'administration doit activer votre compte avant votre première connexion.</p>
                    <a href="login.php" class="btn btn-success px-4">
                        <i class="fa-solid fa-right-to-bracket me-1"></i> Retour à la connexion
                    </a>
                </div>

            <?php else: ?>

                <?php if ($errors !== []): ?>
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0 ps-3">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post" novalidate id="inscriptionForm">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)$_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

                    <!-- ══════ ÉTAPE 1 : Vérification email ══════ -->
                    <div id="step1" <?= $step === 2 ? 'style="display:none"' : '' ?>>
                        <h5 class="mb-3"><i class="fa-solid fa-envelope me-2 text-info"></i>Vérification de votre e-mail</h5>
                        <p class="text-muted small mb-3">Saisissez l'adresse e-mail sur laquelle vous avez reçu l'invitation.</p>

                        <div class="mb-3">
                            <label for="email_check" class="form-label fw-semibold">Adresse e-mail <span class="text-danger">*</span></label>
                            <div class="email-check">
                                <input id="email_check" name="email_check" type="email" class="form-control form-control-lg" value="<?= htmlspecialchars($values['email'], ENT_QUOTES, 'UTF-8') ?>" autocomplete="email" required>
                                <div class="spinner-border spinner-border-sm text-success" id="emailSpinner"></div>
                            </div>
                            <div id="emailResult" class="mt-2"></div>
                        </div>

                        <button type="button" class="btn btn-info text-white w-100 mt-2" id="btnStep1">
                            Vérifier <i class="fa-solid fa-arrow-right ms-1"></i>
                        </button>
                    </div>

                    <!-- ══════ ÉTAPE 2 : Infos parent ══════ -->
                    <div id="step2" <?= $step === 1 ? 'style="display:block"' : '' ?>>
                        <h5 class="mb-3"><i class="fa-solid fa-user me-2 text-success"></i>Vos informations</h5>

                        <div id="eleveInfoBox" class="eleve-badge mb-3">
                            <i class="fa-solid fa-graduation-cap text-info me-1"></i>
                            <span>
                                <?= htmlspecialchars($invitationData['eleve_prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                <?= htmlspecialchars($invitationData['eleve_nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input id="prenom" name="prenom" class="form-control" value="<?= htmlspecialchars($values['prenom'], ENT_QUOTES, 'UTF-8') ?>" autocomplete="given-name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input id="nom" name="nom" class="form-control" value="<?= htmlspecialchars($values['nom'], ENT_QUOTES, 'UTF-8') ?>" autocomplete="family-name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="login" class="form-label">Identifiant <span class="text-danger">*</span></label>
                                <input id="login" name="login" class="form-control" value="<?= htmlspecialchars($values['login'], ENT_QUOTES, 'UTF-8') ?>" autocomplete="username" required>
                                <div class="form-text">4 à 100 caractères : lettres, chiffres, points, tirets ou underscores.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input id="telephone" name="telephone" type="tel" class="form-control" value="<?= htmlspecialchars($values['telephone'], ENT_QUOTES, 'UTF-8') ?>" autocomplete="tel" required>
                            </div>
                            <div class="col-md-8">
                                <label for="email" class="form-label">Adresse e-mail <span class="text-danger">*</span></label>
                                <input id="email" name="email" type="email" class="form-control" value="<?= htmlspecialchars($values['email'], ENT_QUOTES, 'UTF-8') ?>" autocomplete="email" readonly required>
                            </div>
                            <div class="col-md-4">
                                <label for="sexe" class="form-label">Sexe <span class="text-muted">(facultatif)</span></label>
                                <select id="sexe" name="sexe" class="form-select">
                                    <option value="">Non précisé</option>
                                    <?php foreach (['Masculin', 'Féminin', 'Autre'] as $sexe): ?>
                                        <option value="<?= $sexe ?>" <?= $values['sexe'] === $sexe ? 'selected' : '' ?>><?= $sexe ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                <input id="password" name="password" type="password" class="form-control" autocomplete="new-password" minlength="8" required>
                                <div class="form-text">Minimum 8 caractères.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" minlength="8" required>
                            </div>
                        </div>

                        <div class="form-check mt-4">
                            <input id="accept_terms" name="accept_terms" class="form-check-input" type="checkbox" value="1" required>
                            <label for="accept_terms" class="form-check-label">Je confirme que les informations fournies sont exactes.</label>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="button" class="btn btn-outline-secondary" id="btnBack">
                                <i class="fa-solid fa-arrow-left me-1"></i> Retour
                            </button>
                            <button type="submit" class="btn btn-success flex-fill">
                                <i class="fa-solid fa-paper-plane me-1"></i> Créer mon compte
                            </button>
                        </div>
                    </div>
                </form>

                <p class="text-center text-muted small mt-4 mb-0">Vous avez déjà un compte ? <a href="login.php">Connectez-vous</a>.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<script>
(function() {
    const form        = document.getElementById('inscriptionForm');
    if (!form) return;

    const step1       = document.getElementById('step1');
    const step2       = document.getElementById('step2');
    const ind1        = document.getElementById('ind1');
    const ind2        = document.getElementById('ind2');
    const conn1       = document.getElementById('conn1');
    const emailInput  = document.getElementById('email_check');
    const emailResult = document.getElementById('emailResult');
    const emailSpinner= document.getElementById('emailSpinner');
    const btnStep1    = document.getElementById('btnStep1');
    const btnBack     = document.getElementById('btnBack');
    const currentStep = <?= $step ?>;

    let emailOk = false;

    function goStep2() {
        step1.style.display = 'none';
        step2.style.display = 'block';
        ind1.classList.remove('active');
        ind1.classList.add('done');
        ind1.querySelector('.step-num').innerHTML = '<i class="fa-solid fa-check"></i>';
        conn1.classList.add('done');
        ind2.classList.add('active');
        document.getElementById('email').value = emailInput.value;
    }

    function goStep1() {
        step2.style.display = 'none';
        step1.style.display = 'block';
        ind2.classList.remove('active');
        ind1.classList.remove('done');
        ind1.classList.add('active');
        ind1.querySelector('.step-num').textContent = '1';
        conn1.classList.remove('done');
    }

    function checkEmail() {
        const val = emailInput.value.trim();
        if (val === '') { emailResult.innerHTML = ''; emailOk = false; return; }
        emailSpinner.style.display = 'block';
        emailResult.innerHTML = '';

        const fd = new FormData();
        fd.append('action', 'check_email');
        fd.append('email', val);
        fd.append('csrf_token', form.querySelector('[name=csrf_token]').value);

        fetch('', { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                emailSpinner.style.display = 'none';
                if (data.ok) {
                    emailResult.innerHTML = '<div class="text-success"><i class="fa-solid fa-circle-check me-1"></i>Adresse e-mail confirmée</div>';
                    emailOk = true;
                } else {
                    emailResult.innerHTML = '<div class="text-danger"><i class="fa-solid fa-circle-xmark me-1"></i>' + data.msg + '</div>';
                    emailOk = false;
                }
            })
            .catch(() => { emailSpinner.style.display = 'none'; });
    }

    emailInput.addEventListener('blur', checkEmail);
    emailInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); checkEmail(); }
    });

    btnStep1.addEventListener('click', function() {
        checkEmail();
        setTimeout(function() {
            if (emailOk) { goStep2(); }
        }, 500);
    });

    btnBack.addEventListener('click', goStep1);

    // Si on revient à l'étape 2, s'assurer que step2 est visible
    if (currentStep === 2) {
        step1.style.display = 'none';
        step2.style.display = 'block';
    }
})();
</script>
</body>
</html>
