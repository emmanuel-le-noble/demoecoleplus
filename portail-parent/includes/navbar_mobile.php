<?php
/**
 * navbar_mobile.php — Barre de navigation basse et tiroir pour terminaux mobiles
 * 
 * Ecole Plus v1.2.0 — 2026-07-06
 */

declare(strict_types=1);

$current_page = basename($_SERVER['PHP_SELF'] ?? '');
$mes_enfants = $mes_enfants ?? [];
$enfant_actif_id = (int)($enfant_actif_id ?? $_SESSION['active_eleve_id'] ?? 0);
?>

<div class="mobile-bottom-nav d-flex d-lg-none justify-content-around align-items-center fixed-bottom py-2">
    <a href="../dashboard/dashboard.php" class="nav-mobile-item <?= $current_page === 'dashboard.php' ? 'active' : '' ?>">
        <i class="fa-solid fa-border-all"></i>
        <span>Accueil</span>
    </a>

    <a href="../notes/notes.php" class="nav-mobile-item <?= $current_page === 'notes.php' ? 'active' : '' ?>">
        <i class="fa-regular fa-file-lines"></i>
        <span>Notes</span>
    </a>

    <a href="../cahier_texte/cahier_texte.php" class="nav-mobile-item <?= $current_page === 'cahier_texte.php' ? 'active' : '' ?>">
        <i class="fa-solid fa-book-open"></i>
        <span>Devoirs</span>
    </a>

    <a href="../paiements/paiements.php" class="nav-mobile-item <?= $current_page === 'paiements.php' ? 'active' : '' ?>">
        <i class="fa-solid fa-wallet"></i>
        <span>Finances</span>
    </a>

    <a href="#" class="nav-mobile-item" data-bs-toggle="offcanvas" data-bs-target="#mobileMenuOffcanvas" aria-controls="mobileMenuOffcanvas">
        <i class="fa-solid fa-bars"></i>
        <span>Menu</span>
    </a>
</div>

