<?php
// ==========================================================================
// 1. INCLUSION DU FICHIER DE SESSION & CONFIG
// ==========================================================================
require_once __DIR__ . '/../includes/session.php'; // Fournit déjà $pdo et $parent_id

// Récupération des informations fraîches du parent depuis la base de données
$stmtParent = $pdo->prepare("SELECT * FROM parents WHERE ID_PARENT = ? LIMIT 1");
$stmtParent->execute([$parent_id]);
$parent_info = $stmtParent->fetch(PDO::FETCH_ASSOC);

if (!$parent_info) {
    die("Erreur : Impossible de charger les informations de votre profil.");
}

// ==========================================================================
// 2. TRAITEMENT DU FORMULAIRE DE MISE À JOUR
// ==========================================================================
$message_success = "";
$message_error = "";

if (isset($_GET['statut'], $_GET['msg'])) {
    $flashMessage = (string)$_GET['msg'];
    if ($_GET['statut'] === 'success') {
        $message_success = $flashMessage;
    } elseif ($_GET['statut'] === 'error') {
        $message_error = $flashMessage;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    // Vérification du jeton CSRF pour bloquer les attaques cross-site
    if (!isset($_POST['csrf_token']) || !hash_equals((string)($_SESSION['csrf_token'] ?? ''), (string)$_POST['csrf_token'])) {
        $message_error = "Erreur de sécurité : Jeton CSRF invalide ou expiré.";
    } else {
        $tel = trim($_POST['tel_parent'] ?? '');
        $mail = trim($_POST['mail_parent'] ?? '');
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Validation de base
        if (empty($tel) || !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $message_error = "Le numéro de téléphone et l'adresse email sont obligatoires.";
        } else {
        try {
            // Cas 1 : Le parent veut aussi changer son mot de passe
            if (!empty($new_password)) {
                // Vérification du mot de passe actuel (uniquement via password_verify — fallback clair supprimé)
                if (!password_verify($current_password, (string)$parent_info['MTPASS_PARENT'])) {
                    $message_error = "Le mot de passe actuel est incorrect.";
                } elseif (strlen($new_password) < 8) {
                    $message_error = "Le nouveau mot de passe doit contenir au moins 8 caractères.";
                } elseif ($new_password !== $confirm_password) {
                    $message_error = "Le nouveau mot de passe et sa confirmation ne correspondent pas.";
                } else {
                    // Hachage sécurisé du nouveau mot de passe
                    $password_hashed = password_hash($new_password, PASSWORD_BCRYPT);
                    
                    $stmtUpdate = $pdo->prepare("UPDATE parents SET TEL_PARENT = ?, MAIL_PARENT = ?, MTPASS_PARENT = ? WHERE ID_PARENT = ?");
                    $stmtUpdate->execute([$tel, $mail, $password_hashed, $parent_id]);
                    // Redirection toast
                    header("Location: profile.php?statut=success&msg=" . urlencode("Votre profil et mot de passe ont été mis à jour avec succès."));
                    exit;
                }
            } else {
                // Cas 2 : Mise à jour simple (sans changement de mot de passe)
                $stmtUpdate = $pdo->prepare("UPDATE parents SET TEL_PARENT = ?, MAIL_PARENT = ? WHERE ID_PARENT = ?");
                $stmtUpdate->execute([$tel, $mail, $parent_id]);
                // Redirection toast
                header("Location: profile.php?statut=success&msg=" . urlencode("Vos informations personnelles ont été mises à jour."));
                exit;
            }

        } catch (\Throwable $e) {
            error_log('[Profile] Mise à jour impossible : ' . $e->getMessage());
            $message_error = "Une erreur est survenue lors de la mise à jour.";
        }
    }
    } // Fermeture du else CSRF

    // Si on est encore là, c'est qu'il y a une erreur : redirection avec message d'erreur
    if (!empty($message_error)) {
        header("Location: profile.php?statut=error&msg=" . urlencode($message_error));
        exit;
    }
}

include __DIR__ . '/../includes/header.php'; 
include __DIR__ . '/../includes/slidebar.php'; 
?>

<main class="main-content">
    <?php include __DIR__ . '/../includes/topbar.php'; ?>

    <div class="mb-4">
        <h3 class="fw-bold m-0">Mon Profil Parent</h3>
        <p class="text-muted small m-0">Gérez vos informations de contact et la sécurité de votre compte utilisateur.</p>
    </div>

    <?php if(!empty($message_success)): ?>
        <div class="alert alert-success bg-success bg-opacity-10 text-success border-0 small mb-4"><?= htmlspecialchars($message_success, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <?php if(!empty($message_error)): ?>
        <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border-0 small mb-4"><?= htmlspecialchars($message_error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="liquid-card p-4 text-center">
                <div class="mb-3 d-inline-block position-relative">
                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center border border-success border-opacity-25" style="width: 100px; height: 100px; margin: 0 auto;">
                        <i class="fa-solid fa-user-shield text-success fs-1"></i>
                    </div>
                </div>
                
                <h5 class="fw-bold m-0"><?= htmlspecialchars($parent_info['PRENOM_PARENT'] . ' ' . $parent_info['NOM_PARENT'], ENT_QUOTES, 'UTF-8') ?></h5>
                <span class="badge bg-secondary bg-opacity-10 text-muted px-3 py-1 rounded-5 small mt-2">Espace Parent</span>
                
                <hr class="my-4 border-secondary border-opacity-25">

                <div class="text-start">
                    <div class="mb-2 small text-muted">
                        <i class="fa-regular fa-id-card me-2 text-success"></i> Identifiant de connexion : <br>
                        <strong class="text-reset ps-4"><?= htmlspecialchars($parent_info['LOGIN_PARENT'], ENT_QUOTES, 'UTF-8') ?></strong>
                    </div>
                    <div class="mb-2 small text-muted">
                        <i class="fa-regular fa-clock me-2 text-success"></i> Membre depuis le : <br>
                        <strong class="text-reset ps-4"><?php $dateCreation = strtotime($parent_info['DATE_CREATION'] ?? ''); echo $dateCreation ? date('d/m/Y', $dateCreation) : 'N/A'; ?></strong>
                    </div>
                    <div class="small text-muted">
                        <i class="fa-solid fa-graduation-cap me-2 text-success"></i> Enfants à charge : <br>
                        <strong class="text-reset ps-4"><?= isset($mes_enfants) ? count($mes_enfants) : 0 ?> élève(s)</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="liquid-card p-4">
                <h5 class="fw-bold mb-4 border-bottom border-secondary border-opacity-10 pb-2">Modifier mes informations</h5>
                
                <form method="POST" action="">
                    <!-- Jeton de sécurité CSRF -->
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    
                    <h6 class="text-success fw-bold small text-uppercase tracking-wider mb-3">Coordonnées</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Numéro de téléphone *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-secondary bg-opacity-10 border-secondary border-opacity-50 text-muted"><i class="fa-solid fa-phone"></i></span>
                                <input type="text" name="tel_parent" class="form-control bg-secondary bg-opacity-10 border-secondary border-opacity-50" value="<?= htmlspecialchars($parent_info['TEL_PARENT'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Adresse Email *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-secondary bg-opacity-10 border-secondary border-opacity-50 text-muted"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" name="mail_parent" class="form-control bg-secondary bg-opacity-10 border-secondary border-opacity-50" value="<?= htmlspecialchars($parent_info['MAIL_PARENT'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                        </div>
                    </div>

                    <h6 class="text-danger fw-bold small text-uppercase tracking-wider mb-3">Sécurité &amp; Mot de passe (Optionnel)</h6>
                    <p class="text-muted small mb-3" style="font-size: 11px;">Laissez ces champs vides si vous ne souhaitez pas modifier votre mot de passe actuel.</p>
                    
                    <div class="mb-3">
                        <label class="form-label small text-muted">Mot de passe actuel</label>
                        <input type="password" name="current_password" class="form-control bg-secondary bg-opacity-10 border-secondary border-opacity-50" placeholder="••••••••">
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Nouveau mot de passe</label>
                            <input type="password" name="new_password" class="form-control bg-secondary bg-opacity-10 border-secondary border-opacity-50" placeholder="Minimum 6 caractères">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted">Confirmer le nouveau mot de passe</label>
                            <input type="password" name="confirm_password" class="form-control bg-secondary bg-opacity-10 border-secondary border-opacity-50" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="text-end border-top border-secondary border-opacity-10 pt-3">
                        <button type="submit" name="update_profile" class="btn btn-success px-4 fw-semibold small">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Enregistrer les modifications
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
