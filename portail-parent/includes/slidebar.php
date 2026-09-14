<?php
/**
 * slidebar.php — Barre latérale de navigation Desktop
 * 
 * Ecole Plus v1.2.0 — 2026-07-06
 */

declare(strict_types=1);

// ANOMALIE CORRIGÉE : Suppression de la double inclusion inutile de session.php pour préserver l'ordre du framework MVC
$current_page = basename($_SERVER['PHP_SELF'] ?? '');
$mes_enfants = $mes_enfants ?? [];
$enfant_actif_id = (int)($enfant_actif_id ?? $_SESSION['active_eleve_id'] ?? 0);
?>

<div class="sidebar d-none d-lg-flex flex-column p-4">
    
    <div class="sidebar-header mb-4">
        <div class="d-flex align-items-center">
            <div class="icon-box me-2">
                <i class="fa-solid fa-graduation-cap text-success fs-4"></i>
            </div>
            <div>
                <h5 class="m-0 fw-bold tracking-wide">Ecole Plus</h5>
                <small class="tracking-widest uppercase">Portail Parent</small>
            </div>
        </div>
    </div>

    <?php if (!empty($mes_enfants)): ?>
    <div class="child-selector-card p-2 mb-4">
        <form id="formSwitchEnfant" method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)($_SESSION['csrf_token'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
            <label class="small mb-1 d-block">ENFANT ACTIF</label>
            <div class="child-selector p-2 d-flex align-items-center position-relative">
                <div class="avatar-circle me-2">
                    <?= strtoupper(substr((string)($enfant_actif['PRENOM_ELEVE'] ?? 'E'), 0, 1)) ?>
                </div>
                <div class="flex-grow-1">
                    <select name="switch_enfant_id" class="form-select child-select" id="switch-enfant-sidebar">
                        <?php foreach ($mes_enfants as $enf): ?>
                            <option value="<?= htmlspecialchars((string)$enf['ID_ELEVE'], ENT_QUOTES, 'UTF-8') ?>" <?= $enf['ID_ELEVE'] == $enfant_actif_id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($enf['PRENOM_ELEVE'] . ' ' . $enf['NOM_ELEVE'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-info ps-2"><?= htmlspecialchars((string)($enfant_actif['NOMSALLE'] ?? 'Aucune classe'), ENT_QUOTES, 'UTF-8') ?></small>
                </div>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="../dashboard/dashboard.php" class="nav-link <?= $current_page === 'dashboard.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-border-all me-3"></i> Tableau de bord
            </a>
        </li>
        <li>
            <a href="../enfants/child.php" class="nav-link <?= $current_page === 'child.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-user-graduate me-3"></i> Enfants
            </a>
        </li>
        <li>
            <a href="../notes/notes.php" class="nav-link <?= $current_page === 'notes.php' ? 'active' : '' ?>">
                <i class="fa-regular fa-file-lines me-3"></i> Notes et rapports
            </a>
        </li>
        <li>
            <a href="../absences/absences.php" class="nav-link <?= $current_page === 'absences.php' ? 'active' : '' ?>">
                <i class="fa-regular fa-calendar-check me-3"></i> Affluence
            </a>
        </li>
        <li>
            <a href="../cahier_texte/cahier_texte.php" class="nav-link <?= $current_page === 'cahier_texte.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-book-open me-3"></i> Devoirs
            </a>
        </li>
        <li>
            <a href="../paiements/paiements.php" class="nav-link <?= $current_page === 'paiements.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-wallet me-3"></i> Finances
            </a>
        </li>
        <li>
            <a href="../messagerie/messagerie.php" class="nav-link <?= $current_page === 'messagerie.php' ? 'active' : '' ?>">
                <i class="fa-regular fa-comment me-3"></i> Messagerie
            </a>
        </li>
        <li>
            <a href="../emploi_temps/emploi_temps.php" class="nav-link <?= $current_page === 'emploi_temps.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-calendar-days me-3"></i> Emploi du temps
            </a>
        </li>
        <li>
            <a href="../professeurs/professeurs.php" class="nav-link <?= $current_page === 'professeurs.php' ? 'active' : '' ?>">
                <i class="fa-solid fa-chalkboard-user me-3"></i> Professeurs
            </a>
        </li>
    </ul>

    <div class="sidebar-footer pt-3 mt-5 border-top border-success d-flex align-items-center justify-content-between">
        <a href="../auth/profile.php" class="d-flex align-items-center text-decoration-none text-reset profile-trigger">
            <div class="avatar-parent me-2 <?= $current_page === 'profile.php' ? 'text-success border-success' : '' ?>">
                <i class="fa-solid fa-user-tie"></i>
            </div>
            <div class="user-info" style="max-width: 130px;">
                <span class="d-block text-truncate fw-semibold <?= $current_page === 'profile.php' ? 'text-success' : '' ?>">
                    <?= htmlspecialchars((string)($_SESSION['parent_nom'] ?? 'Parent'), ENT_QUOTES, 'UTF-8') ?>
                </span>
                <small class="small text-muted">Mon Profil</small>
            </div>
        </a>
        <a href="../auth/logout.php" class="btn text-danger p-1" title="Déconnexion">
            <i class="fa-solid fa-power-off fs-5"></i>
        </a>
    </div>
</div>

<script nonce="<?= htmlspecialchars((string)($GLOBALS['csp_nonce'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
document.addEventListener('DOMContentLoaded', function() {
    var select = document.getElementById('switch-enfant-sidebar');
    if (select) {
        select.addEventListener('change', function() {
            var form = document.getElementById('formSwitchEnfant');
            if (form) { try { form.submit(); } catch (e) { console.error(e); } }
        });
    }
});
</script>
