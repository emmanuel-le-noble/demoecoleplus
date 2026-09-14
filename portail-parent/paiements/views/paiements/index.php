<?php
/**
 * Vue : Dashboard paiements & scolarité
 *
 * Variables attendues :
 *   $data['libelle_annee'], $data['montant_theorique'], $data['montant_paye']
 *   $data['solde'], $data['pourcentage'], $data['historique']
 *   $data['enfant_actif']
 *
 * Ecole Plus v1.2.0 — 2026-07-05
 */

declare(strict_types=1);
?>

<main class="main-content">
    <?php include __DIR__ . '/../../../includes/topbar.php'; ?>

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold m-0">Scolarité &amp; Finances</h3>
            <p class="text-muted small m-0">État des versements et historique financier de <span class="text-success fw-semibold"><?= e(($data['enfant_actif']['PRENOM_ELEVE'] ?? '') . ' ' . ($data['enfant_actif']['NOM_ELEVE'] ?? 'votre enfant')) ?></span> [<?= e($data['enfant_actif']['NOMSALLE'] ?? 'Classe non définie') ?>]</p>
        </div>
        <a href="recu_print.php?type=releve" target="_blank"
           class="btn btn-sm btn-outline-success py-2 px-3 d-flex align-items-center gap-2">
            <i class="fa-solid fa-file-invoice"></i>
            <span class="d-none d-sm-inline">Imprimer le relevé complet</span>
        </a>
    </div>

    <!-- Cards financiers -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="liquid-card p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">TOTAL SCOLARITÉ</span>
                    <h4 class="fw-bold m-0"><?= fcfa($data['montant_theorique']) ?></h4>
                </div>
                <div class="stat-icon bg-secondary bg-opacity-20 p-3 rounded-3"><i class="fa-solid fa-wallet"></i></div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="liquid-card p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">MONTANT PAYÉ</span>
                    <h4 class="fw-bold text-success m-0"><?= fcfa($data['montant_paye']) ?></h4>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success p-3 rounded-3"><i class="fa-solid fa-circle-check"></i></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="liquid-card p-3 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1" style="font-size: 11px; letter-spacing: 0.5px;">RESTE À PAYER</span>
                    <h4 class="fw-bold <?= $data['solde'] > 0 ? 'text-warning' : 'text-info' ?> m-0">
                        <?= fcfa($data['solde']) ?>
                    </h4>
                </div>
                <div class="stat-icon <?= $data['solde'] > 0 ? 'bg-warning text-warning' : 'bg-info text-info' ?> bg-opacity-10 p-3 rounded-3"><i class="fa-solid fa-money-bill-wave"></i></div>
            </div>
        </div>
    </div>

    <!-- Barre de progression -->
    <div class="liquid-card p-3 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-muted small" style="font-size: 12px;">Progression globale du règlement de la scolarité</span>
            <span class="fw-bold small"><?= $data['pourcentage'] ?>%</span>
        </div>
        <div class="progress custom-progress" style="height: 8px; background-color: rgba(255,255,255,0.05);">
            <?php 
                $barColor = 'bg-danger';
                if ($data['pourcentage'] >= 100) { $barColor = 'bg-success'; }
                elseif ($data['pourcentage'] >= 50) { $barColor = 'bg-info'; }
                elseif ($data['pourcentage'] > 0) { $barColor = 'bg-warning'; }
            ?>
            <div class="progress-bar <?= $barColor ?>" role="progressbar" style="width: <?= $data['pourcentage'] ?>%"></div>
        </div>
    </div>

    <!-- Historique -->
    <div class="liquid-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="fw-bold m-0">Historique des versements en caisse</h5>
            <div class="d-flex align-items-center gap-2">
                <div class="position-relative">
                    <i class="fa-solid fa-magnifying-glass position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%); font-size: 11px;"></i>
                    <input type="text" id="paiement-search" class="form-control form-control-sm py-1" style="padding-left: 30px; border-radius: 8px; font-size: 12px; width: 200px;" placeholder="Filtrer...">
                </div>
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-10 py-1 px-3 small" style="font-size: 11px;">
                    <i class="fa-solid fa-shield-check me-1"></i> Transactions sécurisées
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table id="paiement-table" class="table table-hover align-middle custom-table-glass m-0">
                <thead>
                    <tr class="text-muted border-bottom border-secondary border-opacity-25" style="font-size: 12px;">
                        <th>RÉFÉRENCE REÇU</th>
                        <th>DATE VERSEMENT</th>
                        <th>PAYEUR ENREGISTRÉ</th>
                        <th class="text-center">LIBÉLLÉ / MOTIF</th>
                        <th class="text-end">MONTANT VERSÉ</th>
                        <th class="text-center">REÇU</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['historique'])): ?>
                        <?php foreach ($data['historique'] as $p): ?>
                            <tr class="border-bottom border-secondary border-opacity-10">
                                <td class="fw-bold text-uppercase" style="font-size: 13px;">
                                    <i class="fa-solid fa-receipt me-1 text-muted"></i> REC-<?= str_pad((string)$p['ID'], 5, '0', STR_PAD_LEFT) ?>
                                </td>
                                <td><?= htmlspecialchars(date('d/m/Y', strtotime($p['DATE'])), ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="small"><?= e($p['NOMPAYEUR'] ?: 'Non renseigné') ?></td>
                                <td class="text-center small">Frais de scolarité</td>
                                <td class="text-end fw-bold text-success fs-6">
                                    + <?= fcfa($p['MONTANT']) ?>
                                </td>
                                <td class="text-center">
                                    <a href="recu_print.php?type=recu&id=<?= (int)$p['ID'] ?>" target="_blank"
                                       class="btn btn-sm btn-outline-secondary py-1 px-2"
                                       title="Imprimer le reçu">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-receipt mb-2 fs-3 d-block text-warning"></i>
                                Aucun versement n'a encore été enregistré.
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
    var input = document.getElementById('paiement-search');
    if (!input) return;
    input.addEventListener('input', function() {
        var q = this.value.trim().toLowerCase();
        document.querySelectorAll('#paiement-table tbody tr').forEach(function(row) {
            var text = row.textContent.toLowerCase();
            row.style.display = text.includes(q) ? '' : 'none';
        });
    });
});
</script>
