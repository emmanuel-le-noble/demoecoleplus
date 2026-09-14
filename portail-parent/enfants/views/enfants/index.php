<?php
/**
 * Vue — Famille & Scolarité (Gestion des enfants rattachés)
 *
 * Ecole Plus v1.2.0 — 2026-07-06
 */

declare(strict_types=1);
?>
<main class="main-content">
    <?php include __DIR__ . '/../../../includes/topbar.php'; ?>

    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h3 class="fw-bold m-0">Famille & Scolarité</h3>
            <p class="text-muted small m-0 font-family-sans-serif">Suivi financier, assiduité et relevé de notes en temps réel.</p>
        </div>
        
        <form method="POST" action="" class="d-flex align-items-center gap-2">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)($_SESSION['csrf_token'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
            <label for="select_active_id" class="text-muted small text-nowrap m-0"><i class="fa-solid fa-baby me-1"></i> Enfant sélectionné :</label>
            <select name="select_active_id" id="select_active_id" class="form-select form-select-sm border-secondary">
                <?php if (!empty($data['enfants']) && is_array($data['enfants'])): foreach ($data['enfants'] as $enf): ?>
                    <option value="<?= htmlspecialchars((string)($enf['info']['ID_ELEVE'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" <?= (int)($enf['info']['ID_ELEVE'] ?? 0) === (int)($data['current_enfant_id'] ?? 0) ? 'selected' : '' ?>>
                        <?= htmlspecialchars((string)($enf['info']['PRENOM_ELEVE'] ?? '') . ' ' . (string)($enf['info']['NOM_ELEVE'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; endif; ?>
            </select>
        </form>
    </div>

    <div class="row g-4 mb-5">
        <?php if (!empty($data['enfants']) && is_array($data['enfants'])): foreach ($data['enfants'] as $enf): ?>
            <div class="col-xl-6 col-md-12">
                <div class="liquid-card p-4 h-100 d-flex flex-column justify-content-between <?= $enf['is_selected'] ? 'border border-info' : '' ?>" style="background: <?= $enf['is_selected'] ? 'rgba(13, 202, 240, 0.03)' : '' ?>;">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-container">
                                    <?php if (!empty($enf['info']['PHOTO'])): ?>
                                        <img src="../uploads/photos/<?= htmlspecialchars((string)$enf['info']['PHOTO'], ENT_QUOTES, 'UTF-8') ?>" alt="Photo" class="rounded-circle border border-secondary" style="width: 55px; height: 55px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 55px; height: 55px; font-size: 22px;">
                                            <i class="fa-solid fa-user-graduate"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h5 class="fw-bold m-0"><?= htmlspecialchars((string)($enf['info']['PRENOM_ELEVE'] ?? '') . ' ' . (string)($enf['info']['NOM_ELEVE'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h5>
                                    <span class="badge bg-secondary bg-opacity-20 mt-1" style="font-size: 11px;">
                                        <i class="fa-solid fa-school me-1"></i> <?= htmlspecialchars((string)($enf['nom_classe'] ?? 'Non assignée'), ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </div>
                            </div>
                            <?php if ($enf['is_selected']): ?>
                                <span class="badge bg-info text-dark fw-bold">Sélectionné</span>
                            <?php endif; ?>
                        </div>

                        <hr class="border-secondary border-opacity-25 my-3">

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <div class="p-2 rounded d-flex align-items-center justify-content-between" style="background: rgba(255,255,255,0.02); border-left: 3px solid #ffc107;">
                                    <div>
                                        <small class="text-muted d-block small" style="font-size: 10px;">ASSIDUITÉ</small>
                                        <span class="fw-bold <?= (int)($enf['absences'] ?? 0) > 0 ? 'text-warning' : 'text-muted' ?>"><?= htmlspecialchars((string)($enf['absences'] ?? 0), ENT_QUOTES, 'UTF-8') ?> abs.</span>
                                    </div>
                                    <div class="text-muted opacity-50"><i class="fa-solid fa-user-clock"></i></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 rounded d-flex align-items-center justify-content-between" style="background: rgba(255,255,255,0.02); border-left: 3px solid #0dcaf0;">
                                    <div>
                                        <small class="text-muted d-block small" style="font-size: 10px;">RAPPORTS</small>
                                        <span class="fw-bold text-info"><?= htmlspecialchars((string)($enf['notes_count'] ?? 0), ENT_QUOTES, 'UTF-8') ?> évaluations</span>
                                    </div>
                                    <div class="text-info opacity-50"><i class="fa-solid fa-file-invoice"></i></div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-2 mb-3 text-center">
                            <div class="col-4">
                                <div class="p-2 rounded bg-light bg-opacity-5">
                                    <small class="text-muted d-block text-uppercase" style="font-size: 9px;">Écolage Total</small>
                                    <span class="fw-bold small"><?= fcfa($enf['montant_du'] ?? 0) ?></span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded bg-success bg-opacity-10">
                                    <small class="text-success d-block text-uppercase" style="font-size: 9px;">Déjà Payé</small>
                                    <span class="fw-bold text-success small"><?= fcfa($enf['montant_paye'] ?? 0) ?></span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded <?= (float)($enf['solde'] ?? 0) > 0 ? 'bg-warning bg-opacity-10' : 'bg-info bg-opacity-10' ?>">
                                    <small class="d-block text-uppercase" style="font-size: 9px;">Reste</small>
                                    <span class="fw-bold small"><?= fcfa($enf['solde'] ?? 0) ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="progress mb-3" style="height: 5px; background: rgba(255,255,255,0.05);">
                            <div class="progress-bar bg-info" style="width: <?= htmlspecialchars((string)($enf['pourcentage'] ?? 0), ENT_QUOTES, 'UTF-8') ?>%"></div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-2">
                        <?php if (!$enf['is_selected']): ?>
                            <form method="POST" action="" class="w-50">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)($_SESSION['csrf_token'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="select_active_id" value="<?= htmlspecialchars((string)($enf['info']['ID_ELEVE'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                                <button type="submit" class="btn btn-sm btn-secondary w-100 py-2"><i class="fa-solid fa-eye me-1"></i> Voir ses notes</button>
                            </form>
                        <?php endif; ?>
                        <form method="POST" action="" class="<?= $enf['is_selected'] ? 'w-100' : 'w-50' ?>">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)($_SESSION['csrf_token'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="acceder_enfant_id" value="<?= htmlspecialchars((string)($enf['info']['ID_ELEVE'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                            <button type="submit" class="btn btn-sm btn-outline-info w-100 py-2 fw-semibold">
                                <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Espace Détaillé 
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>

    <div class="liquid-card p-4 rounded-3">
        <div class="border-bottom border-secondary border-opacity-25 pb-3 mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="fw-bold m-0 text-success"><i class="fa-solid fa-star me-2"></i>Dernières Évaluations</h5>
                <p class="text-muted small m-0">Tableau des notes publiées pour l'année en cours.</p>
            </div>
        </div>

        <?php if (empty($data['notes'])): ?>
            <div class="text-center py-4 text-muted">
                <i class="fa-solid fa-folder-open fs-3 mb-2 d-block"></i>
                Aucune note n'a encore été publiée pour cet enfant durant cette année scolaire.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap" style="font-size: 13px;">
                    <thead>
                        <tr class="border-secondary border-opacity-50">
                            <th>Matière</th>
                            <th>Période</th>
                            <th class="text-center">Inté /10</th>
                            <th class="text-center">DS /20</th>
                            <th class="text-center">Moy. Classe</th>
                            <th class="text-center">Composition</th>
                            <th class="text-center fw-bold text-success">Moy. Trimestre</th>
                            <th>Observation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['notes'] as $note): ?>
                            <tr class="border-secondary border-opacity-25">
                                <td class="fw-semibold"><?= htmlspecialchars((string)($note['NOM_MATIERE'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                                <td><span><?= htmlspecialchars((string)($note['libposition'] ?? 'N/A'), ENT_QUOTES, 'UTF-8') ?></span></td>
                                <td class="text-center"><?= !empty($note['NOTEINT']) ? htmlspecialchars((string)$note['NOTEINT'], ENT_QUOTES, 'UTF-8') : '-' ?></td>
                                <td class="text-center"><?= !empty($note['NOTEDS']) ? htmlspecialchars((string)$note['NOTEDS'], ENT_QUOTES, 'UTF-8') : '-' ?></td>
                                <td class="text-center text-muted"><?= !empty($note['MOYCLASS']) ? htmlspecialchars((string)$note['MOYCLASS'], ENT_QUOTES, 'UTF-8') : '-' ?></td>
                                <td class="text-center"><?= !empty($note['NOTECOMP']) ? htmlspecialchars((string)$note['NOTECOMP'], ENT_QUOTES, 'UTF-8') : '-' ?></td>
                                <td class="text-center fw-bold text-success"><?= number_format((float)($note['MOYENTRIMES'] ?? 0.0), 2, ',', ' ') ?></td>
                                <td class="text-muted small italic" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                    <?= htmlspecialchars((string)($note['OBSERVATION'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</main>

<script nonce="<?= htmlspecialchars((string)($GLOBALS['csp_nonce'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
try {
    document.getElementById('select_active_id')?.addEventListener('change', function() {
        try { this.form.submit(); } catch (e) { console.error(e); }
    });
} catch (e) { 
    console.error('[Enfants] Erreur configuration écouteur selection active:', e); 
}
</script>