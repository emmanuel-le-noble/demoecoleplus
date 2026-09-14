<?php
/**
 * topbar.php — Barre supérieure de navigation Desktop & Mobile
 *
 * Ecole Plus v1.3.0 — 2026-09-13
 */

declare(strict_types=1);

$mes_enfants = $mes_enfants ?? [];
$enfant_actif_id = (int)($enfant_actif_id ?? $_SESSION['active_eleve_id'] ?? 0);
?>

<!-- TOPBAR DESKTOP -->
<div class="topbar d-none d-lg-block mb-4">
    <div class="d-flex align-items-center justify-content-end gap-3">
        <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-2" data-theme-toggle style="border-radius: 10px; font-size: .82rem; padding: .4rem .9rem;">
            <i class="fa-solid fa-moon"></i>
            <span class="theme-toggle-label">Sombre</span>
        </button>

        <?php if (count($mes_enfants) > 1): ?>
            <form method="POST" action="" class="m-0" id="switch-enfant-form-desktop">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)($_SESSION['csrf_token'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                <select name="switch_enfant_id" class="form-select form-select-sm border-success rounded-pill px-3" style="cursor: pointer; font-size: .82rem; max-width: 200px;">
                    <?php foreach ($mes_enfants as $enfant): ?>
                        <option value="<?= htmlspecialchars((string)$enfant['ID_ELEVE'], ENT_QUOTES, 'UTF-8') ?>" <?= $enfant['ID_ELEVE'] == $enfant_actif_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($enfant['PRENOM_ELEVE'] . ' ' . $enfant['NOM_ELEVE'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        <?php elseif (count($mes_enfants) === 1): ?>
            <div class="d-flex align-items-center gap-1 py-1 px-3 rounded-pill border border-success border-opacity-10 small" style="font-size: .82rem;">
                <i class="fa-solid fa-child text-success"></i>
                <span class="text-muted">Suivi :</span>
                <strong class="text-success"><?= htmlspecialchars((string)($mes_enfants[0]['PRENOM_ELEVE'] ?? ''), ENT_QUOTES, 'UTF-8') ?></strong>
            </div>
        <?php endif; ?>

        <a href="../auth/profile.php" class="d-flex align-items-center gap-2 py-1 px-3 rounded-pill text-decoration-none text-reset" style="border: 1px solid #e2e8f0; transition: all .2s; font-size: .85rem;">
            <i class="fa-regular fa-user-circle text-secondary"></i>
            <span class="fw-semibold"><?= htmlspecialchars((string)($_SESSION['parent_nom'] ?? 'Parent'), ENT_QUOTES, 'UTF-8') ?></span>
            <small class="text-muted text-uppercase" style="font-size: 9px;">Parent</small>
        </a>
    </div>
</div>

<style>
    .topbar a[href="../auth/profile.php"]:hover { border-color: #0d9488 !important; background: rgba(13,148,136,.04); }
    .topbar .theme-toggle:hover { background: #f1f5f9; }
</style>

<script nonce="<?= htmlspecialchars((string)($GLOBALS['csp_nonce'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('select[name="switch_enfant_id"]').forEach(function(select) {
        select.addEventListener('change', function() {
            if (this.form) { try { this.form.submit(); } catch (e) { console.error(e); } }
        });
    });

    function applyTheme(theme) {
        document.documentElement.dataset.theme = theme;
        try { localStorage.setItem('ecoleplus-theme', theme); } catch (e) {}
        var themeColor = document.getElementById('theme-color');
        if (themeColor) themeColor.content = theme === 'dark' ? '#102a43' : '#0f766e';
        document.querySelectorAll('[data-theme-toggle]').forEach(function(button) {
            var dark = theme === 'dark';
            button.setAttribute('aria-label', dark ? 'Activer le mode clair' : 'Activer le mode sombre');
            var icon = button.querySelector('i');
            if (icon) icon.className = dark ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
            var label = button.querySelector('.theme-toggle-label');
            if (label) label.textContent = dark ? 'Clair' : 'Sombre';
        });
    }

    applyTheme(document.documentElement.dataset.theme === 'dark' ? 'dark' : 'light');
    document.querySelectorAll('[data-theme-toggle]').forEach(function(button) {
        button.addEventListener('click', function() {
            applyTheme(document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark');
        });
    });
});
</script>

<!-- TOPBAR MOBILE & TABLET -->
<div class="topbar-mobile d-flex d-lg-none align-items-center justify-content-between mb-3 p-3 rounded-3 liquid-card">
    <div class="d-flex align-items-center">
        <div class="icon-box-mobile me-2 d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-3" style="width: 35px; height: 35px;">
            <i class="fa-solid fa-graduation-cap fs-5"></i>
        </div>
        <div>
            <h6 class="m-0 fw-bold" style="font-size: 14px;">Ecole Plus</h6>
        </div>
    </div>

    <div class="d-flex align-items-center gap-2">
        <button type="button" class="theme-toggle theme-toggle-mobile btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center p-0" data-theme-toggle aria-label="Changer de thème" title="Changer de thème" style="width: 35px; height: 35px;">
            <i class="fa-solid fa-moon"></i><span class="visually-hidden">Mode sombre</span>
        </button>
        <?php if (count($mes_enfants) > 1): ?>
            <form method="POST" action="" class="m-0" id="switch-enfant-form-mobile">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)($_SESSION['csrf_token'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                <select name="switch_enfant_id" class="form-select form-select-sm border-success rounded-pill px-3 py-1" style="cursor: pointer; font-size: 12px; max-width: 130px;">
                    <?php foreach ($mes_enfants as $enfant): ?>
                        <option value="<?= htmlspecialchars((string)$enfant['ID_ELEVE'], ENT_QUOTES, 'UTF-8') ?>" <?= $enfant['ID_ELEVE'] == $enfant_actif_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars((string)($enfant['PRENOM_ELEVE']), ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        <?php elseif (count($mes_enfants) === 1): ?>
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1" style="font-size: 11px;">
                <i class="fa-solid fa-child me-1"></i> <?= htmlspecialchars((string)($mes_enfants[0]['PRENOM_ELEVE'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
            </span>
        <?php endif; ?>

        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center p-0" type="button" id="mobileProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="width: 35px; height: 35px; border-color: rgba(0,0,0,0.1);">
                <i class="fa-solid fa-user-tie text-secondary"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="mobileProfileDropdown" style="border-radius: 12px; font-size: 13px; min-width: 190px;">
                <li class="dropdown-header border-bottom pb-2 mb-2">
                    <span class="d-block fw-bold text-dark text-truncate" style="max-width: 160px;"><?= htmlspecialchars((string)($_SESSION['parent_nom'] ?? 'Parent'), ENT_QUOTES, 'UTF-8') ?></span>
                    <small class="text-muted">Parent d'élève</small>
                </li>
                <li><a class="dropdown-item py-2" href="../dashboard/dashboard.php"><i class="fa-solid fa-border-all me-2 text-muted"></i> Tableau de bord</a></li>
                <li><a class="dropdown-item py-2" href="../enfants/child.php"><i class="fa-solid fa-user-graduate me-2 text-muted"></i> Enfants</a></li>
                <li><a class="dropdown-item py-2" href="../notes/notes.php"><i class="fa-regular fa-file-lines me-2 text-muted"></i> Notes et rapports</a></li>
                <li><a class="dropdown-item py-2" href="../absences/absences.php"><i class="fa-regular fa-calendar-check me-2 text-muted"></i> Affluence</a></li>
                <li><a class="dropdown-item py-2" href="../cahier_texte/cahier_texte.php"><i class="fa-solid fa-book-open me-2 text-muted"></i> Devoirs</a></li>
                <li><a class="dropdown-item py-2" href="../paiements/paiements.php"><i class="fa-solid fa-wallet me-2 text-muted"></i> Finances</a></li>
                <li><a class="dropdown-item py-2" href="../messagerie/messagerie.php"><i class="fa-regular fa-comment me-2 text-muted"></i> Messagerie</a></li>
                <li><a class="dropdown-item py-2" href="../professeurs/professeurs.php"><i class="fa-solid fa-chalkboard-user me-2 text-muted"></i> Professeurs</a></li>
                <li><a class="dropdown-item py-2" href="../emploi_temps/emploi_temps.php"><i class="fa-solid fa-calendar-days me-2 text-muted"></i> Emploi du temps</a></li>
                <li><a class="dropdown-item py-2" href="../auth/profile.php"><i class="fa-solid fa-user-tie me-2 text-muted"></i> Mon Profil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item py-2 text-danger fw-semibold" href="../auth/logout.php"><i class="fa-solid fa-power-off me-2"></i> Déconnexion</a></li>
            </ul>
        </div>
    </div>
</div>