<!-- Tiroir de Navigation Latérale Mobile (Offcanvas) -->
<div class="offcanvas offcanvas-start bg-white text-dark d-lg-none" tabindex="-1" id="mobileMenuOffcanvas" aria-labelledby="mobileMenuOffcanvasLabel" style="width: 280px; border-right: 1px solid rgba(0,0,0,0.08); z-index: 1100;">
    <div class="offcanvas-header border-bottom py-3">
        <div class="d-flex align-items-center" id="mobileMenuOffcanvasLabel">
            <div class="icon-box-mobile me-2 bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                <i class="fa-solid fa-graduation-cap fs-5"></i>
            </div>
            <div>
                <h5 class="m-0 fw-bold" style="font-size: 16px;">Ecole Plus</h5>
                <small class="text-muted text-uppercase tracking-wider" style="font-size: 9px; letter-spacing: 0.5px;">Portail Parent</small>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="offcanvas-body d-flex flex-column justify-content-between p-3">
        <div>
            <!-- Sélecteur d'enfant actif dans le menu mobile -->
            <?php if (!empty($mes_enfants)): ?>
                <div class="p-3 mb-4 bg-success bg-opacity-10 border border-success border-opacity-10 rounded-3">
                    <form method="POST" action="">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)($_SESSION['csrf_token'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                        <label class="small text-success fw-bold mb-1 d-block" style="font-size: 10px;">ENFANT ACTIF</label>
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle me-2 bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 30px; height: 30px; font-size: 12px; background: linear-gradient(135deg, #10b981, #059669);">
                                <?= strtoupper(substr((string)($enfant_actif['PRENOM_ELEVE'] ?? 'E'), 0, 1)) ?>
                            </div>
                            <div class="flex-grow-1">
                                <select name="switch_enfant_id" class="form-select form-select-sm bg-transparent border-0 fw-bold text-dark p-0 shadow-none" id="switch-enfant-mobile" style="font-size: 13px; cursor: pointer;">
                                    <?php foreach ($mes_enfants as $enf): ?>
                                        <option value="<?= htmlspecialchars((string)$enf['ID_ELEVE'], ENT_QUOTES, 'UTF-8') ?>" <?= (int)$enf['ID_ELEVE'] === $enfant_actif_id ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($enf['PRENOM_ELEVE'] . ' ' . $enf['NOM_ELEVE'], ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

            <ul class="nav nav-pills flex-column gap-1 mb-auto">
                <li class="nav-item">
                    <a href="../dashboard/dashboard.php" class="nav-link text-dark py-2 px-3 d-flex align-items-center <?= $current_page === 'dashboard.php' ? 'active' : '' ?>" style="border-radius: 8px;">
                        <i class="fa-solid fa-border-all me-3 text-muted" style="width: 20px;"></i> Tableau de bord
                    </a>
                </li>
                <li>
                    <a href="../enfants/child.php" class="nav-link text-dark py-2 px-3 d-flex align-items-center <?= $current_page === 'child.php' ? 'active' : '' ?>" style="border-radius: 8px;">
                        <i class="fa-solid fa-user-graduate me-3 text-muted" style="width: 20px;"></i> Enfants
                    </a>
                </li>
                <li>
                    <a href="../notes/notes.php" class="nav-link text-dark py-2 px-3 d-flex align-items-center <?= $current_page === 'notes.php' ? 'active' : '' ?>" style="border-radius: 8px;">
                        <i class="fa-regular fa-file-lines me-3 text-muted" style="width: 20px;"></i> Notes et rapports
                    </a>
                </li>
                <li>
                    <a href="../absences/absences.php" class="nav-link text-dark py-2 px-3 d-flex align-items-center <?= $current_page === 'absences.php' ? 'active' : '' ?>" style="border-radius: 8px;">
                        <i class="fa-regular fa-calendar-check me-3 text-muted" style="width: 20px;"></i> Affluence
                    </a>
                </li>
                <li>
                    <a href="../cahier_texte/cahier_texte.php" class="nav-link text-dark py-2 px-3 d-flex align-items-center <?= $current_page === 'cahier_texte.php' ? 'active' : '' ?>" style="border-radius: 8px;">
                        <i class="fa-solid fa-book-open me-3 text-muted" style="width: 20px;"></i> Devoirs
                    </a>
                </li>
                <li>
                    <a href="../paiements/paiements.php" class="nav-link text-dark py-2 px-3 d-flex align-items-center <?= $current_page === 'paiements.php' ? 'active' : '' ?>" style="border-radius: 8px;">
                        <i class="fa-solid fa-wallet me-3 text-muted" style="width: 20px;"></i> Finances
                    </a>
                </li>
                <li>
                    <a href="../messagerie/messagerie.php" class="nav-link text-dark py-2 px-3 d-flex align-items-center <?= $current_page === 'messagerie.php' ? 'active' : '' ?>" style="border-radius: 8px;">
                        <i class="fa-regular fa-comment me-3 text-muted" style="width: 20px;"></i> Messagerie
                    </a>
                </li>
            </ul>
        </div>

        <div class="border-top pt-3 d-flex align-items-center justify-content-between">
            <a href="../auth/profile.php" class="d-flex align-items-center text-decoration-none text-dark" style="font-size: 13px;">
                <div class="avatar-parent me-2 bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <div class="user-info text-truncate" style="max-width: 150px;">
                    <span class="d-block fw-bold text-dark"><?= htmlspecialchars((string)($_SESSION['parent_nom'] ?? 'Parent'), ENT_QUOTES, 'UTF-8') ?></span>
                    <small class="text-muted d-block" style="font-size: 10px;">Mon Profil</small>
                </div>
            </a>
            <a href="../auth/logout.php" class="btn text-danger p-1" title="Déconnexion">
                <i class="fa-solid fa-power-off fs-5"></i>
            </a>
        </div>
    </div>
</div>

<script nonce="<?= htmlspecialchars((string)($GLOBALS['csp_nonce'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
try {
document.getElementById('switch-enfant-mobile')?.addEventListener('change', function() {
    try { this.form.submit(); } catch (e) { console.error(e); }
});
} catch (e) { console.error('[MobileNav] Erreur setup select enfant:', e); }
</script>