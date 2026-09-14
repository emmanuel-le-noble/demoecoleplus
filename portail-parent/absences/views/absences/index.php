<?php
/**
 * Vue : Dashboard absences & présences
 *
 * Variables attendues :
 *   $data['enfant_actif']      — array (PRENOM_ELEVE, NOM_ELEVE, NOMSALLE)
 *   $data['libelle_annee']     — string
 *   $data['taux_presence']     — float
 *   $data['abs_justifiees']    — int
 *   $data['abs_non_justifiees'] — int
 *   $data['total_absences']    — int
 *   $data['historique']        — array
 *   $data['message_success']   — string
 *   $data['message_error']     — string
 *   $data['csrf_token']        — string
 *
 * Ecole Plus v1.2.0 — 2026-07-05
 */

declare(strict_types=1);
?>

<main class="main-content">
    <?php include __DIR__ . '/../../../includes/topbar.php'; ?>

    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h3 class="fw-bold m-0">Affluence & présences</h3>
            <p class="text-muted small m-0">
                Suivi d'assiduité et relevés de ponctualité de 
                <span class="text-success fw-semibold">
                    <?= htmlspecialchars(($data['enfant_actif']['PRENOM_ELEVE'] ?? '') . ' ' . ($data['enfant_actif']['NOM_ELEVE'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                </span> 
                [<?= htmlspecialchars($data['enfant_actif']['NOMSALLE'] ?? 'Classe non définie', ENT_QUOTES, 'UTF-8') ?>]
            </p>
        </div>
        <button class="btn btn-success btn-sm px-3 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalPermission">
            <i class="fa-solid fa-envelope-open-text me-2"></i> Demander une permission / justifier
        </button>
    </div>

    <?php if (!empty($data['message_success'])): ?>
        <div class="alert alert-success bg-success bg-opacity-10 text-success border-0 small mb-4"><?= htmlspecialchars((string)$data['message_success'], ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <?php if (!empty($data['message_error'])): ?>
        <div class="alert alert-danger bg-danger bg-opacity-10 text-danger border-0 small mb-4"><?= htmlspecialchars((string)$data['message_error'], ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <!-- Statistiques -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="liquid-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small" style="font-size: 11px;">TAUX DE PRÉSENCE</span>
                    <div class="stat-icon bg-success bg-opacity-10 text-success p-2 rounded-2 d-none d-sm-flex" style="font-size: 12px;"><i class="fa-solid fa-user-check"></i></div>
                </div>
                <h3 class="fw-bold m-0"><?= htmlspecialchars((string)($data['taux_presence'] ?? 100), ENT_QUOTES, 'UTF-8') ?> %</h3>
                <div class="progress custom-progress mt-2" style="height: 4px; background-color: rgba(255,255,255,0.05);">
                    <div class="progress-bar bg-success" role="progressbar" style="width: <?= htmlspecialchars((string)($data['taux_presence'] ?? 100), ENT_QUOTES, 'UTF-8') ?>%"></div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="liquid-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small" style="font-size: 11px;">NON JUSTIFIÉES</span>
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger p-2 rounded-2 d-none d-sm-flex" style="font-size: 12px;"><i class="fa-solid fa-triangle-exclamation"></i></div>
                </div>
                <h3 class="fw-bold m-0"><?= htmlspecialchars((string)($data['abs_non_justifiees'] ?? 0), ENT_QUOTES, 'UTF-8') ?> <span class="fs-6 text-muted">H</span></h3>
                <small class="text-danger-glow small d-block mt-1" style="font-size: 11px;">Justificatif requis sous 48h</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="liquid-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small" style="font-size: 11px;">JUSTIFIÉES</span>
                    <div class="stat-icon bg-success bg-opacity-10 text-success p-2 rounded-2 d-none d-sm-flex" style="font-size: 12px;"><i class="fa-solid fa-file-medical"></i></div>
                </div>
                <h3 class="fw-bold m-0"><?= htmlspecialchars((string)($data['abs_justifiees'] ?? 0), ENT_QUOTES, 'UTF-8') ?> <span class="fs-6 text-muted">H</span></h3>
                <small class="text-muted small d-block mt-1" style="font-size: 11px;">Validées par la vie scolaire</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="liquid-card p-3 h-100">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted small" style="font-size: 11px;">TOTAL DES INCIDENTS</span>
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning p-2 rounded-2 d-none d-sm-flex" style="font-size: 12px;"><i class="fa-solid fa-clock-history"></i></div>
                </div>
                <h3 class="fw-bold m-0"><?= htmlspecialchars((string)($data['total_absences'] ?? 0), ENT_QUOTES, 'UTF-8') ?> <span class="fs-6 text-muted">H</span></h3>
                <small class="text-muted small d-block mt-1" style="font-size: 11px;">Cumul annuel global</small>
            </div>
        </div>
    </div>

    <!-- Tableau historique -->
    <div class="liquid-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="fw-bold m-0">Registre des absences & retards</h5>
            <div class="d-flex align-items-center gap-2">
                <div class="position-relative">
                    <i class="fa-solid fa-magnifying-glass position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); font-size: 11px;"></i>
                    <input type="text" id="absence-search" class="form-control form-control-sm py-1" style="padding-left: 30px; border-radius: 8px; font-size: 12px; width: 200px;" placeholder="Filtrer...">
                </div>
                <span class="badge bg-opacity-50 text-muted border border-secondary border-opacity-10 py-2 px-3" style="font-size: 11px;"><?= htmlspecialchars(($data['libelle_annee'] ?? 'Année Académique'), ENT_QUOTES, 'UTF-8') ?></span>
            </div>
        </div>

        <div class="table-responsive">
            <table id="absence-table" class="table table-hover align-middle custom-table-glass m-0">
                <thead>
                    <tr class="text-muted border-bottom border-secondary border-opacity-25" style="font-size: 12px;">
                        <th>DATE</th>
                        <th>CONTEXTE</th>
                        <th class="text-center">DURÉE</th>
                        <th class="text-center">NATURE</th>
                        <th>MOTIFS / REMARQUES</th>
                        <th class="text-end">STATUT / ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['historique']) && is_array($data['historique'])): ?>
                        <?php foreach ($data['historique'] as $abs): ?>
                            <tr class="border-bottom border-secondary border-opacity-10">
                                <td class="fw-semibold">
                                    <?= htmlspecialchars((string)$abs['date'], ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td>
                                    <i class="fa-regular <?= $abs['is_retard'] ? 'fa-clock text-warning' : 'fa-calendar-minus text-danger' ?> me-1"></i> 
                                    <?= $abs['is_retard'] ? 'Heure de début' : 'Cours / Journée' ?>
                                </td>
                                <td class="text-center fw-bold">
                                    <?= htmlspecialchars((string)$abs['duree'], ENT_QUOTES, 'UTF-8') ?> H
                                </td>
                                <td class="text-center">
                                    <span class="badge <?= $abs['is_retard'] ? 'bg-warning' : 'bg-danger' ?> bg-opacity-10 <?= $abs['is_retard'] ? 'text-warning' : 'text-danger' ?> px-2 py-1" style="font-size: 11px;">
                                        <?= $abs['is_retard'] ? 'Retard' : 'Absence' ?>
                                    </span>
                                </td>
                                <td class="small italic text-muted">
                                    <?= htmlspecialchars((string)$abs['motif'], ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td class="text-end">
                                    <?php if ((int)$abs['statut'] === 1): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success px-2 py-1" style="font-size: 11px;"><i class="fa-solid fa-circle-check me-1"></i> Justifié / Validé</span>
                                    <?php elseif ((int)$abs['statut'] === 0): ?>
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1" style="font-size: 11px;"><i class="fa-solid fa-spinner fa-spin me-1"></i> En attente</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1" style="font-size: 11px;"><i class="fa-solid fa-circle-xmark me-1"></i> Non Accepté</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-id-card-clip mb-2 fs-3 d-block text-success"></i>
                                Parfait ! Votre enfant n'a aucune absence ou retard enregistré à ce jour.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script nonce="<?= htmlspecialchars((string)($GLOBALS['csp_nonce'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
document.addEventListener('DOMContentLoaded', function() {
    var input = document.getElementById('absence-search');
    if (!input) return;
    input.addEventListener('input', function() {
        var q = this.value.trim().toLowerCase();
        document.querySelectorAll('#absence-table tbody tr').forEach(function(row) {
            var text = row.textContent.toLowerCase();
            row.style.display = text.includes(q) ? '' : 'none';
        });
    });
});
</script>

<!-- Modal demande de permission -->
<div class="modal fade" id="modalPermission" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border border-secondary border-opacity-25">
            <div class="modal-header border-secondary border-opacity-25">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-paper-plane text-success me-2"></i>Nouvelle Demande / Justification</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string)($data['csrf_token'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                <div class="modal-body">
                    <p class="text-muted small">Remplissez ce formulaire pour planifier une absence future (permission) ou justifier un incident récent auprès de la Vie Scolaire.</p>
                    
                    <div class="mb-3">
                        <label class="form-label small text-muted">Nature de la demande</label>
                        <select name="type_absence" class="form-select bg-secondary bg-opacity-10 border-secondary border-opacity-50">
                            <option value="Absence">Absence (Prévue ou à justifier)</option>
                            <option value="Retard">Retard exceptionnel</option>
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small text-muted">Date concernée *</label>
                            <input type="date" name="date_absence" class="form-control bg-secondary bg-opacity-10 border-secondary border-opacity-50" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted">Durée estimée (Heures) *</label>
                            <input type="number" name="duree_absence" class="form-control bg-secondary bg-opacity-10 border-secondary border-opacity-50" min="1" max="24" value="2" required>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small text-muted">Motif détaillé / Explication *</label>
                        <textarea name="motif_permission" rows="4" class="form-control bg-secondary bg-opacity-10 border-secondary border-opacity-50" placeholder="Ex: Raisons médicales (certificat à fournir), urgence familiale..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-secondary border-opacity-25">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" name="soumettre_permission" class="btn btn-sm btn-success px-3 fw-semibold">Envoyer la demande</button>
                </div>
            </form>
        </div>
    </div>
</div>