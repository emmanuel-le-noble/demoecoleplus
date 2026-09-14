<?php
/**
 * Vue : Liste du Cahier de textes et des Devoirs
 *
 * Ecole Plus v1.2.0 — 2026-07-06
 */

declare(strict_types=1);

if (!function_exists('date_force_french_day')) {
    function date_force_french_day(int $timestamp): string {
        $days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
        return $days[date('w', $timestamp)] ?? '';
    }
}
?>

<main class="main-content">
    <?php include __DIR__ . '/../../../includes/topbar.php'; ?>

    <style>
        .transition-hover:hover { background: rgba(255,255,255,0.05); }
    </style>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h3 class="fw-bold m-0">Cahier de textes & devoirs</h3>
            <p class="text-muted small m-0">
                Suivi du programme et devoirs pour 
                <span class="text-info fw-semibold">
                    <?= htmlspecialchars(($data['enfant_actif']['PRENOM_ELEVE'] ?? '') . ' ' . ($data['enfant_actif']['NOM_ELEVE'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                </span> 
                (Classe : <?= htmlspecialchars(($data['enfant_actif']['NOMSALLE'] ?? $data['enfant_actif']['nomsalle'] ?? 'Non définie'), ENT_QUOTES, 'UTF-8') ?>)
            </p>
        </div>
        
        <form method="GET" action="" class="d-flex align-items-center" id="form-filtre-matiere">
            <!-- Sauvegarde des paramètres contextuels importants s'ils existent dans l'URL -->
            <?php foreach ($_GET as $key => $value): if ($key !== 'matiere'): ?>
                <input type="hidden" name="<?= htmlspecialchars((string)$key, ENT_QUOTES, 'UTF-8') ?>" value="<?= htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8') ?>">
            <?php endif; endforeach; ?>

            <select name="matiere" class="form-select bg-opacity-50 border-secondary border-opacity-25 py-2 small" id="select-matiere-ct" style="font-size: 13px;">
                <option value="0">Toutes les matières</option>
                <?php if (!empty($data['matieres']) && is_array($data['matieres'])): foreach ($data['matieres'] as $mat): ?>
                    <option value="<?= htmlspecialchars((string)$mat['id_matiere'], ENT_QUOTES, 'UTF-8') ?>" <?= (int)$data['matiere_filtre'] === (int)$mat['id_matiere'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars((string)$mat['nom_matiere'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; endif; ?>
            </select>
        </form>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="liquid-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold m-0">Travaux et leçons de la classe</h5>
                    <span class="badge bg-opacity-50 text-success border border-secondary border-opacity-10 py-2 px-3 small" style="font-size: 11px;">
                        <?= count($data['devoirs'] ?? []) ?> élément(s) au total
                    </span>
                </div>

                <div class="d-flex flex-column gap-3">
                    <?php if (!empty($data['devoirs']) && is_array($data['devoirs'])): foreach ($data['devoirs'] as $dev): 
                        $date_echeance = !empty($dev['date_echeance']) ? strtotime((string)$dev['date_echeance']) : null;
                        $est_depasse = ($date_echeance && $date_echeance < strtotime(date('Y-m-d')));
                    ?>
                        <div class="p-3 rounded-3 bg-opacity-25 border border-secondary border-opacity-10 d-flex align-items-start justify-content-between flex-wrap gap-2 transition-hover">
                            
                            <div class="d-flex align-items-start gap-3 flex-grow-1" style="min-width: 300px;">
                                <div class="pt-1">
                                    <input type="checkbox" class="form-check-input bg-transparent border-secondary dev-checkbox" style="cursor:pointer; width:20px; height:20px; border-radius:50%;" id="dev_<?= htmlspecialchars((string)$dev['id'], ENT_QUOTES, 'UTF-8') ?>" title="Marquer comme fait">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                                        <span class="text-info text-uppercase tracking-wider fw-bold small" style="font-size: 11px;">
                                            <?= htmlspecialchars((string)$dev['nom_matiere'], ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                        <span class="text-muted small">| Enseigné par : <strong>M./Mme <?= htmlspecialchars((string)$dev['prof_nom'], ENT_QUOTES, 'UTF-8') ?></strong></span>
                                    </div>

                                    <?php if (!empty($dev['contenu_cours'])): ?>
                                        <div class="p-2 mb-2 bg-secondary bg-opacity-10 rounded border-start border-info border-3">
                                            <small class="text-info fw-bold d-block" style="font-size: 11px;"><i class="fa-solid fa-book-open me-1"></i> Résumé du cours (Fait le <?= !empty($dev['date_cours']) ? date('d/m/Y', strtotime((string)$dev['date_cours'])) : 'N/A' ?>) :</small>
                                            <p class="text-muted m-0 small lh-base"><?= nl2br(htmlspecialchars((string)$dev['contenu_cours'], ENT_QUOTES, 'UTF-8')) ?></p>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($dev['devoirs_a_faire'])): ?>
                                        <div class="mt-2">
                                            <small class="text-warning fw-bold d-block" style="font-size: 11px;"><i class="fa-solid fa-pen-to-square me-1"></i> Travail à faire pour le prochain cours :</small>
                                            <p class="m-0 small lh-base txt-devoir"><?= nl2br(htmlspecialchars((string)$dev['devoirs_a_faire'], ENT_QUOTES, 'UTF-8')) ?></p>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($dev['fichier_devoir'])): ?>
                                        <div class="mt-3">
                                            <a href="../uploads/devoirs/<?= htmlspecialchars((string)$dev['fichier_devoir'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="btn btn-sm btn-outline-info py-1 px-2" style="font-size: 11px;">
                                                <i class="fa-solid fa-paperclip me-1"></i> Télécharger le document joint
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="text-md-end text-start ps-md-0 ps-5 mt-md-0 mt-1 min-w-150">
                                <?php if($date_echeance): ?>
                                    <?php if($est_depasse): ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 small fw-semibold" style="font-size:10px;">EN RETARD</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1 small fw-semibold" style="font-size:10px;">À RENDRE</span>
                                    <?php endif; ?>
                                    
                                    <div class="fw-bold mt-2" style="font-size: 13px;">
                                        Pour le : <?= date('d/m/Y', $date_echeance) ?>
                                    </div>
                                    <small class="text-muted d-block mt-1 text-capitalize" style="font-size: 11px;">
                                        <?= date_force_french_day($date_echeance) ?>
                                    </small>
                                <?php else: ?>
                                    <span class="badge bg-secondary bg-opacity-10 text-muted px-2 py-1 small fw-semibold" style="font-size:10px;">INFO COURS</span>
                                <?php endif; ?>
                            </div>

                        </div>
                    <?php endforeach; else: ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fa-regular fa-folder-open mb-3 fs-2 text-info"></i>
                            <p class="m-0 small">Aucun enregistrement (cours ou devoir) trouvé pour cette sélection.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script nonce="<?= htmlspecialchars((string)($GLOBALS['csp_nonce'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
function marquerCommeFait(checkbox) {
    var parentCard = checkbox.closest('.rounded-3');
    if (!parentCard) return;
    var textDevoir = parentCard.querySelector('.txt-devoir');
    if (checkbox.checked) {
        if (textDevoir) textDevoir.style.textDecoration = 'line-through';
        parentCard.style.opacity = '0.5';
    } else {
        if (textDevoir) textDevoir.style.textDecoration = 'none';
        parentCard.style.opacity = '1';
    }
}
try {
    document.getElementById('select-matiere-ct')?.addEventListener('change', function() {
        try { this.form.submit(); } catch (e) { console.error(e); }
    });
    document.querySelectorAll('.dev-checkbox').forEach(function(cb) {
        cb.addEventListener('click', function() { marquerCommeFait(this); });
    });
} catch (e) { 
    console.error('[CT] Erreur setup JS:', e); 
}
</script>

<?php ?>